
<?php
function before_update_callback($primary, $xcrud){
    echo "<script>window.close();</script>";
    exit;
}
function before_view_date_register_callback($row_data, $primary, $xcrud){
       echo date("m-d-Y",$row_data->get('reg_date')); // like 'postdata'
  }
function publish_discussion_question($xcrud)
{
    if ($xcrud->get('primary'))
    {
        $db = Xcrud_db::get_instance();
        $query = 'UPDATE tbl_discussion_question SET `active` = b\'1\' WHERE id = ' . (int)$xcrud->get('primary');
        $db->query($query);
    }
}
function unpublish_discussion_question($xcrud)
{
    if ($xcrud->get('primary'))
    {
        $db = Xcrud_db::get_instance();
        $query = 'UPDATE tbl_discussion_question SET `active` = b\'0\' WHERE id = ' . (int)$xcrud->get('primary');
        $db->query($query);
    }
}
function update_public_content($postdata, $xcrud){
    $postdata->set('date_public', time());
}
function decode_string($postdata, $xcrud){
	$title_decode = UrlDecodes($postdata->get('title'));
	$keywords_decode = UrlDecodes($postdata->get('keywords'));
	$postdata->set('title_decode', $title_decode);
	$postdata->set('keywords_decode', $keywords_decode);
	$postdata->set('description', $title_decode.','.$keywords_decode);
  $postdata->set('date_public', time());
}
function UrlDecodes($str) {
	  $str = trim(mb_strtolower($str));
		$str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
		$str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
		$str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
		$str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
		$str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
		$str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
		$str = preg_replace('/(đ)/', 'd', $str);
		$str = preg_replace('/[^a-z0-9-\s]/', '', $str);
		$str = preg_replace('/([\s]+)/', ' ', $str);
			return $str;

}