<?php
namespace muuska\http;

use muuska\security\Blowfish;
use muuska\security\Rijndael;
use muuska\util\App;

class CookieVisitorInfoRecorder implements VisitorInfoRecorder
{
    /** @var array Contain cookie content in a key => value format */
    protected $content;
    
    /** @var array cipher tool instance */
    protected $cipherTool;

    protected $modified = false;

    protected $salt;
    
    /**
     * @var Cookie
     */
    protected $cookie;

    /**
     * @param Request $request
     * @param Response $response
     * @param string $name
     * @param string $path
     * @param int $expire
     * @param boolean $cipherAlgorithm
     * @param array $shared_urls
     * @param bool $standalone
     * @param bool $secure
     */
    public function __construct(Request $request, Response $response, $name, $path = '', $expire = null, $cipherAlgorithm = false, $shared_urls = null, $standalone = false, $secure = false)
    {
        $app = App::getApp();
        $mainConfig = $app->getMainConfiguration();
        $cookieIv = $mainConfig->getString('cookie_iv', '2f57912d7bd53cc6f8e8af813c8d738a');
        $cookieKey = $mainConfig->getString('cookie_key', 'ra51pnvb');
        
        $this->content = array();
        $expire = is_null($expire) ? time() + 1728000 : (int) $expire;
        $path = trim(($standalone ? '' : $request->getContextPath()).$path, '/\\').'/';
        if ($path{0} != '/') {
            $path = '/'.$path;
        }
        $path = rawurlencode($path);
        $path = str_replace('%2F', '/', $path);
        $path = str_replace('%7E', '~', $path);
        $domain = $this->getDomain($request, $shared_urls);
        $name = 'MskApplication-'.md5(($standalone ? '' : $app->getVersion()).$name.$domain);
        $this->salt = $standalone ? str_pad('', 8, md5('lib'.__FILE__)) : $cookieIv;
        
        
        if ($standalone) {
            $this->cipherTool = new Blowfish(str_pad('', 56, md5('lib'.__FILE__)), str_pad('', 56, md5('iv'.__FILE__)));
        } elseif (!$cipherAlgorithm || !$mainConfig->containsKey('rijndael_key')) {
            $this->cipherTool = new Blowfish($cookieKey, $cookieIv);
        } else {
            $this->cipherTool = new Rijndael($mainConfig->getString('rijndael_key', ''), $mainConfig->getString('rijndael_iv'));
        }
        $this->cookie = App::https()->createCookie($name, null, $expire, $path, $domain, $secure, true);
        $response->addSendingListener(App::https()->createDefaultResponseSendingListener(function($event){
            $this->save($event->getSource());
        }));
        $this->update($request);
    }

    protected function getDomain(Request $request, $shared_urls = null)
    {
        $serverHost = $request->getServerName();
		$out = array();
		$res = array();
        $r = '!(?:(\w+)://)?(?:(\w+)\:(\w+)@)?([^/:]+)?(?:\:(\d*))?([^#?]+)?(?:\?([^#]+))?(?:#(.+$))?!i';

        if (!preg_match($r, $serverHost, $out) || !isset($out[4])) {
            return false;
        }

        if (preg_match('/^(((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]{1}[0-9]|[1-9]).)'.
            '{1}((25[0-5]|2[0-4][0-9]|[1]{1}[0-9]{2}|[1-9]{1}[0-9]|[0-9]).)'.
            '{2}((25[0-5]|2[0-4][0-9]|[1]{1}[0-9]{2}|[1-9]{1}[0-9]|[0-9]){1}))$/', $out[4])) {
            return false;
        }
        if (!strstr($serverHost, '.')) {
            return false;
        }

        $domain = false;
        if ($shared_urls !== null) {
            foreach ($shared_urls as $shared_url) {
                if ($shared_url != $out[4]) {
                    continue;
                }
                if (preg_match('/^(?:.*\.)?([^.]*(?:.{2,4})?\..{2,3})$/Ui', $shared_url, $res)) {
                    $domain = '.'.$res[1];
                    break;
                }
            }
        }
        if (!$domain) {
            $domain = $out[4];
        }

        return $domain;
    }

    /**
     * Get cookie content.
     */
    protected function update(Request $request)
    {
        $name = $this->cookie->getName();
        if ($request->containsCookie($name)) {
            /* Decrypt cookie content */
            $content = $this->cipherTool->decrypt($request->getCookieValue($name));
            //printf("\$content = %s<br />", $content);

            /* Get cookie checksum */
            $tmpTab = explode('¤', $content);
            array_pop($tmpTab);
            $content_for_checksum = implode('¤', $tmpTab).'¤';
            $checksum = crc32($this->salt.$content_for_checksum);
            //printf("\$checksum = %s<br />", $checksum);

            /* Unserialize cookie content */
            $tmpTab = explode('¤', $content);
            foreach ($tmpTab as $keyAndValue) {
                $tmpTab2 = explode('|', $keyAndValue);
                if (count($tmpTab2) == 2) {
                    $this->content[$tmpTab2[0]] = $tmpTab2[1];
                }
            }
            /* Check if cookie has not been modified */
            if (!isset($this->content['checksum']) || $this->content['checksum'] != $checksum) {
                $this->removeAllValues();
            }

            if (!isset($this->content['date_add'])) {
                $this->content['date_add'] = date('Y-m-d H:i:s');
            }
        } else {
            $this->content['date_add'] = date('Y-m-d H:i:s');
        }
    }

    public function hasValue($name)
    {
		return isset($this->content[$name]);
    }
	
	public function addValue($name, $value)
    {
        $this->setValue($name, $value);
    }
	
	public function setValue($name, $value)
    {
		if (!is_array($value)) {
			if (preg_match('/¤|\|/', $name.$value)) {
				throw new \Exception('Forbidden chars in cookie');
			}
			if (!$this->modified && (!isset($this->content[$name]) || (isset($this->content[$name]) && $this->content[$name] != $value))) {
				$this->modified = true;
			}
			$this->content[$name] = $value;
		}
    }
	
    public function getValue($name, $defaultValue = null)
    {
        return $this->hasValue($name) ? $this->content[$name] : $defaultValue;
    }
	
	public function getValuesByPrefix($prefix)
    {
		$result = array();
		foreach ($this->content as $key => $value) {
			
			if (strncmp($key, $prefix, strlen($prefix)) == 0) {
				$result[$key] = $value;
			}
		}

		return $result;
    }
	
	public function getAllValues()
    {
        return $this->content;
    }
	
	public function removeValue($name)
    {
        if ($this->hasValue($name)) {
			$this->modified = true;
			unset($this->content[$name]);
		}
    }
	
	public function removeValuesByPrefix($prefix)
    {
        $names = array_keys($this->getValuesByPrefix($prefix));
		foreach ($names as $name) {
			$this->removeValue($name);
		}
    }
	
	public function removeAllValues()
    {
        $this->content = array();
        $this->modified = true;
    }
    
    public function addValuesFromArray($array)
    {
        if(is_array($array)){
            foreach ($array as $key => $value) {
                $this->addValue($key, $value);
            }
        }
    }
    
    public function addArrayValue($name, $array)
    {
        $this->addValue($name, serialize($array));
    }
    
    public function getArrayValues($name)
    {
        $result = array();
        $value = $this->getValue($name);
        if(!empty($value)){
            $value = unserialize($value);
            if(is_array($value)){
                $result = $value;
            }
        }
        return $result;
    }

    public function save(Response $response)
    {
        if (!$this->modified || headers_sent()) {
            return;
        }
        
        $cookie = '';
        
        /* Serialize cookie content */
        if (isset($this->content['checksum'])) {
            unset($this->content['checksum']);
        }
        foreach ($this->content as $key => $value) {
            $cookie .= $key.'|'.$value.'¤';
        }
        
        /* Add checksum to cookie */
        $cookie .= 'checksum|'.crc32($this->salt.$cookie);
        $this->modified = false;
        /* Cookies are encrypted for evident security reasons */
        
        
        // Check if the content fits in the Cookie
        $length = (ini_get('mbstring.func_overload') & 2) ? mb_strlen($cookie, ini_get('default_charset')) : strlen($cookie);
        if ($length >= 1048576) {
            return false;
        }
        if ($cookie) {
            $content = $this->cipherTool->encrypt($cookie);
        } else {
            $content = 0;
            $this->cookie->setExpire(1);
        }
        
        
        $this->cookie->setValue($content);
        $response->addCookie($this->cookie);
    }
}
