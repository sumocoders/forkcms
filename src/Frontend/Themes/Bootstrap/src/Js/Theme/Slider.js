import Splide from '@splidejs/splide';

export class Slider {
  constructor ($element) {
    this.element = $element
    this.initSlider()
  }

  initSlider () {
    new Splide( '.splide', {
      type   : 'loop',
      perPage: 3,
      gap: '1rem'
    }).mount()
  }
}
