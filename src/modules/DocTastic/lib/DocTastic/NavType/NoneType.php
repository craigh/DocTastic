<?php

/**
 * Blank Navigation Control
 *
 */
class DocTastic_NavType_NoneType extends DocTastic_NavType_AbstractType {

    /**
     * create files array
     */
    protected function build() {
        
    }

    /**
     * format files array for use
     *
     * @param array $files nested array of files under $root
     */
    protected function format(array $files) {
        
    }

    /**
     * set the control's html
     */
    protected function setHtml() {

    }

    /**
     * do post processing on the tree array
     */
    protected function postProcessBuild() {
        
    }

    public function getModuleSelectorHtml($name = 'docmodule', $selectedValue = 0, $defaultValue = 0, $defaultText = '', $allValue = 0, $allText = '', $submit = true, $disabled = false, $multipleSize = 1, $field = 'directory', $optionsOnly=false, $hideListed=false) {
        return parent::getModuleSelectorHtml($name, $selectedValue, $defaultValue, $defaultText, $allValue, $allText, $submit, $disabled, $multipleSize, $field, $optionsOnly, $hideListed);
    }

}