posApp.filter('toLocal',function(){
	return function(date, format){
    	if(!date) return;
    	
        if(!format)
			format = 'YYYY-MM-DD HH:mm:ss';
        //console.log(date);
        return moment.utc(date, format).local().format('YYYY-MM-DD HH:mm');
    }
})
