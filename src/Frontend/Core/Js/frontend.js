/**
 * Frontend related objects
 */
/* global FB, ga, _gaq, twttr, Image, dataLayer, FileReader, bootstrap, gtag */
/* eslint */
const jsFrontend = {
  debug: false,
  current: {},

  // init, something like a constructor
  init: function () {
    jsFrontend.current.language = jsFrontend.data.get('LANGUAGE')

    // init stuff
    jsFrontend.initAjax()

    jsFrontend.addModalEvents()

    jsFrontend.cookieBar.init()

    // init consent dialog
    jsFrontend.consentDialog.init()

    // init controls
    jsFrontend.controls.init()

    // init form
    jsFrontend.forms.init()

    // init gravatar
    jsFrontend.gravatar.init()

    // init statistics
    jsFrontend.statistics.init()

    // init twitter
    jsFrontend.twitter.init()
  },

  addModalEvents: function () {
    const $modals = $('[role=dialog].modal')

    if ($modals.length === 0) {
      return
    }

    $modals.on('shown.bs.modal', function () {
      $(this).attr('aria-hidden', 'false')
    })
    $modals.on('hide.bs.modal', function () {
      $(this).attr('aria-hidden', 'true')
    })
  },

  // init
  initAjax: function () {
    // set defaults for AJAX
    $.ajaxSetup({
      url: '/frontend/ajax',
      cache: false,
      type: 'POST',
      dataType: 'json',
      timeout: 10000,
      data: {fork: {module: null, action: null, language: jsFrontend.current.language}}
    })
  }
}

/**
 * Controls related javascript
 */
jsFrontend.controls = {
  // init, something like a constructor
  init: function () {
    jsFrontend.controls.bindTargetBlank()
    jsFrontend.controls.toggleCollapse()
  },

  // bind target blank
  bindTargetBlank: function () {
    $('a.targetBlank').attr('target', '_blank').attr('rel', 'noopener noreferrer')
  },

  toggleCollapse: function () {
    const $navToggle = $('.navbar-toggle')

    if ($navToggle.length === 0) {
      return
    }

    $navToggle.on('click', function () {
      const $button = $(this)
      $button.find('[data-role=label]').text(jsFrontend.locale.lbl($button.hasClass('collapsed') ? 'CloseNavigation' : 'OpenNavigation'))
    }).find('[data-role=label]').text(jsFrontend.locale.lbl($navToggle.hasClass('collapsed') ? 'CloseNavigation' : 'OpenNavigation'))
  }
}

/**
 * Handles the cookieBar
 */
jsFrontend.cookieBar = {
  init: function () {
    // if there is no cookiebar we shouldn't do anything
    if ($('#cookie-bar').length === 0) return

    const $cookieBar = $('#cookie-bar')

    // @remark: as you can see we use PHP-serialized values so we can use them in PHP too.
    // hide the cookieBar if needed
    if (utils.cookies.readCookie('cookie_bar_hide') === 'b%3A1%3B') {
      $cookieBar.hide()
    }

    $cookieBar.on('click', '[data-role="cookie-bar-button"]', function (e) {
      e.preventDefault()

      if ($(e.currentTarget).data('action') === 'agree') {
        utils.cookies.setCookie('cookie_bar_agree', 'Y')
        utils.cookies.setCookie('cookie_bar_hide', 'Y')
      } else {
        utils.cookies.setCookie('cookie_bar_agree', 'N')
        utils.cookies.setCookie('cookie_bar_hide', 'Y')
      }
      $cookieBar.hide()
    })
  }
}

/**
 * Handles the privacy consent dialog
 */
jsFrontend.consentDialog = {
  _instance: function () {
    return bootstrap.Modal.getOrCreateInstance('*[data-role=privacy_consent_dialog]')
  },

  init: function () {
    // if there is no consentDialog we shouldn't do anything
    if ($('*[data-role=privacy_consent_dialog]').length === 0) return

    jsFrontend.consentDialog._pushConsentsToGoogleTagManager()

    const $consentDialog = $('*[data-role=privacy_consent_dialog]')
    const $consentForm = $('form[data-role=privacy_consent_dialog_form]')

    // show consent if needed
    if ($consentDialog.data('consentDialogShow')) {
      jsFrontend.consentDialog.show()
      }

    $consentForm.on('click', '*[data-bs-dismiss=modal]', function (e) {
      e.preventDefault()
      jsFrontend.consentDialog.hide()
    })

    $consentForm.on('submit', function (e) {
      e.preventDefault()

      const $levels = $consentForm.find('input[data-role=privacy-level]')
      for (const level of $levels) {
        const name = $(level).data('value')
        const isChecked = $(level).is(':checked')

        // store in jsData
        jsData.privacyConsent.visitorChoices[name] = isChecked

        // store data in functional cookies for later usage
        utils.cookies.setCookie('privacy_consent_level_' + name + '_granted', isChecked ? 1 : 0, 6 * 30)
        utils.cookies.setCookie('privacy_consent_hash', jsData.privacyConsent.levelsHash, 6 * 30)
      }

      $(document).trigger('privacyConsentChanged')

      jsFrontend.consentDialog._pushConsentsToGoogleTagManager()

      jsFrontend.consentDialog.hide()
    })
  },

  show: function () {
    jsFrontend.consentDialog._instance().show()

    // populate the fields with correct data
    for (const [choice, value] of Object.entries(jsData.privacyConsent.visitorChoices)) {
      $('input[data-role=privacy-level][data-value=' + choice + ']').prop('checked', value)
    }
  },

  hide: function () {
    jsFrontend.consentDialog._instance().hide()
  },

  _pushConsentsToGoogleTagManager: function () {
    // stop if gtag does not exist
    if (typeof gtag === 'undefined') {
      return
    }

    const consents = {}
    for (const [choice, value] of Object.entries(jsData.privacyConsent.visitorChoices)) {
      consents[choice] = (value) ? 'granted' : 'denied'
    }
    if (consents !== {}) {
      gtag('consent', 'update', consents)
      dataLayer.push({ event: 'consentUpdate' })
    }
  }
}

/**
 * Data related methods
 */
jsFrontend.data = {
  initialized: false,
  data: {},

  init: function () {
    // check if variable is available
    if (typeof jsData === 'undefined') throw new Error('jsData is not available')

    // populate
    jsFrontend.data.data = jsData
    jsFrontend.data.initialized = true
  },

  exists: function (key) {
    return (typeof jsFrontend.data.data[key] !== 'undefined')
  },

  get: function (key) {
    // init if needed
    if (!jsFrontend.data.initialized) jsFrontend.data.init()

    const keys = key.split('.')
    let data = jsFrontend.data.data
    for (let i = 0; i < keys.length; i++) {
      data = data[keys[i]]
    }

    return data
  }
}

/**
 * Facebook related
 */
jsFrontend.facebook = {
  // will be called after Facebook is initialized
  afterInit: function () {
    // is GA available?
    if (typeof _gaq === 'object') {
      // subscribe and track like
      FB.Event.subscribe('edge.create', function (targetUrl) {
        _gaq.push([
          '_trackSocial', 'facebook', 'like', targetUrl
        ])
      })

      // subscribe and track unlike
      FB.Event.subscribe('edge.remove', function (targetUrl) {
        _gaq.push([
          '_trackSocial', 'facebook', 'unlike', targetUrl
        ])
      })

      // subscribe and track message
      FB.Event.subscribe('message.send', function (targetUrl) {
        _gaq.push([
          '_trackSocial', 'facebook', 'send', targetUrl
        ])
      })
    } else if (typeof ga === 'object') {
      // subscribe and track like
      FB.Event.subscribe('edge.create', function (targetUrl) { ga('send', 'social', 'facebook', 'like', targetUrl) })

      // subscribe and track unlike
      FB.Event.subscribe('edge.remove', function (targetUrl) { ga('send', 'social', 'facebook', 'unlike', targetUrl) })

      // subscribe and track message
      FB.Event.subscribe('message.send', function (targetUrl) { ga('send', 'social', 'facebook', 'send', targetUrl) })
    }
  }
}

/**
 * Form related javascript
 */
jsFrontend.forms = {
  // init, something like a constructor
  init: function () {
    jsFrontend.forms.placeholders()
    jsFrontend.forms.datefields()
    jsFrontend.forms.validation()
    jsFrontend.forms.filled()
    jsFrontend.forms.datePicker()
    jsFrontend.forms.imagePreview()
    jsFrontend.forms.requiredTooltip()
  },

  requiredTooltip: function () {
    $(document).on('focus', '.form-control', function (event) {
      const id = $(event.currentTarget).attr('id')

      // show tooltip
      $('label[for="' + id + '"]').find('abbr').tooltip('show')

      // hide tooltip after 1 second
      setTimeout(function () {
        $('label[for="' + id + '"]').find('abbr').tooltip('hide')
      }, 1000)
    })
  },

  imagePreview: function () {
    $('input[type=file]').on('change', function () {
      const imageField = $(this).get(0)
      // make sure we are uploading an image by checking the data attribute
      if (imageField.getAttribute('data-fork-cms-role') === 'image-field' && imageField.files && imageField.files[0]) {
        // get the image preview by matching the image-preview data-id to the ImageField id
        const $imagePreview = $('[data-fork-cms-role="image-preview"][data-id="' + imageField.id + '"]')
        // use FileReader to get the url
        const reader = new FileReader()

        reader.onload = function (event) {
          $imagePreview.attr('src', event.target.result)
        }

        reader.readAsDataURL(imageField.files[0])
      }
    })
  },

  // once text has been filled add another class to it (so it's possible to style it differently)
  filled: function () {
    $(document).on('blur', 'form input, form textarea, form select', function () {
      if ($(this).val() === '') {
        $(this).removeClass('filled')
      } else {
        $(this).addClass('filled')
      }
    })
  },

  // initialize the date fields
  datefields: function () {
    // jQuery datapicker fallback for browsers that don't support the HTML5 date type
    const $inputDateType = $('input.inputDatefield')
    if ($inputDateType.length) {
      // the browser does not support the HTML5 data type
      if ($inputDateType.get(0).type !== 'date') {
        $inputDateType.addClass('inputDatefieldNormal')
      }
    }

    const $inputDatefields = $('.inputDatefieldNormal, .inputDatefieldFrom, .inputDatefieldTill, .inputDatefieldRange')
    const $inputDatefieldNormal = $('.inputDatefieldNormal')
    const $inputDatefieldFrom = $('.inputDatefieldFrom')
    const $inputDatefieldTill = $('.inputDatefieldTill')
    const $inputDatefieldRange = $('.inputDatefieldRange')

    if ($inputDatefields.length > 0) {
      const dayNames = [
        jsFrontend.locale.loc('DayLongSun'), jsFrontend.locale.loc('DayLongMon'), jsFrontend.locale.loc('DayLongTue'),
        jsFrontend.locale.loc('DayLongWed'), jsFrontend.locale.loc('DayLongThu'), jsFrontend.locale.loc('DayLongFri'),
        jsFrontend.locale.loc('DayLongSat')
      ]
      const dayNamesMin = [
        jsFrontend.locale.loc('DayShortSun'), jsFrontend.locale.loc('DayShortMon'),
        jsFrontend.locale.loc('DayShortTue'), jsFrontend.locale.loc('DayShortWed'),
        jsFrontend.locale.loc('DayShortThu'), jsFrontend.locale.loc('DayShortFri'),
        jsFrontend.locale.loc('DayShortSat')
      ]
      const dayNamesShort = [
        jsFrontend.locale.loc('DayShortSun'), jsFrontend.locale.loc('DayShortMon'),
        jsFrontend.locale.loc('DayShortTue'), jsFrontend.locale.loc('DayShortWed'),
        jsFrontend.locale.loc('DayShortThu'), jsFrontend.locale.loc('DayShortFri'),
        jsFrontend.locale.loc('DayShortSat')
      ]
      const monthNames = [
        jsFrontend.locale.loc('MonthLong1'), jsFrontend.locale.loc('MonthLong2'), jsFrontend.locale.loc('MonthLong3'),
        jsFrontend.locale.loc('MonthLong4'), jsFrontend.locale.loc('MonthLong5'), jsFrontend.locale.loc('MonthLong6'),
        jsFrontend.locale.loc('MonthLong7'), jsFrontend.locale.loc('MonthLong8'), jsFrontend.locale.loc('MonthLong9'),
        jsFrontend.locale.loc('MonthLong10'), jsFrontend.locale.loc('MonthLong11'),
        jsFrontend.locale.loc('MonthLong12')
      ]
      const monthNamesShort = [
        jsFrontend.locale.loc('MonthShort1'), jsFrontend.locale.loc('MonthShort2'),
        jsFrontend.locale.loc('MonthShort3'), jsFrontend.locale.loc('MonthShort4'),
        jsFrontend.locale.loc('MonthShort5'), jsFrontend.locale.loc('MonthShort6'),
        jsFrontend.locale.loc('MonthShort7'), jsFrontend.locale.loc('MonthShort8'),
        jsFrontend.locale.loc('MonthShort9'), jsFrontend.locale.loc('MonthShort10'),
        jsFrontend.locale.loc('MonthShort11'), jsFrontend.locale.loc('MonthShort12')
      ]

      if ($.isFunction($.fn.datepicker)) {
        $inputDatefieldNormal.each(function () {
          // Create a hidden clone (before datepicker init!), which will contain the actual value
          const clone = $(this).clone()
          clone.insertAfter(this)
          clone.hide()

          // Rename the original field, used to contain the display value
          $(this).attr('id', $(this).attr('id') + '-display')
          $(this).attr('name', $(this).attr('name') + '-display')

          // make sure we can make the value empty
          $(this).on('change', function (event) {
            if ($(this).val() === '') {
              clone.val('')
            }
          })
        })

        $inputDatefields.datepicker({
          dayNames: dayNames, // eslint-disable-line object-shorthand
          dayNamesMin: dayNamesMin, // eslint-disable-line object-shorthand
          dayNamesShort: dayNamesShort, // eslint-disable-line object-shorthand
          hideIfNoPrevNext: true,
          monthNames: monthNames, // eslint-disable-line object-shorthand
          monthNamesShort: monthNamesShort, // eslint-disable-line object-shorthand
          nextText: jsFrontend.locale.lbl('Next'),
          prevText: jsFrontend.locale.lbl('Previous'),
          showAnim: 'slideDown'
        })

        // the default, nothing special
        $inputDatefieldNormal.each(function () {
          // get data
          const data = $(this).data()
          const phpDate = new Date(data.year, data.month, data.day, 0, 0, 0) // Get date from php in YYYY-MM-DD format
          const value = ($(this).val() !== '') ? $.datepicker.formatDate(data.mask, phpDate) : '' // Convert the value to the data-mask to display it

          // Create the datepicker with the desired display format and alt field
          $(this).datepicker('option', {
            dateFormat: data.mask,
            firstDay: data.firstday,
            altField: '#' + $(this).attr('id').replace('-display', ''),
            altFormat: 'yy-mm-dd'
          }).datepicker('setDate', value)
        })

        // date fields that have a certain start date
        $inputDatefieldFrom.each(function () {
          // get data
          const data = $(this).data()
          const value = $(this).val()

          // set options
          $(this).datepicker('option', {
            dateFormat: data.mask,
            firstDay: data.firstday,
            minDate: new Date(parseInt(data.startdate.split('-')[0], 10), parseInt(data.startdate.split('-')[1], 10) - 1, parseInt(data.startdate.split('-')[2], 10))
          }).datepicker('setDate', value)
        })

        // date fields that have a certain enddate
        $inputDatefieldTill.each(function () {
          // get data
          const data = $(this).data()
          const value = $(this).val()

          // set options
          $(this).datepicker('option',
            {
              dateFormat: data.mask,
              firstDay: data.firstday,
              maxDate: new Date(parseInt(data.enddate.split('-')[0], 10), parseInt(data.enddate.split('-')[1], 10) - 1, parseInt(data.enddate.split('-')[2], 10))
            }).datepicker('setDate', value)
        })

        // date fields that have a certain range
        $inputDatefieldRange.each(function () {
          // get data
          const data = $(this).data()
          const value = $(this).val()

          // set options
          $(this).datepicker('option',
            {
              dateFormat: data.mask,
              firstDay: data.firstday,
              minDate: new Date(parseInt(data.startdate.split('-')[0], 10), parseInt(data.startdate.split('-')[1], 10) - 1, parseInt(data.startdate.split('-')[2], 10), 0, 0, 0, 0),
              maxDate: new Date(parseInt(data.enddate.split('-')[0], 10), parseInt(data.enddate.split('-')[1], 10) - 1, parseInt(data.enddate.split('-')[2], 10), 23, 59, 59)
            }).datepicker('setDate', value)
        })
      }
    }
  },

  validation: function () {
    $('input, textarea, select').each(function () {
      const $input = $(this)
      const options = {}

      // Check for custom error messages
      $.each($input.data(), function (key, value) {
        if (key.indexOf('error') < 0) return
        key = key.replace('error', '').toLowerCase()
        options[key] = value
      })

      $input.html5validation(options)
    })
  },

  // placeholder fallback for browsers that don't support placeholder
  placeholders: function () {
    // detect if placeholder-attribute is supported
    jQuery.support.placeholder = ('placeholder' in document.createElement('input'))

    if (!jQuery.support.placeholder) {
      // bind focus
      $('input[placeholder], textarea[placeholder]').on('focus', function () {
        // grab element
        const input = $(this)

        // only do something when the current value and the placeholder are the same
        if (input.val() === input.attr('placeholder')) {
          // clear
          input.val('')

          // remove class
          input.removeClass('placeholder')
        }
      })

      $('input[placeholder], textarea[placeholder]').on('blur', function () {
        // grab element
        const input = $(this)

        // only do something when the input is empty or the value is the same as the placeholder
        if (input.val() === '' || input.val() === input.attr('placeholder')) {
          // set placeholder
          input.val(input.attr('placeholder'))

          // add class
          input.addClass('placeholder')
        }
      })

      // call blur to initialize
      $('input[placeholder], textarea[placeholder]').blur()

      // hijack the form so placeholders aren't submitted as values
      $('input[placeholder], textarea[placeholder]').parents('form').submit(function () {
        // find elements with placeholders
        $(this).find('input[placeholder]').each(function () {
          // grab element
          const input = $(this)

          // if the value and the placeholder are the same reset the value
          if (input.val() === input.attr('placeholder')) input.val('')
        })
      })
    }
  },

  // Add date pickers to the appropriate input elements
  datePicker: function () {
    $('input[data-role="fork-datepicker"]').each(
      function (index, datePickerElement) {
        $(datePickerElement).datepicker()
      }
    )
  }
}

/**
 * Gravatar related javascript
 */
jsFrontend.gravatar = {
  // init, something like a constructor
  init: function () {
    $('.replaceWithGravatar').each(function () {
      const element = $(this)
      const gravatarId = element.data('gravatarId')
      const size = element.attr('height')

      // valid gravatar id
      if (gravatarId !== '') {
        // build url
        let url = 'https://www.gravatar.com/avatar/' + gravatarId + '?r=g&d=' + encodeURI(window.location.origin + '/src/Frontend/Core/Layout/images/default_author_avatar.gif')

        // add size if set before
        if (size !== '') url += '&s=' + size

        // create new image
        const gravatar = new Image()
        gravatar.src = url

        // reset src
        gravatar.onload = function () {
          element.attr('src', url).addClass('gravatarLoaded')
        }
      }
    })
  }
}

/**
 * Locale
 */
jsFrontend.locale = {
  initialized: false,
  initializing: false,
  data: {},

  // init, something like a constructor
  init: function () {
    if (typeof jsFrontend.current.language === 'undefined') {
      return
    }

    jsFrontend.locale.initializing = true

    $.ajax({
      url: '/src/Frontend/Cache/Locale/' + jsFrontend.current.language + '.json',
      type: 'GET',
      dataType: 'json',
      async: false,
      success: function (data) {
        jsFrontend.locale.data = data
        jsFrontend.locale.initialized = true
        jsFrontend.locale.initializing = true
      },
      error: function (jqXHR, textStatus, errorThrown) {
        throw new Error('Regenerate your locale-files.')
      }
    })
  },

  // get an item from the locale
  get: function (type, key) {
    // initialize if needed
    if (!jsFrontend.locale.initialized && !jsFrontend.locale.initializing) jsFrontend.locale.init()

    if (!jsFrontend.locale.initialized) {
      setTimeout(
        function () {
          return jsFrontend.locale.get(type, key)
        },
        30
      )

      return
    }

    // validate
    if (typeof jsFrontend.locale.data[type] === 'undefined' ||
      typeof jsFrontend.locale.data[type][key] === 'undefined') {
      return '{$' + type + key + '}'
    }

    return jsFrontend.locale.data[type][key]
  },

  // get an action
  act: function (key) {
    return jsFrontend.locale.get('act', key)
  },

  // get an error
  err: function (key) {
    return jsFrontend.locale.get('err', key)
  },

  // get a label
  lbl: function (key) {
    return jsFrontend.locale.get('lbl', key)
  },

  // get localization
  loc: function (key) {
    return jsFrontend.locale.get('loc', key)
  },

  // get a message
  msg: function (key) {
    return jsFrontend.locale.get('msg', key)
  }
}

/**
 * Google analytics related javascript
 */
jsFrontend.statistics = {
  // init, something like a constructor
  init: function () {
    jsFrontend.statistics.trackOutboundLinks()
  },

  // track all outbound links
  trackOutboundLinks: function () {
    // check if Google Analytics is available
    if (typeof _gaq === 'object' || typeof ga === 'function') {
      // create a new selector
      $.expr[':'].external = function (obj) {
        return (typeof obj.href !== 'undefined') && (obj.hostname !== window.location.hostname)
      }

      // bind on all links that don't have the class noTracking
      $(document).on('click', 'a:external:not(.noTracking)', function (e) {
        // only simulate direct links
        const hasTarget = (typeof $(this).attr('target') !== 'undefined')
        if (!hasTarget) e.preventDefault()

        const link = $(this).attr('href')

        // outbound link by default
        let type = 'Outbound Links'
        let pageView = '/Outbound Links/' + link

        // set mailto
        if (link.match(/^mailto:/)) {
          type = 'Mailto'
          pageView = '/Mailto/' + link.substring(7)
        }

        // set anchor
        if (link.match(/^#/)) {
          type = 'Anchors'
          pageView = '/Anchor/' + link.substring(1)
        }

        // track in Google Analytics
        if (typeof _gaq === 'object') {
          _gaq.push(['_trackEvent', type, pageView])
        } else {
          ga('send', 'event', type, pageView)
        }

        // set time out
        if (!hasTarget) setTimeout(function () { document.location.href = link }, 100)
      })
    }
  }
}

/**
 * Twitter related stuff
 */
jsFrontend.twitter = {
  init: function () {
    // if GA is integrated and a tweet button is used
    if (typeof twttr === 'object' && (typeof _gaq === 'object' || typeof ga === 'object')) {
      // bind event, so we can track the tweets
      twttr.events.on('tweet', function (e) {
        // valid event?
        if (e) {
          let targetUrl = null

          // get url
          if (e.target && e.target.nodeName === 'IFRAME') targetUrl = utils.url.extractParamFromUri(e.target.src, 'url')

          // push to GA
          if (typeof _gaq === 'object') {
            _gaq.push(['_trackSocial', 'twitter', 'tweet', targetUrl])
          } else {
            ga('send', 'social', 'twitter', 'tweet', targetUrl)
          }
        }
      })
    }
  }
}

$(jsFrontend.init)
