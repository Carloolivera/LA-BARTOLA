document.addEventListener('DOMContentLoaded', function() {
  actualizarContadorCarrito();
});

function actualizarContadorCarrito() {
  const cartCountUrl = document.body.dataset.cartCountUrl;
  if (!cartCountUrl) return;

  fetch(cartCountUrl)
    .then(response => response.json())
    .then(data => {
      const cartCount = document.getElementById('cart-count');
      if (cartCount) {
        cartCount.textContent = data.cart_count;
      }
    })
    .catch(error => console.error('Error:', error));
}

// Script para 5 clicks en el logo redirige a caja chica
(function() {
  let clickCount = 0;
  let clickTimer = null;
  const logoLink = document.getElementById('logo-link');

  if (logoLink) {
    const cajaChicaUrl = logoLink.dataset.cajaChicaUrl;
    const homeUrl = logoLink.href;

    logoLink.addEventListener('click', function(e) {
      e.preventDefault();
      clickCount++;

      if (clickCount >= 5) {
        window.location.href = cajaChicaUrl;
        clickCount = 0;
        clearTimeout(clickTimer);
        return false;
      }

      clearTimeout(clickTimer);
      clickTimer = setTimeout(function() {
        if (clickCount < 5) {
          window.location.href = homeUrl;
        }
        clickCount = 0;
      }, 800);
    });
  }
})();
