<?php
class Text_export extends MY_Controller{
	function __construct(){
		parent::__construct();
		$this->load->library('rest');
		$this->load->model('global_model', 'GlobalMD');	
		$this->login = $this->session->userdata('auth_sign');
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
		}else{
			// $param_Expired = array('uid_private' => $id_clients,);
			// $response = $this->rest->get('apps/api/Expired',$param_Expired);
			// if((int)$response->results == 0){
				// redirect(base_url('exits'));
			// }
		}
		$this->permisson = $this->user_data['role'];
		if(isset($this->login)==false){
			redirect(base_url('sign'));
		}
	}
	
	public function index(){
		$name_file = rand().date("Y-m-d H-i-s",time());
		$txtfile = $_SERVER["DOCUMENT_ROOT"].'/download/'.$name_file.'.txt';
		$handle = fopen($txtfile, 'w') or die('Cannot open file:  '.$txtfile); // check the file is readable
		$textContent = '';
		$dump_raw = json_decode($this->redis->get('response_complete'.$this->key_pid));
		foreach($dump_raw as $value){
			// $textContent .=  $value[0]->uid.' | +'.$value[0]->phone ."\r\n";
			$textContent .=  '+'.$value[0]->phone ."\r\n";
		}

		fwrite($handle, $textContent); // write content
		fclose($handle); // close the text file
		$downLink = base_url().'download/'.$name_file.'.txt'; 
		return $this-> dowload($downLink);
		
	}
	private function dowload($file){
		
		header("Cache-Control: public");     
		header("Content-Description: File Transfer");     
		header("Content-Disposition: attachment; filename=".$file."");     
		header("Content-Transfer-Encoding: binary");     
		header("Content-Type: binary/octet-stream");     
		readfile($file);
	}
	
}
?>