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
        $types->add(array(
            'name' => 'None',
            'class' => 'DocTastic_NavType_NoneType'));
    }

}
