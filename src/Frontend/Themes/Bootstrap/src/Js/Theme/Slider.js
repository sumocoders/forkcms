import Splide from '@splidejs/splide';

export class Slider {
  constructor ($element) {
    this.element = $element
    this.initSlider()
  }

  initSlider () {
    document.querySelectorAll('.splide').forEach((element) => {
      new Splide(element, {
        type   : 'loop',
        perPage: 3,
        gap: '1rem'
      }).mount()
    })
  }
}
