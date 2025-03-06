import { library, dom } from '@fortawesome/fontawesome-svg-core'

// Select icons to import from the corresponding library
// Solid icons
import { faFilter } from '@fortawesome/free-solid-svg-icons'

// Regular icons
// Import { faEnvelope } from '@fortawesome/free-regular-svg-icons'

// Brand icons
// Import { faFacebook } from '@fortawesome/free-brands-svg-icons'

// Add icons to the library to make them available in the frontend
library.add(faFilter)

dom.i2svg()
dom.watch()
