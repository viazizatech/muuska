<?php
namespace muuska\mail;

use muuska\util\App;

class PHPMailerSender implements MailSender
{
    protected static $phpMailerLoaded = false;
    
    /**
     * @var \muuska\config\Configuration
     */
    protected $defaultConfig;
    
    /**
     * Method to send mail: ("mail", "sendmail", or "smtp").
     * @var string
     */
    protected $method;
    
    /**
     * @param string $method
     * @param \muuska\config\Configuration $defautConfig
     */
    public function __construct($method, \muuska\config\Configuration $defautConfig){
        $this->method = $method;
        $this->defaultConfig = $defautConfig;
    }
    
    protected function loadPHPMailer() {
        if(!self::$phpMailerLoaded){
            App::getApp()->autoloadLibrary('PHPMailer\\PHPMailer', 'phpmailer');
            self::$phpMailerLoaded = true;
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\mail\MailSender::send()
     */
    public function send(Mail $mail) {
        return $this->sendWithNewConfig($this->defaultConfig, $mail);
    }

    
    /**
     * {@inheritDoc}
     * @see \muuska\mail\MailSender::sendWithNewConfig()
     */
    public function sendWithNewConfig(\muuska\config\Configuration $mailConfig, Mail $mail) {
        $this->loadPHPMailer();
        $mailer = new \PHPMailer\PHPMailer\PHPMailer();
        $this->initMailerMethod($mailer, $mailConfig, $mail);
        $this->initMail($mailer, $mailConfig, $mail);
        return $mailer->send();
    }
    
    /**
     * @param \PHPMailer\PHPMailer\PHPMailer $mailer
     * @param \muuska\config\Configuration $mailConfig
     * @param Mail $mail
     */
    protected function initMailerMethod(\PHPMailer\PHPMailer\PHPMailer $mailer, \muuska\config\Configuration $mailConfig, Mail $mail) {
        if($this->method === 'smtp'){
            $mailer->isSMTP();
            if(App::getApp()->isDevMode()){
                $mailer->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
            }else{
                $mailer->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_OFF;
            }
            $mailer->Host = $mailConfig->getString('host');
            //Set the SMTP port number - likely to be 25, 465 or 587
            $mailer->Port = $mailConfig->getInt('port', 25);
            //Whether to use SMTP authentication
            $mailer->SMTPAuth = true;
            //Username to use for SMTP authentication
            $mailer->Username = $mailConfig->getString('username');
            //Password to use for SMTP authentication
            $mailer->Password = $mailConfig->getString('password');
            
            $mailer->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            
        }elseif($this->method === 'sendmail'){
            $mailer->isSendmail();
        }elseif($this->method === 'mail'){
            $mailer->isMail();
        }
    }
    
    /**
     * @param \PHPMailer\PHPMailer\PHPMailer $mailer
     * @param \muuska\config\Configuration $mailConfig
     * @param Mail $mail
     */
    protected function initMail(\PHPMailer\PHPMailer\PHPMailer $mailer, \muuska\config\Configuration $mailConfig, Mail $mail) {
        $sender = $mail->getSender();
        if($sender !== null){
            $mailer->Sender = $sender->getEmail();
        }
        $mailer->Subject = $mail->getSubject();
        $mailer->ContentType = $mail->getContentType();
        
        $mailer->Encoding = $mail->getEncoding();
        
        if($mail->hasHtmlBody()){
            $mailer->msgHTML($mail->getBody());
        }else{
            $mailer->Body = $mail->getBody();
        }
        $altBody = $mail->getAltBody();
        if(!empty($altBody)){
            $mailer->AltBody = $mail->getAltBody();
        }
        
        $from = $mail->getFrom();
        $fromObject = null;
        if(isset($from[0])){
            $fromObject = $from[0];
        }else{
            $configEmail = $mailConfig->getString('default_email');
            if(!empty($configEmail)){
                $fromObject = App::mails()->createEmailAddress($configEmail, 'default_address_name');
            }
        }
        
        if($fromObject !== null){
            $mailer->setFrom($fromObject->getEmail(), $fromObject->getName());
        }
        
        $to = $mail->getTo();
        if(is_array($to)){
            foreach ($to as $addressObject) {
                $mailer->addAddress($addressObject->getEmail(), $addressObject->getName());
            }
        }
        
        $cc = $mail->getCc();
        if(is_array($cc)){
            foreach ($cc as $addressObject) {
                $mailer->addCC($addressObject->getEmail(), $addressObject->getName());
            }
        }
        
        $bcc = $mail->getBcc();
        if(is_array($bcc)){
            foreach ($bcc as $addressObject) {
                $mailer->addBCC($addressObject->getEmail(), $addressObject->getName());
            }
        }
        
        $replyTo = $mail->getReplyTo();
        if(is_array($replyTo)){
            foreach ($replyTo as $addressObject) {
                $mailer->addReplyTo($addressObject->getEmail(), $addressObject->getName());
            }
        }
        
        $attachments = $mail->getAttachments();
        if(is_array($attachments)){
            foreach ($attachments as $attachment) {
                $mailer->addStringAttachment($attachment->getContent(), $attachment->getName(), $attachment->getEncoding(), $attachment->getContentType(), $attachment->getDisposition());
            }
        }
        
        $headers = $mail->getHeaders();
        if(is_array($headers)){
            foreach ($headers as $name => $value) {
                $mailer->addCustomHeader($name, $value);
            }
        }
    }
}
