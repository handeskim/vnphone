var temp_aller_max_request = '<div style="width: 100%;" class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-warning"></i> Alert Warning!</h4>Do chính sách của trình duyệt về bộ nhớ và tốc độ hiển thị. Nếu danh sách UID vượt quá 20.000 UID chúng tôi sẽ không hiển thị danh sách và Bạn có thể tải xuống bằng cách nhấp vào lưu văn bản hoặc lưu excel.</div>';
var rilaApps = angular.module('rilaApps',[]);
	rilaApps.controller("appraw", function($scope, $http){
	$scope.response = [];
	  $http.get(BASE_URL+'apps/JsonConvertUID').success(function(data) {
		if(data.length < 20000){
			$scope.response = data;
		}else{
			$('#ul_reponse_apps').append(temp_aller_max_request);
		}
	  });
});