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
     * whether or not to build the object
     * @var boolean
     */
    private $_build = true;
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
     * Include core /docs directory in module selector
     * @var boolean
     */
    private $_addCore = false;
    /**
     * navigation types
     * static because is called from a static function
     * @var array
     */
    private static $_types = array(
        0 => array('name' => 'Directory Tree',
            'class' => 'DocTastic_NavType_Tree'),
        1 => array('name' => 'Directory Select Box',
            'class' => 'DocTastic_NavType_Select'),
        2 => array('name' => 'None',
            'class' => 'DocTastic_NavType_None'),
        3 => array('name' => 'Internal (Auto-Generated)',
            'class' => 'DocTastic_NavType_Sensei')
    );
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
     * Loads them in order available
     * @var array
     */
    protected $defaultDoc = array('index.txt', 'readme.txt', 'README');
    /**
     * stores the created html
     * @var string
     */
    protected $html;
    /**
     * filetype extensions allowed to search for with the docs directory (specific)
     * would occur before the files are post processed so would override disallowedExtensions
     * @var array
     */
    protected $allowedExtensions = array(); // 'txt', 'text', 'markdown' ??
    /**
     * Name to display at the root of the tree
     * @var string
     */
    protected $rootName = "Document Root";
    /**
     * The module being rendered
     * @var string
     */
    protected $docModule = 'DocTastic';
    /**
     * Array of files from directory
     * @var array
     */
    protected static $files = array();

    /**
     * get types array
     * static because is called by another static function
     * 
     * @return array
     */
    private static function getTypes() {
        $types = self::$_types;
        // notify EVENT here to modify types
        $event = new Zikula_Event('module.DocTastic.getTypes', $types);
        EventUtil::notify($event);
        return $types;
    }

    /**
     * get the navTypes names for use in selector, etc.
     * @return array array of navType names
     */
    public static function getTypesNames() {
        $types = self::getTypes();
        $names = array();
        foreach ($types as $key => $type) {
            $names[$key] = $type['name'];
        }
        return $names;
    }

    /**
     * Get the classname (full path) from the array index
     * the array index is stored as a DocTastic ModVar (navType)
     * @param integer $key
     * @return string classname e.g. Full_Path_Name
     */
    public static function getClassNameFromKey($key) {
        $types = self::getTypes();
        if (array_key_exists($key, $types)) {
            return $types[$key]['class'];
        } else {
            $dom = ZLanguage::getModuleDomain('DocTastic');
            LogUtil::addErrorPopup(__('Selected navigation type not found. Using default instead.', $dom));
            // return a default
            return $types[0]['class'];
        }
    }

    /**
     * Find and return a working filename with complete relative path
     * if one exists. else return false
     * @return string relative/path/to/filename or ''
     */
    public function getDefaultFile() {
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
    protected function setLanguageEnabled($_languageEnabled) {
        if (isset($_languageEnabled)) {
            $this->_languageEnabled = $_languageEnabled;
        }
    }

    /**
     * Set the docsDirectory
     * @param string $docsDirectory
     */
    protected function setDocsDirectory($docsDirectory) {
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
            // TODO should check to see if a langcode directory exists and if not, default to en or default to lang = ''?
            $lang = DIRECTORY_SEPARATOR . ZLanguage::getLanguageCode();
            // append User dir for users (not admins)
            // TODO should check to see if the User directory exists. If not, default to ''?
            $access = (SecurityUtil::checkPermission($this->docModule, '::', ACCESS_ADMIN)) ? '' : DIRECTORY_SEPARATOR . 'User';
            return $this->_docsDirectory . $lang . $access; // no trailing slash please
        } else {
            // TODO even if lang is not enabled shouldn't we check for access level?
            return $this->_docsDirectory;
        }
    }

    public function __construct($params) {
        if (isset($params['docsDirectory'])) {
            $this->setDocsDirectory($params['docsDirectory']);
        }
        if (isset($params['languageEnabled'])) {
            $this->setLanguageEnabled($params['languageEnabled']);
        }
        if (isset($params['docmodule'])) {
            $this->docModule = $params['docmodule'];
        }
        if (isset($params['addCore'])) {
            $this->_addCore = $params['addCore'];
        }
        if (isset($params['build']) && $params['build'] <> false) {
            $this->_build = $params['build'];
        }
        if ($this->_build) {
            $this->build();
            $this->postProcessBuild();
            $this->setHtml();
            $this->postProcessHtml();
        }
    }

    /**
     * This function duplicates much of the functionality of HtmlUtil::getSelector_Module
     * It customizes the input of that function for ease of use
     * It also further customizes the data before generating the html
     * It also customizes the html to produce the full form
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
            $value = $module[$field];
            $displayname = $module['displayname'];
            $data[$value] = $displayname;
        }
        // customize data here
        if ($this->_addCore) {
            // add core/docs
            $data['Core'] = 'Core Documentation';
        }
        // notify EVENT here to modify modules listed
        $event = new Zikula_Event('module.DocTastic.getModules', $data);
        EventUtil::notify($event);
        asort(&$data);
        // could change to include other STATE of modules (uninstaled, etc)
        $formaction = ModUtil::url('DocTastic', 'admin', 'view');
        $html = "<form action='$formaction' method='POST' enctype='application/x-www-form-urlencoded'>";
        $html .= HtmlUtil::getSelector_Generic($name, $data, $selectedValue, $defaultValue, $defaultText, $allValue, $allText, $submit, $disabled, $multipleSize);
        $html .= "</form>";
        return $html;
    }

    /**
     * Format an array of files as needed for display in navigation
     */
    abstract protected function format(array $files);

    /**
     * Build the control
     */
    abstract protected function build();

    /**
     * Post process the array of files
     */
    abstract protected function postProcessBuild();

    /**
     * set the html for the control
     */
    abstract protected function setHtml();

    /**
     * allow the file contents to modify navType control
     *
     * This function should be overridden by a child class to allow for changes
     * in the navType and control from within the file being read.
     * $the file is not stored internally to the object because it is of no
     * consequence to the object itself. It is only useful inasmuch as it
     * affect how the navigation should be  presented. It is not
     * labeled as 'abstract' because it is not required to be overridden.
     */
    public function interpretFile() {

    }

    /**
     * Post process the HTML before presentation
     *
     * Things could be done here like converting urls or something
     * maybe the safehtml should happen here?
     */
    protected function postProcessHtml() {
        $html  = $this->getModuleSelectorHtml();
        $html .= $this->html;
        
        $this->html = $html;
    }

    /**
     * Get the HTML for the control for display
     * @return string
     */
    public function getHtml() {
        return $this->html;
    }

}