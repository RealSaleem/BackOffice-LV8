$(function(){
  $('.user-delete').click(function(){
  	//console.log('123');
    let confirm_box = confirm('Are you sure you want to delete this user?');
    if(confirm_box){
      $('#user-id').val($(this).data('id'));
      let url = $("#delete-user-form").attr('action');
      url = url.replace('/0','/'+$(this).data('id'));
      $("#delete-user-form").attr('action',url);
      $("#delete-user-form").submit();
    }
  })
})
