<?php
class Text_Wiki_Render_Xhtml_Doclink extends Text_Wiki_Render {
    
    var $conf = array(
        'url_callback' => null,
        'css' => null
    );
    
    function token($options)
    {
        $callback = $this->getConf('url_callback');
        
        if ($callback) {
            $href = call_user_func($callback, $options['path']);
        } else {
            $href = $options['path']; 
        }
        
        if ($this->getConf('css')) {
            $css = ' class="pageref ' . $this->getConf('css') . '"';
        } else {
            $css = ' class="pageref"';
        }
        
        $output = '<a href="' . $href . '"' . $css . '>' . $options['text'] . '</a>';
        
        return $output;
    }
}
