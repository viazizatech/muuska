<?php
namespace muuska\validation;

use muuska\util\App;

class ValidationRuleManager
{
    const ADMIN_PASSWORD_LENGTH = 8;
    const PASSWORD_LENGTH = 5;
    
	/**
	 * @var Validator[]
	 */
	protected $customRules;
	
	protected static $instance;
	
	protected function __construct(){}
	
	/**
	 * @return \muuska\validation\ValidationRuleManager
	 */
	public static function getInstance(){
		if(self::$instance === null){
			self::$instance = new ValidationRuleManager();
		}
		return self::$instance; 
	}
	
	/**
	 * @param string $rule
	 * @param Validator $validator
	 */
	public function addNewRule($rule, Validator $validator){
	    $this->setRuleValidator($rule, $validator);
	}
	
	/**
	 * @param string $rule
	 * @param Validator $validator
	 */
	public function setRuleValidator($rule, Validator $validator){
	    $this->customRules[$rule] = $validator;
	}
	
	/**
	 * @param string $rule
	 * @return bool
	 */
	public function hasCustomRule($rule){
		return isset($this->customRules[$rule]);
	}
	
	/**
	 * @param string $rule
	 * @return bool
	 */
	public function hasRule($rule){
	    return $this->hasCustomRule($rule) || $this->hasInitialRule($rule);
	}
	
	/**
	 * @param string $rule
	 * @return bool
	 */
	public function hasInitialRule($rule){
	    return method_exists($this, $rule);
	}
	
	/**
	 * @param string $rule
	 * @param \muuska\validation\input\ValidationInput $input
	 * @throws \Exception
	 * @return \muuska\validation\result\ValidationResult
	 */
	public function validateByRule($rule, $input){
	    $result = null;
	    if($this->hasCustomRule($rule)){
	        $result = $this->customRules[$rule]->validate($input);
	    }elseif ($this->hasInitialRule($rule)){
	        $error = App::translateFramework(App::translations()->createValidationTranslationConfig(), $rule, $input->getLang());
	        $result = App::validations()->createDefaultValidationResult($this->$rule($input->getValue()), array($error));
	    }else{
	        throw new \Exception(sprintf('Rule %s does not exist', $rule));
	    }
	    return $result;
	}

    /**
     * @param string $ip
     * @return number
     */
    public function isIp2Long($ip)
    {
        return preg_match('#^-?[0-9]+$#', (string)$ip);
    }

    /**
     * @return boolean
     */
    public function isAnything()
    {
        return true;
    }
    
    /**
     * @param mixed $value
     * @return boolean
     */
    public function isRequired($value)
    {
        return $this->isNotNull($value) && $this->isNotEmpty($value);
    }
    
    /**
     * @param mixed $value
     * @return boolean
     */
    public function isNotNull($value)
    {
        return ($value !== null);
    }
    
    /**
     * @param mixed $value
     * @return boolean
     */
    public function isNotEmpty($value)
    {
        return !empty($value);
    }

    /**
     * Check for e-mail validity
     *
     * @param string $email e-mail address to validate
     * @return bool Validity is ok or not
     */
    public function isEmail($email)
    {
        return !empty($email) && preg_match(App::getTools()->cleanNonUnicodeSupport('/^[a-z\p{L}0-9!#$%&\'*+\/=?^`{}|~_-]+[.a-z\p{L}0-9!#$%&\'*+\/=?^`{}|~_-]*@[a-z\p{L}0-9]+(?:[.]?[_a-z\p{L}0-9-])*\.[a-z\p{L}0-9]+$/ui'), $email);
    }

    /**
     * Check for MD5 string validity
     *
     * @param string $md5 MD5 string to validate
     * @return bool Validity is ok or not
     */
    public function isMd5($md5)
    {
        return preg_match('/^[a-f0-9A-F]{32}$/', $md5);
    }

    /**
     * Check for SHA1 string validity
     *
     * @param string $sha1 SHA1 string to validate
     * @return bool Validity is ok or not
     */
    public function isSha1($sha1)
    {
        return preg_match('/^[a-fA-F0-9]{40}$/', $sha1);
    }

    /**
     * Check for a float number validity
     *
     * @param float $float Float number to validate
     * @return bool Validity is ok or not
     */
    public function isFloat($float)
    {
        return strval((float)$float) == strval($float);
    }

    public function isUnsignedFloat($float)
    {
        return strval((float)$float) == strval($float) && $float >= 0;
    }

    /**
     * Check for an image size validity
     *
     * @param string $size Image size to validate
     * @return bool Validity is ok or not
     */
    public function isImageSize($size)
    {
        return preg_match('/^[0-9]{1,4}$/', $size);
    }

    /**
     * Check for name validity
     *
     * @param string $name Name to validate
     * @return bool Validity is ok or not
     */
    public function isName($name)
    {
        return preg_match(App::getTools()->cleanNonUnicodeSupport('/^[^0-9!<>,;?=+()@#"°{}_$%:]*$/u'), stripslashes($name));
    }

    /**
     * Check for sender name validity
     *
     * @param string $mail_name Sender name to validate
     * @return bool Validity is ok or not
     */
    public function isMailName($mail_name)
    {
        return (is_string($mail_name) && preg_match(App::getTools()->cleanNonUnicodeSupport('/^[^<>;=#{}]*$/u'), $mail_name));
    }

    /**
     * Check for e-mail subject validity
     *
     * @param string $mail_subject e-mail subject to validate
     * @return bool Validity is ok or not
     */
    public function isMailSubject($mail_subject)
    {
        return preg_match(App::getTools()->cleanNonUnicodeSupport('/^[^<>]*$/u'), $mail_subject);
    }

    /**
     * Check for module name validity
     *
     * @param string $module_name Module name to validate
     * @return bool Validity is ok or not
     */
    public function isModuleName($module_name)
    {
        return (is_string($module_name) && preg_match('/^[a-zA-Z0-9_-]+$/', $module_name));
    }

    /**
     * Check for template name validity
     *
     * @param string $tpl_name Template name to validate
     * @return bool Validity is ok or not
     */
    public function isTplName($tpl_name)
    {
        return preg_match('/^[a-zA-Z0-9_-]+$/', $tpl_name);
    }

    /**
     * Check for image type name validity
     *
     * @param string $type Image type name to validate
     * @return bool Validity is ok or not
     */
    public function isImageTypeName($type)
    {
        return preg_match('/^[a-zA-Z0-9_ -]+$/', $type);
    }

    /**
     * Check for price validity
     *
     * @param string $price Price to validate
     * @return bool Validity is ok or not
     */
    public function isPrice($price)
    {
        return preg_match('/^[0-9]{1,10}(\.[0-9]{1,9})?$/', $price);
    }

    /**
    * Check for price validity (including negative price)
    *
    * @param string $price Price to validate
    * @return bool Validity is ok or not
    */
    public function isNegativePrice($price)
    {
        return preg_match('/^[-]?[0-9]{1,10}(\.[0-9]{1,9})?$/', $price);
    }

    /**
     * Check for language code (ISO) validity
     *
     * @param string $iso_code Language code (ISO) to validate
     * @return bool Validity is ok or not
     */
    public function isLanguageIsoCode($iso_code)
    {
        return preg_match('/^[a-zA-Z]{2,3}$/', $iso_code);
    }

    public function isLanguageCode($s)
    {
        return preg_match('/^[a-zA-Z]{2}(-[a-zA-Z]{2})?$/', $s);
    }

    public function isStateIsoCode($iso_code)
    {
        return preg_match('/^[a-zA-Z0-9]{1,4}((-)[a-zA-Z0-9]{1,4})?$/', $iso_code);
    }

    public function isNumericIsoCode($iso_code)
    {
        return preg_match('/^[0-9]{2,3}$/', $iso_code);
    }

    /**
     * Check for a message validity
     *
     * @param string $message Message to validate
     * @return bool Validity is ok or not
     */
    public function isMessage($message)
    {
        return !preg_match('/[<>{}]/i', $message);
    }

    /**
     * Check for a country name validity
     *
     * @param string $name Country name to validate
     * @return bool Validity is ok or not
     */
    public function isCountryName($name)
    {
        return preg_match('/^[a-zA-Z -]+$/', $name);
    }

    /**
     * Check for a link (url-rewriting only) validity
     *
     * @param string $link Link to validate
     * @return bool Validity is ok or not
     */
    public function isLinkRewrite($link)
    {
        $allowAccentedCharUrl = false;
        if ($allowAccentedCharUrl) {
            return preg_match(App::getTools()->cleanNonUnicodeSupport('/^[_a-zA-Z0-9\pL\pS-]+$/u'), $link);
        }
        return preg_match('/^[_a-zA-Z0-9\-]+$/', $link);
    }

    /**
     * Check for a route pattern validity
     *
     * @param string $pattern to validate
     * @return bool Validity is ok or not
     */
    public function isRoutePattern($pattern)
    {
        $allowAccentedCharUrl = false;
        if ($allowAccentedCharUrl) {
            return preg_match(App::getTools()->cleanNonUnicodeSupport('/^[_a-zA-Z0-9\(\)\.{}:\/\pL\pS-]+$/u'), $pattern);
        }
        return preg_match('/^[_a-zA-Z0-9\(\)\.{}:\/\-]+$/', $pattern);
    }

    /**
     * Check for a postal address validity
     *
     * @param string $address Address to validate
     * @return bool Validity is ok or not
     */
    public function isAddress($address)
    {
        return empty($address) || preg_match(App::getTools()->cleanNonUnicodeSupport('/^[^!<>?=+@{}_$%]*$/u'), $address);
    }

    /**
     * Check for city name validity
     *
     * @param string $city City name to validate
     * @return bool Validity is ok or not
     */
    public function isCityName($city)
    {
        return preg_match(App::getTools()->cleanNonUnicodeSupport('/^[^!<>;?=+@#"°{}_$%]*$/u'), $city);
    }

    /**
     * Check for search query validity
     *
     * @param string $search Query to validate
     * @return bool Validity is ok or not
     */
    public function isValidSearch($search)
    {
        return preg_match(App::getTools()->cleanNonUnicodeSupport('/^[^<>;=#{}]{0,64}$/u'), $search);
    }

    /**
     * Check for standard name validity
     *
     * @param string $name Name to validate
     * @return bool Validity is ok or not
     */
    public function isGenericName($name)
    {
        return empty($name) || preg_match(App::getTools()->cleanNonUnicodeSupport('/^[^<>={}]*$/u'), $name);
    }

    /**
     * Check for HTML field validity (no XSS please !)
     *
     * @param string $html HTML field to validate
     * @return bool Validity is ok or not
     */
    public function isCleanHtml($html, $allow_iframe = false)
    {
        $events = 'onmousedown|onmousemove|onmmouseup|onmouseover|onmouseout|onload|onunload|onfocus|onblur|onchange';
        $events .= '|onsubmit|ondblclick|onclick|onkeydown|onkeyup|onkeypress|onmouseenter|onmouseleave|onerror|onselect|onreset|onabort|ondragdrop|onresize|onactivate|onafterprint|onmoveend';
        $events .= '|onafterupdate|onbeforeactivate|onbeforecopy|onbeforecut|onbeforedeactivate|onbeforeeditfocus|onbeforepaste|onbeforeprint|onbeforeunload|onbeforeupdate|onmove';
        $events .= '|onbounce|oncellchange|oncontextmenu|oncontrolselect|oncopy|oncut|ondataavailable|ondatasetchanged|ondatasetcomplete|ondeactivate|ondrag|ondragend|ondragenter|onmousewheel';
        $events .= '|ondragleave|ondragover|ondragstart|ondrop|onerrorupdate|onfilterchange|onfinish|onfocusin|onfocusout|onhashchange|onhelp|oninput|onlosecapture|onmessage|onmouseup|onmovestart';
        $events .= '|onoffline|ononline|onpaste|onpropertychange|onreadystatechange|onresizeend|onresizestart|onrowenter|onrowexit|onrowsdelete|onrowsinserted|onscroll|onsearch|onselectionchange';
        $events .= '|onselectstart|onstart|onstop';

        if (preg_match('/<[\s]*script/ims', $html) || preg_match('/('.$events.')[\s]*=/ims', $html) || preg_match('/.*script\:/ims', $html)) {
            return false;
        }

        if (!$allow_iframe && preg_match('/<[\s]*(i?frame|form|input|embed|object)/ims', $html)) {
            return false;
        }

        return true;
    }

    /**
     * Check for product reference validity
     *
     * @param string $reference Product reference to validate
     * @return bool Validity is ok or not
     */
    public function isReference($reference)
    {
        return preg_match(App::getTools()->cleanNonUnicodeSupport('/^[^<>;={}]*$/u'), $reference);
    }

    /**
     * Check for password validity
     *
     * @param string $password Password to validate
     * @param int $size
     * @return bool Validity is ok or not
     */
    public function isPassword($password, $size = null)
    {
        if($size === null){
            $size = self::PASSWORD_LENGTH;
        }
        $passwordLength = App::getStringTools()->strlen($password);
        return ($passwordLength >= $size && $passwordLength < 255);
    }

    public function isPasswordAdmin($password)
    {
        return $this->isPassword($password, self::ADMIN_PASSWORD_LENGTH);
    }

    /**
     * Check for configuration key validity
     *
     * @param string $config_name Configuration key to validate
     * @return bool Validity is ok or not
     */
    public function isConfigName($config_name)
    {
        return preg_match('/^[a-zA-Z_0-9-]+$/', $config_name);
    }

    /**
     * Check date formats like http://php.net/manual/en/function.date.php
     *
     * @param string $date_format date format to check
     * @return bool Validity is ok or not
     */
    public function isPhpDateFormat($date_format)
    {
        // We can't really check if this is valid or not, because this is a string and you can write whatever you want in it.
        // That's why only < et > are forbidden (HTML)
        return preg_match('/^[^<>]+$/', $date_format);
    }

    /**
     * Check for date format
     *
     * @param string $date Date to validate
     * @return bool Validity is ok or not
     */
    public function isDateFormat($date)
    {
        return (bool)preg_match('/^([0-9]{4})-((0?[0-9])|(1[0-2]))-((0?[0-9])|([1-2][0-9])|(3[01]))( [0-9]{2}:[0-9]{2}:[0-9]{2})?$/', $date);
    }

    /**
     * Check for date validity
     *
     * @param string $date Date to validate
     * @return bool Validity is ok or not
     */
    public function isDate($date)
    {
        $matches = array();
        if (!preg_match('/^([0-9]{4})-((?:0?[0-9])|(?:1[0-2]))-((?:0?[0-9])|(?:[1-2][0-9])|(?:3[01]))( [0-9]{2}:[0-9]{2}:[0-9]{2})?$/', $date, $matches)) {
            return false;
        }
        return checkdate((int)$matches[2], (int)$matches[3], (int)$matches[1]);
    }

    /**
     * Check for birthDate validity
     *
     * @param string $date birthdate to validate
     * @return bool Validity is ok or not
     */
    public function isBirthDate($date)
    {
        if (empty($date) || $date == '0000-00-00') {
            return true;
        }
        $birth_date = array();
        if (preg_match('/^([0-9]{4})-((?:0?[1-9])|(?:1[0-2]))-((?:0?[1-9])|(?:[1-2][0-9])|(?:3[01]))([0-9]{2}:[0-9]{2}:[0-9]{2})?$/', $date, $birth_date)) {
            if ($birth_date[1] > date('Y') && $birth_date[2] > date('m') && $birth_date[3] > date('d')
                || $birth_date[1] == date('Y') && $birth_date[2] == date('m') && $birth_date[3] > date('d')
                || $birth_date[1] == date('Y') && $birth_date[2] > date('m')) {
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * Check for boolean validity
     *
     * @param bool $bool Boolean to validate
     * @return bool Validity is ok or not
     */
    public function isBool($bool)
    {
        return $bool === null || is_bool($bool) || preg_match('/^(0|1)$/', $bool);
    }

    /**
     * Check for phone number validity
     *
     * @param string $number Phone number to validate
     * @return bool Validity is ok or not
     */
    public function isPhoneNumber($number)
    {
        return preg_match('/^[+0-9. ()-]*$/', $number);
    }

    /**
     * Check for barcode validity (EAN-13)
     *
     * @param string $ean13 Barcode to validate
     * @return bool Validity is ok or not
     */
    public function isEan13($ean13)
    {
        return !$ean13 || preg_match('/^[0-9]{0,13}$/', $ean13);
    }

    /**
     * Check for barcode validity (UPC)
     *
     * @param string $upc Barcode to validate
     * @return bool Validity is ok or not
     */
    public function isUpc($upc)
    {
        return !$upc || preg_match('/^[0-9]{0,12}$/', $upc);
    }

    /**
     * Check for postal code validity
     *
     * @param string $postcode Postal code to validate
     * @return bool Validity is ok or not
     */
    public function isPostCode($postcode)
    {
        return empty($postcode) || preg_match('/^[a-zA-Z 0-9-]+$/', $postcode);
    }

    /**
     * Check for zip code format validity
     *
     * @param string $zip_code zip code format to validate
     * @return bool Validity is ok or not
     */
    public function isZipCodeFormat($zip_code)
    {
        if (!empty($zip_code)) {
            return preg_match('/^[NLCnlc 0-9-]+$/', $zip_code);
        }
        return true;
    }

    /**
     * Check for table or identifier validity
     * Mostly used in database for ordering : ASC / DESC
     *
     * @param string $way Keyword to validate
     * @return bool Validity is ok or not
     */
    /*public function isSortDirection($direction)
    {
        return ($direction === 'ASC' | $direction === 'DESC' | $direction === 'asc' | $direction === 'desc');
    }*/

    /**
     * Check for table or identifier validity
     * Mostly used in database for ordering : ORDER BY field
     *
     * @param string $order Field to validate
     * @return bool Validity is ok or not
     */
    public function isOrderBy($order)
    {
        return preg_match('/^[a-zA-Z0-9.!_-]+$/', $order);
    }

    /**
     * Check for table or identifier validity
     * Mostly used in database for table names and id_table
     *
     * @param string $table Table/identifier to validate
     * @return bool Validity is ok or not
     */
    public function isTableOrIdentifier($table)
    {
        return preg_match('/^[a-zA-Z0-9_-]+$/', $table);
    }

    /**
     * Check for tags list validity
     *
     * @param string $list List to validate
     * @return bool Validity is ok or not
     */
    public function isTagsList($list)
    {
        return preg_match(App::getTools()->cleanNonUnicodeSupport('/^[^!<>;?=+#"°{}_$%]*$/u'), $list);
    }

    /**
     * Check for an integer validity
     *
     * @param int $value Integer to validate
     * @return bool Validity is ok or not
     */
    public function isInt($value)
    {
        return ((string)(int)$value === (string)$value || $value === false);
    }

    /**
     * Check for an integer validity (unsigned)
     *
     * @param int $value Integer to validate
     * @return bool Validity is ok or not
     */
    public function isUnsignedInt($value)
    {
        return ((string)(int)$value === (string)$value && $value < 4294967296 && $value >= 0);
    }

    /**
     * Check for an percentage validity (between 0 and 100)
     *
     * @param float $value Float to validate
     * @return bool Validity is ok or not
     */
    public function isPercentage($value)
    {
        return ($this->isFloat($value) && $value >= 0 && $value <= 100);
    }

    /**
     * Check for an integer validity (unsigned)
     * Mostly used in database for auto-increment
     *
     * @param int $id Integer to validate
     * @return bool Validity is ok or not
     */
    public function isUnsignedId($id)
    {
        return $this->isUnsignedInt($id); /* Because an id could be equal to zero when there is no association */
    }

    public function isNullOrUnsignedId($id)
    {
        return $id === null || $this->isUnsignedId($id);
    }

    /**
     * Check object validity
     *
     * @param object $object Object to validate
     * @return bool Validity is ok or not
     */
    public function isLoadedObject($object)
    {
		$result =is_object($object) && ($object != null) && $object->isLoaded();
		return  $result;
    }

    /**
     * Check object validity
     *
     * @param int $object Object to validate
     * @return bool Validity is ok or not
     */
    public function isColor($color)
    {
        return preg_match('/^(#[0-9a-fA-F]{6}|[a-zA-Z0-9-]*)$/', $color);
    }

    /**
     * Check url validity (disallowed empty string)
     *
     * @param string $url Url to validate
     * @return bool Validity is ok or not
     */
    public function isUrl($url)
    {
        return preg_match(App::getTools()->cleanNonUnicodeSupport('/^[~:#,$%&_=\(\)\.\? \+\-@\/a-zA-Z0-9\pL\pS-]+$/u'), $url);
    }

    /**
     * Check tracking number validity (disallowed empty string)
     *
     * @param string $tracking_number Tracking number to validate
     * @return bool Validity is ok or not
     */
    public function isTrackingNumber($tracking_number)
    {
        return preg_match('/^[~:#,%&_=\(\)\[\]\.\? \+\-@\/a-zA-Z0-9]+$/', $tracking_number);
    }

    /**
     * Check url validity (allowed empty string)
     *
     * @param string $url Url to validate
     * @return bool Validity is ok or not
     */
    public function isUrlOrEmpty($url)
    {
        return empty($url) || $this->isUrl($url);
    }

    /**
     * Check if URL is absolute
     *
     * @param string $url URL to validate
     * @return bool Validity is ok or not
     */
    public function isAbsoluteUrl($url)
    {
        if (!empty($url)) {
            return preg_match('/^(https?:)?\/\/[$~:;#,%&_=\(\)\[\]\.\? \+\-@\/a-zA-Z0-9]+$/', $url);
        }
        return true;
    }

    public function isMySQLEngine($engine)
    {
        return (in_array($engine, array('InnoDB', 'MyISAM')));
    }

    public function isUnixName($data)
    {
        return preg_match(App::getTools()->cleanNonUnicodeSupport('/^[a-z0-9\._-]+$/ui'), $data);
    }

    public function isTablePrefix($data)
    {
        // Even if "-" is theorically allowed, it will be considered a syntax error if you do not add backquotes (`) around the table name
        return preg_match(App::getTools()->cleanNonUnicodeSupport('/^[a-z0-9_]+$/ui'), $data);
    }

    /**
     * Check for standard name file validity
     *
     * @param string $name Name to validate
     * @return bool Validity is ok or not
     */
    public function isFileName($name)
    {
        return preg_match('/^[a-zA-Z0-9_.-]+$/', $name);
    }

    /**
     * Check for standard name directory validity
     *
     * @param string $dir Directory to validate
     * @return bool Validity is ok or not
     */
    public function isDirName($dir)
    {
        return (bool)preg_match('/^[a-zA-Z0-9_.-]*$/', $dir);
    }
	
    public function isSubDomainName($domain)
    {
        return preg_match('/^[a-zA-Z0-9-_]*$/', $domain);
    }

    /**
     * Check if the value is a sort direction value (DESC/ASC)
     *
     * @param string $value
     * @return bool Validity is ok or not
     */
    public function isSortDirection($value)
    {
        return ($value !== null && ($value === 'ASC' || $value === 'DESC'));
    }

    /**
     * Customization fields' label validity
     *
     * @param string $label
     * @return bool Validity is ok or not
     */
    public function isLabel($label)
    {
        return (preg_match(App::getTools()->cleanNonUnicodeSupport('/^[^{}<>]*$/u'), $label));
    }

    /**
     * Check if $data is a PrestaShop cookie object
     *
     * @param mixed $data to validate
     * @return bool
     */
    public function isCookie($data)
    {
        return (is_object($data) && get_class($data) == 'Cookie');
    }

    /**
     * Price display method validity
     *
     * @param string $data Data to validate
     * @return bool Validity is ok or not
     */
    public function isString($data)
    {
        return is_string($data);
    }

    /**
     * Check for bool_id
     *
     * @param string $ids
     * @return bool Validity is ok or not
     */
    public function isBoolId($ids)
    {
        return (bool)preg_match('#^[01]_[0-9]+$#', $ids);
    }

    /**
     * Check the localization pack part selected
     *
     * @param string $data Localization pack to check
     * @return bool Validity is ok or not
     */
    public function isLocalizationPackSelection($data)
    {
        return in_array((string)$data, array('states', 'taxes', 'currencies', 'languages', 'units', 'groups'));
    }

    /**
     * Check for PHP serialized data
     *
     * @param string $data Serialized data to validate
     * @return bool Validity is ok or not
     */
    public function isSerializedArray($data)
    {
        return $data === null || (is_string($data) && preg_match('/^a:[0-9]+:{.*;}$/s', $data));
    }

    /**
     * Check for Latitude/Longitude
     *
     * @param string $data Coordinate to validate
     * @return bool Validity is ok or not
     */
    public function isCoordinate($data)
    {
        return $data === null || preg_match('/^\-?[0-9]{1,8}\.[0-9]{1,8}$/s', $data);
    }

    /**
     * Check for Language Iso Code
     *
     * @param string $iso_code
     * @return bool Validity is ok or not
     */
    public function isLangIsoCode($iso_code)
    {
        return (bool)preg_match('/^[a-zA-Z]{2,3}$/s', $iso_code);
    }

    /**
     * Check for Language File Name
     *
     * @param string $file_name
     * @return bool Validity is ok or not
     */
    public function isLanguageFileName($file_name)
    {
        return (bool)preg_match('/^[a-zA-Z]{2,3}\.(?:gzip|tar\.gz)$/s', $file_name);
    }

    /**
     *
     * @param array $ids
     * @return bool return true if the array contain only unsigned int value
     */
    public function isArrayWithIds($ids)
    {
        if (count($ids)) {
            foreach ($ids as $id) {
                if ($id == 0 || !$this->isUnsignedInt($id)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Validate SIRET Code
     *
     * @param string $siret SIRET Code
     * @return bool Return true if is valid
     */
    public function isSiret($siret)
    {
        if (App::getStringTools()->strlen($siret) != 14) {
            return false;
        }
        $sum = 0;
        for ($i = 0; $i != 14; $i++) {
            $tmp = ((($i + 1) % 2) + 1) * intval($siret[$i]);
            if ($tmp >= 10) {
                $tmp -= 9;
            }
            $sum += $tmp;
        }
        return ($sum % 10 === 0);
    }

    /**
     * Validate APE Code
     *
     * @param string $ape APE Code
     * @return bool Return true if is valid
     */
    public function isApe($ape)
    {
        return (bool)preg_match('/^[0-9]{3,4}[a-zA-Z]{1}$/s', $ape);
    }

    public function isControllerName($name)
    {
        return (bool)(is_string($name) && preg_match(App::getTools()->cleanNonUnicodeSupport('/^[0-9a-zA-Z-_]*$/u'), $name));
    }
}
