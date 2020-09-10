<?php
namespace muuska\mail;

class FileAttachment implements MailAttachment 
{
    /**
     * @var string
     */
    protected $fileName;
    
    /**
     * @var string
     */
    protected $encoding;
    
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var string
     */
    protected $content;
    
    /**
     * @var string
     */
    protected $contentType;
    
    /**
     * @var string
     */
    protected $disposition;
    
    /**
     * @param string $fileName
     * @param string $name
     * @param string $contentType
     * @param string $disposition
     * @param string $encoding
     */
    public function __construct($fileName, $name = null, $contentType = null, $disposition = null, $encoding = null){
        $this->fileName = $fileName;
        $this->name = empty($name) ? basename($this->fileName) : $name;
        $this->contentType = $contentType;
        $this->disposition = $disposition;
        $this->encoding = $encoding;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\mail\MailAttachment::getEncoding()
     */
    public function getEncoding()
    {
        return $this->encoding;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\mail\MailAttachment::getName()
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\mail\MailAttachment::getContent()
     */
    public function getContent()
    {
        return file_exists($this->fileName) ? file_get_contents($this->fileName) : '';
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\mail\MailAttachment::getContentType()
     */
    public function getContentType()
    {
        return $this->contentType;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\mail\MailAttachment::getDisposition()
     */
    public function getDisposition()
    {
        return $this->disposition;
    }
}
