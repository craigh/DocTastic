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
        return $this->modifyconfig();
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
        //$this->callHooks('module', 'updateconfig', 'DocTastic', array(
        //   'module' => 'DocTastic'));
    
        // clear the cache
        $this->view->clear_cache();
    
        LogUtil::registerStatus($this->__('Done! Updated the DocTastic configuration.'));
        return $this->modifyconfig();
    }

    public function modifyoverrides() {
        if (!SecurityUtil::checkPermission('DocTastic::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        $modules = DBUtil::selectObjectArray('doctastic');
        $navTypes = DocTastic_NavType_Base::getTypesNames();

        foreach($modules as $key => $module) {
            $modules[$key]['navtype_disp'] = $navTypes[$module['navtype']];
            $modules[$key]['editurl'] = ModUtil::url('DocTastic', 'admin', 'modifyoverrides');
            $modules[$key]['deleteurl'] = ModUtil::url('DocTastic', 'admin', 'modifyoverrides');
        }
        $this->view->assign('modules', $modules);

        $navTypeOptions = DocTastic_NavType_Base::getTypesNames();
        $this->view->assign('navTypeOptions', $navTypeOptions);
        
        $this->view->assign('yesno', array(0 => __("No"), 1 => __("Yes")));

        $control = new DocTastic_NavType_None(array(
            'build' => false,
            'addCore' => ModUtil::getVar('DocTastic', 'addCore')));
        $this->view->assign('moduleOptions', $control->getModuleSelectorHtml('modname_1', 0, 0, '', 0, '', false, false, 1, 'directory', true));

        return $this->view->fetch('admin/modules.tpl');
    }

    /**
     * Create a markitup generator page
     * @return string
     */
    public function generator() {
        return $this->view->fetch('admin/generator.tpl');
    }

    /**
     * This function is used by the markitup javascript library
     * to produce a preview of the rendered text
     * @return string
     */
    public function parser() {
        $data = FormUtil::getPassedValue('data', '', 'GETPOST');
        $parsed = StringUtil::getMarkdownExtraParser()->transform($data);
        $this->view->assign('data', DataUtil::formatForDisplayHTML($parsed));
        $this->view->display('admin/parser.tpl');
        return true; // forces the Zikula display engine to not display the theme
    }
    /**
     * @desc set caching to false for all admin functions
     */
    public function postInitialize()
    {
        $this->view->setCaching(false);
    }
    
} // end class def