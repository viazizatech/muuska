<?php
namespace muuska\helper;

use muuska\util\App;

class UploadHelper extends AbstractHelper
{
    /**
     * @var string
     */
    protected $name = 'upload';
    
    /**
     * @var string[]
     */
    protected $excludedExtensions = array();
    
    /**
     * @var string[]
     */
    protected $extensionsToReplace = array('php' => '_php');
    
    /**
     * @var string[]
     */
    protected $allowedExtensions = array();
    
    /**
     * @param \muuska\controller\ControllerInput $input
     * @param string[] $allowedExtensions
     * @param string[] $excludedExtensions
     */
    public function __construct(\muuska\controller\ControllerInput $input, $allowedExtensions = array(), $excludedExtensions = array()){
        parent::__construct($input);
        $this->setAllowedExtensions($allowedExtensions);
        $this->setExcludedExtensions($excludedExtensions);
    }
    
    public function processUploadFile()
    {
        $result = array();
        $hasMoreSubFiles = $this->input->getParam('hasMoreSubFiles');
        $detailsSavingEnabled = $this->input->getParam('detailsSavingEnabled');
        $uploadResult = $this->doUpload('file', $this->input->getParam('isSubFile'), $this->input->getParam('mainFileName'), $hasMoreSubFiles, $detailsSavingEnabled, $this->input->getParam('unusedFileName'), $this->input->getParam('usedFileName'));
        if(!$hasMoreSubFiles){
            if(!$this->hasErrors()){
                $app = App::getApp();
                $fileUrl = $app->getUploadTmpFullUrl($uploadResult['newFile']);
                $result['filePreviewContent'] = $app->getFilePreview($this->input->getSubAppName(), $fileUrl, $uploadResult['fullFileName'], $uploadResult['newFile'], $detailsSavingEnabled, $uploadResult['object']);
                $result['fileValue'] = $uploadResult['newFile'];
                $result['fileUrl'] = $fileUrl;
            }
        }
        $result['mainFileName'] = $uploadResult['newFile'];
        return $result;
    }
    
    public function processDeleteFile()
    {
        $unusedFileName = $this->input->getParam('unusedFileName');
        $usedFileName = $this->input->getParam('usedFileName');
        $detailsSavingEnabled = $this->input->getParam('detailsSavingEnabled');
        if(!empty($unusedFileName) && ($unusedFileName!=$usedFileName)){
            if(!$this->deleteUploadFileFromName($unusedFileName, $detailsSavingEnabled, false)){
                $this->errors[] = $this->l('An error occurred while deleting file');
            }
        }
    }
    
    /**
     * @param string $extension
     * @return boolean
     */
    public function isExtensionAllowed($extension)
    {
        return (!in_array($extension, $this->excludedExtensions) && (empty($this->allowedExtensions) || in_array($extension, $this->allowedExtensions)));
    }
    
    public function doUpload($field = 'file', $isSubFile = false, $mainFileName = '', $hasMoreSubFiles = false, $detailsSavingEnabled = false, $unusedFileName = '', $usedFileName = '')
    {
        $newFile = '';
        $object = null;
        $files = $this->input->getRequest()->getFiles();
        $fileExtension = App::getFileTools()->getFileExtension($files[$field]['name']);
        $extensionInFileSystem = isset($this->extensionsToReplace[$fileExtension]) ? $this->extensionsToReplace[$fileExtension] : $fileExtension;
        $uploadDir = App::getApp()->getUploadTmpDir();
        App::getFileTools()->createDirectoryIfNotExist($uploadDir);
        $newFilePath = '';
        if(!$isSubFile && !empty($unusedFileName) && ($unusedFileName!=$usedFileName)){
            $this->deleteUploadFileFromName($detailsSavingEnabled, $unusedFileName, false);
        }
        if ($files[$field]['error'] > 0) {
            $this->errors[] = $this->l('An error occurred during the file upload process.');
        } elseif (!$this->isExtensionAllowed($fileExtension)) {
            $this->errors[] = $this->l('This file extension is not allowed.');
        }else {
            $newFile = ($isSubFile && !empty($mainFileName)) ? $mainFileName.'_tmp'.strtotime(date('Y-m-d H:i:s')) : $this->getUploadNewFileName($extensionInFileSystem);
            $newFilePath = $uploadDir. $newFile;
            if (!move_uploaded_file($files[$field]['tmp_name'], $newFilePath)) {
                $this->errors[] = $this->l('An error occurred during the file upload process');
            }
        }
        if($isSubFile && !empty($mainFileName) && !empty($newFilePath)){
            $existingFile = $uploadDir.$mainFileName;
            if(file_exists($existingFile)){
                $newFile = $mainFileName;
                if(file_put_contents($existingFile, file_get_contents($newFilePath), FILE_APPEND)===false){
                    $this->errors[] = $this->l('An error occurred adding content to main file');
                }
                @unlink($newFilePath);
            }else{
                $this->errors[] = $this->l('File not exist');
            }
        }
        if($detailsSavingEnabled && !$hasMoreSubFiles){
            /*$dao = $this->daoFactory->getDAO('muuska\model\Upload');
            $object = $dao->createModel();
            $object->setUsed(false);
            $object->setFileName($newFile);
            $object->setUniqueFileName($newFile);
            $object->setOriginalFileName($files [$field]['name']);
            if(!$dao->add($object)){
                $this->errors[] = $this->l('An error occurred while saving file');
            }*/
        }
        return array('newFile' => $newFile, 'extension' => $fileExtension, 'object' => $object, 'fullFileName' => $uploadDir.$newFile);
    }
    
    public function getUploadNewFileName($extension)
    {
        $fileName = '';
        $fileName = strtotime(date('Y-m-d H:i:s')).uniqid(rand()).'.'.$extension;
        return $fileName;
    }
    
    /**
     * @param string $fileName
     * @param boolean $detailsSavingEnabled
     * @param boolean $onlyFile
     * @return boolean
     */
    public function deleteUploadFileFromName($fileName, $detailsSavingEnabled = false, $onlyFile = false)
    {
        $result = true;
        $fullPath = App::getApp()->getUploadTmpFullFile($fileName);
        if(!$detailsSavingEnabled){
            @unlink($fullPath);
        }
        return $result;
    }
    
    /**
     * @return string[]
     */
    public function getExcludedExtensions()
    {
        return $this->excludedExtensions;
    }

    /**
     * @return string[]
     */
    public function getAllowedExtensions()
    {
        return $this->allowedExtensions;
    }

    /**
     * @param string[] $excludedExtensions
     */
    public function setExcludedExtensions($excludedExtensions)
    {
        $this->excludedExtensions = $excludedExtensions;
    }

    /**
     * @param string[] $allowedExtensions
     */
    public function setAllowedExtensions($allowedExtensions)
    {
        $this->allowedExtensions = $allowedExtensions;
    }

}