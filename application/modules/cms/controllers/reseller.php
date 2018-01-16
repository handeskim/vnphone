<?php
class Reseller extends MY_Controller{
	function __construct(){
		parent::__construct();
		$this->load->library('rest');
		$this->load->model('global_model', 'GlobalMD');	
		$this->login = $this->session->userdata('auth_sign');
		
		if($this->login){
			$this->user_data = $this->session->userdata('data_users');
			$this->permisson = $this->user_data['role'];
			$id_clients = $this->user_data['id'];
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
	private function load_data_user(){
		if($this->permisson == 1){
		
		$xcrud = Xcrud::get_instance();
		$xcrud->table('reseller');
		$xcrud->unset_csv();
		$xcrud->unset_remove();
		$xcrud->table_name('Danh sách reseller API');
		$xcrud->label('id_user_reseller','Mã Đại Lý');
		$xcrud->label('name_reseller','Tên đại lý');
		$xcrud->label('status','Trạng Thái');
		
		$xcrud->relation('status','conf_status','id','name');
		$xcrud->relation('id_user_reseller','users','id','clients_code' ,'role = 3');
		// $xcrud->relation('reseller','reseller','id','name_reseller');
		// $xcrud->columns('name,username,score,expired,role,level,status,reseller');
		$xcrud->fields('name_reseller,id_user_reseller,score,status');
		$xcrud->before_insert('update_reseller'); // manualy load
		// $xcrud->change_type('passwords', 'password', 'md5', array('class'=>'xcrud-input form-control', 'maxlength'=>10,'placeholder'=>'Nhập mật khẩu'));
		
		$response = $xcrud->render();
		return $response;
		}else{
			return $this->load_temp_client();
		}
	}
	private function load_temp_client(){
		$temp = '';
		
			$temp .='<div class="col-md-12"><ul>';
			// $temp .='<li> Ngày Hết Hạn: '.$this->user_data['expired'].'</li>';
			// $temp .='<li> Điểm hoạt động còn : '.$this->user_data['score'].'</li>';
			$temp .='</ul></div>';
		
		return $temp;
	}
	public function index(){
		
		$msg ='';
		$data = array(
			'msg' => $msg,
			'content' => $this->load_data_user(),
			'user_data' => $this->user_data,
			'title'=> 'Dashboard',
			'title_main' => 'Dashboard',
		);
		$this->parser->parse('default/header',$data);
		$this->parser->parse('default/sidebar',$data);
		$this->parser->parse('default/main',$data);
		$this->parser->parse('default/layout/main_curd_account',$data);
		$this->parser->parse('default/footer',$data);
	}

	
}
?>