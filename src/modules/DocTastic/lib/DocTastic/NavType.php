<?php
/**
 * Class definition of NavTypes
 *
 * @author craig
 */
class DocTastic_NavType {

    /**
     * stack of information
     * @var array
     */
    private $types;

    /**
     * constructor.
     */
    public function __construct() {
        $this->types = array();
    }

    /**
     * add a Type to the stack
     * @param string $classname
     */
    public function add($type) {
        if (is_array($type)) {
            $tempType = array();
            $tempType['name'] = (isset($type['name'])) ? $type['name'] : false;
            $tempType['class'] = (isset($type['class']) && (class_exists($type['class']))) ? $type['class'] : false;
            if (($tempType['name']) && ($tempType['class'])) {
                $this->types[] = $tempType;
            }
        }
    }

    /**
     * return array of types
     * @return array
     */
    public function getTypes() {
        return $this->types;
    }

}