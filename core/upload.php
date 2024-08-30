<?php

class Upload {
    private $config;
    public function __construct($config) {
        $this->config = $config;
    }

    function file ($files, $target_dir = 'assets/img') {
        if (!empty($files)) {
            $tmp_name = $files['tmp_name'];
            $filename = basename($files['name']);
            $filetype = strtolower(pathinfo($filename,PATHINFO_EXTENSION));
            
            $check = getimagesize($tmp_name);
            if ($check) {
                $mime = $check['mime'];
                // valid image
                if (strpos($mime, 'image') !== false) {
                    if (IS_DEVELOP) {
                        $name = random().'.jpg';
                        $targetDir = $target_dir . '/' . $name;
                        $dirname = getcwd() . '/' . $targetDir;
                        move_uploaded_file($tmp_name, $dirname);

                        return array('url' => $this->config['base_url'] . '/' . $targetDir);
                    } else {
                        try {
                            $response = \Cloudinary\Uploader::upload($tmp_name);
                            if (!empty($response['url'])) {
                                return $response;
                            } else {
                                return array('url' => '');
                            }
                        } catch (Exception $e) {
                            return array('url' => '');
                        }
                    }
                }
            }
        }
    }
}


;?>