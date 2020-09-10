<?php
namespace muuska\html\dropdown;
class CustomDropdown extends AbstractDropdown{
    /**
     * @var string
     */
    protected $componentName = 'custom_dropdown';
    
    /**
     * @var \muuska\html\HtmlContent
     */
    protected $dropdownToggle;
    
    /**
     * @param \muuska\html\HtmlContent $dropdownToggle
     */
    public function __construct(\muuska\html\HtmlContent $dropdownToggle = null) {
        $this->setDropdownToggle($dropdownToggle);
	}
	
    /**
     * @return \muuska\html\HtmlContent
     */
    public function getDropdownToggle()
    {
        return $this->dropdownToggle;
    }

    /**
     * @param \muuska\html\HtmlContent $dropdownToggle
     */
    public function setDropdownToggle(?\muuska\html\HtmlContent $dropdownToggle)
    {
        $this->dropdownToggle = $dropdownToggle;
    }
}