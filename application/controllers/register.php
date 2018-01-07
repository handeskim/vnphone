<?php
class Register extends MY_Controller{
	function __construct(){
		parent::__construct();
		//$this->load->library('rest');
		$this->load->model('global_model', 'GlobalMD');	
		$auth_sign = $this->session->userdata('auth_sign');
		if($auth_sign==true){
			redirect(base_url('apps'));
		}
	}
	
	public function index(){
		$msg = "";
		$cmd = $this->input->post('cmd');
		
		$data = array(
			'msg' => $msg,
			'title'=> 'Tools Convert Phone',
		);
		$this->parser->parse('default/register',$data);
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