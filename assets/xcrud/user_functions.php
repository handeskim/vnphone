<?php
require_once dirname(__file__).'/xcrud_db.php'; 
function random_username(){
	$length = 4;
	$lengthc = 2;
	$randoms = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	$randomc = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $lengthc);
	$username = "DOTVN-1".$randoms.time().$randomc;
	return $username;
}
function random_balance(){
	$lengths = 4;
	$lengthc = 2;
	$lengthx = 2;
	$randoms = substr(str_shuffle("0123456789"), 0, $lengths);
	$randomc = substr(str_shuffle("0123456789"), 0, $lengthc);
	$randomx = substr(str_shuffle("0123456789"), 0, $lengthx);
	$random_balance = $randomx.$randoms.$randomc;
	return $random_balance;
}
function user_callback_add($postdata, $xcrud){
	$customer_id = random_username();
	$postdata->set('date_register',date('Y-m-d H:i:s'));
	$postdata->set('username',$customer_id);
	$id_balance = callback_insert_balance($customer_id);
	$postdata->set('id_balancer',$id_balance);
}
function callback_insert_balance($account_number){
	$date_create = date('Y-m-d H:i:s');
	$pass_otp = random_balance();
	$db = Xcrud_db::get_instance();
	$sql = "INSERT INTO `hk_balance` (`account_number`, `account_balance`,`date_create`,`status_balance`,`balance_code`) VALUES ('{$account_number}', '1', '{$date_create}','1','{$pass_otp}')";
	$db->query($sql);
	return $db->insert_id();
}

?>