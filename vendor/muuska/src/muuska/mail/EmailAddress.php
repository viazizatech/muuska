<?php
namespace muuska\mail;

class EmailAddress
{
    /**
     * @var string
     */
    protected $email;
    
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @param string $email
     * @param string $name
     */
    public function __construct($email, $name = null) {
        $this->setEmail($email);
        $this->setName($name);
    }
    
    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
