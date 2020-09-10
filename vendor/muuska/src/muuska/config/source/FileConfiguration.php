<?php
namespace muuska\config\source;

use muuska\config\AbstractConfiguration;
use muuska\util\App;

abstract class FileConfiguration extends AbstractConfiguration{
    /**
     * @var string
     */
    protected $fileName;
    
    /**
     * @param string $fileName
     */
    public function __construct($fileName) {
        $this->fileName = $fileName;
        $this->load();
    }
    
    /**
     * @return string
     */
    protected function getFileContent() {
        return file_exists($this->fileName) ? file_get_contents($this->fileName) : '';
    }
    
    /**
     * @param string $content
     * @return bool
     */
    protected function saveContent($content) {
        return App::getFileTools()->filePutContents($this->fileName, $content);
    }
}