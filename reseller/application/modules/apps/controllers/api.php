<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Api extends REST_Controller {
	function __construct(){
		parent::__construct();
		
	}
	public function GetAllRecord_get(){
		$result = Appscore::QueryCoreAll();
		$response = array('results' => $result);
		$this->response($response);
	}
	public function ClientScore_get(){
		// $response = array('results' => 0);
		$results = '';
		$Client = $this->session->userdata('data_users');
		if(!empty($Client)){
			$IdClient = $Client['id'];
			$levels = (int)$Client['level'];
			if($levels == 1){
				$ResultScore = Appscore::QueryCoreClientScore($IdClient);
				if(!empty($ResultScore)){
					$response = array('results' => "Điểm bạn có: ". (int)$ResultScore[0]['score']);
				}else{
					$response = array('results' => 0);
				}
			}else{
				$response = array('results' => "Không giới hạn điểm. <br> Hết hạn ngày ".$Client['expired']);
			}
			
			
		}
		
		$this->response($response);
	}
	public function checkuid_get(){
		$response = array();
		$uid = $this->input->get('uid');
		if(!empty($uid)){
			$redis_check = $this->redis->get($uid);
			if(!empty($redis_check)){
				$response = array('results' => json_decode($redis_check));
			}else{
				$result = Appscore::QueryCore($uid);
				if(!empty($result)){
					$response = array('results' => $result,'results_raw'=> json_decode($this->redis->get($uid)));
				}else{
					$response = array('results' => '');
				}
			}
		}
		$this->response($response);
	}
	public function Expired_get(){
		$response = array('results' => 0);
		$uid_private = $this->input->get('uid_private');
		if(!empty($uid_private)){
			$result = Appscore::Expired($uid_private);
			$response = array('results' =>$result);
		}
		
		$this->response($response);
	}
///---End Class Service---///

}

////------Start Class Core Apps-------////
class Appscore extends MY_Controller{
	
	function __construct(){
		parent::__construct();
		
	}
	public function QueryCoreClientScore($userid){
		$this->db = $this->load->database('default', TRUE);
		$sql = "SELECT `score` FROM `users` WHERE `id` = $userid and `level` = 1 limit 1";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
		// if(!empty($result)){
			// $this->redis->set($uid,json_encode($result));
			// return $result;
		// }else{
			// return $result = array('');
		// }
		
	}
	public function QueryCoreAll(){
		$this->db = $this->load->database('default', TRUE);
		$sql = "SELECT `uid`,`phone` FROM `vnphone` LIMIT 100";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
		// if(!empty($result)){
			// $this->redis->set($uid,json_encode($result));
			// return $result;
		// }else{
			// return $result = array('');
		// }
		
	}
	public function QueryCore($uid){
		$DbRedis = $this->redis->get($uid);
		if(!empty($DbRedis)){
			return json_decode($DbRedis);
		}else{
			$this->db = $this->load->database('default', TRUE);
			$sql = "SELECT `uid`,`phone` FROM `vnphone` WHERE uid = '$uid' LIMIT 1";
			$query = $this->db->query($sql);
			$result = $query->result_array();
			if(!empty($result)){
				$this->redis->set($uid,json_encode($result));
				return $result;
			}else{
				return $result = array('');
			}
		}
		
		
	}
	
	public function Expired($userid){
		$this->db = $this->load->database('default', TRUE);
		$sql = "SELECT `id`,`expired` FROM `users` WHERE `id` = $userid and `level` = 2 limit 1";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		if(!empty($result)){
			$timenow = time();
			foreach($result as $value){
				$expired = strtotime ($value['expired']);
				if($expired >= $timenow){
					return $response = 1;
				}else{
					Appscore::update_status_user($userid);
					return $response = 0;
				}
			}
		}else{
			return $response = 0;
		}
	}
	private function update_status_user($userid){
		$this->db = $this->load->database('default', TRUE);
		$dataUpdate = array('status' => 2,);
		$this->db->where('id', $userid);
		$this->db->update('users', $dataUpdate); 
	}
	
	
	
	
///---End Class Apps---///
}	

?>