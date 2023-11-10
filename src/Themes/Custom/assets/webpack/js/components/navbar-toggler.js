export default function NavbarToggler () {
  const toggler = document.querySelector('.navbar-toggler')
  toggler.addEventListener('click', function () {
    document.querySelector('body').classList.toggle('nav-expanded')
  })
}
