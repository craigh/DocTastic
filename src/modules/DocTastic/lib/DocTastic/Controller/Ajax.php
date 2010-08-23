<?php

/**
 * Description of Ajax
 *
 */
class DocTastic_Controller_Ajax extends Zikula_Controller {

    public function _postSetup()
    {
        // no need for a Zikula_View so override it.
    }

    /**
     * create a module override
     */
    function createoverride() {
        if (!SecurityUtil::checkPermission('DocTastic::', '::', ACCESS_ADD)) {
            return AjaxUtil::error(LogUtil::registerPermissionError(null,true));
        }

        if (!SecurityUtil::confirmAuthKey()) {
            return AjaxUtil::error(LogUtil::registerAuthidError());
        }
        // Add item
        $obj = array(
                'modname' => '',
                'navtype' => 0,
                'enable_lang' => 1
            );
        $result = DBUtil::insertObject($obj, 'doctastic');

        if ($result == false) {
            return AjaxUtil::error(LogUtil::registerError($this->__('Error! Could not create the override.')));
        }

        return $obj;
    }

    /**
     * update (edit) a module override
     */
    function updateoverride() {
        if (!SecurityUtil::confirmAuthKey()) {
            return AjaxUtil::error(LogUtil::registerAuthidError());
        }
        $id = FormUtil::getPassedValue('id', null, 'post');
        $navtype = FormUtil::getPassedValue('navtype', 0, 'post');
        $enable_lang = FormUtil::getPassedValue('enable_lang', 1, 'post');
        
        if (!SecurityUtil::checkPermission('DocTastic::', $id . '::', ACCESS_EDIT)) {
            return AjaxUtil::error(LogUtil::registerPermissionError(null,true));
        }

        // Update the item
        $obj = array(
                'id' => $id,
                'navtype' => (int) $navtype,
                'enable_lang' => (int) $enable_lang);

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
        $override['enable_lang_disp'] = $yesno[$override['enable_lang']];

        return $override;

    }

    /**
     * delete a module override
     */
    function deleteoverride() {
        if (!SecurityUtil::confirmAuthKey()) {
            return AjaxUtil::error(LogUtil::registerAuthidError());
        }

        $id = FormUtil::getPassedValue('id', null, 'get');
        $override = DBUtil::selectObjectByID('doctastic', $id);
        //LogUtil::log(var_export($override, true));

        if (!SecurityUtil::checkPermission('DocTastic::', $id . '::', ACCESS_DELETE)) {
            return AjaxUtil::error(LogUtil::registerPermissionError(null,true));
        }

        // Delete the item
        if (DBUtil::deleteObjectByID('doctastic', $id)) {
            return array('id' => $id);
        }

        return AjaxUtil::error(LogUtil::registerError($this->__('Error! Could not delete the requested override.')));
    }
}