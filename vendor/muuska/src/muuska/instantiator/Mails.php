<?php
namespace muuska\instantiator;

class Mails
{
	private static $instance;
	
	protected function __construct(){}
	
	/**
	 * @return \muuska\instantiator\Mails
	 */
	public static function getInstance(){
		if(self::$instance === null){
		    self::$instance = new static();
		}
		return self::$instance; 
	}
	
	/**
	 * @param string $email
	 * @param string $name
	 * @return \muuska\mail\EmailAddress
	 */
	public function createEmailAddress($email, $name = null) {
	    return new \muuska\mail\EmailAddress($email, $name);
	}
	
	/**
	 * @param string $subject
	 * @param \muuska\mail\EmailAddress $to
	 * @param \muuska\mail\EmailAddress $from
	 * @return \muuska\mail\DefaultMail
	 */
	public function createDefaultMail($subject, \muuska\mail\EmailAddress $to = null, \muuska\mail\EmailAddress $from = null) {
	    return new \muuska\mail\DefaultMail($subject, $to, $from);
	}
	
	/**
	 * @param string $method
	 * @param \muuska\config\Configuration $defautConfig
	 * @return \muuska\mail\PHPMailerSender
	 */
	public function createPHPMailerSender($method, \muuska\config\Configuration $defautConfig) {
	    return new \muuska\mail\PHPMailerSender($method, $defautConfig);
	}
	
	/**
	 * @param string $content
	 * @param string $name
	 * @param string $contentType
	 * @param string $disposition
	 * @param string $encoding
	 * @return \muuska\mail\StringAttachment
	 */
	public function createStringAttachment($content, $name, $contentType = null, $disposition = null, $encoding = null) {
	    return new \muuska\mail\StringAttachment($content, $name, $contentType, $disposition, $encoding);
	}
	
	/**
	 * @param string $fileName
	 * @param string $name
	 * @param string $contentType
	 * @param string $disposition
	 * @param string $encoding
	 * @return \muuska\mail\FileAttachment
	 */
	public function createFileAttachment($fileName, $name = null, $contentType = null, $disposition = null, $encoding = null) {
	    return new \muuska\mail\FileAttachment($fileName, $name, $contentType, $disposition, $encoding);
	}
	
	/**
	 * @param object $source
	 * @param \muuska\html\HtmlPage $htmlPage
	 * @param \muuska\html\areacreator\AreaCreatorEditor $areaCreatorEditor
	 * @param \muuska\html\config\HtmlGlobalConfig $htmlGlobalConfig
	 * @param array $params
	 * @return \muuska\mail\event\MailPageFormatingEvent
	 */
	public function createMailPageFormatingEvent(object $source, \muuska\html\HtmlPage $htmlPage, \muuska\html\areacreator\AreaCreatorEditor $areaCreatorEditor, \muuska\html\config\HtmlGlobalConfig $htmlGlobalConfig, $params = array()) {
	    return new \muuska\mail\event\MailPageFormatingEvent($source, $htmlPage, $areaCreatorEditor, $htmlGlobalConfig, $params);
	}
}
