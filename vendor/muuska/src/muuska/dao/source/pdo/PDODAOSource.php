<?php
namespace muuska\dao\source\pdo;
use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\dao\AbstractDAOSource;
use muuska\dao\constants\ReferenceOption;
use muuska\project\constants\ProjectType;
use muuska\util\App;

class PDODAOSource extends AbstractDAOSource {
    /**
     * @var \PDO
     */
    protected $pdo;
	
	/**
	 * @var string
	 */
    protected $tablePrefix;
    
    /**
     * @param string $name
     * @param string $dsn
     * @param string $username
     * @param string $password
     * @param string $tablePrefix
     * @param int $timeout
     * @throws \Exception
     */
    public function __construct($name, $dsn, $username = null, $password = null, $tablePrefix = null, $timeout = null){
        parent::__construct($name);
        $this->tablePrefix = $tablePrefix;
        try {
            if(empty($timeout)){
                $timeout = 5;
            }
            $this->pdo = new \PDO($dsn, $username, $password, array(\PDO::ATTR_TIMEOUT => $timeout, \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true));
            // UTF-8 support
            if ($this->pdo->exec('SET NAMES \'utf8\'') === false) {
                throw new \Exception('Fatal error: no utf-8 support. Please check your server configuration.');
            }
            
            $this->pdo->exec("SET CHARACTER SET utf8");
            
            $this->pdo->exec('SET SESSION sql_mode = \'\'');
            if(App::getApp()->isDevMode()){
                $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            }
        } catch (\PDOException $e) {
            throw new \Exception(sprintf('Link to database cannot be established: %s', utf8_encode($e->getMessage())));
        }
    }
    
    /**
     * @param string $name
     * @param \muuska\config\Configuration $configuration
     * @return \muuska\dao\source\pdo\PDODAOSource
     */
    public static function createFromConfiguration($name = null, \muuska\config\Configuration $configuration = null){
        $dsn = '';
        if(empty($name)){
            $name = 'pdo';
        }
        if($configuration === null){
            $configuration = App::configs()->createJSONConfiguration(App::getApp()->getRootConfigDir() . 'db.json');
        }
        if($configuration->containsKey('dsn')){
            $dsn = $configuration->getString('dsn');
        }else{
            $dsn = $configuration->getString('driver', 'mysql').':host='.$configuration->getString('host', 'localhost').'; port='.$configuration->getInt('port', 3306).'; dbname='.$configuration->getString('database');
        }
        return App::daos()->createPDODAOSource($name, $dsn, $configuration->getString('user'), $configuration->getString('password'), $configuration->getString('table_prefix'), $configuration->getInt('timeout', 5));
    }
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\dao\AbstractDAOSource::protectStringImplementation($str)
	 */
	protected function protectStringImplementation($str)
    {
        $search = array('\\', "\0", "\n", "\r", "\x1a", "'", '"');
		$replace = array('\\\\', '\\0', '\\n', '\\r', "\Z", "\'", '\"');
		
		return str_replace($search, $replace, $str);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAOSource::createDefaultDAO($modelDefinition, $project, $daoFactory)
     */
    public function createDefaultDAO(\muuska\model\ModelDefinition $modelDefinition, \muuska\project\Project $project, \muuska\dao\DAOFactory $daoFactory)
    {
        return App::daos()->createPDODAO($modelDefinition, $daoFactory, $this);
    }
    
    /**
     * @param \muuska\model\ModelDefinition $modelDefinition
     * @return string
     */
    public function getTableName(\muuska\model\ModelDefinition $modelDefinition)
    {
        $project = $modelDefinition->getProject();
        return $this->getProjectFullDbPrefix($project->getType(), $project->getName()) . $modelDefinition->getName();
    }
    
    /**
     * @param \muuska\model\ModelDefinition $modelDefinition
     * @return string
     */
    public function getLangTableName(\muuska\model\ModelDefinition $modelDefinition)
    {
        return $this->getTableName($modelDefinition) . '_lang';
    }
    
    /**
     * @param \muuska\model\ModelDefinition $modelDefinition
     * @return string
     */
    public function getPrimaryFieldNameForLang(\muuska\model\ModelDefinition $modelDefinition)
    {
        return $modelDefinition->getName().'_id';
    }
    
    /**
     * @param \muuska\project\Project $project
     * @return string
     */
    public function getDAOSetupDir(\muuska\project\Project $project)
    {
        return $project->getSetupDir() . 'dao/sql/';
    }
    
    /**
     * @return string
     */
    public function getLastInsertId(){
        return $this->pdo->lastInsertId();
    }
    
    /**
     * @param string $projectType
     * @param string $projectName
     * @return string
     */
    public function getProjectFullDbPrefix($projectType, $projectName){
        $prefix = $this->getProjectTypePrefix($projectType);
        if(($projectType !== ProjectType::APPLICATION) && ($projectType !== ProjectType::FRAMEWORK)){
            $prefix .= '_'.$projectName;
        }
        return $prefix;
    }
    
    /**
     * @param string $projectType
     * @return string
     */
    public function getProjectTypePrefix($projectType){
        $prefix = $this->tablePrefix;
        $prefix .= strtolower(substr($projectType, 0, 1)).'_';
        return $prefix;
    }
    
    /**
     * @return \PDO
     */
    public function getPdo(){
        return $this->pdo;
    }
    
    /**
     * @param string $file
     * @param string $tablePrefix
     * @return boolean
     */
    protected function executeSQLFile($file, $currentTablePrefix)
    {
		$result = true;
		if(file_exists($file)){
			$sqlContent = file_get_contents($file);
			$sqlContent = str_replace('_FW_TABLE_PREFIX_', $this->getProjectFullDbPrefix(ProjectType::FRAMEWORK, null), $sqlContent);
			$sqlContent = str_replace('_APP_TABLE_PREFIX_', $this->getProjectFullDbPrefix(ProjectType::APPLICATION, null), $sqlContent);
			$sqlContent = str_replace('_MOD_TABLE_PREFIX_', $this->getProjectTypePrefix(ProjectType::MODULE), $sqlContent);
			$sqlContent = str_replace('_LIB_TABLE_PREFIX_', $this->getProjectTypePrefix(ProjectType::LIBRARY), $sqlContent);
			$sqlContent = str_replace('_CUSTOM_TABLE_PREFIX_', $this->getProjectTypePrefix(ProjectType::CUSTOM), $sqlContent);
			$sqlContent = str_replace('_MAIN_TABLE_PREFIX_', $this->tablePrefix, $sqlContent);
			$sqlContent = str_replace('_CURRENT_TABLE_PREFIX_', $currentTablePrefix, $sqlContent);
			$sqlRequests = preg_split('/;\s*[\r\n]+/', $sqlContent);
			
			foreach($sqlRequests as $request){
				if (!empty($request)){
					$currentResult = ($this->pdo->exec(trim($request)) !== false);
					$result &= $currentResult;
				}
			}
		}
		return $result;
	}
	
	/**
	 * @param \muuska\project\Project $project
	 * @param string $relativeName
	 * @return bool
	 */
	protected function executeProjectSQL(\muuska\project\Project $project, $relativeName)
	{
	    return $this->executeSQLFile($this->getDAOSetupDir($project).$relativeName.'.sql', $this->getProjectFullDbPrefix($project->getType(), $project->getName()));
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\dao\AbstractDAOSource::installProjectImplementation()
	 */
	protected function installProjectImplementation(\muuska\dao\ProjectDAOInstallInput $input){
	    if($input->hasModelDefinitions()){
	        $this->installModelDefinitions($input->getModelDefinitions(), true);
	    }
	    return $this->executeProjectSQL($input->getProject(), 'install');
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\dao\AbstractDAOSource::uninstallProjectImplementation()
	 */
	protected function uninstallProjectImplementation(\muuska\dao\ProjectDAOUninstallInput $input){
	    if($input->hasModelDefinitions()){
	        $this->uninstallModelDefinitions($input->getModelDefinitions(), true);
	    }
	    return $this->executeProjectSQL($input->getProject(), 'uninstall');
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\dao\AbstractDAOSource::upgradeProjectImplementation($input)
	 */
	protected function upgradeProjectImplementation(\muuska\dao\ProjectDAOUpgradeInput $input){
	    $this->pdo->exec('SET FOREIGN_KEY_CHECKS=0;');
	    if($input->hasRemovedModelDefinitions()){
	        $this->uninstallModelDefinitions($input->getRemovedModelDefinitions(), false);
	    }
	    if($input->hasAddedModelDefinitions()){
	        $this->installModelDefinitions($input->getAddedModelDefinitions(), false);
	    }
	    if($input->hasModelDefinitionUpgradeInfos()){
	        $infos = $input->getModelDefinitionUpgradeInfos();
	        foreach ($infos as $modelDefinitionUpgradeInfo) {
	            $this->upgradeModelDefinition($modelDefinitionUpgradeInfo);
	        }
	    }
	    $this->pdo->exec('SET FOREIGN_KEY_CHECKS=1;');
	    return $this->executeProjectSQL($input->getProject(), 'upgrade/v'.$input->getOldVersion());
	}
	
	/**
	 * @param \muuska\model\ModelDefinition[] $modelDefinitions
	 * @param boolean $autoForeignKeyCheck
	 */
	protected function installModelDefinitions($modelDefinitions, $autoForeignKeyCheck = true){
	    if($autoForeignKeyCheck){
	        $this->pdo->exec('SET FOREIGN_KEY_CHECKS=0;');
	    }
	    foreach ($modelDefinitions as $modelDefinition) {
	        $this->pdo->exec($this->getTableCreationSQL($modelDefinition));
	        if($modelDefinition->isMultilingual()){
	            $this->pdo->exec($this->getLangTableCreationSQL($modelDefinition));
	        }
	    }
	    if($autoForeignKeyCheck){
	        $this->pdo->exec('SET FOREIGN_KEY_CHECKS=1;');
	    }
	}
	
	/**
	 * @param \muuska\model\ModelDefinition[] $modelDefinitions
	 * @param boolean $autoForeignKeyCheck
	 */
	protected function uninstallModelDefinitions($modelDefinitions, $autoForeignKeyCheck = true){
	    if($autoForeignKeyCheck){
	        $this->pdo->exec('SET FOREIGN_KEY_CHECKS=0;');
	    }
	    foreach ($modelDefinitions as $modelDefinition) {
	        if($modelDefinition->isMultilingual()){
	            $this->pdo->exec('DROP TABLE IF EXISTS `'.$this->getLangTableName($modelDefinition).'`;');
	        }
	        $this->pdo->exec('DROP TABLE IF EXISTS `'.$this->getTableName($modelDefinition).'`;');
	    }
	    if($autoForeignKeyCheck){
	        $this->pdo->exec('SET FOREIGN_KEY_CHECKS=1;');
	    }
	}
	
	/**
	 * @param \muuska\dao\ModelDefinitionUpgradeInfo $modelDefinitionUpgradeInfo
	 * @return bool
	 */
	protected function upgradeModelDefinition(\muuska\dao\ModelDefinitionUpgradeInfo $modelDefinitionUpgradeInfo){
	    $this->uninstallModelDefinitions(array($modelDefinitionUpgradeInfo->getOldModelDefinition()), false);
	    $this->installModelDefinitions(array($modelDefinitionUpgradeInfo->getNewModelDefinition()), false);
	}
    
	/**
	 * @param \muuska\model\ModelDefinition $modelDefinition
	 * @return string
	 */
	protected function getTableCreationSQL(\muuska\model\ModelDefinition $modelDefinition)
    {
        $tableName = $this->getTableName($modelDefinition);
        $sql = 'CREATE TABLE IF NOT EXISTS `'.$this->protectString($tableName).'` (';
        $fieldsDefinition = $modelDefinition->getFieldDefinitions();
        $fieldsSql = '';
        if($modelDefinition->isAutoIncrement()){
            $primary = $modelDefinition->getPrimary();
            if(!isset($fieldsDefinition[$primary])){
                $fieldsSql .= '`'.$this->protectString($primary).'` INT(11) NOT NULL AUTO_INCREMENT';
            }
        }
        $fields = $modelDefinition->getSimpleFields();
        foreach ($fields as $field) {
            if(!empty($fieldsSql)){
                $fieldsSql .= ',';
            }
            $fieldsSql .= $this->getFieldSql($modelDefinition, $tableName, $field, $modelDefinition->getFieldDefinition($field), false);
        }
        $sql .= $fieldsSql;
        $primaries = $modelDefinition->getPrimaries();
        if(!empty($primaries)){
            $sql .= ', PRIMARY KEY (`'.implode('`,`', $primaries).'`)';
        }
        $sql .= $this->getMultipeUniquesSql($modelDefinition, $tableName, false);
        $sql .= ') ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';
        return $sql;
    }
    
    /**
     * @param \muuska\model\ModelDefinition $modelDefinition
     * @param string $tableName
     * @param bool $isLangTable
     * @return string
     */
    protected function getMultipeUniquesSql(\muuska\model\ModelDefinition $modelDefinition, $tableName, $isLangTable)
    {
        $sql = '';
        $uniques = $modelDefinition->getMultipleUniques();
        if(!empty($uniques)){
            foreach ($uniques as $fields) {
                $allFieldsOk = true;
                foreach ($fields as $field) {
                    if($modelDefinition->isLangField($field) !== $isLangTable){
                        $allFieldsOk = false;
                        break;
                    }
                }
                if($allFieldsOk && !empty($fields)){
                    $sql .= ', UNIQUE KEY `UNIQUE_' .$tableName . '_' . implode('_', $fields) . '` ('.($isLangTable ? '`lang`, ' : '') . '`'.implode('`,`', $fields).'`)';
                }
            }
        }
        return $sql;
    }
    
    /**
     * @param \muuska\model\ModelDefinition $modelDefinition
     * @return string
     */
    protected function getLangTableCreationSQL(\muuska\model\ModelDefinition $modelDefinition)
    {
        $tableName = $this->getLangTableName($modelDefinition);
        $primaryField = $this->getPrimaryFieldNameForLang($modelDefinition);
        $sql = 'CREATE TABLE IF NOT EXISTS `'.$this->protectString($tableName).'` (';
        $fieldsSql = '`'.$primaryField.'` INT(11) NOT NULL, `lang` varchar(5) NOT NULL';
        $langFields = $modelDefinition->getLangFields();
        foreach ($langFields as $field) {
            $fieldsSql .= ',' . $this->getFieldSql($modelDefinition, $tableName, $field, $modelDefinition->getFieldDefinition($field), true);
        }
        $sql .= $fieldsSql;
        $sql .= ', PRIMARY KEY (`'.$primaryField.'`,`lang`),';
        $sql .= 'CONSTRAINT `FK_'.$tableName.'` FOREIGN KEY (`'.$primaryField.'`) REFERENCES `'.$this->getTableName($modelDefinition).'` (`'.$modelDefinition->getPrimary().'`) ON DELETE CASCADE ON UPDATE CASCADE';
        $sql .= $this->getMultipeUniquesSql($modelDefinition, $tableName, true);
        $sql .= ') ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';
        return $sql;
    }
    
    /**
     * @param \muuska\model\ModelDefinition $modelDefinition
     * @param string $tableName
     * @param string $field
     * @param string $fieldDefinition
     * @param string $isLangField
     * @return string
     */
    protected function getFieldSql(\muuska\model\ModelDefinition $modelDefinition, $tableName, $field, $fieldDefinition, $isLangField)
    {
        $type = isset($fieldDefinition['type']) ? $fieldDefinition['type'] : null;
        $types = array(DataType::TYPE_BOOL => 'TINYINT(1)', DataType::TYPE_DATE => 'DATE', DataType::TYPE_DATETIME => 'DATETIME',
            DataType::TYPE_DECIMAL => 'DECIMAL(20, 6)', DataType::TYPE_FLOAT => 'FLOAT', DataType::TYPE_INT => 'INT(11)');
        $typeSql = isset($types[$type]) ? $types[$type] : 'VARCHAR';
        $unsigned = isset($fieldDefinition['unsigned']) && $fieldDefinition['unsigned'];
        $required = isset($fieldDefinition['required']) && $fieldDefinition['required'];
        if($typeSql === 'VARCHAR'){
            if(isset($fieldDefinition['maxSize']) && !empty($fieldDefinition['maxSize'])){
                $typeSql .= '(' . (int)$fieldDefinition['maxSize'] . ')';
            }else{
                $typeSql = 'TEXT';
            }
        }
        $sql = '`'.$field.'` ' . $typeSql . ($unsigned ? ' UNSIGNED' : '') . ($required ? ' NOT NULL' : '') . (isset($fieldDefinition['default']) ? ' DEFAULT "' . $fieldDefinition['default'] . '"' : '');
        
        if(isset($fieldDefinition['unique']) && $fieldDefinition['unique']){
            $sql .= ', UNIQUE KEY `UNIQUE_' .$tableName . '_' . $field . '` ('.($isLangField ? '`lang`, ' : '').'`'.$field.'`)';
        }
        if(isset($fieldDefinition['nature']) && ($fieldDefinition['nature'] == FieldNature::EXISTING_MODEL_ID)){
            $associationModelDefinition = $modelDefinition->getAssociationDefinition($field);
            $referenceTableName = $this->getTableName($associationModelDefinition);
            $foreignKeyName = 'FK_' .$tableName . '_' . $field . '_'.$referenceTableName;
            $referenceField = isset($fieldDefinition['referenceField']) ? $fieldDefinition['referenceField'] : $associationModelDefinition->getPrimary();
            $sql .= ', CONSTRAINT `'.$foreignKeyName.'` FOREIGN KEY (`'.$field.'`) REFERENCES `'.$referenceTableName.'` (`'.$referenceField.'`)';
            $referenceOptions = array(ReferenceOption::CASCADE => 'CASCADE', ReferenceOption::RESTRICT => 'RESTRICT', ReferenceOption::SET_NULL => 'SET NULL', ReferenceOption::NO_ACTION => 'NO ACTION', ReferenceOption::SET_DEFAULT => 'SET DEFAULT');
            if(isset($fieldDefinition['onDelete']) && !empty($fieldDefinition['onDelete']) && isset($referenceOptions[$fieldDefinition['onDelete']])){
                $sql .= ' ON DELETE ' . $referenceOptions[$fieldDefinition['onDelete']];
            }
            if(isset($fieldDefinition['onUpdate']) && !empty($fieldDefinition['onUpdate']) && isset($referenceOptions[$fieldDefinition['onUpdate']])){
                $sql .= ' ON UPDATE ' . $referenceOptions[$fieldDefinition['onUpdate']];
            }
        }
        
        return $sql;
    }
}
