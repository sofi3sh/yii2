$(document).ready(function() {
    const DROPDOWN_CHILD_OPTION_KEY = 4;
    
    if ($('#option-type').val() == DROPDOWN_CHILD_OPTION_KEY) {
        $('#dynamic-option-section').hide();
        $('#dynamic').val(0);
        $('#display-after').val(0);
    }
    $('#option-type').change(function(event) {
        if (event.target.value == DROPDOWN_CHILD_OPTION_KEY) {
            $('#dynamic-option-section').hide();
        } else {
            $('#dynamic-option-section').show();
        }
    })
});
