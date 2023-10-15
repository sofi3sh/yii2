$(document).ready(function() {
  $('.printed-form-mark').click(function(event) {
      event.preventDefault();
      var title = $(this).attr("data-title");
      var formula = $(this).attr("data-formula");
      window.prompt(title, '{' + formula + '}');
  });
  $('.key_field').html($('#printedformformula-key').val());
  $('#printedformformula-key').keyup(function(){
      $('.key_field').html($(this).val());
  });

  $('#filters-toggle-btn').click(function() {
    $('.filter-options-container').slideToggle();
  })
});
