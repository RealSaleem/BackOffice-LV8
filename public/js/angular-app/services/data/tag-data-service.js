(function(){
	'use strict';
	angular
	  .module('posApp')
	  .factory('tagDataService', tagDataService);

	  tagDataService.$inject = ['ajaxService', '$q'];

	  function tagDataService(ajaxService, $q){
	  	return {
	  		all : all,
	  		find : find,
	  		add : add,
	  		edit: edit,
	  		delete_tag : delete_tag,
	  	};

	  	function all(){
	  		var defered = $q.defer();
	  		ajaxService.get('tag/all', null)
	  			.then(function(response){
	  				if(!response.IsValid){
	  					defered.reject();
	  					return;
	  				}
	  				defered.resolve(response.Payload);
	  			})
	  			.catch(function(){
	  				defered.reject();
	  			});
	  			
	  		return defered.promise;
	  	}

	  	function delete_tag(tag){
	  		var defered = $q.defer();
	  		ajaxService.post('tag/delete', tag)
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

	  	function add(tag){
	  		var defered = $q.defer();
	  		ajaxService.post('tag/confirm_add', tag)
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

	  	function edit (tag){
	  		var defered = $q.defer();
	  		ajaxService.post('tag/edit',tag)
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

	  	function find(q){
	  		var defered = $q.defer();
	  		ajaxService.get('tag/find', {q : q})
	  			.then(function(response){
	  				if(response.IsValid){
	  					defered.resolve(response.Payload);
	  					return;
	  				}
	  				defered.reject();
	  			})
	  			.catch(function(){
	  				defered.reject();	
	  			});
	  		return defered.promise;
	  	}

	  	// function add(name){
	  	// 	var defered = $q.defer();
	  	// 	ajaxService.post('tag/confirm_add', {name : name})
	  	// 		.then(function(response){
	  	// 			if(response.IsValid){
	  	// 				defered.resolve(response.Payload);
	  	// 			}
	  	// 		})
	  	// 	return defered.promise;
	  	// }

	  }
})()