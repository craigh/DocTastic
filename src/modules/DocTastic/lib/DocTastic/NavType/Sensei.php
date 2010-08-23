<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Sensei
 *
 * @author craig
 */
class DocTastic_NavType_Sensei extends DocTastic_NavType_Base {

    /**
     * Array of filenames to load if available
     * Loads them in order available
     * @var array
     */
    protected $defaultDoc = array('chapters.txt');
    /**
     * instance of Sensei_Doc_Renderer_Xhtml
     * @var object
     */
    private $renderer;
    /**
     * instance of Sensei_Doc_Toc
     * @var object
     */
    private $toc;

    /**
     * create toc and renderer objects
     */
    protected function build() {
//        $path = $this->getDirectory() . DIRECTORY_SEPARATOR . 'Admin' . $this->getDefaultFile();
        $this->setLanguageEnabled(true); // must be on for testing atm - overrides admin setting
        $path = $this->getDirectory() . DIRECTORY_SEPARATOR . 'Admin' . $this->getDefaultFile() . DIRECTORY_SEPARATOR . 'chapters.txt';
//        echo $path . "<br />";
        $this->toc = new Sensei_Doc_Toc($path);
        $options = array('template' => "%CONTENT%");
        $this->renderer = new Sensei_Doc_Renderer_Xhtml($this->toc, $options);
    }

    /**
     * format files array for use
     *
     * @param array $files nested array of files under $root
     */
    protected function format(array $files) {

    }

    /**
     * set the control's html
     */
    protected function setHtml() {
        $html  = $this->renderer->renderToc(); // render the table of contents
        $html .= $this->renderer->render();    // render all documentation

        // these aren't doing anything yet
        $introduction = $this->toc->findByIndex(1); // will return the introduction section (first part)
        $introduction = $this->toc->findByPath('introduction'); // will return the introduction section

        $basic_mapping = $this->toc->findByPath('basic-mapping');
        $html .= $basic_mapping->convertNameToPath('mapping-drivers');
        $html .= $basic_mapping->getIndex(); // tells what index (in the en.txt)

        $this->html = $html;
    }

    /**
     * do post processing on the tree array
     */
    protected function postProcessBuild() {
        
    }

}

?>
