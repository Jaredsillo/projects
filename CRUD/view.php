<?php
include('../conexion.php');
include('funciones.php');
// leer materias
$materias = leerMaterias($conexion);
// agregar materias
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['accion'])) {
    $mensaje = crearMaterias($conexion, $_POST);
    header("Location: view.php?msg=" . urlencode(json_encode($mensaje)));
    exit;
}
// actualizar materias
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'editar') {
    $mensaje = actualizarMaterias($conexion, $_POST);
    header("Location: view.php?msg=" . urlencode(json_encode($mensaje)));
    exit;
}
// Eliminar 
if (isset($_GET['eliminar'])) {
    $clave = $_GET['eliminar'];
    $mensaje = eliminarMateria($conexion, $clave);
    header("Location: view.php?msg=" . urlencode(json_encode($mensaje)));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>MCG</title>
</head>
<body>
    <body class="container py-4">
        <h2>Carrera, Materia, Grupos</h2>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#crearMaterias">
            <i class="bi bi-person-add"></i> Agregar Materias
        </button>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Clave M</th>
                    <th>Materia</th>
                    <th>Id Grupos</th>
                    <th>Carrera</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($materias as $materia): ?>
                    <tr>
                        <td><?= htmlspecialchars($materia['materiaC']); ?></td>
                        <td><?= htmlspecialchars($materia['materiaN']); ?></td>
                        <td><?= htmlspecialchars($materia['grupo']); ?></td>
                        <td><?= htmlspecialchars($materia['carrera']); ?></td>
                        <td>
                            <button class="btn btn-success btn-sm btnEditar"
                                data-bs-toggle="modal" data-bs-target="#actualizarMaterias"
                                data-clave="<?= $materia['materiaC'] ?>"
                                data-nombre="<?= $materia['materiaN'] ?>"
                                data-semestre="<?= $materia['semestre'] ?>"
                                data-carrera="<?= $materia['claveC'] ?>"
">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <a href="#" class="btn btn-danger btn-sm btnEliminar"
                                data-id="<?= $materia['materiaC'] ?>">
                                <i class="bi bi-trash3"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Modal agregar -->
        <div class="modal fade" id="crearMaterias" tabindex="-1" aria-labelledby="crearMateriasLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="crearMateriasLabel">Agregar Materia</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="clave" class="form-label">clave</label>
                                <input type="text" class="form-control" id="clave" name="clave" required>
                            </div>
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="semestre" class="form-label">Semestre</label>
                                <select class="form-select" id="semestre" name="semestre">
                                    <option value="">Seleccione..</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="claveC" class="form-label">Carrera</label>
                                <select class="form-select" id="claveC" name="claveC" required>
                                    <option value="">Seleccione..</option>
                                    <?php
                                    $carreras = $conexion->query("SELECT clave, nombre FROM CARRERAS");
                                    foreach ($carreras as $carrera): ?>
                                        <option value="<?= htmlspecialchars($carrera['clave']) ?>">
                                            <?= htmlspecialchars($carrera['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal editar -->
        <div class="modal fade" id="actualizarMaterias" tabindex="-1" aria-labelledby="actualizarMateriasLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="">
                    <input type="hidden" name="accion" value="editar">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="actualizarMateriasLabel">Editar Materia</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Clave</label>
                                <input type="text" class="form-control" id="editClave" name="clave" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="editNombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Semestre</label>
                                <select class="form-select" id="editSemestre" name="semestre" required>
                                    <option value="">Seleccione..</option>
                                    <?php for ($i = 1; $i <= 8; $i++): ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Carrera</label>
                                <select class="form-select" id="editCarrera" name="claveC" required>
                                    <option value="">Seleccione..</option>
                                    <?php
                                    $carreras = $conexion->query("SELECT clave, nombre FROM CARRERAS");
                                    foreach ($carreras as $carrera): ?>
                                        <option value="<?= $carrera['clave'] ?>"><?= $carrera['nombre'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const editarModal = document.getElementById('actualizarMaterias');
                editarModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const clave = button.getAttribute('data-clave');
                    const nombre = button.getAttribute('data-nombre');
                    const semestre = button.getAttribute('data-semestre');
                    const carrera = button.getAttribute('data-carrera');
                    document.getElementById('editClave').value = clave;
                    document.getElementById('editNombre').value = nombre;
                    document.getElementById('editSemestre').value = semestre;
                    document.getElementById('editCarrera').value = carrera;

                });
            });
        </script>
        <script>
            document.querySelectorAll('.btnEliminar').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const clave = this.dataset.id;
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Esta acción no se puede deshacer",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = `view.php?eliminar=${clave}`;
                        }
                    });
                });
            });
        </script>
        <?php if (isset($_GET['msg'])): ?>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const msg = <?= $_GET['msg']; ?>;
                    Swal.fire({
                        title: msg[0],
                        text: msg[1],
                        icon: msg[2],
                        confirmButtonText: 'Aceptar'
                    });
                    if (window.history.replaceState) {
                        const url = new URL(window.location);
                        url.searchParams.delete('msg');
                        window.history.replaceState({}, document.title, url);
                    }
                });
            </script>
        <?php endif; ?>
    </body>
</body>
</html>