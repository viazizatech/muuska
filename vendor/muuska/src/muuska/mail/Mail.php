<?php
namespace muuska\mail;

interface Mail
{
    /**
     * Get the message encoding
     *
     * @return string
     */
    public function getEncoding();

    /**
     * Access headers collection
     *
     * @return array
     */
    public function getHeaders();
    
    /**
     * Get all header lines as an array of Strings.
     *
     * @return array
     */
    public function getHeaderLines();
    
    /**
     * Get the addresses to which replies should be directed.
     *
     * @param string type
     * @return EmailAddress[]
     */
    public function getReplyTo();
    
    /**
     * Retrieve list of From senders
     *
     * @return EmailAddress[]
     */
    public function getFrom();
    
    /**
     * Access the address list of the To header
     *
     * @return EmailAddress[]
     */
    public function getTo();
    
    /**
     * Retrieve list of CC recipients
     *
     * @return EmailAddress[]
     */
    public function getCc();
    
    /**
     * Retrieve list of Bcc recipients
     *
     * @return EmailAddress[]
     */
    public function getBcc();
    
    /**
     * Get the message subject header value
     *
     * @return string
     */
    public function getSubject();
    
    /**
     * Returns the value of the RFC 822 "Sender" header field.
     *
     * @return EmailAddress
     */
    public function getSender();

    /**
     * @return string
     */
    public function getAltBody();
    
    /**
     * @return string
     */
    public function getBody();
    
    /**
     * @return string
     */
    public function getContentType();
    
    /**
     * @return bool
     */
    public function hasHtmlBody();
    
    /**
     * @return MailAttachment[]
     */
    public function getAttachments();
}
