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
 * Class to control User interface
 */
class DocTastic_Controller_User extends Zikula_Controller
{
    /**
     * main
     *
     * main view function for end user
     * @access public
     */
    public function main()
    {
        return $this->view();
    }
    
    /**
     * view items
     * This is a standard function to provide an overview of all of the items
     * available from the module.
     */
    public function view()
    {
        if (!SecurityUtil::checkPermission('DocTastic::', '::', ACCESS_OVERVIEW)) {
            return LogUtil::registerPermissionError();
        }
    
        return $this->view->fetch('user/view.tpl');
    }
} // end class def