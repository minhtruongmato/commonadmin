<?php 
/**
* 
*/
class Dashboard extends Admin_Controller{
	
	function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->render('admin/dashboard_view');
	}
}