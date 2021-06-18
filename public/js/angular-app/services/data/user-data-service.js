(function(){
	'use strict';
	angular
	  .module('posApp')
	  .factory('userDataService', userDataService);

	  userDataService.$inject = ['ajaxService', '$q'];

	  function userDataService(ajaxService, $q){
	  	return {
	  		get_all : get_all,
	  		// add : add,
	  		get_with_registers : get_with_registers,
	  		get : get,
	  		edit : edit,
	  		add_confirmed: add_confirmed,
	  		edit_confirmed: edit_confirmed,
	  		get_user : get_user,
	  		upload_image : upload_image,
	  		checkEmail : checkEmail,
	  	}

	  	function edit_confirmed(users){
	  		var defered = $q.defer();
	  		ajaxService.post('users/edit_confirmed',users)
	  		.then(function(response){
	  			if (!response.IsValid) {
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

	  	function get_user(id){
	  		var defered = $q.defer();
	  		ajaxService.get('users/get', {id : id})
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

	  	function get_all(filters){
	  		var defered = $q.defer();
	  		ajaxService.get('users/get_all', filters)
	  			.then(function(response){
	  				console.log(response);
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

	  	function get(id){
	  		var defered = $q.defer();
	  		ajaxService.get('outlet/get', {id : id})
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

	  	function add_confirmed(user){
	  		var defered = $q.defer();
	  		ajaxService.post('users/add_confirmed',user)
	  			.then(function(response){
	  				if(!response.IsValid){
	  					defered.reject();
	  					return;
	  				};
	  				defered.resolve(response);
	  			})
	  			.catch(function(){
	  				defered.reject();
	  			});
	  		return defered.promise;
	  	}

	  	function upload_image(filename){
	  		var defered = $q.defer();
	  		ajaxService.postWithFile('users/saveImg',filename)
	  			.then(function(response){
	  				if(!response.IsValid){
	  					defered.reject();
	  					return;
	  				};
	  				defered.resolve(response);
	  			})
	  			.catch(function(){
	  				defered.reject();
	  			});
	  		return defered.promise;
	  	}

	  	function get_with_registers(id){
	  		var defered = $q.defer();
	  		ajaxService.get('outlet/get_with_registers', { id : id })
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

	  	function checkEmail(email){
	  		var defered = $q.defer();
	  		ajaxService.get('users/checkEmail',{email : email})
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

	  	function edit(outlet){
	  		var defered = $q.defer();
	  		ajaxService.post('outlet/confirm_edit',outlet)
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
	  }
})()