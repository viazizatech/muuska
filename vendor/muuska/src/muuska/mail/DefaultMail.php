<?php
namespace muuska\mail;

use muuska\util\App;
use muuska\asset\constants\AssetOutputMode;

class DefaultMail implements Mail
{
    /**
     * @var string
     */
    protected $subject;
    
    /**
     * @var string
     */
    protected $encoding;
    
    /**
     * @var EmailAddress
     */
    protected $sender;
    
    /**
     * @var string
     */
    protected $body;
    
    /**
     * @var string
     */
    protected $altBody;
    
    /**
     * @var string
     */
    protected $contentType;
    
    /**
     * @var EmailAddress[]
     */
    protected $from;
    
    /**
     * @var EmailAddress[]
     */
    protected $to;
    
    /**
     * @var EmailAddress[]
     */
    protected $cc;
    
    /**
     * @var EmailAddress[]
     */
    protected $bcc;
    
    /**
     * @var EmailAddress[]
     */
    protected $replyTo;
    
    /**
     * @var MailAttachment[]
     */
    protected $attachments;
    
    /**
     * @var array
     */
    protected $headers;
    
    /**
     * @var array
     */
    protected $headerLines;
    
    /**
     * @param string $subject
     * @param EmailAddress $to
     * @param EmailAddress $from
     */
    public function __construct($subject, EmailAddress $to = null, EmailAddress $from = null){
        $this->setSubject($subject);
        if($to !== null){
            $this->to[] = $to;
        }
        if($from !== null){
            $this->from[] = $from;
        }
    }
    
    /**
     * @param string $email
     * @param string $name
     * @return \muuska\mail\EmailAddress
     */
    protected function createEmailAddress($email, $name = null){
        return App::mails()->createEmailAddress($email, $name);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\mail\Mail::hasHtmlBody()
     */
    public function hasHtmlBody(){
        return ($this->contentType === 'text/html');  
    }
    
    /**
     * @param string $html
     */
    public function setHtml($html){
        $this->setBody($html);
        $this->setContentType('text/html');
    }
    
    /**
     * @param \muuska\html\HtmlPage $htmlPage
     * @param \muuska\html\config\HtmlGlobalConfig $htmlGlobalConfig
     * @param \muuska\html\areacreator\AreaCreatorEditor $areaCreatorEditor
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
     */
    public function setBodyFromHtmlPage(\muuska\html\HtmlPage $htmlPage, \muuska\html\config\HtmlGlobalConfig $htmlGlobalConfig, \muuska\html\areacreator\AreaCreatorEditor $areaCreatorEditor, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
        App::getEventTrigger()->fireMailPageFormating(App::mails()->createMailPageFormatingEvent($this, $htmlPage, $areaCreatorEditor, $htmlGlobalConfig));
        $this->setHtml($htmlPage->generate($htmlGlobalConfig, $callerConfig));
    }
    
    /**
     * @param \muuska\html\HtmlContent $mainContent
     * @param string $title
     * @param string $lang
     * @param string $subAppName
     * @param string[][] $allAlerts
     * @param \muuska\asset\AssetSetter $assetSetter
     * @param \muuska\renderer\HtmlContentRenderer $pageRenderer
     */
    public function setBodyFromMainContent(\muuska\html\HtmlContent $mainContent, $title, $lang = null, $subAppName = null, $allAlerts = array(), \muuska\asset\AssetSetter $assetSetter = null, \muuska\renderer\HtmlContentRenderer $pageRenderer = null){
        $globalConfig = App::createHtmlGlobalConfig($lang, $subAppName, App::assets()->createAssetOutputConfig(AssetOutputMode::INLINE), $assetSetter);
        $htmlPage = App::htmls()->createHtmlPage($title, App::getTools()->createPageAreaCreator($mainContent, $allAlerts));
        $language = App::getApp()->getLanguageInfo($globalConfig->getLang());
        if($language !== null){
            $htmlPage->setLangIso($language->getLanguage());
        }
        $this->setBodyFromHtmlPage($htmlPage, $globalConfig, $htmlPage->getAreaCreator());
    }
    
    /**
     * Add the specified addresses to the existing "From" field.
     * 
     * @param string $email
     * @param string $name
     */
    public function addFrom($email, $name = null){
        $this->addFromAddress($this->createEmailAddress($email, $name));
    }
    
    /**
     * @param string $email
     * @param string $name
     */
    public function addTo($email, $name = null){
        $this->addToAddress($this->createEmailAddress($email, $name));
    }
    
    /**
     * @param string $email
     * @param string $name
     */
    public function addCC($email, $name = null){
        $this->addCCAddress($this->createEmailAddress($email, $name));
    }
    
    /**
     * @param string $email
     * @param string $name
     */
    public function addBCC($email, $name = null){
        $this->addBCCAddress($this->createEmailAddress($email, $name));
    }
    
    /**
     * @param string $email
     * @param string $name
     */
    public function addReplyTo($email, $name = null){
        $this->addReplyToAddress($this->createEmailAddress($email, $name));
    }
    
    
    /**
     * @param EmailAddress $address
     */
    public function addFromAddress(EmailAddress $address){
        $this->from[] = $address;
    }
    
    /**
     * @param EmailAddress[] $addresses
     */
    public function addFromAddresses($addresses){
        if(is_array($addresses)){
            foreach ($addresses as $address) {
                $this->addFromAddress($address);
            }
        }
    }
    
    /**
     * @param EmailAddress $address
     */
    public function addToAddress(EmailAddress $address){
        $this->to[] = $address;
    }
    
    /**
     * @param EmailAddress[] $addresses
     */
    public function addToAddresses($addresses){
        if(is_array($addresses)){
            foreach ($addresses as $address) {
                $this->addToAddress($address);
            }
        }
    }
    
    /**
     * @param EmailAddress $address
     */
    public function addCCAddress(EmailAddress $address){
        $this->cc[] = $address;
    }
    
    /**
     * @param EmailAddress[] $addresses
     */
    public function addCCAddresses($addresses){
        if(is_array($addresses)){
            foreach ($addresses as $address) {
                $this->addCCAddress($address);
            }
        }
    }
    
    /**
     * @param EmailAddress $address
     */
    public function addBCCAddress(EmailAddress $address){
        $this->bcc[] = $address;
    }
    
    /**
     * @param EmailAddress[] $addresses
     */
    public function addBCCAddresses($addresses){
        if(is_array($addresses)){
            foreach ($addresses as $address) {
                $this->addBCCAddress($address);
            }
        }
    }
    
    /**
     * @param EmailAddress $address
     */
    public function addReplyToAddress(EmailAddress $address){
        $this->replyTo[] = $address;
    }
    
    /**
     * @param EmailAddress[] $addresses
     */
    public function addReplyToAddresses($addresses){
        if(is_array($addresses)){
            foreach ($addresses as $address) {
                $this->addReplyToAddress($address);
            }
        }
    }
    
    /**
     * Add this value to the existing values for this header_name.
     * 
     * @param string $name
     * @param string $value
     */
    public function addHeader($name, $value){
        $this->headers[$name] = $value;
    }
    
    /**
     * Add a raw RFC 822 header-line.
     * 
     * @param string $value
     */
    public function addHeaderLine($value){
        $this->headerLines[] = $value;
    }
    
    /**
     * @param MailAttachment $attachment
     */
    public function addAttachment(MailAttachment $attachment){
        $this->attachments[] = $attachment;
    }
    
    /**
     * @param MailAttachment[] $attachments
     */
    public function addAttachments($attachments){
        if (is_array($attachments)) {
            foreach ($attachments as $attachment) {
                $this->addAttachment($attachment);
            }
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\mail\Mail::getSubject()
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * {@inheritDoc}
     * @see \muuska\mail\Mail::getEncoding()
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * {@inheritDoc}
     * @see \muuska\mail\Mail::getSender()
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * {@inheritDoc}
     * @see \muuska\mail\Mail::getBody()
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * {@inheritDoc}
     * @see \muuska\mail\Mail::getAltBody()
     */
    public function getAltBody()
    {
        return $this->altBody;
    }

    /**
     * {@inheritDoc}
     * @see \muuska\mail\Mail::getContentType()
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * {@inheritDoc}
     * @see \muuska\mail\Mail::getFrom()
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * {@inheritDoc}
     * @see \muuska\mail\Mail::getTo()
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * {@inheritDoc}
     * @see \muuska\mail\Mail::getCc()
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * {@inheritDoc}
     * @see \muuska\mail\Mail::getBcc()
     */
    public function getBcc()
    {
        return $this->bcc;
    }

    /**
     * {@inheritDoc}
     * @see \muuska\mail\Mail::getReplyTo()
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * {@inheritDoc}
     * @see \muuska\mail\Mail::getAttachments()
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @param string $encoding
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
    }

    /**
     * @param \muuska\mail\EmailAddress $sender
     */
    public function setSender(EmailAddress $sender)
    {
        $this->sender = $sender;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @param string $altBody
     */
    public function setAltBody($altBody)
    {
        $this->altBody = $altBody;
    }

    /**
     * @param string $contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * @param EmailAddress[] $from
     */
    public function setFrom($from)
    {
        $this->from = array();
        $this->addFromAddresses($from);
    }

    /**
     * @param EmailAddress[] $to
     */
    public function setTo($to)
    {
        $this->to = array();
        $this->addToAddresses($to);
    }

    /**
     * @param EmailAddress[] $cc
     */
    public function setCc($cc)
    {
        $this->cc = array();
        $this->addCCAddresses($cc);
    }

    /**
     * @param EmailAddress[] $bcc
     */
    public function setBcc($bcc)
    {
        $this->bcc = array();
        $this->addBCCAddresses($bcc);
    }

    /**
     * @param EmailAddress[] $replyTo
     */
    public function setReplyTo($replyTo)
    {
        $this->replyTo = array();
        $this->addReplyToAddresses($replyTo);
    }

    /**
     * @param MailAttachment[] $attachments
     */
    public function setAttachments($attachments)
    {
        $this->attachments = array();
        $this->addAttachments($attachments);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\mail\Mail::getHeaders()
     */
    public function getHeaders()
    {
        return $this->headers;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\mail\Mail::getHeaderLines()
     */
    public function getHeaderLines()
    {
        return $this->headerLines;
    }
}
