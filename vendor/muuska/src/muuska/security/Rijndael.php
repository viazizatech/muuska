<?php
namespace muuska\security;
class Rijndael
{
    /**
     * @var string
     */
    protected $_key;
    
    /**
     * @var string
     */
    protected $_iv;

    /**
     * @param string $key
     * @param string $iv
     */
    public function __construct($key, $iv)
    {
        $this->_key = $key;
        $this->_iv = base64_decode($iv);
    }

    /**
     * Base64 is not required, but it is be more compact than urlencode
     *
     * @param string $plaintext
     * @return bool|string
     */
    public function encrypt($plaintext)
    {
        $length = (ini_get('mbstring.func_overload') & 2) ? mb_strlen($plaintext, ini_get('default_charset')) : strlen($plaintext);

        if ($length >= 1048576) {
            return false;
        }
        $ciphertext = null;
        if (function_exists('openssl_encrypt') && version_compare(phpversion(), '5.3.3', '>=')) {
            $ciphertext = openssl_encrypt($plaintext, 'AES-128-CBC', $this->_key, OPENSSL_RAW_DATA, $this->_iv);
        } elseif (function_exists('mcrypt_encrypt')) {
            $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->_key, $plaintext, MCRYPT_MODE_CBC, $this->_iv);
        } else {
            throw new \RuntimeException('Either Mcrypt or OpenSSL extension is required to run Prestashop');
        }
        return base64_encode($ciphertext) . sprintf('%06d', $length);
    }

    /**
     * @param string $ciphertext
     * @throws \RuntimeException
     * @return string
     */
    public function decrypt($ciphertext)
    {
        $output = null;
        if (ini_get('mbstring.func_overload') & 2) {
            $length = intval(mb_substr($ciphertext, -6, 6, ini_get('default_charset')));
            $ciphertext = mb_substr($ciphertext, 0, -6, ini_get('default_charset'));
            if (function_exists('openssl_decrypt') && version_compare(phpversion(), '5.3.3', '>=')) {
                $output = openssl_decrypt(base64_decode($ciphertext), 'AES-128-CBC', $this->_key, OPENSSL_RAW_DATA, $this->_iv);
            } elseif (function_exists('mcrypt_decrypt')) {
                $output = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->_key, base64_decode($ciphertext), MCRYPT_MODE_CBC, $this->_iv);
            } else {
                throw new \RuntimeException('Either Mcrypt or OpenSSL extension is required to run Prestashop');
            }
            return mb_substr(
                $output,
                0,
                $length,
                ini_get('default_charset')
            );
        } else {
            $length = intval(substr($ciphertext, -6));
            $ciphertext = substr($ciphertext, 0, -6);
            if (function_exists('openssl_decrypt') && version_compare(phpversion(), '5.3.3', '>=')) {
                $output = openssl_decrypt(base64_decode($ciphertext), 'AES-128-CBC', $this->_key, OPENSSL_RAW_DATA, $this->_iv);
            } elseif (function_exists('mcrypt_decrypt')) {
                $output = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->_key, base64_decode($ciphertext), MCRYPT_MODE_CBC, $this->_iv);
            } else {
                throw new \RuntimeException('Either Mcrypt or OpenSSL extension is required to run Prestashop');
            }
            return substr($output, 0, $length);
        }
    }
}
