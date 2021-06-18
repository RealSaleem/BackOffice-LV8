<!doctype html>
<html lang="en">
<head>
	<title>{{ ucfirst($store->name) }}</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<style type="text/css">
		body{margin: 0; padding: 0; background: #f4f4f4 url('{{ asset('email_images/bg.jpg') }}') repeat-x; font-family: arial;}
		.mainwrapper{text-align:center; margin:0 auto;  padding:30px 0 0 0; width: 700px; border-radius: 13px; background: #fff; -webkit-box-shadow: 0px 0px 19px 0px rgba(214,214,214,1); -moz-box-shadow: 0px 0px 19px 0px rgba(214,214,214,1); box-shadow: 0px 0px 19px 0px rgba(214,214,214,1); color:#000!important; background-color:#f4f4f4}
		.mainwrapper p.txt, .mainwrapper p{margin: 40px 0; line-height: 23px; font-size:16px}
		.mainwrapper span{color:#666; margin: 11px 0; display: inline-block;}
		.mainwrapper a{text-decoration: none; color: #2a3b97}
		.mainwrapper h3{font-size:20px}
		header{text-align: center; margin: 25px 0} 
		.logo{}
		.logo img{width:18%}
		.heading{background:#000; color: #fff; }
		.footer{padding:30px 0; margin:30px 0; background:#f7f7f7; border-radius: 0 0 13px 13px; }
		.buttons{margin: 25px 0 0 0; display: inline-block}
		.buttons img{width: 180px; margin: 0 5px}
		.table{width: 90%; margin: 0 auto; text-align: left;}
		.table td{border:#ccc solid 1px; padding: 10px; }
		.table th{border:#ccc solid 1px; padding: 10px; }
		.socialsicons a{color: #fff; background: #ccc; border-radius: 4px; display: inline-block; width: 28px; height: 28px; line-height: 28px; font-size: 16px; margin: 0 3px;}
		.socialsicons .facebook:hover{background:#2d4486; }
		.socialsicons .twitter:hover{background:#2699d8; }
		.socialsicons .youtube:hover{background:#fa1927; }
		.socialsicons .linkedin:hover{background:#1772a0; }
		.socialsicons .google:hover{background:#d13431; }
		.copyright{text-align: center; font-size: 13px; color: #949494; padding: 0 0 10px 0 ;}
		@media(max-width:650px) {
		.mainwrapper{width: auto; margin: 0 20px; padding: 25px 15px}
		.logo img{width: 73%}
		.buttons img{width: 195px; margin:0px 0 10px 0}
		.table2 {width: 100%;}
		.table2 tr td{width: auto !important; display: block;}
		.table2 tr th{width: auto !important; display: block;}
		.tableouter{overflow: scroll;}
			 }			
	</style>
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
	<div style="background:url('{{ asset('email_images/bg.jpg') }}'); background-repeat:repeat-x;">
	<header>
		<a href="{{ $store->webstore->url }}" class="logo">
            @if(isset($store->websettings->logo))
                <img src="{{ $store->websettings->logo }}" class="logo" style="max-height:210px;object-fit:contain" >
            @elseif(isset($store->store_logo))
            	<img src="{{ $store->store_logo }}" class="logo" style="max-height:210px;object-fit:contain" >
            @else
                <img src="{{ url('public/assets/email_images/logo.png') }}">
            @endif			
		</a>
	</header>
	<div class="mainwrapper">