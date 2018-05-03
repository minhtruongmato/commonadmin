<?php 

/**
* 
*/
class Product_category extends Admin_Controller{
	private $request_language_template = array(
        'title'
    );
    private $author_data = array();
    private $controller = '';

	function __construct(){
		parent::__construct();
		$this->load->model('product_category_model');
		$this->load->helper('common');
        $this->load->helper('file');

        $this->data['template'] = build_template();
        $this->data['request_language_template'] = $this->request_language_template;
        $this->controller = 'product_category';
        $this->data['controller'] = $this->controller;
		$this->author_data = handle_author_common_data();
	}

    public function index(){
        $this->render('admin/'. $this->controller .'/list_product_category_view');
    }

	public function create(){
		$this->load->helper('form');
        $this->load->library('form_validation');
        $this->render('admin/'. $this->controller .'/create_product_category_view');
        
    }

    public function detail($id){
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->render('admin/'. $this->controller .'/detail_product_category_view');
    }

    public function edit($id){
        $this->load->helper('form');
        $this->load->library('form_validation');

            $this->render('admin/'. $this->controller .'/edit_product_category_view');
        
        }


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
}