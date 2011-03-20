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
 * Class to control Installer interface
 */
class DocTastic_Installer extends Zikula_AbstractInstaller
{
    /**
     * Initializes a new install
     *
     * This function will initialize a new installation.
     * It is accessed via the Zikula Admin interface and should
     * not be called directly.
     *
     * @return  boolean    true/false
     */
    public function install()
    {
        // create table
        if (!DBUtil::createTable('doctastic')) {
            return LogUtil::registerError($this->__('Error! Could not create the table.'));
        }
        ModUtil::setVar('DocTastic', 'navType', 0);
        ModUtil::setVar('DocTastic', 'addCore', 1);
        ModUtil::setVar('DocTastic', 'enableLanguages', 1);

        EventUtil::registerPersistentModuleHandler('DocTastic', 'module_dispatch.service_links', array('DocTastic_Handlers', 'servicelinks'));

        return true;
    }
    
    /**
     * Upgrades an old install
     *
     * This function is used to upgrade an old version
     * of the module.  It is accessed via the Zikula
     * Admin interface and should not be called directly.
     *
     * @param   string    $oldversion Version we're upgrading
     * @return  boolean   true/false
     */
    public function upgrade($oldversion)
    {
        if (!SecurityUtil::checkPermission('DocTastic::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
    
        switch ($oldversion) {
            case '1.0.0':
                //future development
        }
    
        return true;
    }
    
    /**
     * removes an install
     *
     * This function removes the module from your
     * Zikula install and should be accessed via
     * the Zikula Admin interface
     *
     * @return  boolean    true/false
     */
    public function uninstall()
    {
        $result = DBUtil::dropTable('doctastic');
        $result = $result && $this->delVars();

        // unregister handlers
        EventUtil::unregisterPersistentModuleHandlers('DocTastic');

        return $result;
    }
} // end class def