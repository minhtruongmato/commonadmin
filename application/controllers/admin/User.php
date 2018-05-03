<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
    }

    public function index() {

    }

    public function login() {
        $this->data['page_title'] = 'Login';
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('identity', 'Identity', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');
            $this->form_validation->set_rules('remember', 'Remember me', 'integer');
            if ($this->form_validation->run() === TRUE) {
                $remember = (bool) $this->input->post('remember');
                if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                    redirect('admin/dashboard', 'refresh');
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    redirect('admin/user/login', 'refresh');
                }
            }
        }
        $this->load->helper('form');
        $this->render('admin/login_view', 'admin_master');
    }

    public function logout() {
        if(!$this->ion_auth->logout()){
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(HTTP_BAD_REQUEST)
                ->set_output(json_encode(array('status' => HTTP_BAD_REQUEST)));
        }
        $this->output
            ->set_content_type('application/json')
            ->set_status_header(HTTP_SUCCESS)
            ->set_output(json_encode(array('status' => HTTP_SUCCESS)));
    }

}
