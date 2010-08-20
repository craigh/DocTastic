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
 * Class to control Block display and interface
 */
class DocTastic_Block_Docs extends Zikula_Block
{
    /**
     * initialise block
     */
    public function init()
    {
        SecurityUtil::registerPermissionSchema('DocTastic:docsblock:', 'Block title::');
    }
    
    /**
     * get information on block
     */
    public function info()
    {
        return array(
            'text_type'        => 'Docs',
            'module'           => 'DocTastic',
            'text_type_long'   => $this->__('Docs tree block'),
            'allow_multiple'   => true,
            'form_content'     => false,
            'form_refresh'     => false,
            'show_preview'     => true,
            'admin_tableless'  => true);
    }
    
    /**
     * display block
     */
    public function display($blockinfo)
    {
        if (!SecurityUtil::checkPermission('DocTastic:docsblock:', "$blockinfo[title]::", ACCESS_OVERVIEW)) {
            return;
        }
        if (!ModUtil::available('DocTastic')) {
            return;
        }
        $vars = BlockUtil::varsFromContent($blockinfo['content']);
    
        $this->view->assign('vars', $vars);
    
        $blockinfo['content'] = $this->view->fetch('blocks/docs.tpl');
    
        return BlockUtil::themeBlock($blockinfo);
    }
    
    /**
     * modify block settings ..
     */
    public function modify($blockinfo)
    {
        $vars = BlockUtil::varsFromContent($blockinfo['content']);
    
        $this->view->assign('vars', $vars);
    
        return $this->view->fetch('blocks/docs_modify.tpl');
    }
    
    /**
     * update block settings
     */
    public function update($blockinfo)
    {
        $vars = BlockUtil::varsFromContent($blockinfo['content']);
    
        // alter the corresponding variable
        $vars['showAdminDocTasticinBlock'] = FormUtil::getPassedValue('showAdminDocTasticinBlock', '', 'POST');
    
        // write back the new contents
        $blockinfo['content'] = BlockUtil::varsToContent($vars);
    
        // clear the block cache
        $this->view->clear_cache('blocks/docs.tpl');
    
        return $blockinfo;
    }
} // end class def