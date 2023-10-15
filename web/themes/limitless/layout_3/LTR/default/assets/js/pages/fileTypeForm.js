$(document).ready(function(){
  $('.role_checkbox').change(function(){
      if($(this).is(':checked')){
          $('.' + $(this).attr('id')).attr('checked', true);
          $('.' + $(this).attr('id')).parent().addClass('checked');
      }else{
          $('.' + $(this).attr('id')).attr('checked', false);
          $('.' + $(this).attr('id')).parent().removeClass('checked');
      }
  });
});
