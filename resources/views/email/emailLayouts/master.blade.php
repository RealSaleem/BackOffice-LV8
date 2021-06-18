<!--
<script type="text/javascript">
	var BASE_URL = '{{ CustomUrl::asset('') }}';
</script>
@include('email.emailLayouts.header')
<body style="background:#f4f4f4;">
@yield('content')

@include('email.emailLayouts.footer')
@yield('scripts')
</body>
</html>
-->

@include('email.emailLayouts.header')

@yield('content')
		
@include('email.emailLayouts.footer')