<?php

class DocTastic_Handlers
{

    /**
     * populate Services menu with hook option link
     *
     * @param Zikula_Event $event
     */
    public static function servicelinks(Zikula_Event $event)
    {
        $module = $event->getArg('modname');
        if (!DocTastic_Util::isExempt($module)) {
            $args = array('docmodule' => $module);
            $event->data[] = array('url' => ModUtil::url('DocTastic', 'user', 'view', $args), 'text' => $module . ' ' . __('Documentation'));
        }
    }

    /**
     * add NavTypes to stack
     * 
     * @param Zikula_Event $event
     */
    public static function getTypes(Zikula_Event $event)
    {
        $types = $event->getSubject();
        $types->add(array(
            'name' => 'Directory Tree',
            'class' => 'DocTastic_NavType_TreeType'));
        $types->add(array(
            'name' => 'Directory Select Box',
            'class' => 'DocTastic_NavType_SelectType'));
    }

    /**
     * add help docs to page
     * @param Zikula_Event $event
     * @return string
     */
    public static function renderHelp(Zikula_Event $event)
    {
        $enableInlineHelp = ModUtil::getVar('DocTastic', 'enableInlineHelp', false);
        $type = FormUtil::getPassedValue('type', 'user', 'GETPOST');
        if (($enableInlineHelp) && ($type == 'admin')) {
            $view = $event->getSubject();
            $topmodule = $view->getToplevelmodule();
            $lang = $view->getLanguage();
            $func = FormUtil::getPassedValue('func', 'main', 'GETPOST');
            $html = DocTastic_Util::getInlineHelp($topmodule, $type, $func, $lang);
            $view->assign('doctastic_help', array(
                'topmodule' => $topmodule,
                'type' => $type,
                'func' => $func,
                'lang' => $lang,
                'html' => $html,
            ));
            $template = "file:" . getcwd() . "/modules/DocTastic/templates/admin/help.tpl";
            $content = $event->getData();
            $content = $view->fetch($template) . $content;
            $event->setData($content);
        }
    }

}
