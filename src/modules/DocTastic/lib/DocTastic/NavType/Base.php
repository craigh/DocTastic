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
    protected $_disallowedExtensions = array('php', 'odp', 'odt', 'doc', 'docx', 'swf', 'jpg', 'gif', 'png', 'htm', 'html', 'tpl', 'pot', 'htaccess');
    /**
     * User or Admin type
     * @var string
     */
    protected $userType = 'admin';
    /**
     * append language on docsDirectory
     * @see getDirectory
     * @var boolean
     */
    private $_languageEnabled = true;
    /**
     * The docs directory to display
     * @var string
     */
    private $docsDirectory = 'docs';
    /**
     * navigation types
     * @var array
     */
    public static $types = array('Tree', 'Select');
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
    public $docModule = '';

    /**
     * set the Navigation Type
     * @param string $_navType
     */
    public function setType($type) {
        if ((in_array($type, self::$types)) && (!empty($type))) {
            $this->type = $type;
        }
    }
    public static function getTypeFromKey($key) {
        return self::$types[$key];
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
            $this->docsDirectory = $docsDirectory;
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
            return $this->docsDirectory . '/' . $lang; // no trailing slash please
        } else {
            return $this->docsDirectory;
        }
        // TODO add perm check here and if !admin, append 'User'
    }

    public function __construct($params) {
        if (isset($params['docsDirectory'])) {
            $this->setDocsDirectory($params['docsDirectory']);
        }
        if (isset($params['languageEnabled'])) {
            $this->set_languageEnabled($params['languageEnabled']);
        }
    }

    abstract protected function formatArray(array $files);

    abstract protected function postProcessArray();

    abstract public function getHTML();

}