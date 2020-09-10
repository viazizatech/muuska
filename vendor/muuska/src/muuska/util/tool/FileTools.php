<?php
namespace muuska\util\tool;
use muuska\constants\FileTypeConst;
use muuska\constants\FolderPath;
use muuska\util\App;
use muuska\util\MimeType;
use muuska\constants\FieldNature;
class FileTools
{
	const FOLDER_TREE_SEPARATOR = '/';
	
	private static $finalFilesCache = array();
	
	protected static $extensionsByType = array(
		FileTypeConst::IMAGE => array('jpeg', 'jpg', 'png', 'gif', 'svg', 'bmp', 'yuv', 'tif', 'tiff', 'thm', 'tga', 'psd', 'dds', 'pspimage'),
		FileTypeConst::PDF => array('pdf'),
		FileTypeConst::DOC => array('doc', 'docx', 'odt', 'rtf', 'wpd', 'wps', 'dotx'),
		FileTypeConst::FONT => array('fnt', 'ttf', 'fon', 'woff', 'txf', 'ttc', 'tte', 'ext', 'eot'),
		FileTypeConst::COMPRESSED => array('7z', 'cbr', 'deb', 'gz', 'pkg', 'rar', 'rpm', 'stx', 'zip', 'zipx'),
		FileTypeConst::EXECUTABLE => array('apk', 'app', 'bat', 'cgi', 'com', 'exe', 'gadget', 'jar', 'wsf'),
		FileTypeConst::AUDIO => array('aif', 'iff', 'm3u', 'm4a', 'mid', 'mp3', 'mpa', 'wav', 'wma', 'amr', 'au', 'omg', 'ogg', 'wave'),
		FileTypeConst::DATABASE => array('accdb', 'sql'),
		FileTypeConst::VIDEO => array('mp4', 'mpg', 'avi', 'mov', 'flv', 'webm', 'mkv', 'vob', 'ogv', 'ogg', 'drc', 'mng', 'mp2', 'mpeg', 'qt', 'srt', 'swf',
			'mpe', 'mpv', 'm2v', 'm4v', '3gp', '3g2', 'f4v', 'f4p', 'f4a', 'f4b', 'nsv', 'roq', 'mxf', 'amv', 'asf', 'rmvb', 'rm', 'yuv', 'wmv', 'gifv', 'gif'),
	);
    
	protected static $instance;
	
	protected function __construct(){}
	
    /**
     * @return \muuska\util\tool\FileTools
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }
    
    /**
     * @param string $directory
     * @return string
     */
    public function normalizeDirectory($directory)
    {
        return rtrim($directory, '/\\').DIRECTORY_SEPARATOR;
    }
	
	/**
	 * @param string $file
	 * @return string
	 */
	public function standardizeFile($file)
    {
        return str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, $file);
    }
	
	/**
	 * @param string $fileName
	 * @return string
	 */
	public function resolveFilename($fileName)
	{
		$fileName = $this->standardizeFile(str_replace('//', '/', $fileName));
		$parts = explode(DIRECTORY_SEPARATOR, $fileName);
		$out = array();
		foreach ($parts as $part){
			if ($part == '.') continue;
			if ($part == '..') {
				array_pop($out);
				continue;
			}
			$out[] = $part;
		}
		return implode(DIRECTORY_SEPARATOR, $out);
	}
	
	/**
	 * @param string $name
	 * @return string
	 */
	public function getFileExtension($name)
    {
		return strtolower(pathinfo($name, PATHINFO_EXTENSION));
	}
	
	/**
	 * @param string $string
	 * @return string
	 */
	public function getExtensionFromString($string)
    {
		$extension = '';
		$lastDotIndex = strrpos($string, '.');
		if($lastDotIndex !== false){
			$extensionIndex = ($lastDotIndex + 1);
			if($extensionIndex < strlen($string)){
				$extension = substr($string, $extensionIndex);
			}
		}
		return $extension;
	}
	
	/**
	 * @param string $fileName
	 * @param string $newExtension
	 * @return string
	 */
	public function replaceFileExtension($fileName, $newExtension)
    {
		$extension = $this->getFileExtension($fileName);
		return str_replace('.'.$extension, '.'.$newExtension, $fileName);
	}
	
	/**
	 * @param int $type
	 * @return array
	 */
	public function getExtensionsByFileType($type)
    {
        $types = array($type);
	    if($type == FileTypeConst::ALL){
	        $types = array_keys(self::$extensionsByType);
	    }
	    return $this->getExtensionsByFileTypes($types);
	}
	
	/**
	 * @param array $types
	 * @return array
	 */
	public function getExtensionsByFileTypes($types)
	{
	    if(!is_array($types) && ($types == FileTypeConst::ALL)){
	        $types = array_keys(self::$extensionsByType);
	    }else{
	        $types = is_array($types) ? $types : array($types);
	    }
	    $extensions = array();
	    foreach($types as $value){
	        if(isset(self::$extensionsByType[$value])){
	            $extensions = array_merge($extensions, self::$extensionsByType[$value]);
	        }
	    }
	    return $extensions;
	}
	
	/**
	 * @param array $dirs
	 * @param boolean $recursively
	 * @param boolean $onlyFiles
	 * @param boolean $onlyFolders
	 * @param string $fileExtension
	 * @param array $dataToExclude
	 * @param string $tabKeyPrefix
	 * @return string[]
	 */
	public function getDirectoryContent($dirs, $recursively = false, $onlyFiles = false, $onlyFolders = false, $fileExtension = null, $dataToExclude = array(), $tabKeyPrefix = '')
    {
		$stringTools = App::getStringTools();
        $result = array();
		$dirs = is_array($dirs) ? $dirs : array($dirs);
		foreach($dirs as $dir){
			if (is_dir($dir)) {
				$dir = ($stringTools->endsWith($dir, '/') || $stringTools->endsWith($dir, '\\')) ? $dir : $dir.DIRECTORY_SEPARATOR;
				$files = file_exists($dir) ? scandir($dir) :array();
				foreach ($files as $fileName) {
					if (($fileName[0] != '.') && !in_array($fileName, $dataToExclude) && ($fileName != 'index.php')) {
						$fullPath = $dir.$fileName;
						$key = $tabKeyPrefix . $fileName;
						if(is_dir($fullPath)){
							if(!$onlyFiles){
								$result[$key] = $fullPath;
							}
							if($recursively){
								$newTableKeyPrefix = $tabKeyPrefix . $fileName . self::FOLDER_TREE_SEPARATOR;
								$result += $this->getDirectoryContent($fullPath.DIRECTORY_SEPARATOR, $recursively, $onlyFiles, $onlyFolders, $fileExtension, $dataToExclude, $newTableKeyPrefix);
							}
						}elseif(!$onlyFolders && (($fileExtension === null) || $stringTools->endsWith($fileName, $fileExtension))){
							$result[$key] = $fullPath;
						}
					}
				}
			}
		}
        return $result;
    }
	
	/**
	 * @param string $path
	 * @param string $baseDir
	 * @param boolean $keepRelativePath
	 * @param string $extensionToRemove
	 * @return string
	 */
	public function getFileNameFromPath($path, $baseDir = '', $keepRelativePath = false, $extensionToRemove = null)
    {
		$fileName = str_replace('\\', '/', $path);
		$baseDir = str_replace('\\', '/', $baseDir);
		$fileSuffix = empty($extensionToRemove) ? '' : '.'.$extensionToRemove;
		if(!empty($baseDir)){
			$fileName = str_replace($baseDir, '', $fileName);
		}
		if(empty($baseDir) || !$keepRelativePath){
			$tab = explode('/', $fileName);
			$fileName = end($tab);
		}
		$fileName = str_replace($fileSuffix, '', $fileName);
        return $fileName;
    }
	
	/**
	 * @param string $destination
	 * @return bool
	 */
	public function createIndexFile($destination)
    {
	    $indexFileContent= '<?php
                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
            
                header("Cache-Control: no-store, no-cache, must-revalidate");
                header("Cache-Control: post-check=0, pre-check=0", false);
                header("Pragma: no-cache");
            
                header("Location: ../");
                exit;
            
            ?>';
		$stringTools = App::getStringTools();
		$destination = ($stringTools->endsWith($destination, '/') || $stringTools->endsWith($destination, '\\')) ? $destination : $destination.DIRECTORY_SEPARATOR;
		return file_put_contents($destination.'index.php', $indexFileContent);
    }
    
	/**
	 * @param string $fileName
	 * @param boolean $addIndexFileInDir
	 * @return boolean
	 */
	public function createFile($fileName, $addIndexFileInDir = false)
    {
		$result = false;
		$fileName = str_replace('\\', '/', $fileName);
		$tab = explode('/', $fileName);
		if(!empty($tab)){
			$newTab = array_slice($tab, 0, (count($tab)-1));
			$dir = implode('/', $newTab);
			if(file_exists($dir) || $this->createDirectory($dir, $addIndexFileInDir)){
				$result = touch($fileName);
			}
		}
		return $result;
    }
    
    /**
     * @param string $dir
     * @param boolean $addIndexFile
     * @return boolean
     */
    public function createDirectory($dir, $addIndexFile = false)
    {
		$result = false;
		if($addIndexFile){
			$dir = str_replace('\\', '/', $dir);
			$dir = str_replace('//', '/', $dir);
			$tabDir = explode('/', $dir);
			$tabLength = count($tabDir);
			$fileExist = false;
			$index = $tabLength-1;
			while(!$fileExist && ($index>0)){
				$newTab = array_slice($tabDir, 0, $index);
				if(file_exists(implode('/', $newTab))){
					$fileExist = true;
				}else{
					$index--;
				}
			}
			$result = true;
			for($i=$index+1; $i <= $tabLength; $i++){
				if(!isset($tabDir[$i]) || !empty($tabDir[$i])){
					$newTab = array_slice($tabDir, 0, $i);
					$newDir = implode('/', $newTab);
					$result = ($result && mkdir($newDir, 0777, false) && $this->createIndexFile($newDir));
				}
			}
		}else{
			$result = mkdir($dir, 0777, true);
		}
		return $result;
    }
    
    /**
     * @param string $dir
     * @param boolean $addIndexFile
     * @return boolean
     */
    public function createDirectoryIfNotExist($dir, $addIndexFile = false)
    {
		$result = true;
		if(!file_exists($dir)){
			$result = $this->createDirectory($dir, $addIndexFile);
		}
		return $result;
    }
    
    /**
     * @param string $fileName
     * @param mixed $content
     * @param int $flags
     * @param resource $context
     * @return bool
     */
    public function filePutContents($fileName, $content, $flags = null, $context = null){
        if(!file_exists($fileName)){
            $this->createFile($fileName, false);
        }
        return file_put_contents($fileName, $content, $flags, $context);
    }
	
	/**
	 * @param string $fileName
	 * @param array $data
	 * @param boolean $saveAsKeyValueArray
	 * @return boolean
	 */
	public function saveXMLContentFromArray($fileName, $data, $saveAsKeyValueArray = true)
    {
		$fileExist = true;
		if(!file_exists($fileName)){
			$fileExist = $this->createFile($fileName, true);
		}
		if($fileExist && !empty($data)){
			$dom = new \DOMDocument;
			$dom->formatOutput = true;
			$items = $dom->createElement('items');
			$dom->appendChild($items);
			foreach ($data as $key => $row){
				$item = $dom->createElement('item');
				if($saveAsKeyValueArray || !is_array($row)){
					$item->setAttribute('name', $key);
					$item->setAttribute('value', $row);
					$items->appendChild($item);
				}elseif(is_array($row)){
					foreach ($row as $rowKey => $rowValue){
						$item->setAttribute($rowKey, $rowValue);
					}
					$items->appendChild($item);
				}
			}
			$dom->save($fileName);
		}
		return true;
    }
	
	/**
	 * @param string $file
	 * @param boolean $resultAsKeyValueArray
	 * @param array $arrayModel
	 * @return array
	 */
	public function getArrayFromXML($file, $resultAsKeyValueArray = true, $arrayModel = array())
    {
		$result = array();
		$dom = new \DOMDocument;
		if(file_exists($file)){
            $dom->load($file);
			$list = $dom->getElementsByTagName('item');
			foreach ($list as $item){
				if($resultAsKeyValueArray){
					$name = $item->getAttribute('name');
					$result[$name] = $item->getAttribute('value');
				}else{
					$row = array();
					foreach ($arrayModel as $attributeName){
						$row[$attributeName] = $item->getAttribute($attributeName);
					}
					$result[] = $row;
				}
			}
        }
		return $result;
    }
	
	/**
	 * @param string $file
	 * @param array $fieldsDefinition
	 * @param string $itemTagName
	 * @param string $mainLang
	 * @param boolean $getOtherLangsValues
	 * @return array
	 */
	public function getDataListFromXML($file, $fieldsDefinition, $itemTagName = '', $mainLang = '', $getOtherLangsValues = true)
    {
		$result = array();
		if(empty($itemTagName)){
			$itemTagName = 'item';
		}
		$dom = new \DOMDocument;
		if(file_exists($file)){
			
            $dom->load($file);
			$list = $dom->getElementsByTagName($itemTagName);
			foreach ($list as $item){
				$simpleFieldsValues = array();
				$langFieldsValues = array();
				foreach ($item->childNodes as $childNode) {
					if($childNode->nodeType == XML_ELEMENT_NODE){
						$tagName = $childNode->tagName;
						$fieldValueKey = $tagName;
						$translatable = false;
						if(isset($fieldsDefinition[$tagName])){
							$fieldValueKey = isset($fieldsDefinition[$tagName]['fieldName']) ? $fieldsDefinition[$tagName]['fieldName'] : $tagName;
							$translatable = isset($fieldsDefinition[$tagName]['translatable']) ? $fieldsDefinition[$tagName]['translatable'] : false;
						}
						if(!$translatable){
							$simpleFieldsValues[$fieldValueKey] = $childNode->nodeValue;
						}else{
							$langNodeFound = false;
							$defaultLangValue = '';
							if($childNode->hasChildNodes()){
								foreach ($childNode->childNodes as $subChildNode) {
									if($subChildNode->nodeType == XML_ELEMENT_NODE){
										$lang = $subChildNode->getAttribute('lang');
										$value = $childNode->nodeValue;
										$langFieldsValues[$fieldValueKey][$lang] = $value;
										if($lang == $mainLang){
											$defaultLangValue = $value;
										}elseif(empty($mainLang) && empty($defaultLangValue)){
											$defaultLangValue = $value;
										}
									}
								}
							}
							if(!$langNodeFound){
								$defaultLangValue = $childNode->nodeValue;
							}
							$simpleFieldsValues[$fieldValueKey] = $defaultLangValue;
						}
					}
				}
				$row = $simpleFieldsValues;
				if($getOtherLangsValues){
					$row['langFieldsValues'] = $langFieldsValues;
				}
				$result[] = $row;
			}
        }
		return $result;
    }
    
	/**
	 * @param string $file
	 * @param array $fieldsDefinition
	 * @param string $itemTagName
	 * @param string $mainLang
	 * @param boolean $getOtherLangsValues
	 * @return array
	 */
	public function getDataItemFromXML($file, $fieldsDefinition, $itemTagName = '', $mainLang = '', $getOtherLangsValues = true)
    {
		$list = $this->getDataListFromXML($file, $fieldsDefinition, $itemTagName, $mainLang, $getOtherLangsValues);
		$result = isset($list[0]) ? $list[0] : null;
		return $result;
    }
	
	/**
	 * @param string $fileName
	 * @param array $data
	 * @return boolean
	 */
	public function writePhpDefineValues($fileName, $data){
        $handle = fopen($fileName, "w+");
        if ($handle !== false) {
            fputs($handle, '<?php');
			if(is_array($data)){
				foreach ($data as $key => $value){
					$finalValue = str_replace('\'', '\\\'', $value);
					
					$declaration = 'define(\'' . $key . '\', \'' . $finalValue . '\');';
					fputs($handle, PHP_EOL . $declaration);
				}
			}
            fputs($handle, PHP_EOL . '?>');
            fclose($handle);
        }else {
            return false;
        }
        return true;
    }
	
	/**
	 * @param string $fileName
	 * @param string $extension
	 * @return string
	 */
	public function getMimeTypeByExtension($fileName, $extension = null) {
		if(empty($extension)){
			$extension = $this->getFileExtension($fileName);
		}
		return MimeType::getMimeTypeByExtension($extension);
	}
	
	/**
	 * @param string $fileName
	 * @param string $extension
	 * @param array $httpHeaderAdditionals
	 * @return bool
	 */
	public function outputFile($fileName, $extension = null, $httpHeaderAdditionals = array()) {
		if(empty($extension)){
			$extension = $this->getFileExtension($fileName);
		}
		$mimeType = $this->getMimeTypeByExtension($fileName, $extension);
		if(!is_file($fileName)) {
			header('HTTP/1.0 404 Not Found');
			return 404;
		}

		if(!is_readable($fileName)) {
			header('HTTP/1.0 403 Forbidden');
			return 403;
		}

		$stat = @stat($fileName);
		$etag = sprintf('%x-%x-%x', $stat['ino'], $stat['size'], $stat['mtime'] * 1000000);

		header('Expires: ');
		header('Cache-Control: ');
		header('Pragma: ');

		if(isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $etag) {
			header('Etag: "' . $etag . '"');
			header('HTTP/1.0 304 Not Modified');
			return 304;
		} elseif(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $stat['mtime']) {
			header('Last-Modified: ' . date('r', $stat['mtime']));
			header('HTTP/1.0 304 Not Modified');
			return 304;
		}

		header('Last-Modified: ' . date('r', $stat['mtime']));
		header('Etag: "' . $etag . '"');
		header('Accept-Ranges: bytes');
		/*header('Content-Length:' . $stat['size']);*/
		/*header('Content-Length:' . filesize($fileName));*/
		if(!empty($mimeType)){
			header('Content-Type: '.$mimeType);
		}
		foreach($httpHeaderAdditionals as $header) {
			header($header);
		}

		if(@readfile($fileName) === false) {
			header('HTTP/1.0 500 Internal Server Error');
			return 500;
		} else {
			//header('HTTP/1.0 200 OK');
			return 200;
		}
		
		if(!empty($mimeType)){
			header('Content-Type: '.$mimeType);
		}
		header('X-Sendfile: '.$fileName);
	}
	
	/**
	 * @param string $file
	 * @return array
	 */
	public function getArrayFromJsonFile($file) {
	    $array = array();
	    if(file_exists($file)){
	        $array = json_decode(file_get_contents($file), true);
	        if(!is_array($array)){
	            $array = array();
	        }
	    }
	    return $array;
	}
	
	/**
	 * @param string $dir
	 */
	public function clearDirectoryContent($dir) {
	    $files = glob($dir.'/*');
	    foreach($files as $file){
	        if(is_file($file)){
	            unlink($file);
	        }
	    }
	}
	
	/**
	 * @param string $str
	 * @return bool
	 */
	public function recursiveDelete($str) {
	    if (is_file($str)) {
	        return @unlink($str);
	    }
	    elseif (is_dir($str)) {
	        $scan = glob(rtrim($str,'/').'/*');
	        foreach($scan as $path) {
	            $this->recursiveDelete($path);
	        }
	        return @rmdir($str);
	    }
	}
	
	/**
	 * @param string $source
	 * @param string $destination
	 */
	public function copyDirContent($source, $destination) {
	    if (file_exists($source)) {
	        $dir = opendir($source);
	        $source = $this->standardizeFile($this->normalizeDirectory($source));
	        $destination = $this->standardizeFile($this->normalizeDirectory($destination));
	        $this->createDirectoryIfNotExist($destination);
	        while(false !== ( $file = readdir($dir)) ) {
	            if (( $file != '.' ) && ( $file != '..' )) {
	                if ( is_dir($source . $file) ) {
	                    $this->copyDirContent($source . $file, $destination . $file);
	                } else {
	                    copy($source . $file, $destination . $file);
	                }
	            }
	        }
	        closedir($dir);
	    }
	}
	
	/**
	 * @param string $coreDir
	 * @param string $subPathInApp
	 */
	public function copyAssets($coreDir, $subPathInApp) {
	    $this->copyDirContent($coreDir . FolderPath::ASSETS, App::getApp()->getPublicDir().FolderPath::ASSETS . '/' . $subPathInApp);
	}
	
	/**
	 * @param string $fileName
	 * @return string
	 */
	public function base64EncodeImage($fileName) {
	    $result = '';
	    if (file_exists($fileName)) {
	        $fileType = $this->getMimeTypeByExtension($fileName);
	        $imgBinary = fread(fopen($fileName, "r"), filesize($fileName));
	        $result = 'data:image/' . $fileType . ';base64,' . base64_encode($imgBinary);
	    }
	    return $result;
	}
	
	public function getAllowedExtensions($fieldDefinition) {
	    $result = array();
	    if (isset($fieldDefinition['allowedExtensions'])) {
	        $result = $fieldDefinition['allowedExtensions'];
	    }else{
	        if (isset($fieldDefinition['nature']) && ($fieldDefinition['nature'] == FieldNature::IMAGE)) {
	            $result = $this->getExtensionsByFileType(FileTypeConst::IMAGE);
	        }elseif (isset($fieldDefinition['nature']) && ($fieldDefinition['nature'] == FieldNature::IMAGE)){
	            if(isset($fieldDefinition['fileType'])){
	                $result = $this->getExtensionsByFileType($fieldDefinition['fileType']);
	            }elseif(isset($fieldDefinition['fileTypes'])){
	                $result = $this->getExtensionsByFileTypes($fieldDefinition['fileTypes']);
	            }
	        }
	    }
	    return $result;
	}
}
