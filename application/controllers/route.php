<?php
class Route extends MY_Controller{
	function __construct(){
		parent::__construct();
		$this->load->library('rest');
		$this->load->model('global_model', 'GlobalMD');	
		$this->login = $this->session->userdata('auth_sign');
		if($this->login){
			$this->user_data = $this->session->userdata('data_users');
			$this->permisson = $this->user_data['role'];
			$id_clients = $this->user_data['id'];
			$this->uid_clients = $this->user_data['id'];
			$this->level = $this->user_data['level'];
			$pid = $this->session->userdata('session_id');
			$this->key_pid = $this->uid_clients .date("Ymd",time()).'_'.$pid;
			if($this->level == 2){
				$param_Expired = array('uid_private' => $id_clients,);
				$response = $this->rest->get('apps/api/Expired',$param_Expired);
				if((int)$response->results == 0){
					redirect(base_url('exits'));
				}	
			}
			$this->permisson = $this->user_data['role'];
		}else{
			redirect(base_url('sign'));
		}
	
		
	}
	

	public function index(){
		$error = "";
		$msg ='';
		$data = array(
			'key_pid' => $this->key_pid,
			'error' => $error,
			'msg' => $msg,
			'user_data' => $this->user_data,
			'title'=> 'Tools',
			'title_main' => 'Tools',
		);
		
		
		$this->parser->parse('default/header',$data);
		$this->parser->parse('default/sidebar',$data);
		$this->parser->parse('default/main',$data);
		$this->parser->parse('default/main_error',$data);
		$this->parser->parse('default/footer',$data);
	}
	
	

	
	
}
?>