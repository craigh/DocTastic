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
class DocTastic_Api_Admin extends Zikula_AbstractApi
{
    /**
     * Get available admin panel links
     *
     * @return array array of admin links
     */
    public function getlinks()
    {
        // Define an empty array to hold the list of admin links
        $links = array();
    
        // Check the users permissions to each avaiable action within the admin panel
        // and populate the links array if the user has permission
        if (SecurityUtil::checkPermission('DocTastic::', '::', ACCESS_OVERVIEW)) {
            $links[] = array(
                'url' => ModUtil::url('DocTastic', 'user', 'view'),
                'text' => $this->__('View Documentation'),
                'class' => 'z-icon-es-view');
        }
        if (SecurityUtil::checkPermission('DocTastic::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => ModUtil::url('DocTastic', 'admin', 'modifyconfig'),
                'text' => $this->__('Default Settings'),
                'class' => 'z-icon-es-config');
        }
        if (SecurityUtil::checkPermission('DocTastic::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => ModUtil::url('DocTastic', 'admin', 'modifyoverrides'),
                'text' => $this->__('Module Overrides'),
                'class' => 'z-icon-es-config');
        }
        if (SecurityUtil::checkPermission('DocTastic::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => ModUtil::url('DocTastic', 'admin', 'generator'),
                'text' => $this->__('Document Generator'),
                'class' => 'z-icon-es-new');
        }
    
        // Return the links array back to the calling function
        return $links;
    }

} // end class def