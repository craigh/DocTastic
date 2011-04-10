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
 * External Util class for example
 */
class DocTastic_Util
{

    /**
     * get types array
     * static because is called by another static function
     *
     * @return array
     */
    private static function getNavTypes() {
        $event = new Zikula_Event('module.doctastic.gettypes', new DocTastic_NavType());
        $types = EventUtil::getManager()->notify($event)->getSubject()->getTypes();
        return $types;
    }

    /**
     * get the navTypes names for use in selector, etc.
     * @return array array of navType names
     */
    public static function getTypesNames() {
        $types = self::getNavTypes();
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
        $types = self::getNavTypes();
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
     * Get the modules that are exempted
     * @return array
     */
    public static function getExempt() {
        ModUtil::dbInfoLoad('DocTastic');
        $exempt = DBUtil::selectObject('doctastic', 'WHERE exempt=1', array('modname'));
        return (isset($exempt) && !empty($exempt)) ? $exempt : array();
    }

    /**
     * Is a module exempted?
     * @param string $module
     * @return boolean
     */
    public static function isExempt($module) {
        $exemptModules = self::getExempt();
        if (in_array($module, $exemptModules)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * get the modules listed in the doctastic table
     * which is a list of overrides
     * @return array
     */
    public static function getListed() {
        ModUtil::dbInfoLoad('DocTastic');
        $modules = DBUtil::selectObjectArray('doctastic');

        $navTypes = DocTastic_Util::getTypesNames();

        foreach($modules as $key => $module) {
            $modules[$key]['navtype_disp'] = $navTypes[$module['navtype']];
            $modules[$key]['editurl'] = ModUtil::url('DocTastic', 'admin', 'modifyoverrides');
            $modules[$key]['deleteurl'] = ModUtil::url('DocTastic', 'admin', 'modifyoverrides');
        }
        return $modules;
    }

    /**
     * get rendered file for inline help
     * func defaults to 'main' because some modules still do this.
     * 
     * @param string $mod
     * @param string $type
     * @param string $func
     * @param string $lang
     * @return string
     */
    public static function getInlineHelp($mod, $type, $func = 'main', $lang = 'en') {
        $type = ucwords($type);
        $files = array(
            0 => "modules/$mod/docs/$lang/Help/$type/$func.txt", // normal
            1 => "modules/$mod/docs/en/Help/$type/$func.txt",  // en normal
            2 => "modules/$mod/docs/$lang/Help/Default/help.txt", // translated default
            3 => "modules/$mod/docs/en/Help/Default/help.txt", // en default
            4 => "modules/DocTastic/docs/en/Help/Default/help.txt"); // DocTastic en default
        foreach ($files as $file) {
            if (file_exists($file)) {
                $helpfile = $file;
                break;
            }
        }
        $fileContents = FileUtil::readFile($helpfile);
        return StringUtil::getMarkdownExtraParser()->transform($fileContents);
    }
} // end class def