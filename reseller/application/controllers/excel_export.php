<?php
class Excel_export extends MY_Controller{
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
		$filename = random_name_text();
		$this->load->library('excel');
		try { 
			$Array_name_field = array('phone');
			$this->excel->setActiveSheetIndex(0);
			try { 
				$data_field = json_decode($this->redis->get('response_complete'.$this->key_pid));
				$dataexport = array();
				$dataexport[] = $Array_name_field;
				$x = 0;
				$filed = array();
				foreach($data_field as $value){
					foreach($Array_name_field as $fields)
					{
					   $filed[$x][] = $value[0]->$fields;
					}
					$dataexport[] = $filed[$x];
					$x++;
				}
				$this->excel->getActiveSheet()->fromArray($dataexport,NULL, 'A1');
				$filename= $name_save.date("Y-m-d H-i-s",time()).'.xlsx';
				header('Content-Type:  application/vnd.ms-excel'); 
				header('Content-Disposition: attachment;filename="'.$filename.'"'); 
				$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
				ob_end_clean();
				$objWriter->save('php://output');
			}catch (Exception $e) {
				echo "No query data exists please turn back and try again";
			}
		}catch (Exception $e) {
			echo "No data exist Please come out and try again";
		}
			
		
		
	}
	
}
?>