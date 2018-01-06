<?php
class Dashboard extends MY_Controller{
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
		$xcrud->table('users');
		$xcrud->where('role !=','0');
		$xcrud->unset_csv();
		$xcrud->table_name('Danh sách tài khoản');
		$xcrud->label('name','Họ Và Tên');
		$xcrud->label('username','Tên Đăng Nhập');
		$xcrud->label('score','Điểm');
		$xcrud->label('passwords','Mật khẩu');
		$xcrud->label('level','Loại sử dụng');
		$xcrud->label('role','Quyền hạn');
		$xcrud->label('status','Trạng Thái');
		$xcrud->validation_required('status')->validation_required('passwords',8)->validation_required('username')->validation_required('name');
		$xcrud->validation_required('role')->validation_required('level')->validation_required('status');
		$xcrud->relation('status','conf_status','id','name');
		$xcrud->relation('level','conf_score','id','name');
		$xcrud->relation('role','conf_role','id','name');
		$xcrud->columns('name,username,score,expired,role,level,status');
		$xcrud->fields('name,username,passwords,score,expired,role,level,status');
		$xcrud->change_type('passwords', 'password', 'md5', array('class'=>'xcrud-input form-control', 'maxlength'=>10,'placeholder'=>'Nhập mật khẩu'));
		
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