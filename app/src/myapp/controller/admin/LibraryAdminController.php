<?php
namespace myapp\controller\admin;

use muuska\controller\CrudController;
use myapp\model\LibraryDefinition;
use muuska\constants\ExternalFieldEditionType;
use muuska\constants\ExternalFieldViewType;

class LibraryAdminController extends CrudController
{

    protected function onCreate()
    {
        $this->modelDefinition = LibraryDefinition::getInstance();
    }

    protected function createFormHelper($update)
    {
        $helper = parent::createFormHelper($update);
        $helper->addExternalFieldDefinition('addressId', ExternalFieldEditionType::ALL_FIELDS);
        $helper->addMultipleAssociation('specialities', 'specialityId');
        $helper->addMultipleAssociation('types', 'typeId');
        return $helper;
    }

    protected function createViewHelper()
    {
        $helper = parent::createViewHelper();
        $helper->addExternalFieldDefinition('addressId', ExternalFieldViewType::ALL_FIELDS);
        $helper->addMultipleAssociation('specialities', 'specialityId');
        $helper->addMultipleAssociation('types', 'typeId');
        $helper->setInnerNavigationEnabled(true);
        $helper->addControllerInnerNavigation('book', $this->l('Books'), 'library');
        return $helper;
    }

    protected function createListHelper()
    {
        $listHelper = parent::createListHelper();
        $definition = array(
            'otherFields' => array(
                'country' => array('label' => $this->l('Country'))
            ),
            'hidden' => true
        );
        $listHelper->addExternalFieldDefinition('addressId', $definition);
        $listHelper->setSpecificSearchEnabled(true);
        $listHelper->setInnerSearchEnabled(false);
        $listHelper->setQuickSearchEnabled(true);
        $listHelper->setSpecificSortEnabled(true);
        $listHelper->setAjaxEnabled(true);
        return $listHelper;
    }
}
