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
        $module = ModUtil::getName();
        $args = array('docmodule' => $module);
        $event->data[] = array('url' => ModUtil::url('DocTastic', 'user', 'view', $args), 'text' => $module . ' ' . __('Documentation'));
    }
}
