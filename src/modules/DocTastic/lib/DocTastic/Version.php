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
 * Class to control Version information
 */
class DocTastic_Version extends Zikula_Version
{
    public function getMetaData()
    {
        $meta = array();
        $meta['displayname']    = $this->__('DocTastic');
        $meta['url']            = $this->__(/*!used in URL - nospaces, no special chars, lcase*/'doctastic');
        $meta['description']    = $this->__('Documentation Reader for Zikula Application Framework');
        $meta['version']        = '1.0.0';

        $meta['securityschema'] = array(
            'DocTastic::'      => '::');
        $meta['core_min']       = '1.3.0'; // requires minimum 1.3.0 or later
        //$meta['core_max'] = '1.3.0'; // doesn't work with versions later than x.x.x

        return $meta;
    }
} // end class def