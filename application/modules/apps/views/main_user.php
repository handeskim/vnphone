<!--<script src='https://www.google.com/recaptcha/api.js'></script>-->
<script src="<?php echo base_url();?>app/apps.js"></script>
<script src="<?php echo base_url();?>app/temp.js"></script>
<script src="https://www.jquery-az.com/boots/js/bootstrap-filestyle.min.js"></script>
<div class="col-md-12">
<br>
<script>
			
			$('#fileuploadID').filestyle({
				buttonName : 'btn-info',
                buttonText : ' Select a File'
			});                        
</script>
<form action="#" method="POST" enctype="multipart/form-data">
	<!--
	<div class="col-md-4">
		<label>List UID (UID max to 10000)</label>
		<textarea class="col-md-12 form-control" rows="20" id="listUid" name="ListUidTextarea" ></textarea>
	</div>
	-->
	<div class="col-md-12">
		<div class="col-md-12">
			<div class="form-group">
				<label>Đường dẫn tới file UID (.txt) (Tối đa 100,000 UID 1 lần)</label>
					<input id="fileuploadID" type="file" name="file"><br>
			</div>
			<!--
			<div class="form-group">
				<label>Bootstrap style button 3</label>
				<input type="file" id="BSbtninfo" tabindex="-1" >
				<div class="bootstrap-filestyle input-group">
					<input type="text" class="form-control " placeholder="" disabled=""><span class="group-span-filestyle input-group-btn" tabindex="0"><label for="BSbtninfo" class="btn btn-info "><span class="icon-span-filestyle glyphicon glyphicon-folder-open"></span><span class="buttonText"> Select a File</span></label></span>
				</div>
			</div>
			-->
		</div>
		<div class="col-md-12">
		<hr>
		<label>Kết quả</label> 
		<div class="col-md-12" ng-controller="appraw" id="reponseConverts">
			<ul class="col-md-12" id="ul_reponse_apps">
				<div ng-repeat="listx in response">
					<li><span>{{ listx[0].uid }} | {{ listx[0].phone }}</span><br></li>
				</div>
			</ul>
		</div>
		</div>
		<div class="col-md-12" style="margin-bottom: 10px;">
			<div class="col-md-6">
				<input type="hidden" name="cmd" value="c1"/>
				<input id="btnConvert" name="submit" value="Start Convert" type="submit" class="btn btn-primary"> </input>
			</div>	
			<div class="col-md-6">
				<a id="button_system_exit" href="<?php echo base_url();?>apps/RefreshListUID" class="btn btn-danger"> <i class="fa fa-refresh"></i> Remove / Refresh</a>
			</div>
		</div>	
		<div class="col-md-12">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{total_uid}</h3>

              <p>TỔNG UID</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>{total_convert}</h3>

              <p>TỔNG UID TÌM THẤY</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
          </div>
        </div>
		<!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>{udi_kotimthay}</h3>

              <p>TỔNG UID KO TÌM THẤY</p>
            </div>
            <div class="icon">
			<i class="ion ion-person-add"></i>
             
            </div>
          </div>
        </div>
  
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{Percent_convert}% </h3>

              <p>TỶ LỆ THÀNH CÔNG</p>
            </div>
            <div class="icon">
               <i class="ion ion-pie-graph"></i>
            </div>
          </div>
        </div>
		      <!-- ./col -->
      </div>
		<div class="col-md-12">	
			<div class="col-md-6">
				<a id="button_system_exit" href="<?php echo base_url();?>text_export" class="btn btn-warning"> <i class="fa fa-file-excel-o"></i> Save Txt</a>
			</div>
			<div class="col-md-6">
				<a id="button_system_exit" href="<?php echo base_url();?>excel_export" class="btn btn-success"> <i class="fa fa-file-text"></i> Save Excel</a>
			</div>
		</div>
		
	</div>
</form>

</div>
<div class="col-md-12">	
<br>
</div>
<style>
.btn-file {
    position: relative;
    overflow: hidden;
}

#ul_reponse_apps{
	height: 200px;
    overflow: overlay;
    background: #fff;
    border: 1px solid #ecf0f5;
    list-style: decimal-leading-zero;
}

#btnConvert{
    margin:  5px auto;
    width: 100%;
    height: 39px;
    font-size: 21px;
    text-align:  center;
    text-transform: unset;
    color: whitesmoke;
}
#button_system_exit{
    margin:  5px auto;
    width: 100%;
    height: 39px;
    font-size: 21px;
    text-align:  center;
    text-transform: unset;
    color: whitesmoke;
}
.col-md-12 {
    width: 100%;
    background: #fff;
}
</style>
