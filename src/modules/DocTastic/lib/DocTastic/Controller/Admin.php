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
class DocTastic_Controller_Admin extends Zikula_AbstractController
{

    /**
     * the main administration function
     * This function is the default function, and is called whenever the
     * module is initiated without defining arguments.
     */
    public function main()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('DocTastic::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        return $this->modifyconfig();
    }

    /**
     * @desc   present administrator options to change module configuration
     * @return config template
     */
    public function modifyconfig()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('DocTastic::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
    
        $sel = HtmlUtil::getSelector_Generic('navType', DocTastic_Util::getTypesNames(), ModUtil::getVar('DocTastic', 'navType'));
        $this->view->assign('navTypeSelector', $sel);
    
        return $this->view->fetch('admin/modifyconfig.tpl');
    }

    /**
     * @desc   sets module variables as requested by admin
     * @return status/error ->back to modify config page
     */
    public function updateconfig()
    {
        $this->checkCsrfToken();
        
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('DocTastic::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        $modVars = array(
            'navType' => FormUtil::getPassedValue('navType', 0, 'POST'),
            'addCore' => FormUtil::getPassedValue('addCore', 0, 'POST'),
            'enableLanguages' => FormUtil::getPassedValue('enableLanguages', 0, 'POST'),
            'enableInlineHelp' => FormUtil::getPassedValue('enableInlineHelp', 0, 'POST'),
        );

        // delete all the old vars
        ModUtil::delVar('DocTastic');
    
        // set the new variables
        ModUtil::setVars('DocTastic', $modVars);
    
        // clear the cache
        $this->view->clear_cache();
    
        LogUtil::registerStatus($this->__('Done! Updated the DocTastic configuration.'));
        return $this->modifyconfig();
    }

    public function modifyoverrides() {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('DocTastic::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());

        $modules = DocTastic_Util::getListed();
        $this->view->assign('modules', $modules);

        $navTypeOptions = DocTastic_Util::getTypesNames();
        $this->view->assign('navTypeOptions', $navTypeOptions);
        
        $this->view->assign('yesno', array(0 => __("No"), 1 => __("Yes")));

        $control = new DocTastic_NavType_NoneType(array(
            'build' => false,
            'addCore' => ModUtil::getVar('DocTastic', 'addCore')));
        $this->view->assign('moduleOptions', $control->getModuleSelectorHtml('modname_1', 0, 0, '', 0, '', false, false, 1, 'directory', true, true));

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