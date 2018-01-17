<?php
class History extends MY_Controller{
	function __construct(){
		parent::__construct();
		$this->load->library('rest');
		$this->load->model('global_model', 'GlobalMD');	
		$this->login = $this->session->userdata('auth_sign');
		
		if($this->login){
			$this->user_data = $this->session->userdata('data_users');
			$this->permisson = $this->user_data['role'];
			$id_clients = $this->user_data['id'];
			$this->uid = $this->user_data['id'];
			$this->level = $this->user_data['level'];
			if($this->level == 2){
				$param_Expired = array('uid_private' => $id_clients,);
				$response = $this->rest->get('apps/api/Expired',$param_Expired);
				if((int)$response->results == 0){
					redirect(base_url('exits'));
				}	
			}
		}else{
			redirect(base_url('sign'));
		}
		
	}

	
	private function historys(){
		$xcrud = Xcrud::get_instance();
		$xcrud->table('history');
		$xcrud->table_name('Lịch sử Convert UID');
		$xcrud->where('uid',$this->uid);
		$xcrud->unset_csv();
		$xcrud->unset_add();
		$xcrud->unset_print();
		$xcrud->unset_edit();
		$xcrud->unset_view();
		$xcrud->columns('total_uid,total_transfer,percent_transfer,uid_found,times');
		$xcrud->button('{history_file}','download file','fa fa-cloud-download','',array('target'=>'_blank'));
		$response = $xcrud->render();
		return $response;
	}

	public function index(){
		
		$msg ='';
		$data = array(
			'msg' => $msg,
			'content' => $this->historys(),
			'user_data' => $this->user_data,
			'title'=> 'History Convert',
			'title_main' => 'History Convert',
		);
		$this->parser->parse('default/header',$data);
		$this->parser->parse('default/sidebar',$data);
		$this->parser->parse('default/main',$data);
		$this->parser->parse('default/layout/main_curd_account',$data);
		$this->parser->parse('default/footer',$data);
	}

	
}
?>