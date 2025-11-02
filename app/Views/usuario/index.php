<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<section class="py-5 text-center text-light bg-black">
  <div class="container">
    <h1 class="fw

-bold mb-4 text-warning">
      <i class="bi bi-people"></i> Gestión de Usuarios
    </h1>
    <p class="text-beige">Administra los usuarios del sistema</p>
  </div>
</section>

<section class="py-5 bg-dark text-light">
  <div class="container">
    <?php if (session('success')): ?>
      <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i><?= session('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <?php if (session('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle me-2"></i><?= session('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <div class="mb-4">
      <a href="<?= site_url('usuario/crear') ?>" class="btn btn-warning">
        <i class="bi bi-plus-circle"></i> Nuevo Usuario
      </a>
    </div>

    <div

 class="card bg-black text-light border-warning">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-dark table-hover">
            <thead class="text-warning">
              <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Grupo</th>
                <th>Estado</th>
                <th>Fecha Registro</th>
                <th class="text-center">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($usuarios)): ?>
                <?php foreach ($usuarios as $u): ?>
                  <tr>
                    <td><?= $u['id'] ?></td>
                    <td>
                      <i class="bi bi-person-circle me-2"></i>
                      <?= esc($u['username']) ?>
                    </td>
                    <td>
                      <?php
                        $badgeClass = 'secondary';
                        if ($u['group'] == 'admin') $badgeClass = 'danger

';
                        elseif ($u['group'] == 'vendedor') $badgeClass = 'info';
                        elseif ($u['group'] == 'cliente') $badgeClass = 'success';
                      ?>
                      <span class="badge bg-<?= $badgeClass ?>">
                        <?= ucfirst($u['group'] ?? 'Sin grupo') ?>
                      </span>
                    </td>
                    <td>
                      <button class="btn btn-sm <?= $u['active'] ? 'btn-success' : 'btn-secondary' ?>" 
                              onclick="toggleEstado(<?= $u['id'] ?>, this)">
                        <i class="bi bi-<?= $u['active'] ? 'check-circle' : 'x-circle' ?>"></i>
                        <?= $u['active'] ? 'Activo' : 'Inactivo' ?>
                      </button>
                    </td>
                    <td><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
                    <td class="text-center">
                      <a href="<?= site_url('usuario/editar/' . $u['id']) ?>" 


                         class="btn btn-sm btn-outline-warning me-1">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <?php if ($u['id'] != auth()->id()): ?>
                        <button onclick="eliminarUsuario(<?= $u['id'] ?>)" 
                                class="btn btn-sm btn-outline-danger">
                          <i class="bi bi-trash"></i>
                        </button>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-center py-4">
                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                    <p class="mt-2 text-muted">No hay usuarios registrados</p>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>


</section>

<script>
function toggleEstado(id, button) {
  fetch('<?= site_url('usuario/toggleEstado') ?>/' + id, {
    method: 'POST',
    headers: {
      'X-Requested-With': 'XMLHttpRequest'
    }
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      if (data.active == 1) {
        button.className = 'btn btn-sm btn-success';
        button.innerHTML = '<i class="bi bi-check-circle"></i> Activo';
      } else {
        button.className = 'btn btn-sm btn-secondary';
        button.innerHTML = '<i class="bi bi-x-circle"></i> Inactivo';
      }
    } else {
      alert(data.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Error al cambiar el estado');
  });
}

function eliminarUsuario(id) {
  if (!confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.')) {
    return;
  }
  
  window.location.href = '<?= site_url('usuario/eliminar') ?>/' + id;
}
</script>

<?= $this->endSection() ?>