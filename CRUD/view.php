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
                <!-- <th>Semestre</th> -->
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
                    <!-- <td><//?= htmlspecialchars($materia['semestre']); ?></td> -->
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
                            <label for="carrera" class="form-label">Carrera</label>
                            <select class="form-select" id="carrera" name="carrera" required>
                                <option value="">Seleccione..</option>
                                <?php
                                $carreras = $conexion->query("SELECT clave, nombre FROM CARRERAS");
                                foreach ($carreras as $carrera): ?>
                                <option value="<?= htmlspecialchars($carrera['nombre']) ?>"><?= htmlspecialchars($carrera['clave']) ?></option>
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    
</body>
</body>
</html>