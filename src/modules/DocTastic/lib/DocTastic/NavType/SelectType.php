<?php

/**
 * Extends NavType_Base to create a select box-style navigation of Documents
 * using HtmlUtil::getSelector_Generic
 */
class DocTastic_NavType_SelectType extends DocTastic_NavType_AbstractType {

    /**
     * create files array
     */
    protected function build() {
        $files = FileUtil::getFiles($this->getDirectory(), true, true, $this->allowedExtensions, null, false);
        $this->format($files);
    }

    /**
     * format files array for use in HtmlUtil::getSelector_Generic
     * @param array $files flat array of files under $root
     */
    protected function format(array $files) {
        $string = "-----------------------------------------------------------";
        foreach ($files as $key => $file) {
            $file = str_replace('\\', '/', $file);
            $fileparts = explode('/', $file);
            $name = array_pop($fileparts);
            $depth = count($fileparts);
            $name = substr($string, 0, $depth) . $name;
            // do not include entries with disallowed extensions
            if (!in_array(FileUtil::getExtension($name), $this->disallowedExtensions)) {
                $path = DataUtil::formatForOS($this->getDirectory() . '/' . $file);
                $this->files[$path] = $name;
            }
        }
    }

    /**
     * set the control's html
     */
    protected function setHtml() {
        $selectedValue = FormUtil::getPassedValue('file', $this->getDefaultFile(), 'GETPOST');
        $defaultText = $this->rootName;
        $select = HtmlUtil::getSelector_Generic('file', $this->files, $selectedValue, 0, $defaultText, null, null, true);
        $url = ModUtil::url('DocTastic', 'user', 'view');
        $html  = "<form action='$url' method='POST' enctype='application/x-www-form-urlencoded'>";
        $html .= $select;
        $html .= "<input type='hidden' name='docmodule' value='$this->docModule' />";
        $html .= "</form>";

        $this->html = $html;
    }

    /**
     * do post processing on the tree array
     */
    protected function postProcessBuild() {
        // nothing to do atm
    }

}