<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<section class="py-5" style="background-color: #000; min-height: 80vh;">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-5">
      <h1 style="color: #D4B68A;">Gestión de Stock</h1>
      <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalAgregarPlato">
        <i class="bi bi-plus-circle"></i> Agregar Plato
      </button>
    </div>

    <div class="row g-4">
      <?php if (empty($platos)): ?>
        <div class="col-12">
          <p class="text-center text-muted">No hay platos registrados</p>
        </div>
      <?php else: ?>
        <?php foreach ($platos as $plato): ?>
          <div class="col-md-6 col-lg-4">
            <div class="card bg-dark text-light h-100">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                  <h5 class="card-title" style="color: #D4B68A;"><?= $plato['nombre'] ?></h5>
                  <span class="badge bg-<?= $plato['activo'] ? 'success' : 'danger' ?>">
                    <?= $plato['activo'] ? 'Activo' : 'Inactivo' ?>
                  </span>
                </div>
                <p class="card-text small"><?= $plato['descripcion'] ?></p>
                <p class="card-text"><strong>Precio:</strong> $<?= number_format($plato['precio'], 2) ?></p>
                <div class="d-flex gap-2">
                  <button class="btn btn-sm btn-outline-warning" 
                          onclick="editarPlato(<?= htmlspecialchars(json_encode($plato)) ?>)">
                    <i class="bi bi-pencil"></i> Editar
                  </button>
                  <button class="btn btn-sm btn-outline-danger" 
                          onclick="eliminarPlato(<?= $plato['id'] ?>)">
                    <i class="bi bi-trash"></i> Eliminar
                  </button>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<div class="modal fade" id="modalAgregarPlato" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header" style="border-color: #d4af37;">
        <h5 class="modal-title" style="color: #D4B68A;">Agregar Plato</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="<?= site_url('admin/agregar-plato') ?>" method="post">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control bg-dark text-light" name="nombre" required style="border-color: #d4af37;">
          </div>
          <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea class="form-control bg-dark text-light" name="descripcion" rows="3" required style="border-color: #d4af37;"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Precio</label>
            <input type="number" step="0.01" class="form-control bg-dark text-light" name="precio" required style="border-color: #d4af37;">
          </div>
          <div class="mb-3">
            <label class="form-label">URL Imagen</label>
            <input type="text" class="form-control bg-dark text-light" name="imagen" style="border-color: #d4af37;">
          </div>
        </div>
        <div class="modal-footer" style="border-color: #d4af37;">
          <button type="button" class="btn btn-outline-warning" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-warning">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modalEditarPlato" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header" style="border-color: #d4af37;">
        <h5 class="modal-title" style="color: #D4B68A;">Editar Plato</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="formEditarPlato" method="post">
        <div class="modal-body">
          <input type="hidden" name="id" id="edit_id">
          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control bg-dark text-light" name="nombre" id="edit_nombre" required style="border-color: #d4af37;">
          </div>
          <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea class="form-control bg-dark text-light" name="descripcion" id="edit_descripcion" rows="3" required style="border-color: #d4af37;"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Precio</label>
            <input type="number" step="0.01" class="form-control bg-dark text-light" name="precio" id="edit_precio" required style="border-color: #d4af37;">
          </div>
          <div class="mb-3">
            <label class="form-label">URL Imagen</label>
            <input type="text" class="form-control bg-dark text-light" name="imagen" id="edit_imagen" style="border-color: #d4af37;">
          </div>
          <div class="mb-3">
            <label class="form-label">Estado</label>
            <select class="form-select bg-dark text-light" name="activo" id="edit_activo" style="border-color: #d4af37;">
              <option value="1">Activo</option>
              <option value="0">Inactivo</option>
            </select>
          </div>
        </div>
        <div class="modal-footer" style="border-color: #d4af37;">
          <button type="button" class="btn btn-outline-warning" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-warning">Actualizar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function editarPlato(plato) {
  document.getElementById('edit_id').value = plato.id;
  document.getElementById('edit_nombre').value = plato.nombre;
  document.getElementById('edit_descripcion').value = plato.descripcion;
  document.getElementById('edit_precio').value = plato.precio;
  document.getElementById('edit_imagen').value = plato.imagen || '';
  document.getElementById('edit_activo').value = plato.activo;
  
  document.getElementById('formEditarPlato').action = '<?= site_url('admin/editar-plato/') ?>' + plato.id;
  
  new bootstrap.Modal(document.getElementById('modalEditarPlato')).show();
}

function eliminarPlato(id) {
  if (confirm('¿Estás seguro de eliminar este plato?')) {
    window.location.href = '<?= site_url('admin/eliminar-plato/') ?>' + id;
  }
}
</script>

<?= $this->endSection() ?>