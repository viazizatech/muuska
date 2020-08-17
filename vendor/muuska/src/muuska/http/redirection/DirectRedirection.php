<?php
namespace muuska\http\redirection;

class DirectRedirection extends AbstractRedirection
{   
    /**
     * @var string
     */
    protected $url;
 
    /**
     * @param string $type
     * @param string $url
     * @param int $statusCode
     */
    public function __construct($type, $url, $statusCode = null){
        parent::__construct($type, $statusCode);
        $this->url = $url;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\http\redirection\Redirection::getFinalUrl()
     */
    public function getFinalUrl(RedirectionInput $input) {
        return $this->getUrl();
    }
    
    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
