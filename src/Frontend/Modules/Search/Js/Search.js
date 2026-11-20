/**
 * Interaction for the search module
 */
/* global TomSelect */

jsFrontend.search =
{
  // init, something like a constructor
  init: function () {
    // auto suggest (search widget)
    if ($('input[data-role=fork-widget-search-field]').length > 0) jsFrontend.search.autosuggest(55)

    // autocomplete (search results page: autocomplete based on known search terms)
    var autocomplete = null
    if ($('input[data-role=fork-search-field][data-autocomplete=enabled]').length > 0) autocomplete = jsFrontend.search.autocomplete()

    // live suggest (search results page: live feed of matches)
    if ($('input[data-role=fork-search-field][data-live-suggest=enabled]').length > 0 && $('[data-role="search-results-container"]').length > 0) {
      // Tom Select hides the original input, so listen to the text typed in its control instead
      if (autocomplete !== null) {
        autocomplete.on('type', function (term) {
          // when the field loses focus Tom Select empties the text and keeps the term as the selected item
          jsFrontend.search.livesuggest(term === '' ? autocomplete.getValue() : term)
        })
      } else {
        $('input[data-role=fork-search-field][data-live-suggest=enabled]').on('keyup', function () {
          jsFrontend.search.livesuggest($(this).val())
        })
      }
    }
  },

  // autocomplete (search results page: autocomplete based on known search terms)
  autocomplete: function () {
    var input = $('input[data-role=fork-search-field][data-autocomplete=enabled]')[0]

    return jsFrontend.search.tomSelect(input, 'Autocomplete', null, function (result) {
      return {value: result.url, text: result.term}
    })
  },

  // auto suggest (search widget)
  autosuggest: function (length) {
    // set default values
    if (typeof length === 'undefined') length = 100

    $('input[data-role=fork-widget-search-field]').each(function () {
      jsFrontend.search.tomSelect(this, 'Autosuggest', length, function (result) {
        return {value: result.full_url, text: result.title, description: result.text}
      })
    })
  },

  // livesuggest (search results page: live feed of matches)
  livesuggest: function (term) {
    var $searchContainer = $('*[data-role=search-results-container]')

    // nothing changed since the last term
    if (term === jsFrontend.search.livesuggestTerm) return

    // remember the latest term, a call that is still running asks for it when it is done
    jsFrontend.search.livesuggestTerm = term

    // make sure we're allowed to do the call (= previous call is no longer processing)
    if (!jsFrontend.search.allowLivesuggestCall) return

    // an empty term has no results, the server answers it with a 400
    if (term === '') {
      $searchContainer.html('')

      return
    }

    // temporarily allow no more calls
    jsFrontend.search.allowLivesuggestCall = false

    // fade out
    $searchContainer.fadeTo(0, 0.5)

    // ajax call!
    $.ajax({
      data: {
        fork: {module: 'Search', action: 'Livesuggest'},
        term: term
      },
      success: function (data, textStatus) {
        // alert the user
        if (data.code !== 200 && jsFrontend.debug) { window.alert(data.message) }

        if (data.code === 200) {
          // replace search results
          $searchContainer.html(utils.string.html5(data.data))

          // fade in
          $searchContainer.fadeTo(0, 1)
        }
      },
      error: function () {
        // replace search results
        $searchContainer.html('')

        // fade in
        $searchContainer.fadeTo(0, 1)
      },
      complete: function () {
        // allow for new calls
        jsFrontend.search.allowLivesuggestCall = true

        // the term changed while this call was running
        var latestTerm = jsFrontend.search.livesuggestTerm
        if (latestTerm !== term) {
          jsFrontend.search.livesuggestTerm = null
          jsFrontend.search.livesuggest(latestTerm)
        }
      }
    })
  },

  allowLivesuggestCall: true,
  livesuggestTerm: null,

  // turn a search field into a Tom Select that suggests the results of the given ajax action
  tomSelect: function (input, action, length, mapResult) {
    return new TomSelect(input, {
      maxItems: 1,
      // a text input can't hold a newline, so a typed term never gets split into several items
      delimiter: '\n',
      // the typed term is an item too, Enter or a click on it searches for it
      create: function (term) {
        return {value: term, text: term}
      },
      // keep the typed term when the field loses focus, so the submit button searches for it
      createOnBlur: true,
      addPrecedence: true,
      persist: false,
      hideSelected: true,
      // the server already filters the suggestions
      searchField: [],
      score: function () {
        return function () {
          return 1
        }
      },
      load: function (term, callback) {
        var tomSelect = this

        $.ajax({
          data: {
            fork: {module: 'Search', action: action, language: jsFrontend.current.language},
            term: term,
            length: length
          },
          success: function (json) {
            // a slower response for an earlier term would replace the suggestions of the current one,
            // drop it and let that term load again when it is typed again
            if (term !== tomSelect.inputValue()) {
              delete tomSelect.loadedSearches[term]
              callback()

              return
            }

            tomSelect.clearOptions()
            callback($.map(json.data, function (result) {
              return $.extend(mapResult(result), {isSuggestion: true})
            }))
          },
          error: function () {
            callback()
          }
        })
      },
      render: {
        option: function (data, escape) {
          var description = data.description ? '<p>' + escape(data.description) + '</p>' : ''

          return '<div><strong>' + escape(data.text) + '</strong>' + description + '</div>'
        },
        option_create: function (data, escape) {
          return '<div class="create">' + escape(utils.string.ucfirst(jsFrontend.locale.lbl('Search'))) + ' <strong>' + escape(data.input) + '</strong>&hellip;</div>'
        },
        no_results: null
      },
      // Tom Select shows the term as an item next to the typed text, put it back in the text so it can be edited
      onFocus: function () {
        var term = this.getValue()

        if (term === '') return

        var text = this.options[term].text
        this.removeOption(term, true)
        this.setTextboxValue(text)
      },
      onChange: function (value) {
        // the item was created on blur, wait for the submit button
        if (value === '' || !this.isFocused) return

        if (this.options[value].isSuggestion) {
          window.location.href = value

          return
        }

        input.form.requestSubmit()
      },
      // when we have been typing in the search textfield and we blur out of it, we're ready to save it
      onBlur: function () {
        var term = this.getValue()

        if (term === '' || this.options[term].isSuggestion) return

        $.ajax({
          data: {
            fork: {module: 'Search', action: 'Save'},
            term: term
          }
        })
      }
    })
  }
}

$(jsFrontend.search.init)
