<?php

class Render {
    protected $header = 'index';
    protected $footer = 'index';

    function load_page($php_file = '') {
          global $db;
          if(empty($php_file)) $php_file = HOME_FILE;
        
          $data = $this->view_to_string($php_file);
          if (strpos($php_file, 'modal') === false) {
                $data['header'] = $this->php_to_string(__DIR__ . '/../template/header/' . $this->header . '.php');
                $data['footer'] = $this->php_to_string(__DIR__ . '/../template/footer/'. $this->footer . '.php');
                include('./template/layout.php');
          }
          else echo $data['content']; // for modal
    }

    function view_to_string($php_file) {
        try {
            $explode = explode('.', $php_file);
            if(count($explode) <= 1) $php_file = $php_file.'.php';

            $file = __DIR__ . '/../pages/'.$php_file;

            if (is_dir(str_replace('.php', '', $file))) {
                $file = str_replace('.php', '', $file) .'/index.php';
            }

            // CHECK FOR PARAMS
            $param = '';
            $param_value = '';
            if(!file_exists($file)) {
                $dir = dirname($file);
                $param_value = str_replace('.php', '', basename($file));
                $files = glob($dir .'/_*.php');
                if (count($files) > 0) {
                    $file = $files[0];
                    $filename = basename($file);
                    $param = str_replace(array('_', '.php'), '', $filename);
                }
                else {
                    throw new Exception('Page not found');
                }
            }

            foreach(globals() as $key => $value) {
                $$key = $value;
            }
            
            ob_start();
            $params = array();
            if (!empty($param) && !empty($param_value)) {
                $params[$param] = ($param_value === 'index' ? '' : $param_value);
            }
            include($file);
            $strView = ob_get_clean();
            $data = array('content' => $strView);
            return $data;
        }
        catch(Exception $e) {
            $data = array('message' => $e->getMessage());
            extract($data);
            include(__DIR__ . '/../template/error404.php');
        }
    }

    function php_to_string($php_file) {
        ob_start();
        $params = array();
        include($php_file);
        $strView = ob_get_clean();
        return $strView;
    }

    function header($file = 'index') {
        $this->header = $file;
    }

    function footer($file = 'index') {
        $this->footer = $file;
    }
}
