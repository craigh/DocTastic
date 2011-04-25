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
		$this->redirect(ModUtil::url('DocTastic', 'user', 'view'));
    }

    public function view()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('DocTastic::', '::', ACCESS_OVERVIEW), LogUtil::getErrorMsgPermission());

        $docmodule = FormUtil::getPassedValue('docmodule', 'DocTastic', 'GETPOST');
        if ($docmodule == 'Core') {
            $docsDirectory = 'docs';
        } else {
            $docmoduleInfo = ModUtil::getInfoFromName($docmodule);
            $baseUri = System::getBaseUri() . '/';
            $relativePath = str_replace($baseUri, '', ModUtil::getBaseDir($docmoduleInfo['name']));
            $docsDirectory = $relativePath . '/' . 'docs';
        }

        $moduleConfig = DBUtil::selectObjectByID('doctastic', $docmodule, 'modname');
        if (isset($moduleConfig) && !empty($moduleConfig)) {
            $navTypeKey = $moduleConfig['navtype'];
            $languageEnabled = $moduleConfig['enablelang'];
        } else {
            $navTypeKey = ModUtil::getVar('DocTastic', 'navType');
            $languageEnabled = ModUtil::getVar('DocTastic', 'enableLanguages');
        }
        $classname = DocTastic_Util::getClassNameFromKey($navTypeKey);

        $control = new $classname(array(
            'docmodule' => $docmodule,
            'docsDirectory' => $docsDirectory,
            'addCore' => $this->getVar('addCore'),
            'languageEnabled' => $languageEnabled));
        if (!$control instanceof DocTastic_NavType_AbstractType) {
           throw new Zikula_Exception_Fatal($this->__f('NavType must be instance of %s.', 'DocTastic_NavType_AbstractType'));
        }

        $file = FormUtil::getPassedValue('file', $control->getDefaultFile(), 'GETPOST');
        $file = DataUtil::formatForOS($file);

        $this->view->setCache_Id($file);
        if (!$this->view->is_cached('user/view.tpl')) {
            if (isset($file) && !empty($file) && file_exists($file)) {
                $fileContents = FileUtil::readFile($file);
                $control->interpretFile($fileContents);
                $renderedFile = StringUtil::getMarkdownExtraParser()->transform($fileContents);
                $this->view->assign('document', $renderedFile);
                $file = str_replace('\\', '/', $file);
                $nameparts = explode('/', $file);
                $name = array_pop($nameparts);
                $this->view->assign('documentname', $name);
            } else {
                $this->view->assign('document', '');
                $this->view->assign('documentname', '');
            }

            $this->view->assign('navigation', $control->getHtml());
            $this->view->assign('directory', $control->getDirectory());
        }
        
        return $this->view->fetch('user/view.tpl');
    }
} // end class def