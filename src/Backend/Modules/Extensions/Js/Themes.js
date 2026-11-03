/**
 * Interaction for the pages templates
 */
jsBackend.extensions = {
  init: function () {
    jsBackend.extensions.themeSelection.init()
  }
}

jsBackend.extensions.themeSelection = {
  init: function () {
    var $installedThemes = $('#installedThemes')
    // store the list items
    var listItems = $('.js-theme-selector')

    // one of the templates (ie. hidden radiobuttons) in the templateSelection <ul> are clicked
    listItems.on('click', function (e) {
      var $this = $(e.currentTarget)
      // store the object
      var radiobutton = $(this).parents('.card-select').find('input:radio:first')

      // set checked
      radiobutton.prop('checked', true)

      // if the radiobutton is checked
      if (radiobutton.is(':checked')) {
        // remove the selected state from all other templates
        $installedThemes.find('.card').removeClass('card-primary')
        listItems.removeClass('btn-primary').addClass('btn-secondary')
        listItems.find('.available-theme').removeClass('d-none')
        listItems.find('.selected-theme').addClass('d-none')

        // add a selected state to the parent
        radiobutton.closest('.card').addClass('card-primary')
        $this.addClass('btn-primary')
        $this.find('.available-theme').addClass('d-none')
        $this.find('.selected-theme').removeClass('d-none')
      }
    })
  }
}

$(jsBackend.extensions.init)
