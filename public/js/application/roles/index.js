$(function(){
	$('.role-delete').click(function(){
		//console.log('123');
		let confirm_box = confirm('Are you sure you want to delete this role?');
		if(confirm_box){
			$('#role-id').val($(this).data('id'));
			let url = $("#delete-role-form").attr('action');
			url = url.replace('/0','/'+$(this).data('id'));
			$("#delete-role-form").attr('action',url);
			$("#delete-role-form").submit();
		}
	})
})
