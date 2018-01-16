<section class="content">
	<div class="row">
	<div class="pad margin no-print">
      <div class="callout callout-info" style="margin-bottom: 0!important;">
        <h4><i class="fa fa-info"></i> Note:</h4>
        không tắt mạng hoặc trình duyệt khi quá trình xử lý thanh toán.
      </div>
    </div>
	  <div class="col-md-6 col-xs-6">
		<div class="box box-info">
			<form role="form" method="post" action="<?php echo base_url('cms/pay/bank_pay');?>" id="form">
				<div class="box-body">
					<h2>Thanh Toán Internet Banking</h2>
					<div class="form-group">
					  <div class="radio">
						<label>
						  <input type="radio" name="pack" id="optionsRadios1" value="1" checked="">
							VIP 1 (1.000.000 đ) / 100.000 UID
						</label>
					  </div>
					  <div class="radio">
						<label>
						  <input type="radio" name="pack" id="optionsRadios2" value="2">
							VIP 2 (2.000.000 đ) / 250.000 UID
						</label>
					  </div>
					  <div class="radio">
						<label>
						  <input type="radio" name="pack" id="optionsRadios3" value="3" >
						  VIP 3 (5.000.000 đ) / 700.000 UID
						</label>
					  </div>
					   <div class="radio">
						<label>
						  <input type="radio" name="pack" id="optionsRadios3" value="4" >
							VIP 4 (8.000.000 đ) / 1.200.000 UID
						</label>
					  </div>
					</div>
					<div class="box-footer">
					
						<button type="submit" class="btn btn-primary">Thanh Toán</button>
					 </div>
				</div>
			</form>
		</div>
	   </div>
	    <div class="col-md-6 col-xs-6">
		<div class="box box-primary">
			<form role="form" method="post" action="<?php echo base_url('cms/pay/visa_pay');?>" id="form">
				<div class="box-body">
					<h2>Thanh Toán Visa/Master Card </h2>
					<div class="form-group">
					  <div class="radio">
						<label>
						  <input type="radio" name="pack" id="optionsRadios1" value="1" checked="">
							VIP 1 (1.000.000 đ) / 100.000 UID
						</label>
					  </div>
					  <div class="radio">
						<label>
						  <input type="radio" name="pack" id="optionsRadios2" value="2">
							VIP 2 (2.000.000 đ) / 250.000 UID
						</label>
					  </div>
					  <div class="radio">
						<label>
						  <input type="radio" name="pack" id="optionsRadios3" value="3" >
						  VIP 3 (5.000.000 đ) / 700.000 UID
						</label>
					  </div>
					   <div class="radio">
						<label>
						  <input type="radio" name="pack" id="optionsRadios3" value="4" >
							VIP 4 (8.000.000 đ) / 1.200.000 UID
						</label>
					  </div>
					</div>
					<div class="box-footer">
						<button type="submit" class="btn btn-primary">Thanh Toán</button>
					 </div>
				</div>
			</form>
		</div>
	   </div>
	</div>
</section>