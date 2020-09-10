<?php
namespace muuska\mail;

interface MailSender
{
    /**
     * @param Mail $mail
     * @return bool
     */
    public function send(Mail $mail);
    
    /**
     * @param \muuska\config\Configuration $mailConfig
     * @param Mail $mail
     * @return bool
     */
    public function sendWithNewConfig(\muuska\config\Configuration $mailConfig, Mail $mail);
}
