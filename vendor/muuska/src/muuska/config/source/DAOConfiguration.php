<?php
namespace muuska\config\source;

use muuska\config\AbstractConfiguration;

class DAOConfiguration extends AbstractConfiguration{
    
    /**
     * @var \muuska\dao\DAO
     */
    protected $dao;
    
    /**
     * @var \muuska\config\model\ConfigModel[]
     */
    protected $configModels = array();
    
    /**
     * @param \muuska\dao\DAO $dao
     */
    public function __construct(\muuska\dao\DAO $dao) {
        $this->dao = $dao;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\config\AbstractConfiguration::load()
     */
    protected function load()
    {
        $data = $this->dao->getData();
        $this->configValues = array();
        $this->configModels = array();
        foreach ($data as $object) {
            $name = $object->getName();
            $this->configValues[$name] = $object->getValue();
            $this->configModels[$name] = $object;
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\config\Configuration::save()
     */
    public function save()
    {
        $this->autoLoadConfigValues();
        foreach ($this->configModels as $key => $configModel) {
            if(array_key_exists($key, $this->configValues)){
                if($configModel->getValue() != $this->configValues[$key]){
                    $configModel->setValue($this->configValues[$key]);
                    $this->dao->update($configModel);
                }
            }else{
                $this->dao->delete($configModel);
                unset($this->configModels[$key]);
            }
        }
        foreach ($this->configValues as $key => $value) {
            if(!isset($this->configModels[$key])){
                $configModel = $this->dao->createModel();
                $configModel->setName($key);
                $configModel->setValue($value);
                if($this->dao->add($configModel)){
                    $this->configModels[$key] = $configModel;
                }
            }
        }
        return true;
    }
}