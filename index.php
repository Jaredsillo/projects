<?php
include('conexion.php');
include('funcionesCrud.php');

$mensaje = null;
// Leer alumnos
$alumnos = obtenerAlumnos($conexion);

//Agregar alumno
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['accion'])) {
    $mensaje = agregarAlumno($conexion, $_POST);
    header("Location: index.php?msg=" . urlencode(json_encode($mensaje)));
    exit;
}
// Editar alumno
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'editar') {
    $mensaje = actualizarAlumno($conexion, $_POST);
    header("Location: index.php?msg=" . urlencode(json_encode($mensaje)));
    exit;
}
//Eliminar alumno
if (isset($_GET['eliminar'])) {
    $mensaje = eliminarAlumno($conexion, $_GET['eliminar']);
    header("Location: index.php?msg=" . urlencode(json_encode($mensaje))); 
    exit;
}
// Mostrar mensaje si existe en la URL
if (isset($_GET['msg'])) {
    $mensaje = json_decode($_GET['msg'], true);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>CRUD Alumnos</title>
</head>
<body class="container py-4">
    <h2>CRUD Alumnos</h2>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#agregarAlumno">
        <i class="bi bi-person-add"></i> Agregar Alumno
    </button>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Matrícula</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Género</th>
                <th>Estatus</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alumnos as $alumno): ?>
                <tr>
                    <td><?= htmlspecialchars($alumno['matricula']); ?></td>
                    <td><?= htmlspecialchars($alumno['nombre']); ?></td>
                    <td><?= htmlspecialchars($alumno['correo']); ?></td>
                    <td><?= htmlspecialchars($alumno['telefono']); ?></td>
                    <td><?= htmlspecialchars($alumno['genero']); ?></td>
                    <td><?= $alumno['estatus'] ? 'Activo' : 'Inactivo'; ?></td>
                    <td>
                        <button class="btn btn-success btn-sm btnEditar"
                            data-matricula="<?= $alumno['matricula']; ?>"
                            data-nombre="<?= htmlspecialchars($alumno['nombre']); ?>"
                            data-correo="<?= htmlspecialchars($alumno['correo']); ?>"
                            data-telefono="<?= htmlspecialchars($alumno['telefono']); ?>"
                            data-genero="<?= $alumno['genero']; ?>"
                            data-estatus="<?= $alumno['estatus']; ?>"
                            data-bs-toggle="modal" data-bs-target="#editarModal">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <a href="#" class="btn btn-danger btn-sm btnEliminar" data-id="<?= $alumno['matricula']; ?>">
                            <i class="bi bi-trash3"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Modal agregar -->
    <div class="modal fade" id="agregarAlumno" tabindex="-1" aria-labelledby="agregarAlumnoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarAlumnoLabel">Agregar Alumno</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="matricula" class="form-label">Matrícula</label>
                            <input type="text" class="form-control" id="matricula" name="matricula" required>
                        </div>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="correo" name="correo" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono">
                        </div>
                        <div class="mb-3">
                            <label for="genero" class="form-label">Género</label>
                            <select class="form-select" id="genero" name="genero">
                                <option value="">Seleccione...</option>
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
                            </select>
                        </div>
                        <div hidden="hidden">
                            <div class="mb-3">
                                <label for="estatus" class="form-label">Estatus</label>
                                <select class="form-select" id="estatus" name="estatus">
                                    <option value="1">Activo</option>
                                    <!-- <option value="0">Inactivo</option> -->
                                </select>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal editar -->
    <div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarModalLabel">Editar Alumno</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <input type="hidden" name="accion" value="editar">
                        <input type="hidden" name="matricula" id="matriculaEditar">
                        <div class="mb-3">
                            <label for="nombreEditar" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombreEditar" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="correoEditar" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="correoEditar" name="correo" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefonoEditar" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefonoEditar" name="telefono">
                        </div>
                        <div class="mb-3">
                            <label for="generoEditar" class="form-label">Género</label>
                            <select class="form-select" id="generoEditar" name="genero">
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="estatusEditar" class="form-label">Estatus</label>
                            <select class="form-select" id="estatusEditar" name="estatus">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // modal de editar
    document.querySelectorAll('.btnEditar').forEach(boton => {
        boton.addEventListener('click', function () {
            document.getElementById('matriculaEditar').value = this.dataset.matricula;
            document.getElementById('nombreEditar').value = this.dataset.nombre;
            document.getElementById('correoEditar').value = this.dataset.correo;
            document.getElementById('telefonoEditar').value = this.dataset.telefono;
            document.getElementById('generoEditar').value = this.dataset.genero;
            document.getElementById('estatusEditar').value = this.dataset.estatus;
        });
    });
    // eliminación
    document.querySelectorAll('.btnEliminar').forEach(boton => {
        boton.addEventListener('click', function (e) {
            e.preventDefault();
            let matricula = this.getAttribute('data-id');
            Swal.fire({
                title: "Seguro que deseas eliminar este alumno?",
                // text: "¿Seguro que deseas eliminar este alumno?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "?eliminar=" + matricula;
                }
            });
        });
    });
    </script>

    <?php if (!empty($mensaje)): ?>
    <script>
    Swal.fire({
        title: "<?php echo $mensaje[0]; ?>",
        text: "<?php echo $mensaje[1]; ?>",
        icon: "<?php echo $mensaje[2]; ?>",
        confirmButtonText: "OK"
    });
    </script>
    <?php endif; ?>
</body>
</html>
