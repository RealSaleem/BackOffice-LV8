(function () {
    'use strict';
    angular
	  .module('posApp')
	  .factory('currencyDataService', currencyDataService);

    currencyDataService.$inject = ['ajaxService', '$q'];

    function currencyDataService(ajaxService, $q) {
        return {
            get_all: get_all,
        }

        function get_all(register_id) {
            var defered = $q.defer();
            ajaxService.get('customer/get_currency', null)
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
    }
})()