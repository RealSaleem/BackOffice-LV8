posApp.filter('abs',function(){
	return function(val) {
	   return Math.abs(val);
	 }
})
posApp.filter('round_off',function($rootScope){
	return function(val) {
		if(val != null && val > 0){
	   return parseFloat(val).toFixed($rootScope.decimal);
	}
	 return val;
	 }
})