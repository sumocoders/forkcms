/**
 * Interaction for the Profiles module
 */
jsBackend.Profiles = {
  init: function () {
    jsBackend.Profiles.massAddToGroup.init()
    jsBackend.Profiles.editEmail.init()
    jsBackend.Profiles.editPassword.init()
  },

  massAddToGroup: {
    init: function () {
      // update the hidden input for the new group's ID with the remembered value
      var $txtNewGroup = $('input[name="newGroup"]')

      // clone the groups SELECT into the "add to group" mass action dialog
      $('.jsMassActionAddToGroupSelectGroup').replaceWith(
        $('select[name="group"]')
        .clone(true)
        .removeAttr('id')
        .attr('name', 'newGroup')
        .on('change', function () {
          // update the hidden input for the new group's ID with the current value
          $txtNewGroup.val(this.value)
        })
      )
    }
  },

  editEmail: {
    init: function () {
      if ($('#newEmailBox').length === 0) return false

      $('#newEmail').on('change', function () {
        jsBackend.Profiles.editEmail.toggleBox()
      })

      jsBackend.Profiles.editEmail.toggleBox()
    },

    toggleBox: function () {
      var $item = $('#newEmail')
      var checked = ($item.attr('checked') === 'checked')

      $('#newEmailBox').toggle(checked)
    }
  },

  editPassword: {
    init: function () {
      if ($('#newPasswordBox').length === 0) return false

      $('#newPassword').on('change', function () {
        jsBackend.Profiles.editPassword.toggleBox()
      })

      jsBackend.Profiles.editPassword.toggleBox()
    },

    toggleBox: function () {
      var $item = $('#newPassword')
      var checked = ($item.attr('checked') === 'checked')

      $('#newPasswordBox').toggle(checked)
    }
  },
}

$(jsBackend.Profiles.init)
