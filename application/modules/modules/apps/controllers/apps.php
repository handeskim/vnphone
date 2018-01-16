<?php
class Apps extends MY_Controller{
	function __construct(){
		parent::__construct();
		$this->load->library('rest');
		$this->load->model('global_model', 'GlobalMD');	
		$this->login = $this->session->userdata('auth_sign');
		$config =  array('server' => base_url(),);
		$this->rest->initialize($config);
		if(isset($this->login)){
			$this->user_data = $this->session->userdata('data_users');
			$this->permisson = $this->user_data['role'];
			$id_clients = $this->user_data['id'];
			$this->uid_clients = $this->user_data['id'];
			$this->level = $this->user_data['level'];
			$this->expired = $this->user_data['expired'];
			$this->score = $this->user_data['score'];
			$pid = $this->session->userdata('session_id');
			$this->key_pid = $this->uid_clients .date("Ymd",time()).'_'.$pid;
			if($this->level == 2){
				$param_Expired = array('uid_private' => $id_clients,);
				$response = $this->rest->get('apps/api/Expired',$param_Expired);
				if((int)$response->results == 0){
					redirect(base_url('exits'));
				}	
			}
			$this->permisson = $this->user_data['role'];
		}else{
			redirect(base_url('sign'));
		}
	
		
	}
	

	public function index(){
		$paramsx = array(
			'uid_clients' => $this->uid_clients,
			'level' => $this->level,
			'expired' => $this->expired,
			'score' => $this->score,
		);
		$response = $this->rest->get('apps/api/ClientScore',$paramsx);
		// $response = $this->rest->get('apps/api/ClientScore');
		// $checkPoint = $response->results;
		// var_dump($response);
		// var_dump($checkPoint);
		// if((int)$this->level == 1){
			// if($checkPoint <= 0 ){
				// $Client = $this->session->userdata('data_users');
				// var_dump($Client);
				// echo "sao the nhi";
				// redirect(base_url('route'));
			// }
		// }
		$error = "";
		$msg ='';
		$params = array(
			'total_uid' => '0',
			'total_convert' => '0',
			'Percent_convert' => 0,
			'udi_kotimthay' => 0,
		);
		//	$recaptcha = $this->input->post('g-recaptcha-response');
		//$response_capchar = $this->recaptcha->verifyResponse($recaptcha);
		//	if (isset($response_capchar['success']) and $response_capchar['success'] === true) {
		$cmd = $this->input->post('cmd');
		if($cmd == 'c1'){
			$ArrayConvert = $this->FileUpload();
			if(!empty($ArrayConvert)){
				$x = count($ArrayConvert);
				if($x > 100000){
					$msg = $this->limit_request_http();
				}else{
					$cached = $this->redis->get($this->key_pid);
					if(!empty($cached)){
						$msg = $this->cached_request();
					}else{
						$params = $this->running_convert($ArrayConvert);
					}
				}
			}else{
				$listtext = $this->input->post('ListUidTextarea');
				if(!empty($listtext)){
					if(strlen($listtext) > 0){
						$ArrayConvert = $this->convert_string_to_array_textarea($listtext);
						$x = count($ArrayConvert);
						if($x > 10000){
							$msg = $this->limit_request_http();
						}else{
							$cached = $this->redis->get($this->key_pid);
							if(!empty($cached)){
								$msg = $this->cached_request();
							}else{
								$params = $this->running_convert($ArrayConvert);
							}
						}
					}
				}
			}
			
		}
		$data = array(
			'key_pid' => $this->key_pid,
			'error' => $error,
			'total_uid' => $params['total_uid'],
			'total_convert' => $params['total_convert'],
			'Percent_convert' => $params['Percent_convert'],
			'msg' => $msg,
			'user_data' => $this->user_data,
			'title'=> 'Tools',
			'title_main' => 'Tools',
			'udi_kotimthay' =>$params['udi_kotimthay'],
		);
		
		$this->parser->parse('default/header',$data);
		$this->parser->parse('default/sidebar',$data);
		$this->parser->parse('default/main',$data);
		$this->parser->parse('main_user',$data);
		$this->parser->parse('default/footer',$data);
	}
	private function adddotstring($strNum) {
 
        $len = strlen($strNum);
        $counter = 3;
        $result = "";
        while ($len - $counter >= 0)
        {
            $con = substr($strNum, $len - $counter , 3);
            $result = '.'.$con.$result;
            $counter+= 3;
        }
        $con = substr($strNum, 0 , 3 - ($counter - $len) );
        $result = $con.$result;
        if(substr($result,0,1)=='.'){
            $result=substr($result,1,$len+1);   
        }
        return $result;
	}
	private function running_convert($ArrayConvert){
		$total_convert ='0';
		$Percent_convert = 0;
		$kotimthay ='0';
		$total_uid = count($ArrayConvert);
		$this->redis->set($this->key_pid, json_encode($ArrayConvert));
		//////////////////////////////////////////////////////////////////////////
		$params = json_decode($this->redis->get($this->key_pid));
		$responseQueryConvert = $this->GlobalMD->convert_uid(json_decode($this->redis->get($this->key_pid)));
		$this->redis->set('response_'.$this->key_pid, json_encode($responseQueryConvert));
		/////////////////////////////////////////////////////////////////////////
		$ResultsConvert = $this->ResponseConvertUID();
		$this->PaymentConvert($ResultsConvert);
		/////////////////////////////////////////////////////////////////////////
		$ResultsConvertCompleteUID = $this->ResponseConvertCompleteUID();
		$total_convertComplete = count($ResultsConvertCompleteUID);
		$total_convert_raw = count($ResultsConvert);
		$Percent = $total_convert_raw/$total_uid;
		$total_convert = $total_convertComplete;
		if(!empty($Percent)){
			if($Percent > 0){
				$Percent_convert = round(($Percent*100),1);
			}
		}
		$kotimthay = $total_uid-$total_convert;
		$response = array(
			'total_uid' =>number_format($total_uid),
			'total_convert' =>number_format($total_convert),
			'Percent_convert' =>$Percent_convert,
			'udi_kotimthay' =>number_format($kotimthay)

		);
		return $response;
	}
	private function ConvertArrayToshiff($array,$keys){
		$arrayNew = array();
		for($x=0; $x < $keys; $x++){
			$arrayNew[] = $array[$x];
		}
		return $arrayNew;
	}
	
	private function PaymentConvert($array){
		$user_data = $this->session->userdata('data_users');
		$uid = $user_data['id'];
		$level = $user_data['level'];
		if($level==1){
			$sql = "SELECT `score` FROM `users` WHERE id = '$uid' and `score` >= 1 LIMIT 1";
			$query = $this->db->query($sql);
			$result = $query->result_array();
			if(!empty($result)){
					$score_old = (int)$result[0]['score'];
					$score_new = count($array);
					if($score_new <= $score_old){
						$score_update = $score_old - $score_new;
						$this->GlobalMD->UpdateScroreClients($score_update);
						$this->redis->set('response_complete'.$this->key_pid, json_encode($array));
					}
					if($score_new > $score_old){
						$score_transfer = $score_new - $score_old;
						$keys = $score_new -
						$score_update = 0;
						$this->GlobalMD->UpdateScroreClients($score_update);
						$arrayNewComplete = $this->ConvertArrayToshiff($array,$score_old);
						$this->redis->set('response_complete'.$this->key_pid, json_encode($arrayNewComplete));
					}
					
			}else{
				return false;
			}
		}else{
			$this->redis->set('response_complete'.$this->key_pid, json_encode($array));
		
		}
	}
	private function ResponseConvertCompleteUID(){
		$dump_raw = json_decode($this->redis->get('response_complete'.$this->key_pid));
		if(!empty($dump_raw)){
			return $dump_raw;
		}else{
			return 0;
		}
	}
	private function ResponseConvertUID(){
		$dump_raw = json_decode($this->redis->get('response_'.$this->key_pid));
		if(!empty($dump_raw)){
			return $dump_raw;
		}else{
			return 0;
		}
	}
	public function JsonConvertUID(){
		$dump_raw = $this->redis->get('response_complete'.$this->key_pid);
		if(!empty($dump_raw)){
			echo $dump_raw;
		}
	}
	public function RefreshListUID(){
		$this->redis->del($this->key_pid);
		$this->redis->del('response_'.$this->key_pid);
		$this->redis->del('response_complete'.$this->key_pid);
		redirect(base_url('apps'));
	}
	private function cached_request(){
		$msg = '<div class="alert alert-danger alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<h4><i class="icon fa fa-ban"></i> Sorry!</h4>
		Xem lại file UID của bạn, vui lòng bấm Remove / Refresh và thực hiện lại
		</div>';
		return $msg;
	}
	private function limit_request_http(){
		$msg = '<div class="alert alert-danger alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<h4><i class="icon fa fa-ban"></i>Xem lại file UID của bạn, vui lòng chọn file và thực hiện lại!
		</div>';
		return $msg;
	}
	private function convert_string_to_array_textarea($params){
		return explode("\n", str_replace("\r", "", $params));
	}
	private function FileUpload(){
		$outoutArr = array();
		if($_FILES['file']['tmp_name'] != "" && $_FILES['file']['type'] == "text/plain"){
			$uploaded_file = $_FILES['file']['tmp_name'];
			$file_open = fopen($uploaded_file, 'r');
			$file_read = fread($file_open,filesize($uploaded_file));
			fclose($file_open);
			$newline_ele = "\n";
			$data_split = explode($newline_ele, str_replace("\r", "", $file_read));
			$new_tab = "\t";
			$outoutArr = array();
			foreach ($data_split as $string)
			{
				$row = explode($new_tab, $string);
				if(isset($row['0']) && $row['0'] != ""){
					$outoutArr[] = trim($row['0']);
				}
			}
		}
		//return $outoutArr; 
		return array_filter($outoutArr); //
	}

	
	
}
?>