 'use strict';
posApp.factory('languageService', function () {
    var defaultLanguage = 'en';


     //document.getElementsByTagName("html")[0].getAttribute("lang");
    //{!! config("app.locale") !!}; //'en';
    //var locate = {!! config('app.locale') !!};
    console.log(defaultLanguage);
    return {
        get: function (key) {
            console.log(lang)
        	return lang[defaultLanguage].filter(item => {
                if(item.Key == key){
                    return item.Value;
                }
            })[0];
        },
    }
});
