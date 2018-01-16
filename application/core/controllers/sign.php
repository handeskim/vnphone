<?php
class Sign extends MY_Controller{
	function __construct(){
		parent::__construct();
		//$this->load->library('rest');
		$this->load->model('global_model', 'GlobalMD');	
		$auth_sign = $this->session->userdata('auth_sign');
		if($auth_sign==true){
			redirect(base_url('apps'));
		}
	}
	public function SignCheck($params_sign){
		$username = trim($params_sign["username"]);
		$password  = trim($params_sign["password"]);
		$sql_sign = "SELECT * FROM `users` WHERE `username` = '$username' AND `passwords` = '$password' AND `status` = 1";
		$result = $this->GlobalMD->query_global($sql_sign);
		if(!empty($result)){
			return $result;
		}else{
			return false;	
		}
	}
	public function index(){
		$msg = "";
		$cmd = $this->input->post('cmd');
		if(isset($cmd)){
			if($cmd=="cmdSign"){
				$username = $this->input->post('username');
				$password = $this->input->post('password');
				if($username==null || $username==''|| $password==null|| $password==''){
						$msg = $this->default_error();
				}else{
					$params_sign = array(
						'username' => $username,
						'password' => md5($password),
					);
					$results = $this->SignCheck($params_sign);
					if($results==false){
						$msg = $this->default_error_notfound();
					}else{
						$params_session = array(
							'auth_sign'=> true,
							'data_users'=> $results[0],
						);
						$this->session->set_userdata($params_session);
						$check = $this->session->userdata('auth_sign');
						
						if($check==true){
							redirect(base_url('apps'));
						}else{
							$msg = $this->default_error_session();
						}
					}
				}
			}
		}
		$data = array(
			'msg' => $msg,
			'title'=> 'Tools Convert Phone',
		);
		$this->parser->parse('default/sign',$data);
	}
	
	private function default_error_session(){
			$msg = '<div class="alert alert-danger alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<p style="font-size: 14px;"><i class="icon fa fa-ban"></i> Alert! Pl? Deletel Caching Return Sign!. </p>
				</div>';
			return $msg;
	}
	private function default_error_notfound(){
			$msg = '<div class="alert alert-danger alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<p style="font-size: 14px;"><i class="icon fa fa-ban"></i>Tài khoản hết hạn hoặc không đúng mật khẩu. Vui lòng liên hệ với Admin</p>
				</div>';
			return $msg;
	}
	private function default_error(){
			$msg = '<div class="alert alert-danger alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<p style="font-size: 14px;"><i class="icon fa fa-ban"></i>Vui lòng nhập user và password</p>
				</div>';
			return $msg;
	}
	
}
?>