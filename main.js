$(document).ready(function() {

  $('[data-action=disable]').submit(function(){
    var form = $(this);
    $(this).find('input[type="submit"], button[type="submit"]').each(function (index) {
      // Bail out if the form contains validation errors
      if ($.validator && !$(this).valid()) return;
      // Create a disabled clone of the submit button
      $(this).clone(false).removeAttr('id').prop('disabled', true).insertBefore($(this));
      // Hide the actual submit button and move it to the beginning of the form
      $(this).hide();
      form.prepend($(this));
    });
  });

});
