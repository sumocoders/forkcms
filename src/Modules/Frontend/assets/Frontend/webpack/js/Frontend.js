// import plugins
import 'bootstrap'

// component imports
import { Components } from './_Components'
import TogglePasswordInputType from '../../../../../../Core/assets/js/Components/TogglePasswordInputType'

export class Frontend {
  initFrontend () {
    this.components = new Components()
    this.components.initComponents()

    Frontend.initToggleSecrets()
  }

  static initToggleSecrets () {
    $('[data-role="toggle-visibility"]').each((index, element) => {
      element.toggleSecret = new TogglePasswordInputType(element)
    })
  }
}

$(window).on('load', () => {
  window.frontend = new Frontend()
  window.frontend.initFrontend()
})
