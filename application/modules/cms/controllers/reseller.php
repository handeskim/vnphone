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
	public function load_daily(){
		$sql = "SELECT * FROM users WHERE role = 3";
		return $this->GlobalMD->query_global($sql);
	}
	public function add_reseller(){
		if($this->permisson == 1){
			$cmd = $this->input->post('cmd');
			if(isset($cmd)){
				if($cmd == "cmdAddReseller"){
					$reseller = $this->input->post('reseller');
					if(isset($reseller)){
						if(!empty($reseller)){
							$name_reseller = random_partner($reseller);
							$secret = core_encrypt($name_reseller);
							$params = array(
								'name_reseller' => $name_reseller,
								'id_user_reseller' => $reseller,
								'secret' => $secret,
								'apps_id' => $name_reseller,
								'score' => 30,
								'status' => 1,

							);
							$this->db->trans_start();
							$status = $this->db->insert('reseller', $params); 
							$this->db->trans_complete();
							if($status==true){
								redirect(base_url('cms/reseller'));
							}else{
								redirect(base_url('cms/reseller/add_reseller'));
							}
						}
					}
				}
			}
			$msg ='';
			$data = array(
				'msg' => $msg,
				'daily' => $this->load_daily(),
				'user_data' => $this->user_data,
				'title'=> 'Dashboard',
				'title_main' => 'Dashboard',
			);
			$this->parser->parse('default/header',$data);
			$this->parser->parse('default/sidebar',$data);
			$this->parser->parse('default/main',$data);
			$this->parser->parse('default/layout/main_add_reseller',$data);
			$this->parser->parse('default/footer',$data);
		}else{
			echo "Not enough authority to access";
		}
	}
	private function load_data_user(){
		if($this->permisson == 1){
		
		$xcrud = Xcrud::get_instance();
		$xcrud->table('reseller');
		$xcrud->unset_csv();
		$xcrud->unset_add();
		$xcrud->unset_remove();
		$xcrud->table_name('Danh sách reseller API');
		$xcrud->label('id_user_reseller','Mã Đại Lý');
		$xcrud->label('name_reseller','Tên đại lý');
		$xcrud->label('status','Trạng Thái');
		$xcrud->relation('status','conf_status','id','name');
		$xcrud->relation('id_user_reseller','users','id','clients_code' ,'role = 3');
		$xcrud->fields('name_reseller,score,status,secret,apps_id');
		$xcrud->validation_required('name_reseller');
		$xcrud->validation_required('score');
		$xcrud->validation_required('status');
		$xcrud->validation_required('secret');
		$xcrud->validation_required('apps_id');
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
		$this->parser->parse('default/layout/main_curd_reseller',$data);
		$this->parser->parse('default/footer',$data);
	}

	
}
?>