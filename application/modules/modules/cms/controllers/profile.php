<?php
class Profile extends MY_Controller{
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
			$data_user = $this->user_data;
			$id_user = $data_user['id'];
				$xcrud = Xcrud::get_instance();
				$xcrud->table('users');
				$xcrud->where('id',$id_user);
				$xcrud->unset_csv();
				$xcrud->unset_remove();
				$xcrud->unset_add();
				$xcrud->unset_remove();
				$xcrud->unset_view();
				$xcrud->unset_csv();
				$xcrud->unset_limitlist();
				$xcrud->unset_numbers();
				$xcrud->unset_pagination();
				$xcrud->unset_print();
				$xcrud->unset_search();
				$xcrud->table_name('Thông Tin Tài Khoản Của Tôi');
				$xcrud->label('name','Họ Và Tên');
				$xcrud->label('username','Tên Đăng Nhập');
				$xcrud->label('score','Điểm');
				$xcrud->label('passwords','Mật khẩu');
				$xcrud->label('level','Loại sử dụng');
				$xcrud->label('role','Quyền hạn');
				$xcrud->label('status','Trạng Thái');
				$xcrud->validation_required('status')->validation_required('passwords',6)->validation_required('username')->validation_required('name');
				$xcrud->validation_required('role')->validation_required('level')->validation_required('status');
				$xcrud->relation('status','conf_status','id','name');
				$xcrud->relation('level','conf_score','id','name');
				$xcrud->relation('role','conf_role','id','name');
				$xcrud->columns('name,username,score,expired,role,level,status');
				$xcrud->change_type('passwords', 'password', 'md5', array('class'=>'xcrud-input form-control', 'maxlength'=>10,'placeholder'=>'Nhập mật khẩu'));
				$xcrud->fields('name,passwords');
				$response = $xcrud->render('edit', $id_user); 
			return $response;
	}
	public function index(){
		
		$msg ='';
		$data = array(
			'msg' => $msg,
			'content' => $this->load_data_user(),
			'user_data' => $this->user_data,
			'title'=> 'Apps Convert Phone',
			'title_main' => 'Tài Khoản của tôi',
		);
		$this->parser->parse('default/header',$data);
		$this->parser->parse('default/sidebar',$data);
		$this->parser->parse('default/main',$data);
		$this->parser->parse('default/layout/main_curd_account',$data);
		$this->parser->parse('default/footer',$data);
	}

	
}
?>