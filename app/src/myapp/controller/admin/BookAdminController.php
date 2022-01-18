<?php
namespace myapp\controller\admin;

use muuska\constants\ExternalFieldEditionType;
use muuska\controller\CrudController;
use muuska\util\App;
use myapp\model\BookDefinition;
use myapp\model\LibraryDefinition;

class BookAdminController extends CrudController
{
    protected function onCreate() {
        $this->modelDefinition = BookDefinition::getInstance();
    }
    
    protected function initParamResolver()
    {
        $parser = App::controllers()->createModelControllerParamParser(LibraryDefinition::getInstance(), 'library', true, array('modelField' => 'libraryId'));
        $this->paramResolver = App::controllers()->createDefaultControllerParamResolver($this->input, $this->result, array($parser));
    }

    protected function createFormHelper($update)
    {
        $helper = parent::createFormHelper($update);
        $subExternalFieldsDefinition = array(
            'addressId' => array(
                'editionType' => ExternalFieldEditionType::ALL_FIELDS
            )
        );
        $helper->addExternalFieldDefinition('authorId', ExternalFieldEditionType::ALL_FIELDS, null, $subExternalFieldsDefinition);
        return $helper;
    }
    
    protected function createListHelper()
    {
        $helper = parent::createListHelper();
        $helper->addExcludedFields(array('id', 'code', 'language', 'publicationDate', 'abstract', 'digitalDocument', 'numberOfPages'));
        return $helper;
    }
}
