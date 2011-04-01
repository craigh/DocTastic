<?php

/**
 * Extends NavType_Base to create a tree-style navigation of Documents
 * using the Zikula_Tree class
 */
class DocTastic_NavType_TreeType extends DocTastic_NavType_AbstractType {

    /**
     * stores the ongoing treeid for Zikula_Tree
     * @var integer
     */
    private static $_treeid = 1;
    /**
     * stores nodes of the tree where we don't want links
     * @var array
     */
    private static $_treenodes = array();

    /**
     * create the files array
     */
    protected function build() {
        $files = FileUtil::getFiles($this->getDirectory(), true, true, $this->allowedExtensions, null, true);
        // create root entry
        $this->files[] = $this->_makeArray(self::$_treeid, 0, $this->rootName, '');
        self::$_treenodes[] = self::$_treeid;
        // create Document tree
        $this->format($files, 1, $this->getDirectory());
    }

    /**
     * format files array for use in JS Zikula_Tree
     *
     * @param array $files nested array of files under $root
     * @param integer $parent_id id used in recursion
     * @param string $root pathname files are structured under
     */
    protected function format(array $files, $parent_id = 0, $root = '') {
        foreach ($files as $key => $file) {
            self::$_treeid++;
            if (is_array($file)) {
                $this->files[] = $this->_makeArray(self::$_treeid, $parent_id, $key, $root);
                if (!in_array(self::$_treeid, self::$_treenodes)) {
                    self::$_treenodes[] = self::$_treeid;
                }
                $this->format($file, self::$_treeid, $root . DIRECTORY_SEPARATOR . $key);
            } else {
                $this->files[] = $this->_makeArray(self::$_treeid, $parent_id, $file, $root);
            }
        }
    }

    /**
     * set the control's html
     */
    protected function setHtml() {
        $tree = new Zikula_Tree();
        $tree->loadArrayData($this->files);
        $html = $tree->getHTML();
        $this->html = $html;
    }

    /**
     * return an array item for the Zikula_Tree
     *
     * @param integer $id tree id
     * @param integer $pid tree parent id
     * @param string $name display name
     * @param string $path path to parent dir
     * @return array the array item
     */
    private function _makeArray($id, $pid, $name, $path, $overwrite = array()) {
        $args = array(
            'file' => DataUtil::formatForOS($path . DIRECTORY_SEPARATOR . $name),
            'docmodule' => $this->docModule,
        );
        $href = ModUtil::url('DocTastic', 'user', 'view', $args); // $this->userType instead of 'user' ?
        $treeitem = array(
            'id' => $id,
            'parent_id' => $pid,
            'name' => $name,
            'title' => $name,
            'icon' => null,
            'class' => null,
            'active' => true,
            'expanded' => false,
            'href' => $href,
        );
        if ((is_array($overwrite)) and (!empty($overwrite))) {
            foreach ($overwrite as $k => $v) {
                $treeitem[$k] = $v;
            }
        }

        return $treeitem;
    }

    /**
     * do post processing on the tree array
     */
    protected function postProcessBuild() {
        foreach ($this->files as $key => $item) {
            // remove link from tree nodes
            if (in_array($item['id'], self::$_treenodes)) {
                $args = array('docmodule' => $this->docModule);
                $this->files[$key]['href'] = ModUtil::url('DocTastic', 'user', 'view', $args); // $this->userType instead of 'user' ?
            }
            if ($item['parent_id'] == 0)
                continue;
            // remove entries with disallowed extensions
            if (in_array(FileUtil::getExtension($item['name']), $this->disallowedExtensions)) {
                unset($this->files[$key]);
            }
        }
    }

}