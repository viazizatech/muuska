<?php
namespace muuska\util\tool;

use muuska\asset\constants\AssetNames;
use muuska\constants\FolderPath;
use muuska\constants\Names;
use muuska\constants\operator\Operator;
use muuska\translation\constants\ThemeTranslationPrefix;
use muuska\util\App;

class Tools
{
	protected static $instance = null;
	
    /**
     * @return \muuska\util\tool\Tools
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }
	
    /**
    * Random password generator
    *
    * @param int $length Desired length (optional)
    * @param string $flag Output type (NUMERIC, ALPHANUMERIC, NO_NUMERIC, RANDOM)
    * @return bool|string Password
    */
    public function generatePassword($length = 8, $flag = 'ALPHANUMERIC')
    {
        $length = (int)$length;

        if ($length <= 0) {
            return false;
        }

        switch ($flag) {
            case 'NUMERIC':
                $str = '0123456789';
                break;
            case 'NO_NUMERIC':
                $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'RANDOM':
                $num_bytes = ceil($length * 0.75);
                $bytes = $this->getBytes($num_bytes);
                return substr(rtrim(base64_encode($bytes), '='), 0, $length);
            case 'ALPHANUMERIC':
            default:
                $str = 'abcdefghijkmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
        }

        $bytes = $this->getBytes($length);
        $position = 0;
        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $position = ($position + ord($bytes[$i])) % strlen($str);
            $result .= $str[$position];
        }

        return $result;
    }

    /**
     * @param int $length
     * @return string
     */
    public function getBytes($length)
    {
        $length = (int)$length;

        if ($length <= 0) {
            return false;
        }

        if (function_exists('openssl_random_pseudo_bytes')) {
            $crypto_strong = null;
            $bytes = openssl_random_pseudo_bytes($length, $crypto_strong);

            if ($crypto_strong === true) {
                return $bytes;
            }
        }

        if (function_exists('mcrypt_create_iv')) {
            $bytes = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);

            if ($bytes !== false && strlen($bytes) === $length) {
                return $bytes;
            }
        }

        $result         = '';
        $entropy        = '';
        $msec_per_round = 400;
        $bits_per_round = 2;
        $total          = $length;
        $hash_length    = 20;

        while (strlen($result) < $length) {
            $bytes  = ($total > $hash_length) ? $hash_length : $total;
            $total -= $bytes;

            for ($i=1; $i < 3; $i++) {
                $t1 = microtime(true);
                $seed = mt_rand();

                for ($j=1; $j < 50; $j++) {
                    $seed = sha1($seed);
                }

                $t2 = microtime(true);
                $entropy .= $t1 . $t2;
            }

            $div = (int) (($t2 - $t1) * 1000000);

            if ($div <= 0) {
                $div = 400;
            }

            $rounds = (int) ($msec_per_round * 50 / $div);
            $iter = $bytes * (int) (ceil(8 / $bits_per_round));

            for ($i = 0; $i < $iter; $i ++) {
                $t1 = microtime();
                $seed = sha1(mt_rand());

                for ($j = 0; $j < $rounds; $j++) {
                    $seed = sha1($seed);
                }

                $t2 = microtime();
                $entropy .= $t1 . $t2;
            }

            $result .= sha1($entropy, true);
        }

        return substr($result, 0, $length);
    }

    /**
    * Encrypt password
    *
    * @param string $password String to encrypt
    * @return string
    */
    public function encrypt($password)
    {
        return md5(App::getApp()->getMainConfiguration()->getString('cookie_key').$password);
    }

    /**
    * Encrypt data string
    *
    * @param string $data String to encrypt
    * @return string
    */
    public function encryptIV($data)
    {
        return md5(App::getApp()->getMainConfiguration()->getString('cookie_iv').$data);
    }

    /**
     * Delete unicode class from regular expression patterns
     * @param string $pattern
     * @return string pattern
     */
    public function cleanNonUnicodeSupport($pattern)
    {
        if (!defined('PREG_BAD_UTF8_OFFSET')) {
            return $pattern;
        }
        return preg_replace('/\\\[px]\{[a-z]{1,2}\}|(\/[a-z]*)u([a-z]*)$/i', '$1$2', $pattern);
    }


    /**
     * Allows to display the category description without HTML tags and slashes
     * @param string $description
     * @return string
    */
    public function getDescriptionClean($description)
    {
        return strip_tags(stripslashes($description));
    }

    /**
     * @param string $html
     * @param array $uri_unescape
     * @param boolean $allow_style
     * @param boolean $use_html_purifier
     * @param boolean $allow_html_iframe
     * @return string
     */
    public function purifyHTML($html, $uri_unescape = null, $allow_style = false, $use_html_purifier = true, $allow_html_iframe = false)
    {
        require_once(App::getApp()->getLibrariesDir().'htmlpurifier/HTMLPurifier.standalone.php');
		static $purifier = null;

        if ($use_html_purifier) {
            if ($purifier === null) {
                $config = \HTMLPurifier_Config::createDefault();

                $config->set('Attr.EnableID', true);
                $config->set('HTML.Trusted', true);
                $config->set('Cache.SerializerPath', App::getApp()->getCacheDir().'purifier');
                $config->set('Attr.AllowedFrameTargets', array('_blank', '_self', '_parent', '_top'));
                if (is_array($uri_unescape)) {
                    $config->set('URI.UnescapeCharacters', implode('', $uri_unescape));
                }

                if ($allow_html_iframe) {
                    $config->set('HTML.SafeIframe', true);
                    $config->set('HTML.SafeObject', true);
                    $config->set('URI.SafeIframeRegexp', '/.*/');
                }

                /** @var HTMLPurifier_HTMLDefinition|HTMLPurifier_HTMLModule $def */
                // http://developers.whatwg.org/the-video-element.html#the-video-element
                if ($def = $config->getHTMLDefinition(true)) {
                    $def->addElement('video', 'Block', 'Optional: (source, Flow) | (Flow, source) | Flow', 'Common', array(
                        'src' => 'URI',
                        'type' => 'Text',
                        'width' => 'Length',
                        'height' => 'Length',
                        'poster' => 'URI',
                        'preload' => 'Enum#auto,metadata,none',
                        'controls' => 'Bool',
                    ));
                    $def->addElement('source', 'Block', 'Flow', 'Common', array(
                        'src' => 'URI',
                        'type' => 'Text',
                    ));
                    if ($allow_style) {
                        $def->addElement('style', 'Block', 'Flow', 'Common', array('type' => 'Text'));
                    }
                }

                $purifier = new \HTMLPurifier($config);
            }
            if (App::getApp()->getMainConfiguration()->getBool('magis_quotes_gpc', true)) {
                $html = stripslashes($html);
            }

            $html = $purifier->purify($html);

            if (App::getApp()->getMainConfiguration()->getBool('magis_quotes_gpc', true)) {
                $html = addslashes($html);
            }
        }

        return $html;
    }
    
	/**
	 * @param string $class
	 * @return string
	 */
	public function getClassNameWithoutNamespace($class)
    {
		$bits = explode('\\', $class);
        return end($bits);
    }
    
    /**
     * @param \muuska\asset\Asset $assets
     * @return \muuska\asset\Asset
     */
    public function sortAssets($assets)
    {
        $result = $assets;
        usort($assets, function($asset1, $asset2){
            $value1 = (int)$asset1->getPosition();
            $value2 = (int)$asset2->getPosition();
            if($value1 === $value2){
                return 0;
            }else{
                return ($value1 < $value2) ? -1 : 1;
            }
        });
        return $result;
    }
    
    /**
     * @param string $relativeFile
     * @param boolean $relatedToTheme
     * @return string
     */
    public function getTranslationRelativeFileKey($relativeFile, $relatedToTheme = true){
        return $this->getThemeTranslationPrefix($relatedToTheme).$relativeFile;
    }
    
    /**
     * @param string $relatedToTheme
     * @return string
     */
    public function getThemeTranslationPrefix($relatedToTheme){
        return ($relatedToTheme ? ThemeTranslationPrefix::YES : ThemeTranslationPrefix::NO);
    }
    
    /**
     * @param string[] $scopes
     * @return string
     */
    public function getScopeFromArray($scopes){
        $string = '';
        $first = true;
        foreach ($scopes as $scope) {
            if($first){
                $string .= $scope;
                $first = false;
            }else{
                $string .= '["'.$scope.'"]';
            }
        }
        return $string;
    }
    
    /**
     * @param string $defaultJsScope
     * @param string $scope
     * @return string
     */
    public function concatTwoScopes($defaultJsScope, $scope)
    {
        if(empty($scope)){
            $scope = $defaultJsScope;
        }elseif (!empty($defaultJsScope)){
            $scope = $defaultJsScope . (App::getStringTools()->startsWith('[', $scope) ? '' : '.').$scope;
        }
        return $scope;
    }
    
    /**
     * @param string[] $scopes
     * @param \muuska\asset\AssetSetter $assetSetter
     * @param \muuska\asset\AssetTranslation $assetTranslation
     * @param string[] $innerScopes
     */
    public function formatAssetTranslation($scopes, \muuska\asset\AssetSetter $assetSetter, \muuska\asset\AssetTranslation $assetTranslation, $innerScopes = array()){
        $scope = App::getTools()->getScopeFromArray($scopes);
        $groupKey = 'SCOPE_'.$scope;
        if(!$assetSetter->hasAssetGroup($groupKey)){
            $assetSetter->addAssetGroup(App::assets()->createAssetGroup($groupKey, array(App::assets()->createJsArrayScope($scopes))));
        }
        if(!empty($innerScopes)){
            $allScopes = array_merge($scopes, $innerScopes);
            $allScopeStr = App::getTools()->getScopeFromArray($allScopes);
            $groupNewKey = 'SCOPE_'.$allScopeStr;
            if(!$assetSetter->hasAssetGroup($groupNewKey)){
                $assetSetter->addAssetGroup(App::assets()->createAssetGroup($groupNewKey, array(App::assets()->createJsArrayScope(array_merge(array($scope), $innerScopes)))));
            }
            $assetTranslation->setScope($allScopeStr);
        }else{
            $assetTranslation->setScope($scope);
        }
        $assetSetter->appendAssetToContainer(AssetNames::TRANSLATION_CONTAINER, $assetTranslation, true);
    }
    
    /**
     * @param \muuska\html\HtmlPage $htmlPage
     * @param \muuska\html\areacreator\AreaCreatorEditor $areaCreatorEditor
     * @param \muuska\util\theme\Theme $theme
     * @param \muuska\config\Configuration $mainConfig
     * @param string $templateConfigKey
     * @param string $defaultTemplateFile
     */
    public function autoFormatHtmlPage(\muuska\html\HtmlPage $htmlPage, \muuska\html\areacreator\AreaCreatorEditor $areaCreatorEditor, \muuska\util\theme\Theme $theme, \muuska\config\Configuration $mainConfig = null, $templateConfigKey = '', $defaultTemplateFile = '')
    {
        if(!$htmlPage->hasRenderer()){
            $template = (($mainConfig !== null) && !empty($templateConfigKey)) ? $mainConfig->getString($templateConfigKey, $defaultTemplateFile) : $defaultTemplateFile;
            if(!empty($template)){
                $htmlPage->setRenderer($theme->createTemplate($template));
                $file = $this->getThemeFinalContentPositionDir($theme) . $template . '.json';
                $areaCreatorEditor->addContentPositions(App::getFileTools()->getArrayFromJsonFile($file));
            }
        }
    }
    
    /**
     * @param \muuska\util\theme\Theme $theme
     * @return string
     */
    public function getThemeFinalContentPositionDir(\muuska\util\theme\Theme $theme)
    {
        return App::getApp()->getRootConfigDir() . $theme->getSubPathInApp() . '/' . FolderPath::CONTENT_POSITIONS . '/';
    }
    
    /**
     * @param mixed $value
     * @param mixed $expectedValue
     * @param int $operator
     * @return boolean
     */
    public function checkValue($value, $expectedValue, $operator = null, $strict = false){
        $result = false;
        if(empty($operator) || ($operator == Operator::EQUALS)){
            $result = $strict ? ($value === $expectedValue) : ($value === $expectedValue);
        }elseif($operator == Operator::DIFFERENT){
            $result = $strict ? ($value !== $expectedValue) : ($value != $expectedValue);
        }elseif($operator == Operator::CONTAINS){
            $result = (strpos($value, $expectedValue) !== false);
        }elseif($operator == Operator::NOT_CONTAINS){
            $result = (strpos($value, $expectedValue) === false);
        }elseif($operator == Operator::START_WITH){
            $result = App::getStringTools()->startsWith($value, $expectedValue);
        }elseif($operator == Operator::NOT_START_WITH){
            $result = !App::getStringTools()->startsWith($value, $expectedValue);
        }elseif($operator == Operator::END_WITH){
            $result = App::getStringTools()->endsWith($value, $expectedValue);
        }elseif($operator == Operator::NOT_END_WITH){
            $result = !App::getStringTools()->endsWith($value, $expectedValue);
        }elseif($operator == Operator::IN_LIST){
            $result = (is_array($expectedValue) && in_array($value, $expectedValue, $strict));
        }elseif($operator == Operator::NOT_IN_LIST){
            $result = (!is_array($expectedValue) || !in_array($value, $expectedValue, $strict));
        }elseif(($operator == Operator::BETWEEN) || ($operator == Operator::NOT_BETWEEN)){
            if(is_array($expectedValue) && isset($expectedValue[0]) && isset($expectedValue[1])){
                $tmpResult = (($value >= $expectedValue[0]) && ($value <= $expectedValue[1]));
                $result = ($operator == Operator::BETWEEN) ? $tmpResult : !$tmpResult;
            }
        }
        return $result;
    }
    
    /**
     * @param \muuska\html\HtmlContent $mainContent
     * @param string[][] $allAlerts
     * @return \muuska\html\areacreator\DefaultAreaCreator
     */
    public function createPageAreaCreator(\muuska\html\HtmlContent $mainContent = null, $allAlerts = array()) {
        $areaCreator = App::htmls()->createDefaultAreaCreator();
        
        if(($mainContent !== null) ){
            if(($mainContent instanceof \muuska\html\HtmlComponent) || ($mainContent instanceof \muuska\html\HtmlString)){
                $mainContent->setName(Names::MAIN_CONTENT);
            }
            $areaCreator->addContentCreator($mainContent);
        }
        
        if (is_array($allAlerts)) {
            foreach ($allAlerts as $alertType => $messages) {
                if(!empty($messages)){
                    $alert = App::htmls()->createHtmlAlert($alertType, $messages);
                    $name = 'alert_'.$alertType;
                    $alert->setName($name);
                    $areaCreator->addContentCreator($alert);
                    $areaCreator->addContentAtPosition(Names::ALERT_POSITION, $name);
                }
            }
        }
        return $areaCreator;
    }
}
