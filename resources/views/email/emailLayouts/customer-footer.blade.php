
			<br>
			<br>
			<br>
			<br>
			<br>
			<p>
				<span>Best Regards <br>
				{{ ucfirst($store->name) }} Team</span> <br><a href="{{ $store->webstore->url }}">
					{{ $store->webstore->url }}
				</a>
			</p>
	        	{{--
			<div class="footer">
<!-- 				<div class="buttons">
					<a href="https://www.nextaxe.com"><img src="{{ CustomUrl::asset('assets/email_images/google.png') }}"></a>
					<a href="https://www.nextaxe.com"><img src="{{ CustomUrl::asset('assets/email_images/apple.png') }}"></a>
				</div> -->
				<br><br>

	        	@if(!is_null($store->websettings->links) && sizeof($store->websettings->links) > 0)
					<p>@lang('site.follow_us')</p>
					@foreach($store->websettings->links as $row)
						<a href="{{ $row->link }}" target="_blank">
							<i class="{{ $row->icon }}"></i>
						</a>
					@endforeach
	        	@endif
			</div>
	        		--}}
		</div>
		<div class="copyright">
			<p>Copyright © {{ strtoupper($store->name) }}, {{ date('Y') }}. All right reserved.</p>
		</div>
		</div>
	</body>
</html>
