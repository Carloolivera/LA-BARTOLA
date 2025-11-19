// Carrito en memoria
let cart = {};

// Toggle de categorías
function toggleCategory(header) {
  const content = header.nextElementSibling;
  header.classList.toggle('collapsed');
  content.classList.toggle('collapsed');
}

// Agregar al carrito
function addToCart(platoId, platoNombre, platoPrecio, stock) {
  const addBtn = document.getElementById(`add-btn-${platoId}`);
  const controls = document.getElementById(`controls-${platoId}`);

  // Ocultar botón de agregar y mostrar controles
  addBtn.classList.add('hidden');
  controls.classList.add('active');

  // Inicializar en carrito si no existe
  if (!cart[platoId]) {
    cart[platoId] = {
      nombre: platoNombre,
      precio: platoPrecio,
      cantidad: 0,
      stock: stock
    };
  }

  // Incrementar cantidad
  changeQuantity(platoId, 1);
}

// Cambiar cantidad
function changeQuantity(platoId, delta) {
  if (!cart[platoId]) return;

  const controls = document.getElementById(`controls-${platoId}`);
  const stock = parseInt(controls.dataset.stock);
  const nuevaCantidad = cart[platoId].cantidad + delta;

  // Validar stock
  if (delta > 0 && nuevaCantidad > stock) {
    // Mostrar mensaje de stock insuficiente
    const qtyDisplay = document.getElementById(`qty-${platoId}`);
    qtyDisplay.style.color = '#dc3545';
    qtyDisplay.textContent = 'Max: ' + stock;

    setTimeout(() => {
      qtyDisplay.style.color = '';
      qtyDisplay.textContent = cart[platoId].cantidad;
    }, 1500);

    return; // No permitir agregar más
  }

  cart[platoId].cantidad = nuevaCantidad;

  if (cart[platoId].cantidad <= 0) {
    delete cart[platoId];

    // Mostrar botón de agregar y ocultar controles
    const addBtn = document.getElementById(`add-btn-${platoId}`);
    const controls = document.getElementById(`controls-${platoId}`);

    addBtn.classList.remove('hidden');
    controls.classList.remove('active');
  }

  updateCartDisplay();
}

// Actualizar visualización del carrito
function updateCartDisplay() {
  const cartFloat = document.getElementById('cartFloat');
  const cartCount = document.getElementById('cartCount');
  const cartTotal = document.getElementById('cartTotal');

  let totalItems = 0;
  let totalPrice = 0;

  Object.keys(cart).forEach(platoId => {
    const item = cart[platoId];
    totalItems += item.cantidad;
    totalPrice += item.precio * item.cantidad;

    const qtyDisplay = document.getElementById(`qty-${platoId}`);
    if (qtyDisplay) {
      qtyDisplay.textContent = item.cantidad;
    }
  });

  if (totalItems > 0) {
    cartFloat.style.display = 'flex';
    cartCount.textContent = totalItems;
    cartTotal.textContent = '$' + totalPrice.toLocaleString('es-AR');
  } else {
    cartFloat.style.display = 'none';
  }
}

// Ir al carrito
async function goToCart() {
  const carritoUrl = document.getElementById('cartFloat').dataset.carritoUrl;
  const agregarUrl = document.getElementById('cartFloat').dataset.agregarUrl;

  // Mostrar loading
  const cartFloat = document.getElementById('cartFloat');
  cartFloat.style.opacity = '0.5';
  cartFloat.style.pointerEvents = 'none';

  try {
    // Agregar todos los productos al carrito del servidor
    for (const platoId in cart) {
      const item = cart[platoId];

      const formData = new FormData();
      formData.append('plato_id', platoId);
      formData.append('cantidad', item.cantidad);
      formData.append('notas', '');

      const response = await fetch(agregarUrl, {
        method: 'POST',
        body: formData
      });

      const data = await response.json();
    }

    // Redirigir al carrito
    window.location.href = carritoUrl;
  } catch (error) {
    cartFloat.style.opacity = '1';
    cartFloat.style.pointerEvents = 'auto';
  }
}

// Buscador
function initSearch() {
  const searchInput = document.getElementById('searchInput');
  const clearSearch = document.getElementById('clearSearch');

  searchInput.addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase().trim();

    if (searchTerm) {
      clearSearch.style.display = 'block';
    } else {
      clearSearch.style.display = 'none';
    }

    const platoItems = document.querySelectorAll('.plato-item');
    let hasResults = false;

    // Dividir el término de búsqueda en palabras individuales
    const searchWords = searchTerm.split(/\s+/).filter(word => word.length > 0);

    platoItems.forEach(item => {
      const name = item.getAttribute('data-name');
      const desc = item.getAttribute('data-desc');
      const fullText = `${name} ${desc}`;

      // Verificar si todas las palabras de búsqueda están presentes (en cualquier orden)
      const matchesAll = searchWords.every(word => fullText.includes(word));

      if (matchesAll) {
        item.style.display = 'flex';
        hasResults = true;
      } else {
        item.style.display = 'none';
      }
    });

    // Mostrar/ocultar categorías vacías
    document.querySelectorAll('.category-section').forEach(section => {
      const visibleItems = section.querySelectorAll('.plato-item[style="display: flex;"]');
      if (visibleItems.length === 0 && searchTerm) {
        section.style.display = 'none';
      } else {
        section.style.display = 'block';
      }
    });
  });

  clearSearch.addEventListener('click', function() {
    searchInput.value = '';
    clearSearch.style.display = 'none';

    document.querySelectorAll('.plato-item').forEach(item => {
      item.style.display = 'flex';
    });

    document.querySelectorAll('.category-section').forEach(section => {
      section.style.display = 'block';
    });
  });
}

// Acceso admin discreto (5 clicks en el logo redirige a caja chica)
function initAdminAccess() {
  let adminClicks = 0;
  let adminClickTimer = null;
  const adminLogo = document.getElementById('adminLogo');
  const cajaChicaUrl = adminLogo.dataset.cajaChicaUrl;

  adminLogo.addEventListener('click', function() {
    adminClicks++;

    if (adminClicks === 5) {
      window.location.href = cajaChicaUrl;
      adminClicks = 0;
      clearTimeout(adminClickTimer);
    } else {
      clearTimeout(adminClickTimer);
      adminClickTimer = setTimeout(() => {
        adminClicks = 0;
      }, 2000);
    }
  });
}

// Cargar carrito de la sesión al iniciar
function initCarrito(carritoServidor) {
  if (Object.keys(carritoServidor).length > 0) {
    // Convertir el carrito del servidor al formato local
    Object.keys(carritoServidor).forEach(platoId => {
      const item = carritoServidor[platoId];
      cart[platoId] = {
        nombre: item.nombre,
        precio: item.precio,
        cantidad: item.cantidad
      };

      // Actualizar la UI para mostrar los controles
      const addBtn = document.getElementById(`add-btn-${platoId}`);
      const controls = document.getElementById(`controls-${platoId}`);
      const qtyDisplay = document.getElementById(`qty-${platoId}`);

      if (addBtn && controls && qtyDisplay) {
        addBtn.classList.add('hidden');
        controls.classList.add('active');
        qtyDisplay.textContent = item.cantidad;
      }
    });
  }

  updateCartDisplay();
}

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
  initSearch();
  initAdminAccess();

  // El carrito se inicializa desde home.php con initCarrito()
});
