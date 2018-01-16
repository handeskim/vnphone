<?php
class Payment extends MY_Controller{
	function __construct(){
		parent::__construct();
	
		
		 $this->access_key = 'ljahosq5n5g86y1ahlr4';
		 $this->secret = 'pxfozqrv4y9w5auvo9wtw240ccypyyjx';
		 $this->return_url = base_url()."payment/pay";
		$this->urls = '';
	}
	public function testcase(){
		echo '<a href="'.base_url().'payment/payment/transactions?amount=1000000&order_id=123123123&order_info=12"> TEST NHE </a>';
	}

	public function transactions(){
		$this->urls  =  isset($_SERVER['HTTP_REFERER']) ? $this->urls  : base_url();
		$amount =  $this->input->get_post('amount'); 
		$order_id =  $this->input->get_post('order_id');  
		$order_info = $this->input->get_post('order_info'); 
		if(isset($amount)==true){
			
			if(isset($order_id)==true){
				
				if(isset( $order_info)==true){
					
					$params = array(
						'amount' => (int)$amount,
						'order_id' => $order_id,
						'order_info' => 'Thanh toan Package '.$order_info,
					);
					
					$name = $this->visaCharging($params);
					var_dump($name);
				}else{
					redirect($this->urls );
				}
			}else{
				redirect($this->urls );
			}
		}else{
			redirect($this->urls );
		}
		
	}
	public function Payment_success(){
		echo "Payment success ";
	}
	public function Payment_Cancel(){
		echo "Payment Cancel ";
	}
	public function pay(){
	
            
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
				'authorized' => $response_code
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
	private function visaCharging($params){
		
		$amount = $params['amount'];
		$order_info = $params['order_info'];
		$order_id = $params['order_id'];
		$data = "access_key=".$this->access_key."&amount=".$amount."&order_id=".$order_id."&order_info=".$order_info;
		$signature = hash_hmac("sha256", $data,  $this->secret);
  		$data.= "&signature=".$signature."&return_url=". $this->return_url;
		$json_visaCharging = $this->execPostRequest('http://visa.1pay.vn/visa-charging/api/handle/request', $data);
		$decode_visaCharging = json_decode($json_visaCharging,true);  // decode json
		$pay_url = $decode_visaCharging["pay_url"];
		header("Location: $pay_url");
		
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
}
?>