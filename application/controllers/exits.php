<?php
class Exits extends MY_Controller{
	function __construct(){
		parent::__construct();
		
		$auth_sign = $this->session->userdata('auth_sign');
		if($auth_sign==false){
			redirect('https://vnphones.com');
		}
	}
	
	public function index(){
			$this->session->unset_userdata($this->session->all_userdata());
			$this->session->unset_userdata('auth_sign');
			$this->session->unset_userdata('data_users');
			redirect(base_url('sign'));
	}
	
	
}
?>