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
class DocTastic_Controller_User extends Zikula_AbstractController
{
    public function main()
    {
        return $this->view();
    }

    public function view()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('DocTastic::', '::', ACCESS_OVERVIEW), LogUtil::getErrorMsgPermission());

        $docmodule = FormUtil::getPassedValue('docmodule', 'DocTastic', 'GETPOST');
        if ($docmodule == 'Core') {
            $docsDirectory = 'docs';
        } else {
            $docmoduleInfo = ModUtil::getInfoFromName($docmodule);
            $relativePath = str_replace(System::getBaseUri() . DIRECTORY_SEPARATOR, '', ModUtil::getBaseDir($docmoduleInfo['name']));
            $docsDirectory = $relativePath . DIRECTORY_SEPARATOR . 'docs';
        }

        $moduleConfig = DBUtil::selectObjectByID('doctastic', $docmodule, 'modname');
        if (isset($moduleConfig) && !empty($moduleConfig)) {
            $navTypeKey = $moduleConfig['navtype'];
            $languageEnabled = $moduleConfig['enablelang'];
        } else {
            $navTypeKey = ModUtil::getVar('DocTastic', 'navType');
            $languageEnabled = ModUtil::getVar('DocTastic', 'enableLanguages');
        }
        $classname = DocTastic_NavType_Base::getClassNameFromKey($navTypeKey);

        $control = new $classname(array(
            'docmodule' => $docmodule,
            'docsDirectory' => $docsDirectory,
            'addCore' => $this->getVar('addCore'),
            'languageEnabled' => $languageEnabled));

        $file = FormUtil::getPassedValue('file', $control->getDefaultFile(), 'GETPOST');

        if (isset($file) && !empty($file) && file_exists($file)) {
            $fileContents = FileUtil::readFile($file);
            $control->interpretFile($fileContents);
            $renderedFile = StringUtil::getMarkdownExtraParser()->transform($fileContents);
            $this->view->assign('document', $renderedFile);
            $nameparts = explode(DIRECTORY_SEPARATOR, $file);
            $name = array_pop($nameparts);
            $this->view->assign('documentname', $name);
        } else {
            $this->view->assign('document', '');
            $this->view->assign('documentname', '');
        }

        $this->view->assign('navigation', $control->getHtml());
        $this->view->assign('directory', $control->getDirectory());

        return $this->view->fetch('user/view.tpl');
    }
} // end class def