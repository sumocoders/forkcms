/* Bootstrap imports */
import Bootstrap from 'bootstrap-sass/assets/javascripts/bootstrap'

/* Utilities imports */
import SweetScroll from 'sweet-scroll'
import { Resize } from './Utilities/Resize'
/* import {Fancybox} from './Utilities/Fancybox' */

/* Theme imports */
/* eg. import tooltip from './Theme/Tooltip' */

/* Renders */
window.sweetscroll = new SweetScroll()
window.resizeFunction = new Resize()

window.resizeFunction.resize()
