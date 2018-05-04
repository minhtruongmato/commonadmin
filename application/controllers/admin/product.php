<?php 

/**
* 
*/
class Product extends Admin_Controller{
	private $request_language_template = array(
        'title', 'description', 'content'
    );
    private $author_data = array();

	function __construct(){
		parent::__construct();
		$this->load->model('product_model');
        $this->load->model('product_category_model');
		$this->load->helper('common');
        $this->load->helper('file');

        $this->data['template'] = build_template();
        $this->data['request_language_template'] = $this->request_language_template;
        $this->data['controller'] = $this->product_model->table;
		$this->author_data = handle_author_common_data();
	}

    public function index(){
        $this->data['keyword'] = '';
        if($this->input->get('search')){
            $this->data['keyword'] = $this->input->get('search');
        }
        $this->load->library('pagination');
        $per_page = 10;
        $total_rows  = $this->product_category_model->count_search('vi', $this->data['keyword']);
        $config = $this->pagination_config(base_url('admin/'.$this->data['controller'].'/index'), $total_rows, $per_page, 4);
        $this->data['page'] = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $this->pagination->initialize($config);
        $this->data['page_links'] = $this->pagination->create_links();
        $this->data['result'] = $this->product_model->get_all_with_pagination_search('desc','vi' , $per_page, $this->data['page'], $this->data['keyword']);
        foreach ($this->data['result'] as $key => $value) {
            $parent_title = $this->build_parent_title($value['product_category_id']);
            $this->data['result'][$key]['parent_title'] = $parent_title;
        }
        $this->render('admin/product_category/list_product_category_view');
    }

	public function create(){
		$this->load->helper('form');
        $product_category = $this->product_category_model->get_all_with_pagination_search('ASC');
        $this->data['product_category'] = build_array_for_dropdown($product_category);
        unset( $this->data['product_category'][0]);
        if($this->input->post()){
            $this->load->library('form_validation');
            $this->form_validation->set_rules('title_vi', 'Tiêu đề', 'required');
            $this->form_validation->set_rules('title_en', 'Title', 'required');
            $this->form_validation->set_rules('description_vi', 'Mô tả', 'required');
            $this->form_validation->set_rules('description_en', 'Description', 'required');
            $this->form_validation->set_rules('content_vi', 'Nội dung', 'required');
            $this->form_validation->set_rules('content_en', 'Content', 'required');
            if($this->form_validation->run() == TRUE){
                if(!empty($_FILES['image_shared']['name'])){
                    $this->check_img($_FILES['image_shared']['name'], $_FILES['image_shared']['size']);
                }
                $slug = $this->input->post('slug_shared');
                
                $unique_slug = $this->product_model->build_unique_slug($slug);
                if(!file_exists("assets/upload/".$this->data['controller']."/".$unique_slug) && !empty($_FILES['image_shared']['name'])){
                    mkdir("assets/upload/".$this->data['controller']."/".$unique_slug, 0755);
                    mkdir("assets/upload/".$this->data['controller']."/".$unique_slug.'/thumb', 0755);
                }
                if(!empty($_FILES['image_shared']['name'])){
                    $image = $this->upload_image('image_shared', $_FILES['image_shared']['name'], 'assets/upload/'.$this->data['controller']."/".$unique_slug, 'assets/upload/'.$this->data['controller']."/".$unique_slug .'/thumb');
                }
                $shared_request = array(
                    'slug' => $unique_slug,
                    'product_category_id' => $this->input->post('parent_id_shared')
                );
                if(isset($image)){
                    $shared_request['image'] = $image;
                }
                $this->db->trans_begin();
                $insert = $this->product_model->common_insert(array_merge($shared_request,$this->author_data));
                if($insert){
                    $requests = handle_multi_language_request('product_id', $insert, $this->request_language_template, $this->input->post(), $this->page_languages);
                    $this->product_model->insert_with_language($requests);
                }
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('message_error', MESSAGE_CREATE_ERROR);
                    $this->render('admin/product_category/create_product_category_view');
                } else {
                    $this->db->trans_commit();
                    $this->session->set_flashdata('message_success', MESSAGE_CREATE_SUCCESS);
                    redirect('admin/'. $this->data['controller'] .'', 'refresh');
                }
            }
        }
        $this->render('admin/product_category/create_product_category_view');
	}

    public function detail($id){
        $this->load->helper('form');
        $this->load->library('form_validation');

        $detail = $this->product_model->get_by_id($id, array('title', 'description', 'content'));
        

        $detail = build_language($this->data['controller'], $detail, array('title', 'description', 'content'), $this->page_languages);
        $parent_title = $this->build_parent_title($detail['product_category_id']);
        $detail['parent_title'] = $parent_title;

        $this->data['detail'] = $detail;
        
        

        $this->render('admin/product_category/detail_product_category_view');
    }
    function remove($id){
        if($id &&  is_numeric($id) && ($id > 0)){
            $product_category = $this->product_model->get_by_id($id, array('title'));
            if($this->product_model->findcolumn(array('id' => $id,'is_deleted' => 0)) == 0){
                $this->session->set_flashdata('message_error',MESSAGE_ISSET_ERROR);
                redirect('admin/product', 'refresh');
            }
            if($product_category){
                $data = array('is_deleted' => 1);
                $update = $this->product_model->common_update($id, $data);
                if($update){
                    $this->session->set_flashdata('message_success',MESSAGE_REMOVE_SUCCESS);
                    return redirect('admin/'.$this->data['controller'],'refresh');
                }
                $this->session->set_flashdata('message_error',MESSAGE_REMOVE_ERROR);
                return redirect('admin/'.$this->data['controller'],'refresh');
            }
        }
        $this->session->set_flashdata('message_error',MESSAGE_ID_ERROR);
        return redirect('admin/'.$this->data['controller'],'refresh');
    }

    public function edit($id){
        if($id &&  is_numeric($id) && ($id > 0)){
            $this->load->helper('form');
            $this->data['category'] = build_array_for_dropdown($this->product_category_model->get_all_with_pagination_search(),$id);
            unset($this->data['category'][0]);
            if($this->product_model->findcolumn(array('id' => $id,'is_deleted' => 0)) == 0){
                $this->session->set_flashdata('message_error',MESSAGE_ISSET_ERROR);
                redirect('admin/product', 'refresh');
            }
            $detail = $this->product_model->get_by_id($id, array('title','description','content'));
            $this->data['detail'] = build_language($this->data['controller'], $detail, array('title','description','content'), $this->page_languages);
            if($this->input->post()){
                $this->load->library('form_validation');
                $this->form_validation->set_rules('title_vi', 'Tiêu đề', 'required');
                $this->form_validation->set_rules('title_en', 'Title', 'required');
                $this->form_validation->set_rules('description_vi', 'Mô tả', 'required');
                $this->form_validation->set_rules('description_en', 'Description', 'required');
                $this->form_validation->set_rules('content_vi', 'Nội dung', 'required');
                $this->form_validation->set_rules('content_en', 'Content', 'required');
                if($this->form_validation->run() == TRUE){
                    if(!empty($_FILES['image_shared']['name'])){
                        $this->check_img($_FILES['image_shared']['name'], $_FILES['image_shared']['size']);
                    }
                    $unique_slug = $this->data['detail']['slug'];
                    if($unique_slug !== $this->input->post('slug_shared')){
                        $unique_slug = $this->product_model->build_unique_slug($this->input->post('slug_shared'));
                    }
                    if(!file_exists("assets/upload/".$this->data['controller']."/".$unique_slug) && !empty($_FILES['image_shared']['name'])){
                        mkdir("assets/upload/".$this->data['controller']."/".$unique_slug, 0755);
                        mkdir("assets/upload/".$this->data['controller']."/".$unique_slug.'/thumb', 0755);
                    }
                    if(!empty($_FILES['image_shared']['name'])){
                        $image = $this->upload_image('image_shared', $_FILES['image_shared']['name'], 'assets/upload/'.$this->data['controller']."/".$unique_slug, 'assets/upload/'.$this->data['controller']."/".$unique_slug .'/thumb');
                    }
                    $shared_request = array(
                        'product_category_id' => $this->input->post('parent_id_shared')
                    );
                    if($unique_slug != $this->data['detail']['slug']){
                        $shared_request['slug'] = $unique_slug;
                    }
                    if(isset($image)){
                        $shared_request['image'] = $image;
                    }
                    $this->db->trans_begin();
                    $update = $this->product_model->common_update($id,array_merge($shared_request,$this->author_data));
                    if($update){
                        $requests = handle_multi_language_request('product_id', $id, $this->request_language_template, $this->input->post(), $this->page_languages);
                        foreach ($requests as $key => $value) {
                            $this->product_model->update_with_language($id, $requests[$key]['language'],$value);
                        }
                    }
                    if ($this->db->trans_status() === false) {
                        $this->db->trans_rollback();
                        $this->session->set_flashdata('message_error', MESSAGE_EDIT_ERROR);
                        $this->render('admin/'. $this->data['controller'] .'/edit_product_category_view');
                    } else {
                        $this->db->trans_commit();
                        $this->session->set_flashdata('message_success', MESSAGE_EDIT_SUCCESS);
                        if(isset($image) && !empty($this->data['detail']['image'])){
                            if(file_exists('assets/upload/'. $this->data['controller'] .'/'.$this->data['detail']['image']))
                            unlink('assets/upload/'. $this->data['controller'] .'/'.$this->data['detail']['image']);
                        }
                        redirect('admin/'. $this->data['controller'] .'', 'refresh');
                    }
                }
            }
        }else{
            $this->session->set_flashdata('message_error',MESSAGE_ID_ERROR);
            redirect('admin/'. $this->data['controller'] .'', 'refresh');
        }
        $this->render('admin/product_category/edit_product_category_view');
    }


    /**
     * [build_parent_title description]
     * @param  [type] $parent_id [description]
     * @return [type]            [description]
     */
    protected function build_parent_title($parent_id){
        $sub = $this->product_category_model->get_by_id($parent_id, array('title'));

        if($parent_id != 0){
            $title = explode('|||', $sub['product_category_title']);
            $sub['title_en'] = $title[0];
            $sub['title_vi'] = $title[1];

            $title = $sub['title_vi'];
        }else{
            $title = 'Danh mục gốc';
        }
        return $title;
    }
    protected function check_img($filename, $filesize){
        $map = strripos($filename, '.')+1;
        $fileextension = substr($filename, $map,(strlen($filename)-$map));
        if(!($fileextension == 'jpg' || $fileextension == 'jpeg' || $fileextension == 'png' || $fileextension == 'gif')){
            $this->session->set_flashdata('message_error', MESSAGE_FILE_EXTENSION_ERROR);
            redirect('admin/'.$this->data['controller']);
        }
        if ($filesize > 1228800) {
            $this->session->set_flashdata('message_error', sprintf(MESSAGE_PHOTOS_ERROR, 1200));
            redirect('admin/'.$this->data['controller']);
        }
    }
}