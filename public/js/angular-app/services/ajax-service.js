(function() {
	'use strict';

	angular
		.module('posApp')
		.factory('ajaxService', ajaxService);

	ajaxService.$inject = ['$http', '$q', 'ENV'];

	var activeService = 0;
	/* @ngInject */
	function ajaxService($http, $q, ENV) {
		var service = {
			post: postPromise,
			get: getPromise,
			postWithFile: postWithFilePromise
		};
		return service;
		function updateService(val){
			activeService += val;
			if(activeService == 0){
				//$ionicLoading.hide();
				$('#LoaderDiv').hide();

			}else{
				//$ionicLoading.show();
				$('#LoaderDiv').show();
			}
		}

		function getPromise(url, data, isBackground){
    		if(isBackground !== true)
            	updateService(1);
		  	return CreatePromise(url,data, isBackground,'Get');
		}

		function postPromise(url, data, isBackground){
            if(isBackground !== true)
            	updateService(1);
            return CreatePromise(url, data, isBackground, 'Post');
		}

		function postWithFilePromise(url, fileName, isBackground){
            if(isBackground !== true)
            	updateService(1);
            let data = new FormData($('#'+fileName)[0]);
            // data.append('image',fileName);
            return CreatePromise(url, data, isBackground, 'PostWithFile');
		}

		function CreatePromise(url,data, isBackground, type){
			url = ENV.serviceUrl + url;
    		var deferred = $q.defer();
    		var promise;
    		var headers = {
    			'Content-Type': undefined,
          		//'X-CSRF-Token': window.Laravel,
    		};

        	if(type == 'Post'){
        		//data["_token"] = window.Laravel;
            	promise = $http.post(url, data);
        	}
            else if (type == 'Get') {
            	promise = $http.get(url, { params: data });
            }
            else{
            	promise = $http.post(url, data,headers);
            }

            promise.then(
				function (response) {
				    if(isBackground !== true)
				    	updateService(-1);

				    if (response.data.IsValid == false) {
				        toastr.clear();

				        if (response.data.Errors != null) {
				            for (var property in response.data.Errors) {
				                if (response.data.Errors.hasOwnProperty(property)) {
				                    toastr.error(response.data.Errors[property]);
				                }
				            }
				        } else {
				            //toastr.error(response.data.Payload.errorInfo[2], response.data.Exception);
				            toastr.error(response.data.Payload, response.data.Exception);
				        }
				    } else {
				        //toastr.success('', 'heheheh');
				    }
				    deferred.resolve(response.data);
				}, function (response) {
				    if(isBackground !== true)
				    	updateService(-1);
				    toastr.clear();
				    toastr.error(response.Message,'Error');
				    deferred.reject(response);
				}
		  	)
		  	return deferred.promise;
        }
	}
})();