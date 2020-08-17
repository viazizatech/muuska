<?php
namespace muuska\project;

use muuska\asset\constants\AssetNames;
use muuska\constants\FolderPath;
use muuska\util\AbstractExtraDataProvider;
use muuska\util\App;
use muuska\project\constants\ProjectType;

class AbstractProject extends AbstractExtraDataProvider implements Project
{
    /**
     * @var string
     */
    protected $type = ProjectType::MODULE;
    
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var string
     */
    protected $corePath;
    
    /**
     * @var \muuska\dao\DAOFactory
     */
    protected $daoFactory;
    
    /**
     * @var string
     */
    protected $version = '1.0';
    
    private $tmpData;
    
    /**
     * @var string[]
     */
    protected $subFoldersInApp = array();
    
    /**
     * @param ProjectInput $input
     */
    public function __construct(ProjectInput $input){
        $this->corePath = $input->getCorePath();
        $this->daoFactory = $input->getDaoFactory();
        $this->onCreate();
    }
    
    protected function onCreate(){
        $this->initSubFoldersInApp();
        
        $installedInfo = $this->getInstalledInfo();
        if(($installedInfo !== null) && ($installedInfo->getVersion() !== $this->getVersion())){
            $this->upgrade();
        }
    }
    
    protected function initSubFoldersInApp(){
        $this->subFoldersInApp[] = strtolower($this->type);
        if(!empty($this->name)){
            $this->subFoldersInApp[] = $this->name;
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::getName()
     */
    public function getName(){
        return $this->name;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::getType()
     */
    public function getType(){
        return $this->type;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::getVersion()
     */
    public function getVersion(){
        return $this->version;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::getSubProject()
     */
    public function getSubProject($subAppName){
        if(!isset($this->tmpData['subProjects']) || !array_key_exists($subAppName, $this->tmpData['subProjects'])){
            $this->tmpData['subProjects'][$subAppName] = $this->createSubProject($subAppName);
        }
        return $this->tmpData['subProjects'][$subAppName];
    }
    
    /**
     * @param string $subAppName
     * @return SubProject
     */
    protected function createSubProject($subAppName){}
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::hasSubProject()
     */
    public function hasSubProject($subAppName){
        return ($this->getSubProject($subAppName) !== null);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::getInstalledInfo()
     */
    public function getInstalledInfo(){
        return App::getApp()->getInstalledProjectInfo($this->getType(), $this->getName());
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::isInstalled()
     */
    public function isInstalled(){
        return App::getApp()->isProjectInstalled($this->getType(), $this->getName());
    }
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::isActive()
     */
    
    public function isActive(){
        return App::getApp()->isProjectActive($this->getType(), $this->getName());
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::isUpToDate()
     */
    public function isUpToDate(){
        $installedInfo = $this->getInstalledInfo();
        return (($installedInfo !== null) && ($installedInfo->getVersion() === $this->getVersion()));
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::createTemplate()
     */
    public function createTemplate($relativePath, \muuska\translation\TemplateTranslator $translator = null, $innerPath = null){
        $baseTranslator = $translator;
        $innerTranslator = null;
        if(!empty($innerPath)){
            $innerTranslator = $translator;
            $baseTranslator = null;
        }
        if($baseTranslator === null){
            $baseTranslator = $this->getTranslator(App::translations()->createTemplateTranslationConfig($relativePath, false));
        }
        return App::renderers()->createPHPTemplate($relativePath, $this->getTemplateDir() .$this->getCommonFolderPath(), $baseTranslator, $innerPath, $innerTranslator);
    }
    
    /**
     * @return string
     */
    protected function getCommonFolderPath(){
        return (empty(FolderPath::COMMON_SUB_FOLDER) ? '' : FolderPath::COMMON_SUB_FOLDER . '/');
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::getCorePath()
     */
    public function getCorePath(){
        return $this->corePath;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::getCoreDir()
     */
    public function getCoreDir(){
        return App::getApp()->getRootDir().$this->corePath.'/';
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::getConfigDir()
     */
    public function getConfigDir(){
        return $this->getCoreDir().FolderPath::CONFIG.'/';
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::getSetupDir()
     */
    public function getSetupDir(){
        return $this->getCoreDir().FolderPath::SETUP.'/';
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::getTranslationDirPattern()
     */
    public function getTranslationDirPattern(){
        $installedInfo = $this->getInstalledInfo();
        return (($installedInfo === null) || !$installedInfo->isTranslationMoved()) ? $this->getCoreDir().FolderPath::TRANSLATION.'/{lang}/' : App::getApp()->getStorageDir().FolderPath::TRANSLATION.'/'.$this->getSubPathInApp().'/{lang}/';
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::getTemplateDir()
     */
    public function getTemplateDir(){
        return $this->getCoreDir() . FolderPath::TEMPLATES.'/';
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::getAssetPathPattern()
     */
    public function getAssetPathPattern(){
        return FolderPath::ASSETS . '/'.$this->getSubPathInApp().'/{type}/';
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::hasSpecificDAOSource()
     */
    public function hasSpecificDAOSource(){
        return !empty($this->getSpecificDaoSourceKey());
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::createModelDAO()
     */
    public function createModelDAO(\muuska\model\ModelDefinition $modelDefinition, \muuska\dao\DAOFactory $daoFactory, \muuska\dao\DAOSource $daoSource) {
        return $daoSource->createDefaultDAO($modelDefinition, $this, $daoFactory);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::getSpecificDAOSource()
     */
    public function getSpecificDAOSource(\muuska\dao\DAOFactory $daoFactory) {
        $key = $this->getSpecificDaoSourceKey();
        if(!empty($key) && !$daoFactory->isSourceRegistered($key)){
            $daoFactory->registerSource($this->createSpecificDaoSource());
        }
        return $key;
    }
    
    /**
     * @return \muuska\dao\DAOSource
     */
    protected function createSpecificDAOSource() {
        return null;
    }
    
    /**
     * @return string
     */
    protected function getSpecificDAOSourceKey() {
        return null;
    }
    
    public function createAssetGroup($name, \muuska\asset\AssetSetter $assetSetter){
        $group = null;
        if($name === AssetNames::PROJECT_DEFAULT_GROUP){
            $group = $this->createDefaultAssetGroup($name, $assetSetter);
        }
        if(($group !== null) && !$assetSetter->hasAssetGroup($name)){
            $assetSetter->addAssetGroup($group);
        }
        return $group;
    }
    
    /**
     * @param string $name
     * @param \muuska\asset\AssetSetter $assetSetter
     * @return \muuska\asset\AssetGroup
     */
    public function createDefaultAssetGroup($name, \muuska\asset\AssetSetter $assetSetter){
        return null;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::getAssetResolver()
     */
    public function getAssetResolver(){
        if(!isset($this->tmpData['assetResolver'])){
            $subPattern = $this->getAssetPathPattern().$this->getCommonFolderPath();
            $dirPath = App::getApp()->getPublicDir().$subPattern;
            if (!$this->isInstalled()) {
                $dirPath = $this->getCoreDir().FolderPath::ASSETS.'/{type}/'.$this->getCommonFolderPath();
            }
            $this->tmpData['assetResolver'] = App::assets()->createDefaultRelativeAssetResolver(App::getApp()->getPublicUrl().$subPattern, $dirPath);
        }
        return $this->tmpData['assetResolver'];
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::createAsset()
     */
    public function createAsset($assetType, $location, $library = null, $priority = null, $locationInPage = null){
        return App::assets()->createRelativeUriAsset($assetType, $location, $this->getAssetResolver(), $library, $priority, $locationInPage);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::createHtmlImage()
     */
    public function createHtmlImage($location, $alt = null, $title = null, $library = null){
        return App::htmls()->createRelativeHtmlImage($this->getAssetResolver(), $location, $alt, $title, $library);
    }
    
    /**
     * @return \muuska\translation\TranslationStore
     */
    public function getTranslatorStore(){
        if(!isset($this->tmpData['translatorStore'])){
            $baseDir = $this->getTranslationDirPattern().$this->getCommonFolderPath();
            $this->tmpData['translatorStore'] = App::translations()->createTranslationStore($baseDir, false, null);
        }
        return $this->tmpData['translatorStore'];
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::getTranslator()
     */
    public function getTranslator(\muuska\translation\config\TranslatorConfig $translatorConfig, \muuska\translation\Translator $alternativeTranslator = null){
        $translatorStore = $this->getTranslatorStore();
        return ($translatorStore !== null) ? $translatorStore->getTranslator($translatorConfig, $alternativeTranslator) : null;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::createJSTranslation()
     */
    public function createJSTranslation($lang, $location, \muuska\asset\AssetSetter $assetSetter = null, $defaultScopeEnabled = true, $priority = null, $locationInPage = null){
        $result = null;
        $translatorStore = $this->getTranslatorStore();
        if ($translatorStore !== null) {
            $result = $translatorStore->createJSTranslation($lang, $location, false, null, $defaultScopeEnabled, $priority, $locationInPage);
            if($assetSetter !== null){
                $this->formatAssetTranslation($assetSetter, $result);
            }
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::getSubFoldersInApp()
     */
    public function getSubFoldersInApp(){
        return $this->subFoldersInApp;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::getTranslationJsScopes()
     */
    public function getTranslationJsScopes(){
        $result = $this->subFoldersInApp;
        if(!empty(FolderPath::COMMON_SUB_FOLDER)){
            $result[] = FolderPath::COMMON_SUB_FOLDER;
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::formatAssetTranslation()
     */
    public function formatAssetTranslation(\muuska\asset\AssetSetter $assetSetter, \muuska\asset\AssetTranslation $assetTranslation, $innerScopes = array()){
        App::getTools()->formatAssetTranslation($this->getTranslationJsScopes(), $assetSetter, $assetTranslation);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::getSubPathInApp()
     */
    public function getSubPathInApp(){
        return implode('/', $this->subFoldersInApp);
    }
    
    public function onDAOEvent($code, \muuska\dao\event\DAOEvent $event)
    {
        
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::createResourceTree()
     */
    public function createResourceTree($subAppName, \muuska\security\ResourceTree $subResourceTree = null){
        $result = null;
        if(empty($this->name)){
            $result = App::securities()->createResourceTree($this->type, App::securities()->createResourceTree($subAppName, $subResourceTree));
        }else{
            $result = App::securities()->createResourceTree($this->type, App::securities()->createResourceTree($this->name, App::securities()->createResourceTree($subAppName, $subResourceTree)));
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Project::checkUpgradeInput()
     */
    public function checkUpgradeInput(\muuska\project\setup\ProjectUpgrade $projectUpgrade){
        return (isset($this->tmpData['upgradeToken'.$this->getVersion()]) && ($projectUpgrade->getToken() === $this->tmpData['upgradeToken'.$this->getVersion()]));
    }
    
    protected function upgrade(){
        $upgrade = $this->createUpgrade();
        if($upgrade !== null){
            $this->tmpData['upgradeToken'.$this->getVersion()] = $upgrade->getToken();
            App::getApp()->upgradeProject($this, $upgrade);
        }
    }
    
    /**
     * @return \muuska\project\setup\ProjectUpgrade
     */
    protected function createUpgrade(){
        return App::projects()->createDefaultProjectUpgrade($this, $this->daoFactory);
    }
}
