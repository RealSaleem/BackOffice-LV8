(function () {
    'use strict';
    angular
	  .module('posApp')
	  .factory('registerDataService', registerDataService);

    registerDataService.$inject = ['ajaxService', '$q'];

    function registerDataService(ajaxService, $q) {
        return {
            edit: edit,
            get: get,
            get_all: get_all,
            open: open,
            close: close,
            get_registerbook_history: get_registerbook_history,
            add: add,
            delete_registerbook: delete_registerbook,
            edit_order : edit_order,
            void_sale : void_sale,
        }

        function open(data)
        {
            var defered = $q.defer();
            ajaxService.post('registerbook/open', data)
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

        function close(data)
        {
            var defered = $q.defer();
            ajaxService.post('registerbook/close', data)
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

        function edit_order(data)
        {
            var defered = $q.defer();
            ajaxService.post('register_closure/edit_order', data)
	  			.then(function (response) {
	  			    if (!response.IsValid) {
	  			        //defered.reject();
	  			        return;
	  			    };
	  			    defered.resolve(response.Payload);
	  			})
	  			.catch(function () {
	  			    //defered.reject();
	  			});
            return defered.promise;
        }

        function void_sale(data)
        {
            var defered = $q.defer();
            ajaxService.post('register_closure/void_sale', data)
	  			.then(function (response) {
	  			    if (!response.IsValid) {
	  			        //defered.reject();
	  			        return;
	  			    };
	  			    defered.resolve(response.Payload);
	  			})
	  			.catch(function () {
	  			    //defered.reject();
	  			});
            return defered.promise;
        }


        function get(id) {
            var defered = $q.defer();
            ajaxService.get('registerbook/get', { id: id })
	  			.then(function (response) {
	  			    if (!response.IsValid) {
	  			        defered.reject();
	  			        window.location = site_url('outlet');
	  			        return;
	  			    };
	  			    defered.resolve(response.Payload);
	  			})
	  			.catch(function () {
	  			    defered.reject();
	  			});

            return defered.promise;
        }

        function get_all(outlet_id) {
            var defered = $q.defer();
	  		let params = {};
	  		if(outlet_id > 0){
	  			params.outlet_id = outlet_id;
	  		}
            ajaxService.get('registerbook/get_all', params)
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

        function get_registerbook_history(id) {
            var defered = $q.defer();
            ajaxService.get('registerbook/get_registerbook_history', { history_id: id })
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

        function add(register){
	  		var defered = $q.defer();
	  		ajaxService.post('registerbook/confirm_add',register)
	  			.then(function(response){
	  				if(!response.IsValid){
	  					$('#LoaderDiv').hide();
	  					defered.reject();
	  					return;
	  				};
	  				defered.resolve(response.Payload);
	  			})
	  			.catch(function(){
	  				defered.reject();
	  			});
	  		return defered.promise;
	  	}

	  	function delete_registerbook(register){
	  		var defered = $q.defer();
	  		ajaxService.post('registerbook/delete',register)
	  			.then(function(response){
	  				if(!response.IsValid){
	  					defered.reject();
	  					return;
	  				};
	  				defered.resolve(response.Payload);
	  			})
	  			.catch(function(){
	  				defered.reject();
	  			});
	  		return defered.promise;
	  	}

        function edit(register) {
            var defered = $q.defer();
            ajaxService.post('registerbook/confirm_edit', register)
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