<?php

/**
 * Description of Ajax
 *
 */
class DocTastic_Controller_Ajax extends Zikula_Controller_AbstractAjax {

    public function _postSetup()
    {
        // no need for a Zikula_View so override it.
    }

    /**
     * create a module override
     */
    function createoverride() {
        if (!SecurityUtil::checkPermission('DocTastic::', '::', ACCESS_ADD)) {
            LogUtil::registerPermissionError(null,true);
            throw new Zikula_Exception_Forbidden();
        }

        if (!SecurityUtil::confirmAuthKey()) {
            LogUtil::registerPermissionError(null,true);
            throw new Zikula_Exception_Forbidden();
        }

        // Add item
        $obj = array(
                'modname' => '',
                'navtype' => 0,
                'enablelang' => 1,
                'exempt' => 0
            );
        $result = DBUtil::insertObject($obj, 'doctastic');

        if ($result == false) {
            return AjaxUtil::error(LogUtil::registerError($this->__('Error! Could not create the override.')));
        }

        return new Zikula_Response_Ajax($obj);;
    }

    /**
     * update (edit) a module override
     */
    function updateoverride() {
        if (!SecurityUtil::confirmAuthKey()) {
            LogUtil::registerPermissionError(null,true);
            throw new Zikula_Exception_Forbidden();
        }
        $id = FormUtil::getPassedValue('id', null, 'post');
        $modname = FormUtil::getPassedValue('modname', '', 'post');
        $navtype = FormUtil::getPassedValue('navtype', 0, 'post');
        $enablelang = FormUtil::getPassedValue('enablelang', 1, 'post');
        $exempt = FormUtil::getPassedValue('exempt', 0, 'post');
        
        if (!SecurityUtil::checkPermission('DocTastic::', $id . '::', ACCESS_EDIT)) {
            LogUtil::registerPermissionError(null,true);
            throw new Zikula_Exception_Forbidden();
        }

        // does item already exist with that modname?
//        $exists = DBUtil::selectObjectByID('doctastic', $modname, 'modname');
//        if ($exists) {
//            // delete temp DB entry if we can confirm it IS a new/temp...
//            // DBUtil::deleteObjectByID('doctastic', $id);
//            return AjaxUtil::error(LogUtil::registerError(__('Module already has override')));
//        }

        // Update the item
        $obj = array(
                'id' => $id,
                'modname' => $modname,
                'navtype' => (int) $navtype,
                'enablelang' => (int) $enablelang,
                'exempt' => (int) $exempt);

        $result = DBUtil::updateObject($obj, 'doctastic');

        if ($result == false) {
            // check for sessionvar
            $msgs = LogUtil::getStatusMessagesText();
            if (!empty($msgs)) {
                // return with msg, but not via AjaxUtil::error
                return array('error' => true,
                        'id' => $id,
                        'message' => $msgs);
            }
        }

        $navTypes = DocTastic_NavType_Base::getTypesNames();
        $yesno = array(0 => __("No"), 1 => __("Yes"));

        $override = DBUtil::selectObjectByID('doctastic', $id, 'id', null, null, null, false);

        $override['navtype_disp'] = $navTypes[$override['navtype']];
        $override['enablelang_disp'] = $yesno[$override['enablelang']];
        $override['exempt_disp'] = $yesno[$override['exempt']];

        return new Zikula_Response_Ajax($override);

    }

    /**
     * delete a module override
     */
    function deleteoverride() {
        if (!SecurityUtil::confirmAuthKey()) {
            LogUtil::registerPermissionError(null,true);
            throw new Zikula_Exception_Forbidden();
        }

        $id = FormUtil::getPassedValue('id', null, 'get');
        $override = DBUtil::selectObjectByID('doctastic', $id);

        if (!SecurityUtil::checkPermission('DocTastic::', $id . '::', ACCESS_DELETE)) {
            LogUtil::registerPermissionError(null,true);
            throw new Zikula_Exception_Forbidden();
        }

        // Delete the item
        if (DBUtil::deleteObjectByID('doctastic', $id)) {
            return new Zikula_Response_Ajax(array('id' => $id));
        }

        throw new Zikula_Exception_Fatal(LogUtil::registerError($this->__('Error! Could not delete the requested override.')));
    }
}