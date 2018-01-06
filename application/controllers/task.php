<?php
class Sign extends MY_Controller{
	function __construct(){
		parent::__construct();
		$this->load->model('global_model', 'GlobalMD');	
	}
	
	public function expired(){
		
	}
	
	
}
?>