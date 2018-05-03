<?php 

/**
* 
*/
class Menu extends Admin_Controller{
	
	private $request_language_template = array(
        'title'
    );
    private $author_data = array();
    private $controller = '';

	function __construct(){
		parent::__construct();
        $this->load->model('post_category_model');
        $this->load->model('post_model');
		$this->load->helper('common');
        $this->load->helper('file');

        $this->data['template'] = build_template();
        $this->data['request_language_template'] = $this->request_language_template;
        $this->controller = $this->uri->segment(2);

		$this->author_data = handle_author_common_data();
	}

	public function index(){
		$menus = $this->post_category_model->get_menu(0, 'asc');
        // $nav =$this->showCategories($menus, 0);
        $this->data['nav'] = $this;
		$this->data['menus'] = $menus;
		$this->render('admin/menu_view');
	}

	public function sort(){
        $params = array();
        parse_str($this->input->get('sort'), $params);
        $i = 1;
        foreach($params as $value){
            $this->post_category_model->update_sort($i, $value[0]);
            $i++;
        }
   }

    function showCategories($categories, $parent_id = 0, $char = ''){
        $cate_child = array();
        foreach ($categories as $key => $item){
            if ($item['parent_id'] == $parent_id){
                $cate_child[] = $item;
                unset($categories[$key]);
            }
        }
        // print_r($cate_child);die;
        if ($cate_child){
            echo '<ul>';
            foreach ($cate_child as $key => $item){
                echo '<li><a href="'. base_url('admin/menu/'. $item['slug']) .'">'.$item['title'];
                $this->showCategories($categories, $item['id'], $char.'|---');
                echo '</li>';
            }
            echo '</ul>';
        }
    }
}