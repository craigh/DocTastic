<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NavTypeSelect
 *
 * @author craig
 */
class DocTastic_NavType_Select extends DocTastic_NavType_Base {

    /**
     * Constructor
     * create files array and set the docModule
     */
    public function __construct($params) {
        parent::__construct($params);
        $files = FileUtil::getFiles($this->getDirectory(), true, true, $this->allowedExtensions, null, false);
        $this->formatArray($files);
        $this->postProcessArray();
        $this->docModule = FormUtil::getPassedValue('docmodule', '');
    }

    /**
     * format files array for use in HtmlUtil::getSelector_Generic
     * @param array $files flat array of files under $root
     * @return array array structured for Html::Util::getSelector_Generic
     */
    protected function formatArray(array $files) {
        $string = "-----------------------------------------------------------";
        foreach ($files as $key => $file) {
            $fileparts = explode(DIRECTORY_SEPARATOR, $file);
            $name = array_pop($fileparts);
            $depth = count($fileparts);
            $name = substr($string, 0, $depth) . $name;
            $path = implode(DIRECTORY_SEPARATOR, $fileparts);
            // do not include entries with disallowed extensions
            if (!in_array(FileUtil::getExtension($name), $this->_disallowedExtensions)) {
                self::$files[$this->getDirectory() . DIRECTORY_SEPARATOR . $file] = $name;
            }
        }
    }

    public function getHTML() {
        $selectedValue = FormUtil::getPassedValue('file', '', 'POST');
        $defaultText = $this->rootName;
        $select = HtmlUtil::getSelector_Generic('file', self::$files, $selectedValue, 0, $defaultText, null, null, true);
        $url = ModUtil::url('DocTastic', 'admin', 'view');
        $authkey = SecurityUtil::generateAuthKey('DocTastic');
        $html = "<form action='$url' method='POST' enctype='application/x-www-form-urlencoded'>";
        $html .= $select;
        $html .= "<input type='hidden' name='authid' value='$authkey' />";
        $html .= "<input type='hidden' name='docmodule' value='$this->docModule' />";
        $html .= "</form>";

        return $html;
    }

    protected function postProcessArray() {
        // nothing to do atm
    }

}