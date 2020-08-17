<?php
namespace muuska\option\provider;
use muuska\constants\Approval;

class ApprovalProvider extends AbstractOptionProvider
{
    /**
     * {@inheritDoc}
     * @see \muuska\option\provider\AbstractOptionProvider::createTranslator()
     */
    protected function createTranslator(){
        return $this->getFrameworkTranslator('approval');
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\option\provider\AbstractOptionProvider::initOptions()
     */
    protected function initOptions()
    {
        $this->addArrayOption(Approval::PENDING, $this->l('Pending'));
        $this->addArrayOption(Approval::APPROVED, $this->l('Approved'));
        $this->addArrayOption(Approval::DECLINED, $this->l('Declined'));
        $this->addArrayOption(Approval::CANCELED, $this->l('Canceled'));
    }
}
