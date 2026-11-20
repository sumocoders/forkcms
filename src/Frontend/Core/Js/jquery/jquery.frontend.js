/*!
 * jQuery Fork stuff
 */

/**
 * HTML5 validation
 */
(function ($) {
  $.fn.html5validation = function (options) {
    // define defaults
    var $input = $(this)
    var errorMessage = ''
    var type = ''
    var defaults = {
      required: jsFrontend.locale.err('FieldIsRequired'),
      email: jsFrontend.locale.err('EmailIsInvalid'),
      date: jsFrontend.locale.err('DateIsInvalid'),
      number: jsFrontend.locale.err('NumberIsInvalid'),
      value: jsFrontend.locale.err('InvalidValue')
    }

    options = $.extend(defaults, options)

    $input.on('invalid', function (e) {
      if ($input[0].validity.valueMissing) {
        errorMessage = options.required
      } else if (!$input[0].validity.valid) {
        type = $input[0].type
        errorMessage = options.value

        if (options[type]) {
          errorMessage = options[type]
        }
      }

      e.target.setCustomValidity(errorMessage)
      $input.parents('.form-group').addClass('has-error')

      $input.on('input change', function (e) {
        e.target.setCustomValidity('')
      })
    })

    $input.on('blur', function (e) {
      $input.parents('.form-group').removeClass('has-error')
      e.target.checkValidity()
    })
  }
})(jQuery)
