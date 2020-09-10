<?php
namespace muuska\dao\source\json;
use muuska\dao\AbstractDAOSource;
use muuska\util\App;

class JSONDAOSource extends AbstractDAOSource {
    
    /**
     * {@inheritDoc}
     * @see \muuska\dao\DAOSource::createDefaultDAO($modelDefinition, $project, $daoFactory)
     */
    public function createDefaultDAO(\muuska\model\ModelDefinition $modelDefinition, \muuska\project\Project $project, \muuska\dao\DAOFactory $daoFactory){
        return App::daos()->createJSONDAO($modelDefinition, $daoFactory, $this);
    }
    
    public function protectString($string, $htmlOk = false){
        return (string)$string;
    }
}
