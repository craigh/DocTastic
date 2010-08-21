<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NavTypeBase
 *
 * @author craig
 */
abstract class DocTastic_NavType_Base {

    /**
     * Navigation Type (class name)
     * @var string
     */
    protected $type = 'Tree';
    /**
     * Array of files from directory
     * @var array
     */
    protected static $files = array();
    /**
     * filetype extensions that should not be displayed in navigation
     * @var array
     */
    protected $disallowedExtensions = array('php', 'odp', 'odt', 'doc', 'docx', 'swf', 'jpg', 'gif', 'png', 'htm', 'html', 'tpl', 'pot', 'htaccess');
    /**
     * User or Admin type
     * @var string
     */
    protected $userType = 'admin';
    /**
     * Array of filenames to load if available
     * @var array
     */
    protected $defaultDoc = array('index.txt', 'readme.txt', 'README');
    /**
     * append language on docsDirectory?
     * @see getDirectory
     * @var boolean
     */
    private $_languageEnabled = true;
    /**
     * The docs directory to display
     * @var string
     */
    private $_docsDirectory = 'docs';
    /**
     * navigation types
     * @var array
     */
    public static $types = array('Tree', 'Select', 'None');
    /**
     * filetype extensions allowed to search for with the docs directory (specific)
     * @var array
     */
    public $allowedExtensions = array(); // 'txt', 'text', 'markdown' ??
    /**
     * Name to display at the root of the tree
     * @var string
     */
    public $rootName = "Document Root";
    /**
     * The module being rendered
     * @var string
     */
    public $docModule = 'DocTastic';

    /**
     * set the Navigation Type
     * @param string $_navType
     */
    public function setType($type) {
        if ((in_array($type, self::$types)) && (!empty($type))) {
            $this->type = $type;
        }
    }

    /**
     * get the NavType from the array index
     * the array index is stored as a DocTastic ModVar (navType)
     * @param integer $key
     * @return string Type
     */
    public static function getTypeFromKey($key) {
        return self::$types[$key];
    }

    /**
     * Find and return a working filename with complete relative path
     * if one exists. else return false
     * @return string relative/path/to/filename or ''
     */
    public function getWorkingDefault() {

        foreach ($this->defaultDoc as $file) {
            if (file_exists($this->getDirectory() . DIRECTORY_SEPARATOR . $file)) {
                return $this->getDirectory() . DIRECTORY_SEPARATOR . $file;
            }
        }
        return '';
    }

    /**
     * set whether to append language to docsDirectory
     * @param boolean $_languageEnabled
     */
    public function set_languageEnabled($_languageEnabled) {
        if (isset($_languageEnabled)) {
            $this->_languageEnabled = $_languageEnabled;
        }
    }

    /**
     * Set the docsDirectory
     * @param string $docsDirectory
     */
    public function setDocsDirectory($docsDirectory) {
        if (isset($docsDirectory) && !empty($docsDirectory)) {
            $this->_docsDirectory = $docsDirectory;
        }
    }

    /**
     * Get the directory to be searched
     * @return string
     */
    public function getDirectory() {
        if ($this->_languageEnabled) {
            // append language code
            $lang = ZLanguage::getLanguageCode();
            // append User dir for users (not admins)
            $access = (SecurityUtil::checkPermission($this->docModule, '::', ACCESS_ADMIN)) ? '' : DIRECTORY_SEPARATOR . 'User';
            return $this->_docsDirectory . DIRECTORY_SEPARATOR . $lang . $access; // no trailing slash please
        } else {
            return $this->_docsDirectory;
        }
    }

    public function __construct($params) {
        if (isset($params['docsDirectory'])) {
            $this->setDocsDirectory($params['docsDirectory']);
        }
        if (isset($params['languageEnabled'])) {
            $this->set_languageEnabled($params['languageEnabled']);
        }
        if (isset($params['docmodule'])) {
            $this->docModule = $params['docmodule'];
        }
    }

    /**
     * This function duplicates some of the functionality of HtmlUtil::getSelector_Module
     * It customizes the input of that function for ease of use and further customizes the data.
     *
     * @param string $name
     * @param string $selectedValue
     * @param string $defaultValue
     * @param string $defaultText
     * @param string $allValue
     * @param string $allText
     * @param boolean $submit
     * @param boolean $disabled
     * @param integer $multipleSize
     * @param string $field
     * @return string html for inclusion into template
     */
    protected function getModuleSelectorHtml($name='docmodule', $selectedValue=0, $defaultValue=0, $defaultText='', $allValue=0, $allText='', $submit=true, $disabled=false, $multipleSize=1, $field='directory') {
        $selectedValue = (isset($selectedValue) && !empty($selectedValue)) ? $selectedValue : $this->docModule;
        $data = array();
        $modules = ModUtil::getModulesByState(3, 'displayname');
        foreach ($modules as $module) {
            $value        = $module[$field];
            $displayname  = $module['displayname'];
            $data[$value] = $displayname;
        }
        // customize data here
        // add core/docs
        if (ModUtil::getVar('DocTastic', 'addCore')) {
            $data['Core'] = 'Core Documentation';
        }
        asort(&$data);
        // change to include other STATE of modules (uninstaled, etc)
        $formaction = ModUtil::url('DocTastic', 'admin', 'view');
        $html  = "<form action='$formaction' method='POST' enctype='application/x-www-form-urlencoded'>";
        $html .= HtmlUtil::getSelector_Generic($name, $data, $selectedValue, $defaultValue, $defaultText, $allValue, $allText, $submit, $disabled, $multipleSize);
        $html .= "</form>";
        return $html;
    }

    abstract protected function formatArray(array $files);

    abstract protected function postProcessArray();

    abstract public function getHTML();
}