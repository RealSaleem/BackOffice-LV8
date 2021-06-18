(function () {
    'use strict';
    angular
	  .module('posApp')
	  .factory('cashFlowDataService', cashFlowDataService);

    cashFlowDataService.$inject = ['ajaxService', '$q'];

    function cashFlowDataService(ajaxService, $q) {
        return {
            add: add,
            remove: remove,
            get_all: get_all,
            delete_cashflow : delete_cashflow,
        }

        function add(data) {
            var defered = $q.defer();
            ajaxService.post('cash-management/add', data)
	  			.then(function (response) {
	  			    if (!response.IsValid) {
	  			        defered.reject();
	  			        return;
	  			    };
	  			    defered.resolve(response.Payload);
	  			})
	  			.catch(function () {
	  			    defered.reject();
	  			});

            return defered.promise;
        }

        function remove(data) {
            var defered = $q.defer();
            ajaxService.post('cash-management/remove', data)
	  			.then(function (response) {
	  			    if (!response.IsValid) {
	  			        defered.reject();
	  			        return;
	  			    };
	  			    defered.resolve(response.Payload);
	  			})
	  			.catch(function () {
	  			    defered.reject();
	  			});

            return defered.promise;
        }

        function get_all(register_id) {
            var defered = $q.defer();
            ajaxService.get('cash-management/get_all/' + register_id , null)
	  			.then(function (response) {
	  			    if (!response.IsValid) {
	  			        defered.reject();
	  			        return;
	  			    };
	  			    defered.resolve(response.Payload);
	  			})
	  			.catch(function () {
	  			    defered.reject();
	  			});

            return defered.promise;
        }

        function delete_cashflow(id){
        	var defered = $q.defer();
        	ajaxService.get('cash-management/delete_cashflow',{id:id})
        	.then(function(response){
        		if (!response.IsValid) {
        			defered.reject();
        			return;
        		};
        		defered.resolve(response.Payload);
        	})
        	.catch(function () {
	  			    defered.reject();
	  			});
            return defered.promise;
        }
    }
})()