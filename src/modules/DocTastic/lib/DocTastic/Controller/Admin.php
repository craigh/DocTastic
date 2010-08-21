<?php
/**
 * Copyright Craig Heydenburg 2010 - DocTastic
 *
 * DocTastic
 * Documentation Reader for Zikula Application Framework
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control Admin interface
 */
class DocTastic_Controller_Admin extends Zikula_Controller
{

    /**
     * the main administration function
     * This function is the default function, and is called whenever the
     * module is initiated without defining arguments.
     */
    public function main()
    {
        if (!SecurityUtil::checkPermission('DocTastic::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        return $this->view();
    }

    /**
     * @desc   present administrator options to change module configuration
     * @return config template
     */
    public function modifyconfig()
    {
        if (!SecurityUtil::checkPermission('DocTastic::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
    
        $modinfo = ModUtil::getInfo(ModUtil::getIdFromName('DocTastic'));
        $this->view->assign('version', $modinfo['version']);

        $sel = HtmlUtil::getSelector_Generic('navType', DocTastic_NavType_Base::getTypesNames(), ModUtil::getVar('DocTastic', 'navType'));
        $this->view->assign('navTypeSelector', $sel);
    
        return $this->view->fetch('admin/modifyconfig.tpl');
    }

    /**
     * @desc   sets module variables as requested by admin
     * @return status/error ->back to modify config page
     */
    public function updateconfig()
    {
        if (!SecurityUtil::checkPermission('DocTastic::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        $modVars = array(
            'navType' => FormUtil::getPassedValue('navType', 0, 'POST'),
            'addCore' => FormUtil::getPassedValue('addCore', 0, 'POST'),
            'enableLanguages' => FormUtil::getPassedValue('enableLanguages', 0, 'POST'),
        );

        // delete all the old vars
        ModUtil::delVar('DocTastic');
    
        // set the new variables
        ModUtil::setVars('DocTastic', $modVars);
    
        // Let any other modules know that the modules configuration has been updated
        $this->callHooks('module', 'updateconfig', 'DocTastic', array(
            'module' => 'DocTastic'));
    
        // clear the cache
        $this->view->clear_cache();
    
        LogUtil::registerStatus($this->__('Done! Updated the DocTastic configuration.'));
        return $this->modifyconfig();
    }

    /**
     * @desc set caching to false for all admin functions
     */
    public function postInitialize()
    {
        $this->view->setCaching(false);
    }
    
    /**
     * @desc View a rendered document with navigation
     */
    public function view()
    {
        $docmodule = FormUtil::getPassedValue('docmodule', 'DocTastic', 'GETPOST');
        if ($docmodule == 'Core') {
            $docsDirectory = 'docs';
        } else {
            $docmoduleInfo = ModUtil::getInfoFromName($docmodule);
            $relativePath = str_replace(System::getBaseUri() . "/", '', ModUtil::getBaseDir($docmoduleInfo['name']));
            $docsDirectory = $relativePath . DIRECTORY_SEPARATOR . 'docs';
        }

        $navTypeKey = ModUtil::getVar('DocTastic', 'navType');
        $classname = DocTastic_NavType_Base::getClassNameFromKey($navTypeKey);

        $control = new $classname(array(
            'docmodule' => $docmodule,
            'docsDirectory' => $docsDirectory,
            'addCore' => ModUtil::getVar('DocTastic', 'addCore'),
            'languageEnabled' => ModUtil::getVar('DocTastic', 'enableLanguages')));

        $file = FormUtil::getPassedValue('file', $control->getWorkingDefault(), 'GETPOST');

        if (isset($file) && !empty($file)) {
            $fileContents = FileUtil::readFile($file);
            $renderedFile = StringUtil::getMarkdownExtraParser()->transform($fileContents);
            $this->view->assign('document', $renderedFile);
            $nameparts = explode(DIRECTORY_SEPARATOR, $file);
            $name = array_pop($nameparts);
            $this->view->assign('documentname', $name);
        } else {
            $this->view->assign('document', '');
            $this->view->assign('documentname', '');
        }

        $this->view->assign('navigation', $control->getHTML());
        $this->view->assign('directory', $control->getDirectory());

        $modinfo = ModUtil::getInfo(ModUtil::getIdFromName('DocTastic'));
        $this->view->assign('version', $modinfo['version']);

        return $this->view->fetch('admin/view.tpl');
    }

} // end class def