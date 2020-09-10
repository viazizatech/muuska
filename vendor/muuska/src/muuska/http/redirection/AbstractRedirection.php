<?php
namespace muuska\http\redirection;

abstract class AbstractRedirection implements Redirection
{
    /**
     * @var string
     */
    protected $type;
    
    /**
     * @var int
     */
    protected $statusCode;
    
    /**
     * @param string $type
     * @param int $statusCode
     */
    public function __construct($type, $statusCode = null){
        $this->type = $type;
        $this->statusCode = $statusCode;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\http\redirection\Redirection::redirect()
     */
    public function redirect(RedirectionInput $input) {
        $input->getResponse()->sendRedirect($this->getFinalUrl($input));
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\http\redirection\Redirection::getAjaxParams()
     */
    public function getAjaxParams(RedirectionInput $input) {
        $result = array(
            'hasRedirection' => true,
            'redirectionType' => $this->type,
            'redirectionUrl' => $this->getFinalUrl($input),
        );
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\http\redirection\Redirection::getType()
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\http\redirection\Redirection::getStatusCode()
     */
    public function getStatusCode(){
        return $this->statusCode;
    }
}
