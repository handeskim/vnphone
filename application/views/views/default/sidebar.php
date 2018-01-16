<aside class="main-sidebar">
<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
  <!-- Sidebar user panel -->
  <div class="user-panel">
	<div class="pull-left image">
	  <img src="<?php echo base_url();?>public/images/avata/default.png" class="img-circle" alt="User Image">
	</div>
	<div class="pull-left info">
	  <p><?php echo $user_data["name"]; ?></p>
	  <a href="#"><i class="fa fa-circle text-success"></i> Online</a><br>
	</div>
	
  </div>
  <!-- search form -->
  
  <!-- /.search form -->
  <!-- sidebar menu: : style can be found in sidebar.less -->
  
  <ul class="sidebar-menu" data-widget="tree">
	<li class="header">HẠN SỬ DỤNG / ĐIỂM</li>
	<li ><a><i class="fa fa-money"></i><span id="pid_score"> </span><a></li>
	<li class="header" style="color: #4b646f;background: #1a2226;">CÔNG CỤ</li>
		<?php 
	   if(!empty($user_data['role'])){
		   if($user_data['role']==1){
			   echo '<li><a href="http://vnphones.com/"><i class="fa fa-fw fa-home"></i><span> Trang Chủ </span></a></li><li><a href="'.base_url('cms/dashboard').'"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
				<li ><a href="'.base_url('cms/reseller').'"><i class="fa fa-gg-circle"></i> <span> Tạo API Đại lý</span></a></li>
				<li ><a href="http://vnphones.com/convert/apps"><i class="fa fa-send"></i> <span> Tools Convert UID</span></a></li>';
		   }else{
			   echo '<li><a href="http://vnphones.com/"><i class="fa fa-fw fa-home"></i><span> Trang Chủ </span></a></li><li><a href="'.base_url('apps').'"><i class="fa fa-send"></i> <span>Tools Convert UID</span></a></li>';
		   }
	   }
	?>
	<li ><a  href="<?php echo base_url('cms/pay');?>"><i class="fa fa-cc-visa"></i> <span>Nạp Tài khoản</span></a></li>
	<li ><a target="_blank" href="http://www.textfilesplitter.com/"><i class="fa fa-edit"></i> <span>Split Text File</span></a></li>
	<li ><a href="<?php echo base_url();?>cms/profile"><i class="fa fa-lock"></i> <span>Đổi Mật Khẩu</span></a></li>
	<li><a href="<?php echo base_url()?>exits"><i class="fa fa-sign-out"></i> <span>Thoát</span></a></li>
  </ul>
</section>
<!-- /.sidebar -->
</aside>
<script>
jQuery(document).ready(function(){
	jQuery.get( url_global+"apps/api/ClientScore", function( data_client_score ) {
		jQuery( "#pid_score" ).html( data_client_score.results );
	});
});
</script>