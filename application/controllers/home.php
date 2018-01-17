<?php
class Home extends MY_Controller{
	function __construct(){
		parent::__construct();
	}
	
	public function index(){
			redirect(base_url('home'));
	}
	
	
}
?>