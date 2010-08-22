<?php

/**
 * Blank Navigation Control
 *
 */
class DocTastic_NavType_None extends DocTastic_NavType_Base {

    protected function build() {
        $this->postProcessArray();
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
        $html = $this->getModuleSelectorHtml();
        $this->html = $html;
    }

    /**
     * do post processing on the tree array
     */
    protected function postProcessBuild() {
        
    }

}