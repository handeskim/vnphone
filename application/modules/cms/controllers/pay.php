<?php
class Pay extends MY_Controller{
	function __construct(){
		parent::__construct();
		$this->load->library('rest');
		$this->load->model('global_model', 'GlobalMD');	
		$this->login = $this->session->userdata('auth_sign');
		$this->access_key = '6iktb042wo6n1kvecvyo';
		 $this->secret = 'mey1cwp2xk2vksfpmgw0btvqpbbip7i3';
		 $this->return_url = base_url()."cms/pay/Transfer";
		$this->urls = '';
		if($this->login){
			$this->user_data = $this->session->userdata('data_users');
			$this->permisson = $this->user_data['role'];
			$this->clients = $this->user_data['id'];
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
	
	public function visa_pay(){
		if(isset($_POST['pack'])){
			$pack = $_POST['pack'];
			$Paycart = $this->packed($pack);
			if(isset($Paycart)){
				if(!empty($Paycart)){
					
					$id_transction = core_token_csrf();
					$Transaction = array(
						'order_id' => $id_transction,
						'id_clients' => $this->clients,
						'score' => (int)$Paycart['score'],
						'amount' => (int)$Paycart['amount'],
						'order_info' => 'Thanh toan Package VIP'.$pack,
						'time_pay' => date('Y-m-d H:i:s',time()),
						'status' => 1000,
					);
					$this->db->trans_start();
					$install_transaction = $this->db->insert('transaction',$Transaction);
					$order_id = $this->db->insert_id();
					$this->db->trans_complete();
					
					if($install_transaction==true){
						$this->TPay_Visa($Transaction,$order_id);
					}else{
						redirect(base_url('cms/pay'));
					}
				}else{
					redirect(base_url('cms/pay'));
				}
			}else{
				redirect(base_url('cms/pay'));
			}
		}else{
			redirect(base_url('cms/pay'));
		}
	}
	
	public function bank_pay(){
		if(isset($_POST['pack'])){
			$pack = $_POST['pack'];
			$Paycart = $this->packed($pack);
			if(isset($Paycart)){
				if(!empty($Paycart)){
					$id_transction = core_token_csrf();
					$Transaction = array(
						'order_id' => $id_transction,
						'id_clients' => $this->clients,
						'score' => (int)$Paycart['score'],
						'amount' => (int)$Paycart['amount'],
						'order_info' => 'Thanh toan Package VIP'.$pack,
						'time_pay' => date('Y-m-d H:i:s',time()),
						'status' => 1000,
					);
					$this->db->trans_start();
					$install_transaction = $this->db->insert('transaction',$Transaction);
					$order_id = $this->db->insert_id();
					$this->db->trans_complete();
					
					if($install_transaction==true){
						$this->TPay_Local($Transaction,$order_id);
					}else{
						redirect(base_url('cms/pay'));
					}
				}else{
					redirect(base_url('cms/pay'));
				}
			}else{
				redirect(base_url('cms/pay'));
			}
		}else{
			redirect(base_url('cms/pay'));
		}
	}
	public function TPay_Visa($params,$order_id){
		
		$this->urls  =  isset($_SERVER['HTTP_REFERER']) ? $this->urls  : base_url();
		$amount =  $params['amount'];   
		$order_info = $params['order_info'];
		if(isset($amount)){
				$data = "access_key=".$this->access_key."&amount=".$amount."&order_id=".$order_id."&order_info=".$order_info;
				$signature = hash_hmac("sha256", $data,  $this->secret);
				$data.= "&signature=".$signature."&return_url=". $this->return_url;
				$json_visaCharging = $this->execPostRequest('http://visa.1pay.vn/visa-charging/api/handle/request', $data);
				$decode_visaCharging = json_decode($json_visaCharging,true); 
				$pay_url = $decode_visaCharging["pay_url"];
				header("Location: $pay_url");
				
		}else{
			redirect(base_url('cms/pay'));
		}
	}
	public function TPay_Local($params,$order_id){
		
		$this->urls  =  isset($_SERVER['HTTP_REFERER']) ? $this->urls  : base_url();
		$amount =  $params['amount'];   
		$order_info = $params['order_info'];
		if(isset($amount)){
			$command = 'request_transaction';
			$data = "access_key=".$this->access_key."&amount=".$amount."&command=".$command."&order_id=".$order_id."&order_info=".$order_info."&return_url=".$this->return_url;
		    $signature = hash_hmac("sha256", $data, $this->secret);
		    $data1 = "access_key=".$this->access_key."&amount=".$amount."&command=".$command."&order_id=".urlencode($order_id)."&order_info=".urlencode($order_info)."&return_url=".urlencode($this->return_url);
		    $data1.= "&signature=".$signature;
		    $json_bankCharging = $this->execPostRequest('http://api.1pay.vn/bank-charging/service/v2', $data1);
			$decode_bankCharging=json_decode($json_bankCharging,true);
			$pay_url = $decode_bankCharging["pay_url"];
		   header("Location: $pay_url");
		}else{
			redirect(base_url('cms/pay'));
		}
	}
	public function Transfer(){
		$trans_ref = isset($_GET["trans_ref"]) ? $_GET["trans_ref"] : NULL;
		$response_code = isset($_GET["response_code"]) ? $_GET["response_code"] : NULL;
		$order_id = $_GET["order_id"];
		$params = json_encode($_GET);
		if($response_code == "00")
		{
			$this->loging($params);
			$this->TransactionPayment($response_code,$order_id);	
		}else{
			$this->loging($params);
			$this->db->trans_start();
			$TransactionData = array('status' => $response_code,);
			$this->db->where('id', $order_id);
			$status = $this->db->update('transaction', $TransactionData);
			$this->db->trans_complete();
			redirect(base_url().'cms/pay/Payment_Cancel');
		}
	}
	
	public function Payment_success(){
		$msg ='
		<div class="callout callout-success">
			<h4> Payment success!</h4>
				<a href="'.base_url("apps").'" > Nhấn vào đây để trở về</a>
		</div>';
		$data = array(
			'msg' => $msg,
			'user_data' => $this->user_data,
			'title'=> 'Payment success',
			'title_main' => 'Payment success',
		);
		$this->parser->parse('default/header',$data);
		$this->parser->parse('default/sidebar',$data);
		$this->parser->parse('default/main',$data);
		$this->parser->parse('default/layout/payment_response',$data);
		$this->parser->parse('default/footer',$data);
	}
	public function Payment_Cancel(){
		$msg ='<div class="callout callout-danger">
        <h4> Payment Cancel!</h4>
		<a href="'.base_url("cms/pay").'" > Nhấn vào đây để thử lại thanh toán</a>
		</div>';
		$data = array(
			'msg' => $msg,
			'user_data' => $this->user_data,
			'title'=> 'Payment Cancel',
			'title_main' => 'Payment Cancel',
		);
		$this->parser->parse('default/header',$data);
		$this->parser->parse('default/sidebar',$data);
		$this->parser->parse('default/main',$data);
		$this->parser->parse('default/layout/payment_response',$data);
		$this->parser->parse('default/footer',$data);
	}
	private function TransactionPayment($response_code,$order_id){
		$sql = "SELECT * FROM `transaction` WHERE id = '$order_id' and status = 1000 ";
		$GetinfoOder = $this->GlobalMD->query_global($sql);
		if(!empty($GetinfoOder)){
			$id_clients = $GetinfoOder[0]['id_clients'];
			$sqlClients = "SELECT * FROM `users` WHERE id = '$id_clients'";
			$GetinfoClients = $this->GlobalMD->query_global($sqlClients);
			/////////////////
			$score_old = (int)$GetinfoClients[0]['score'];
			$score_new = (int)$GetinfoOder[0]['score'];
			$score_update = (int)$score_old + (int)$score_new;
			//////////////////
			$this->db->trans_start();
				$clientData = array('score' => $score_update,);
				$this->db->where('id', $id_clients);
				$status = $this->db->update('users', $clientData); 
				$TransactionData = array('status' => $response_code,);
				$this->db->where('id', $order_id);
				$status = $this->db->update('transaction', $TransactionData);
			$this->db->trans_complete();
			redirect(base_url().'cms/pay/Payment_success');
		}else{
			redirect(base_url().'cms/pay/Payment_Cancel');
		}
		
	}
	private function execPostRequest($url, $data)
	{
	 $ch = curl_init();
	 curl_setopt($ch, CURLOPT_URL, $url);
	 curl_setopt($ch, CURLOPT_POST, 1);
	 curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	 curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	 $result = curl_exec($ch);
	 curl_close($ch);
	 return $result;
	}
	
	private function loging($params){
		try{
			$all_userdata = json_encode($this->session->all_userdata());
			$now = DateTime::createFromFormat('U.u', microtime(true));
			$date = date("Y-m-d H:i:s.u",time());
			$install = $this->db->insert('logs_pay', array(
				'uri' => $this->uri->uri_string(),
				'method' =>  'GET',
				'params' => serialize($params),
				'userdata' => $all_userdata,
				'ip_address' => $this->input->ip_address(),
				'time' => $now->format("m-d-Y H:i:s.u"),
				
			));
			if($install==true){
				return true;
			}else{
				return false;
			}
		}catch (Exception $e) {
			return false;
		}
	
	}
	private function packed($pack){
		$payCart = array();
			if($pack==1){
				$payCart = array(
					'amount' => 1000000,
					'score' => 100000,
				);
			}
			if($pack==2){
				$payCart = array(
					'amount' => 2000000,
					'score' => 250000,
				);
			}
			if($pack==3){
				$payCart = array(
					'amount' => 5000000,
					'score' => 700000,
				);
			}
			if($pack==4){
				$payCart = array(
					'amount' => 8000000,
					'score' => 1200000,
				);
			}
		return $payCart;
	}
	private function load_history_pay(){
		$xcrud = Xcrud::get_instance();
		$xcrud->table('transaction');
		$xcrud->table_name('Lịch sử Thanh Toán');
		if($this->permisson > 1){
			$xcrud->where('id_clients',$this->clients);
			$xcrud->unset_add();
		}
		$xcrud->unset_csv();
		$xcrud->unset_remove();
		$xcrud->unset_print();
		$xcrud->unset_edit();
		$xcrud->unset_view();
		$xcrud->relation('status','conf_status_pay','code','name_pay');
		$xcrud->relation('id_clients','users','id','clients_code');
		$xcrud->columns('order_id,order_info,score,amount,time_pay,status');
		$response = $xcrud->render();
		return $response;
	}
	public function history(){
		$msg ='';
		$data = array(
			'msg' => $msg,
			'content' => $this->load_history_pay(),
			'user_data' => $this->user_data,
			'title'=> 'Payment',
			'title_main' => 'Payment',
		);
		$this->parser->parse('default/header',$data);
		$this->parser->parse('default/sidebar',$data);
		$this->parser->parse('default/main',$data);
		$this->parser->parse('default/layout/main_curd_history_pay',$data);
		$this->parser->parse('default/footer',$data);
	}
	public function index(){
		
		$msg ='';
		$data = array(
			'msg' => $msg,
			'user_data' => $this->user_data,
			'title'=> 'Payment',
			'title_main' => 'Payment',
		);
		$this->parser->parse('default/header',$data);
		$this->parser->parse('default/sidebar',$data);
		$this->parser->parse('default/main',$data);
		$this->parser->parse('default/layout/main_curd_payment',$data);
		$this->parser->parse('default/footer',$data);
	}

	
}
?>