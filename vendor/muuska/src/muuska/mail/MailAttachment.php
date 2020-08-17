<?php
namespace muuska\mail;

interface MailAttachment
{
    /**
     * @return string
     */
    public function getContent();
    
    /**
     * @return string
     */
    public function getName();
    
    /**
     * @return string
     */
    public function getContentType();
    
    /**
     * @return string
     */
    public function getEncoding();
    
    /**
     * @return string
     */
    public function getDisposition();
}
