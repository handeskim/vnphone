<section class="content">
<div class="row">

  <div class="col-md-12 col-xs-12">
       <div class="login-box-body">
			<h4 class="login-box-msg"> KHỞI TẠO API ĐẠI LÝ</br></h5>
			<form action="#" method="post">
			 <div class="form-group has-feedback">
				
				<div class="form-group">
                 <label> Chọn Tài khoản đại lý</label>
                  <select name="reseller" class="form-control" required>
					<?php if(!empty($daily)){
						foreach($daily as $value_daily){
					?>
                    <option value="<?php echo $value_daily['id'];?>"> <?php echo $value_daily['clients_code'].'  ('.$value_daily['username'].')';?></option>
					<?php }}?>
                  </select>
                </div>
			  </div>
			  <div class="row">
						<input type="hidden" name="cmd" class="form-control" value="cmdAddReseller" required />
				<!-- /.col -->
				<div class="col-xs-6">
				  <button type="submit" class="btn btn-primary  btn-lg ">Tạo Tài Khoản</button>
				</div>
				<!-- /.col -->
			  </div>
			</form>
		  </div>
  </div>
</div>
</section>