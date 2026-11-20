/**
 * Interaction for the media module
 * global jsBackend
 * global utils
 */
jsBackend.mediaLibrary = {
  init: function () {
    // load the tree
    jsBackend.mediaLibrary.tree.init()

    // add some extra controls
    jsBackend.mediaLibrary.controls.init()

    // adds functionalities for library
    jsBackend.mediaLibrary.library.init()
  }
}

/**
 * Add some extra controls
 * global jsBackend
 */
jsBackend.mediaLibrary.controls = {
  init: function () {
    // save and edit
    $('#saveAndEdit').on('click', function () {
      $('form').append('<input type="hidden" name="after_save" value="MediaItemEdit" />').submit()
    })
  }
}

/**
 * All methods related to the library overview
 * global jsBackend
 */
jsBackend.mediaLibrary.library = {
  currentType: null,
  init: function () {
    // start or not
    if ($('#library').length === 0) {
      return false
    }

    // init edit folder dialog
    jsBackend.mediaLibrary.library.addEditFolderDialog()

    // init mass action hidden input fields
    jsBackend.mediaLibrary.library.dataGrids()
  },

  /**
   * Add edit folder dialog
   */
  addEditFolderDialog: function () {
    var $editMediaFolderDialog = $('#editMediaFolderDialog')
    var $editMediaFolderSubmit = $('#editMediaFolderSubmit')

    // stop here
    if ($editMediaFolderDialog.length === 0) {
      return false
    }

    $editMediaFolderSubmit.on('click', function () {
      // Update folder using ajax
      $.ajax({
        data: {
          fork: {action: 'MediaFolderEdit'},
          folder_id: $('#mediaFolderId').val(),
          name: $('#mediaFolderName').val()
        },
        success: function (json, textStatus) {
          if (json.code !== 200) {
            // show error if needed
            if (jsBackend.debug) {
              window.alert(textStatus)
            }

            // show message
            jsBackend.messages.error('success', textStatus)

            return
          }

          // show message
          jsBackend.messages.add('success', json.message)

          // reload document
          window.location.reload(true)
        }
      })
    })
  },

  /**
   * Move audio to another folder or connect audio to a gallery
   */
  dataGrids: function () {
    if (window.location.hash === '') {
      // select first tab
      jsBackend.tabs.show($('#library .nav-tabs a:first'))
    }

    // When mass action button is clicked
    $('.jsMassActionSubmit').on('click', function () {
      // We remember the current type (image, file, movie, audio, ...)
      jsBackend.mediaLibrary.library.currentType = $(this).closest('.jsMassAction').find('select[name=action]').attr('id').replace('mass-action-', '')
    })

    // Submit form
    $('#confirmMassActionMediaItemMove').find('button[type=submit]').on('click', function () {
      $('#move-to-folder-id-for-type-' + jsBackend.mediaLibrary.library.currentType).val($('#moveToFolderId').val())
      $('#form-for-' + jsBackend.mediaLibrary.library.currentType).submit()
    })

    $('#confirmMassActionMediaItemDelete').find('button[type=submit]').on('click', function () {
      $('#form-for-' + jsBackend.mediaLibrary.library.currentType).submit()
    })
  }
}

/**
 * All methods related to the tree
 * global jsBackend
 */
jsBackend.mediaLibrary.tree = {
  // init, something like a constructor
  init: function () {
    var $tree = $('#tree > div')

    if ($tree.length === 0) {
      return false
    }

    $tree
      .on('state_ready.jstree', function (e, data) {
        var openedFolderId = jsBackend.data.get('MediaLibrary.openedFolderId')

        if (openedFolderId) {
          data.instance.select_node('folder-' + openedFolderId, true)
        }
      })
      // activate_node fires on every click, select_node not when the folder of the current page is clicked
      .on('activate_node.jstree', jsBackend.mediaLibrary.tree.onSelect)
      .on('move_node.jstree', jsBackend.mediaLibrary.tree.onMove)
      .jstree({
        core: {
          multiple: false,
          // folders have no move restrictions, only allow moving them by drag and drop
          check_callback: function (operation) {
            return operation === 'move_node'
          },
          themes: {dots: true}
        },
        plugins: ['dnd', 'state', 'types'],
        dnd: {
          copy: false,
          is_draggable: jsBackend.tree.isDraggable
        },
        state: {
          key: 'fork_media_folders',
          filter: jsBackend.tree.filterState
        },
        types: {
          default: {icon: 'fa-regular fa-folder'},
          folder: {icon: 'fa-regular fa-folder'}
        }
      })

    jsBackend.tree.toggleCollapse($tree)
  },

  // when an item is selected
  onSelect: function (e, data) {
    // only a click redirects, not the selection on page load
    if (!data.event) {
      return
    }

    // get current and new URL
    var currentPageURL = window.location.pathname + window.location.search
    var newPageURL = data.node.a_attr.href

    // only redirect if destination isn't the current one.
    if (typeof newPageURL !== 'undefined' && newPageURL !== currentPageURL) {
      window.location = newPageURL
    }
  },

  // when an item is moved
  onMove: function (e, data) {
    if (jsBackend.tree.isRollingBack) {
      return
    }

    // folders have no sequence, MediaFolderMove only uses the new parent
    var drop = jsBackend.tree.getDrop(data)

    // make the call
    $.ajax({
      data: {
        fork: {action: 'MediaFolderMove'},
        id: data.node.li_attr['data-id'],
        dropped_on: drop.droppedOn,
        type: drop.type
      },
      success: function (json) {
        // show message
        jsBackend.messages.add('success', json.message)
      },
      error: function () {
        // show message
        jsBackend.messages.add('danger', jsBackend.locale.err('CantBeMoved'))

        jsBackend.tree.rollback(data)
      }
    })
  }
}

/** global jsBackend */
$(jsBackend.mediaLibrary.init)
