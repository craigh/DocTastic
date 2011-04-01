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
        $this->checkAjaxToken();
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('DocTastic::', '::', ACCESS_ADD));

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
        $this->checkAjaxToken();

        $id = $this->request->getPost()->get('id', null);
        $modname = $this->request->getPost()->get('modname', '');
        $navtype = $this->request->getPost()->get('navtype', 0);
        $enablelang = $this->request->getPost()->get('enablelang', 1);
        $exempt = $this->request->getPost()->get('exempt', 0);

        $this->throwForbiddenUnless(SecurityUtil::checkPermission('DocTastic::', $id . '::', ACCESS_EDIT));

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

        $navTypes = DocTastic_Util::getTypesNames();
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
        $this->checkAjaxToken();

        $id = $this->request->getPost()->get('id', null);

        $this->throwForbiddenUnless(SecurityUtil::checkPermission('DocTastic::', $id . '::', ACCESS_DELETE));

        $override = DBUtil::selectObjectByID('doctastic', $id);

        // Delete the item
        if (DBUtil::deleteObjectByID('doctastic', $id)) {
            return new Zikula_Response_Ajax(array('id' => $id));
        }

        throw new Zikula_Exception_Fatal(LogUtil::registerError($this->__('Error! Could not delete the requested override.')));
    }
}