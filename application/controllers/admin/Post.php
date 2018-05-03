<?php 

/**
* 
*/
class Post extends Admin_Controller{
	private $request_language_template = array(
        'title', 'description', 'content'
    );
    private $author_data = array();
    private $controller = '';

	function __construct(){
		parent::__construct();
		$this->load->model('post_model');
        $this->load->model('post_category_model');
		$this->load->helper('common');
        $this->load->helper('file');

        $this->data['template'] = build_template();
        $this->data['request_language_template'] = $this->request_language_template;
        $this->controller = 'post';
        $this->data['controller'] = $this->controller;

		$this->author_data = handle_author_common_data();
	}

    public function index(){
        $keywords = '';
        if($this->input->get('search')){
            $keywords = $this->input->get('search');
        }
        $total_rows  = $this->post_model->count_search('vi');
        if($keywords != ''){
            $total_rows  = $this->post_model->count_search('vi', $keywords);
        }

        $this->load->library('pagination');
        $config = array();
        $base_url = base_url('admin/'. $this->controller .'/index');
        $per_page = 10;
        $uri_segment = 4;
        foreach ($this->pagination_config($base_url, $total_rows, $per_page, $uri_segment) as $key => $value) {
            $config[$key] = $value;
        }
        $this->data['page'] = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $this->pagination->initialize($config);
        $this->data['page_links'] = $this->pagination->create_links();

        $result = $this->post_model->get_all_with_pagination_search('desc','vi' , $per_page, $this->data['page']);
        if($keywords != ''){
            $result = $this->post_model->get_all_with_pagination_search('desc','vi' , $per_page, $this->data['page'], $keywords);
        }
        foreach ($result as $key => $value) {
            $parent_title = $this->build_parent_title($value['post_category_id']);
            $result[$key]['parent_title'] = $parent_title;
        }
        // print_r($result);die;
        $this->data['result'] = $result;
        
        
        $this->render('admin/post_category/list_post_category_view');
    }

	public function create(){
		$this->load->helper('form');
        $this->load->library('form_validation');

        $post_category = $this->post_category_model->get_all_with_pagination_search('ASC');
        $post_category = build_array_for_dropdown($post_category);
        unset($post_category[0]);
        $this->data['post_category'] = $post_category;

        $this->form_validation->set_rules('title_vi', 'Tiêu đề', 'required');
        $this->form_validation->set_rules('title_en', 'Title', 'required');

        if ($this->form_validation->run() == FALSE) {
        	$this->render('admin/post_category/create_post_category_view');
        } else {
        	if($this->input->post()){
        		$check_upload = true;
                if ($_FILES['image_shared']['size'] > 1228800) {
                    $check_upload = false;
                }
                if($check_upload == true){
                	$slug = $this->input->post('slug_shared');
                    $unique_slug = $this->post_model->build_unique_slug($slug);
                    $image = $this->upload_image('image_shared', $_FILES['image_shared']['name'], 'assets/public/upload/'. $this->controller, 'assets/public/upload/'.$this->controller.'/thumb');

                    $shared_request = array(
                        'slug' => $unique_slug,
                        'image' => $image,
                        'post_category_id' => $this->input->post('parent_id_shared'),
                        'created_at' => $this->author_data['created_at'],
                        'created_by' => $this->author_data['created_by'],
                        'updated_at' => $this->author_data['updated_at'],
                        'updated_by' => $this->author_data['updated_by']
                    );
                    $this->db->trans_begin();

                    $insert = $this->post_model->common_insert($shared_request);
                    if($insert){
                        $requests = handle_multi_language_request('post_id', $insert, $this->request_language_template, $this->input->post(), $this->page_languages);
                        $this->post_model->insert_with_language($requests);
                    }

                    if ($this->db->trans_status() === false) {
                        $this->db->trans_rollback();
                        $this->load->libraries('session');
                        $this->session->set_flashdata('message_error', MESSAGE_CREATE_ERROR);
                        $this->render('admin/'. $this->controller .'/create_post_category_view');
                    } else {
                        $this->db->trans_commit();
                        $this->session->set_flashdata('message_success', MESSAGE_CREATE_SUCCESS);
                        redirect('admin/'. $this->controller, 'refresh');
                    }
                }else{
                    $this->session->set_flashdata('message_error',sprintf(MESSAGE_PHOTOS_ERROR, 1200));
                    redirect('admin/'. $this->controller);
                }
        	}
        }
        
	}

    public function detail($id){
        $this->load->helper('form');
        $this->load->library('form_validation');

        $detail = $this->post_model->get_by_id($id, array('title', 'description', 'content'));
        

        $detail = build_language($this->controller, $detail, array('title', 'description', 'content'), $this->page_languages);
        $parent_title = $this->build_parent_title($detail['post_category_id']);
        $detail['parent_title'] = $parent_title;

        $this->data['detail'] = $detail;
        
        

        $this->render('admin/post_category/detail_post_category_view');
    }

    public function edit($id){
        $this->load->helper('form');
        $this->load->library('form_validation');

        $detail = $this->post_model->get_by_id($id, array('title', 'description', 'content'));
        $detail = build_language($this->controller, $detail, array('title', 'description', 'content'), $this->page_languages);
        $category = $this->post_category_model->get_all_with_pagination_search('ASC');
        
        $category = build_array_for_dropdown($category);
        unset($category[0]);
        $this->data['category'] = $category;
        
        $this->data['detail'] = $detail;
        // print_r($category);die;

        $this->form_validation->set_rules('title_vi', 'Tiêu đề', 'required');
        $this->form_validation->set_rules('title_en', 'Title', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->render('admin/post_category/edit_post_category_view');
        } else {
            if($this->input->post()){
                $check_upload = true;
                if ($_FILES['image_shared']['size'] > 1228800) {
                    $check_upload = false;
                }
                if ($check_upload == true) {
                    $slug = $this->input->post('slug_shared');
                    $unique_slug = $this->post_model->build_unique_slug($slug, $id);
                    $image = $this->upload_image('image_shared', $_FILES['image_shared']['name'], 'assets/public/upload/'. $this->controller .'', 'assets/public/upload/'. $this->controller .'/thumb');
                    $shared_request = array(
                        'slug' => $unique_slug,
                        'post_category_id' => $this->input->post('parent_id_shared'),
                        'created_at' => $this->author_data['created_at'],
                        'created_by' => $this->author_data['created_by'],
                        'updated_at' => $this->author_data['updated_at'],
                        'updated_by' => $this->author_data['updated_by']
                    );
                    if($image){
                        $shared_request['image'] = $image;
                    }
                    $this->db->trans_begin();

                    $update = $this->post_model->common_update($id, $shared_request);
                    if($update){
                        $requests = handle_multi_language_request('post_id', $id, $this->request_language_template, $this->input->post(), $this->page_languages);
                        foreach ($requests as $key => $value){
                            $this->post_model->update_with_language($id, $requests[$key]['language'], $value);
                        }
                    }

                    if ($this->db->trans_status() === false) {
                        $this->db->trans_rollback();
                        $this->load->libraries('session');
                        $this->session->set_flashdata('message_error', MESSAGE_EDIT_ERROR);
                        $this->render('admin/'. $this->controller .'/edit/'.$id);
                    } else {
                        $this->db->trans_commit();
                        $this->session->set_flashdata('message_success', MESSAGE_EDIT_SUCCESS);
                        if($image != '' && $image != $detail['image'] && file_exists('assets/public/upload/'. $this->controller .'/'.$detail['image'])){
                            unlink('assets/public/upload/'. $this->controller .'/'.$detail['image']);
                        }
                        redirect('admin/'. $this->controller .'', 'refresh');
                    }
                }else{
                    $this->session->set_flashdata('message_error', sprintf(MESSAGE_PHOTOS_ERROR, 1200));
                    redirect('admin/'. $this->controller .'');
                }
            }
        }
    }


    /**
     * [build_parent_title description]
     * @param  [type] $parent_id [description]
     * @return [type]            [description]
     */
    protected function build_parent_title($parent_id){
        $sub = $this->post_category_model->get_by_id($parent_id, array('title'));

        if($parent_id != 0){
            $title = explode('|||', $sub['post_category_title']);
            $sub['title_en'] = $title[0];
            $sub['title_vi'] = $title[1];

            $title = $sub['title_vi'];
        }else{
            $title = 'Danh mục gốc';
        }
        return $title;
    }
}