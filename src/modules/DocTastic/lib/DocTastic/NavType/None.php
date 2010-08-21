<?php

/**
 * Blank Navigation Control
 *
 */
class DocTastic_NavType_None extends DocTastic_NavType_Base {

    public function __construct($params) {
        parent::__construct($params);
        $this->postProcessArray();
    }

    /**
     * format files array for use
     *
     * @param array $files nested array of files under $root
     * @param integer $parent_id id used in recursion
     * @param string $root pathname files are structured under
     * @return array array structured as needed
     */
    protected function formatArray(array $files, $parent_id = 0, $root = '') {

    }

    /**
     * get the control's html
     */
    public function getHTML() {
        $html = $this->getModuleSelectorHtml();
        return $html;
    }

    /**
     * do post processing on the tree array
     */
    protected function postProcessArray() {
        
    }

}