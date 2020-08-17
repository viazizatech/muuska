<?php
namespace muuska\project;

use muuska\asset\constants\AssetNames;
use muuska\asset\constants\AssetType;
use muuska\project\constants\SubAppName;
use muuska\project\constants\ProjectType;
use muuska\util\App;
use muuska\asset\constants\AssetPriority;

class Framework extends AbstractProject
{
    /**
     * @var string
     */
    protected $type = ProjectType::FRAMEWORK;
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\AbstractProject::createSubProject()
     */
    protected function createSubProject($subAppName){
        $result = null;
        if ($subAppName === SubAppName::FRONT_OFFICE) {
            $result = App::projects()->createFrontFramework($this);
        }elseif ($subAppName === SubAppName::BACK_OFFICE) {
            $result = App::projects()->createAdminFramework($this);
        }
        return $result;
    }
    
	/**
	 * {@inheritDoc}
	 * @see \muuska\project\AbstractProject::createAssetGroup()
	 */
	public function createAssetGroup($name, \muuska\asset\AssetSetter $assetSetter){
        $result = parent::createAssetGroup($name, $assetSetter);
        if(($result === null) && ($name === AssetNames::FRAMEWORK_DEFAULT_GROUP)){
            $result = App::assets()->createAssetGroup($name, array(
                $this->createAsset(AssetType::JS, 'utils.js', null, AssetPriority::MAX),
                $this->createAsset(AssetType::JS, 'theme.js', null, AssetPriority::MAX),
                $this->createAsset(AssetType::JS, 'FileUpload.js', null, AssetPriority::MAX)
            ));
        }
        if(($result !== null) && !$assetSetter->hasAssetGroup($name)){
            $assetSetter->addAssetGroup($result);
        }
        return $result;
    }
}
