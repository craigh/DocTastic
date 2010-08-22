<?php

/**
 * Extends NavType_Base to create a select box-style navigation of Documents
 * using HtmlUtil::getSelector_Generic
 */
class DocTastic_NavType_Select extends DocTastic_NavType_Base {

    /**
     * create files array
     */
    public function build() {
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
            $fileparts = explode(DIRECTORY_SEPARATOR, $file);
            $name = array_pop($fileparts);
            $depth = count($fileparts);
            $name = substr($string, 0, $depth) . $name;
            $path = implode(DIRECTORY_SEPARATOR, $fileparts);
            // do not include entries with disallowed extensions
            if (!in_array(FileUtil::getExtension($name), $this->disallowedExtensions)) {
                self::$files[$this->getDirectory() . DIRECTORY_SEPARATOR . $file] = $name;
            }
        }
    }

    /**
     * set the control's html
     */
    public function setHTML() {
        $selectedValue = FormUtil::getPassedValue('file', $this->getWorkingDefault(), 'GETPOST');
        $defaultText = $this->rootName;
        $select = HtmlUtil::getSelector_Generic('file', self::$files, $selectedValue, 0, $defaultText, null, null, true);
        $url = ModUtil::url('DocTastic', 'admin', 'view');
        //$authkey = SecurityUtil::generateAuthKey('DocTastic');
        $html  = $this->getModuleSelectorHtml();
        $html .= "<form action='$url' method='POST' enctype='application/x-www-form-urlencoded'>";
        $html .= $select;
        //$html .= "<input type='hidden' name='authid' value='$authkey' />";
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