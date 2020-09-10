<?php
namespace muuska\util;

interface FormNavigation
{
    /**
     * 
     */
    public function isSubmitted();
    
    public function isUpdate();
    
    public function loadSavedData();
    
    public function checkFieldsAccess();
    
    public function retrieveSubmittedData();
    
    public function prepareData();
    
    public function validateFormData();
    
    public function isDataValid();
    
    public function executeOperation();
    
    /**
     * 
     */
    public function renderForm();
    
    public function createResult($saveProceed, $saved, $content);
}

