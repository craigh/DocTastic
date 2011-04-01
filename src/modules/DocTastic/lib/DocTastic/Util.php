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

} // end class def