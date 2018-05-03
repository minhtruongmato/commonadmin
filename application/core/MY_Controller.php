<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    protected $data = array();
    protected $author_info = array();
    protected $page_languages = array('vi', 'en');
    protected $langAbbreviation = 'vi';

    function __construct() {
        parent::__construct();

        $this->data['page_title'] = 'Template';
        $this->data['before_head'] = '';
        $this->data['before_body'] = '';
    }

    protected function render($the_view = NULL, $template = 'master') {
        if ($template == 'json' || $this->input->is_ajax_request()) {
            header('Content-Type: application/json');
            echo json_encode($this->data);
        } else {
            $this->data['the_view_content'] = (is_null($the_view)) ? '' : $this->load->view($the_view, $this->data, TRUE);
            $this->load->view('templates/' . $template . '_view', $this->data);
        }
    }

    protected function pagination_config($base_url, $total_rows, $per_page, $uri_segment) {
        $config['base_url'] = $base_url;
        $config['per_page'] = $per_page;
        $config['uri_segment'] = $uri_segment;
        $config['prev_link'] = 'Prev';
        $config['next_link'] = 'Next';
        $config['total_rows'] = $total_rows;
        $config['reuse_query_string'] = true;
        return $config;
    }

}

class Admin_Controller extends MY_Controller {

    function __construct() {
        parent::__construct();

        $this->data['page_languages'] = array('vi' => 'Tiếng Việt', 'en' => 'English');

        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/user/login', 'refresh');
        }
        $this->data['user_email'] = $this->ion_auth->user()->row()->username;
        $this->data['page_title'] = 'Administrator area';

        // Set timezone
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        // Insert author informations to database when insert, update or delete
        $this->author_info = array(
            'created_at' => date('Y-m-d H:i:s', now()),
            'created_by' => $this->ion_auth->user()->row()->username,
            'updated_at' => date('Y-m-d H:i:s', now()),
            'updated_by' => $this->ion_auth->user()->row()->username
        );
    }

    protected function render($the_view = NULL, $template = 'admin_master') {
        parent::render($the_view, $template);
    }

    protected function upload_image($image_input_id, $image_name, $upload_path, $upload_thumb_path = '', $thumbs_with = 500, $thumbs_height = 500) {
        $image = '';
        if (!empty($image_name)) {
            $config['upload_path'] = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['file_name'] = $image_name;
            $config['max_size'] = '1200';
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload($image_input_id)) {
                $upload_data = $this->upload->data();
                $image = $upload_data['file_name'];

                $config_thumb['source_image'] = $upload_path . '/' . $image;
                $config_thumb['create_thumb'] = TRUE;
                $config_thumb['maintain_ratio'] = TRUE;
                $config_thumb['new_image'] = $upload_thumb_path;
                $config_thumb['width'] = $thumbs_with;
                $config_thumb['height'] = $thumbs_height;

                $this->load->library('image_lib', $config_thumb);

                $this->image_lib->resize();
            }
        }

        return $image;
    }

    protected function upload_file($upload_path = '', $file_name = '', $upload_thumb_path = '', $thumbs_with = 500, $thumbs_height = 500) {
        $config = $this->config_file($upload_path);

        $image = '';
        $file = $_FILES[$file_name];
        $count = count($file['name']);
        $image_list = array();
        $config_thumb = array();

        for ($i = 0; $i < $count; $i++) {

            $_FILES['userfile']['name'] = $file['name'][$i];
            $_FILES['userfile']['type'] = $file['type'][$i];
            $_FILES['userfile']['tmp_name'] = $file['tmp_name'][$i];
            $_FILES['userfile']['error'] = $file['error'][$i];
            $_FILES['userfile']['size'] = $file['size'][$i];

            $this->load->library('upload', $config);

            if ($this->upload->do_upload()) {
                $data = $this->upload->data();
                $image_list[] = $data['file_name'];
                $image = $data['file_name'];

                $this->load->library('image_lib');

                $config['image_library'] = 'gd2';
                $config_thumb['source_image'] = $upload_path . '/' . $image;
                $config_thumb['create_thumb'] = TRUE;
                $config_thumb['maintain_ratio'] = TRUE;
                $config_thumb['new_image'] = $upload_thumb_path;
                $config_thumb['width'] = $thumbs_with;
                $config_thumb['height'] = $thumbs_height;

                $this->image_lib->initialize($config_thumb);
                $this->image_lib->resize();
                $this->image_lib->clear();

                $this->image_lib->resize($image);
                
            }
        }
        return $image_list;
    }

    function config_file($upload_path = '') {
        $config = array();
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size'] = '1200';
        $config['encrypt_name'] = TRUE;
//        $config['max_width']     = '1028';
//        $config['max_height']    = '1028';

        return $config;
    }
}

class Public_Controller extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper('form');

        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $this->langAbbreviation = $this->uri->segment(1) ? $this->uri->segment(1) : 'vi';
        if($this->langAbbreviation == 'vi' || $this->langAbbreviation == 'en' || $this->langAbbreviation == ''){
            $this->session->set_userdata('langAbbreviation', $this->langAbbreviation);
        }

        if($this->session->userdata('langAbbreviation') == 'vi'){
            $langName = 'vietnamese';
            $this->config->set_item('vietnamese', $langName);
            $this->session->set_userdata("langAbbreviation",'vi');
            $this->lang->load('vietnamese_lang', 'vietnamese');
        }

        if($this->session->userdata('langAbbreviation') == 'en' || $this->session->userdata('langAbbreviation') == ''){
            $langName = 'english';
            $this->config->set_item('language', $langName);
            $this->session->set_userdata("langAbbreviation",'en');
            $this->lang->load('english_lang', 'english');
        }


    }

    protected function render($the_view = NULL, $template = 'master') {
        parent::render($the_view, $template);
    }
}
