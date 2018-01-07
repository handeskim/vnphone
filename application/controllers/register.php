<?php
class Register extends MY_Controller{
	function __construct(){
		parent::__construct();
		$this->load->model('global_model', 'GlobalMD');	
		$auth_sign = $this->session->userdata('auth_sign');
		if($auth_sign==true){
			redirect(base_url('apps'));
		}
	}
	
	public function index(){
		$msg = "";
		$cmd = $this->input->post('cmd');
		if(isset($cmd)){
			if($cmd=="cmdRegister"){
				$email = $this->input->post('email');
				$password = trim($this->input->post('password'));
				$password_scure = trim($this->input->post('password_scure'));
				if($this->validate_email($email) == true){
					if(!empty($password)){
						if(!empty($password_scure)){
							if(md5($password) == md5($password_scure)){
								if($this->checkEmailUsername($email)==false){
									$params = array(
										'name' => $email,
										'username' => $email,
										'passwords' => md5($password),
										'score' => 50,
										'role' => 2,
										'status' => 1,
										'level' => 1,
										'reseller' => 7,
									);
									$InstallUser = $this->AddClients($params);
									if(isset($InstallUser)==true){
										$params_sign = array(
											'username' => $email,
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
										$msg = $this->tmp_default_success("đăng ký thành công");
									}else{
										$msg = $this->tmp_default_error("Vui lòng thử lại");
									}	
								}else{
									$msg = $this->tmp_default_error("Tên đăng nhập đã tồn tại");
								}
							}else{
								$msg = $this->tmp_default_error("vui lòng kiểm tra lại mật khẩu không giống nhau");
							}
						}else{
							$msg = $this->tmp_default_error("vui lòng kiểm tra lại xác nhận password");
						}
					}else{
						$msg = $this->tmp_default_error("vui lòng kiểm tra lại password");
					}
				}else{
					$msg = $this->tmp_default_error("vui lòng kiểm tra lại địa chỉ email");
				}
			}
		}
		$data = array(
			'msg' => $msg,
			'title'=> 'Tools Convert Phone',
		);
		$this->parser->parse('default/register',$data);
	}
	
	
	private function checkEmailUsername($email){
		if(!empty($email)){
			$sql = "SELECT username FROM users WHERE username = '$email'";
			$response = $this->GlobalMD->query_global($sql);
			if(!empty($response)){
				return true;
			}else{
				return false;
			}
		}else{
			return true;
		}
	}
	private function AddClients($params){
		$this->db->trans_start();
		$this->db->insert('users',$params);
		$this->db->trans_complete();
		return $this->db->insert_id();
	}
	private function SignCheck($params_sign){
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
	private function validate_email($email){
		$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
		if (preg_match($regex, $email)) {
			return true;
		} else { 
			return false;
		}  
	}
	private function tmp_default_error($msg){
			$msg = '<div class="alert alert-danger alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<p style="font-size: 14px;"><i class="icon fa fa-ban"></i>'.$msg.'</p>
				</div>';
			return $msg;
	}
	private function tmp_default_success($msg){
			$msg = '<div class="alert alert-success  alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<p style="font-size: 14px;"><i class="icon fa fa-ban"></i>'.$msg.'</p>
				</div>';
			return $msg;
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