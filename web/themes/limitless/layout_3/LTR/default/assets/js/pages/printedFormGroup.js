$(document).ready(function() {
  $('.add-template').click(function(event){
    event.preventDefault();
    var clone = $(this).parents('.form-group').clone();
    clone.find('.delete-template').show();
    $('.btns').before(clone);
    $('.add-template').hide();
    $('.add-template:last').show();
  });

  $('body').on('click', '.delete-template', function(){
      $(this).parents('.form-group').remove();
      $('.add-template').hide();
      $('.add-template:last').show();
  });
});
