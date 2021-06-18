"use strict";

if(!BASE_URL)
	var BASE_URL  = 'http://localhost:8000/';
var SERVICE_URL = BASE_URL;
posApp.constant('ENV', {
	name 			:'development',
	dataProvider	:'LOCAL',
	serviceUrl 		: SERVICE_URL
})