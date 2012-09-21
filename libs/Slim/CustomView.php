<?php

class CustomView extends Slim_View
{
    static protected $_layout = NULL;

    public static function set_layout($layout=NULL)
    {
        self::$_layout = $layout;
    }

    public function render($template)
    {
        extract($this->data);
        $templatePath = $this->getTemplatesDirectory() . '/' . ltrim($template, '/');
        if ( !file_exists($templatePath) ) {
            throw new RuntimeException('View cannot render template `' . $templatePath . '`. Template does not exist.');
        }
        ob_start();
        require $templatePath;
        $html = ob_get_clean();
        return $this->_render_layout($html);
    }

    public function _render_layout($_html)
    {
        extract($this->data);
        if(isset($_SERVER['HTTP_HOST'])){
            $data['base_url'] = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
            $data['base_url'] .= '://'. $_SERVER['HTTP_HOST'];
            $data['base_url'] .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
        }else{
            $data['base_url'] = 'http://localhost';
        }
        
        if(self::$_layout !== NULL)
        {
            $layout_path = $this->getTemplatesDirectory() . '/' . ltrim(self::$_layout, '/');
            if ( !file_exists($layout_path) ) {
                throw new RuntimeException('View cannot render layout `' . $layout_path . '`. Layout does not exist.');
            }
            ob_start();
            require $layout_path;
            $_html = ob_get_clean();
        }
        return $_html;
    }
}