<?php
include('conexion.php');

// Leer alumnos
function obtenerAlumnos($conexion) {
    $sql = "SELECT a.matricula, a.nombre, d.correo, d.telefono, d.genero, d.estatus
            FROM ALUMNOS a
            JOIN DETALLE_ALUMNOS d ON a.matricula = d.matricula";
    return $conexion->query($sql);
}

// Agregar un nuevo alumno
function agregarAlumno($conexion, $datos) {
    try {
        $conexion->beginTransaction();
        $stmt = $conexion->prepare("INSERT INTO ALUMNOS (matricula, nombre) VALUES (?, ?)");
        $stmt->execute([$datos['matricula'], $datos['nombre']]);
        $stmt = $conexion->prepare("INSERT INTO DETALLE_ALUMNOS (matricula, correo, telefono, genero, estatus)
                                    VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $datos['matricula'], 
            $datos['correo'], 
            $datos['telefono'], 
            $datos['genero'], 
            $datos['estatus']
        ]);
        $conexion->commit();
        return ['Éxito', 'Alumno agregado correctamente', 'success'];
    } catch (PDOException $e) {
        $conexion->rollBack();
        return ['Error', $e->getMessage(), 'error'];
    }    
}

// Actualizar un alumno
function actualizarAlumno($conexion, $datos) {
    try {
        $conexion->beginTransaction();
        $stmt = $conexion->prepare("UPDATE ALUMNOS SET nombre = ? WHERE matricula = ?");
        $stmt->execute([$datos['nombre'], $datos['matricula']]);
        $stmt = $conexion->prepare("UPDATE DETALLE_ALUMNOS SET correo = ?, telefono = ?, genero = ?, estatus = ?
                                    WHERE matricula = ?");
        $stmt->execute([
            $datos['correo'], 
            $datos['telefono'], 
            $datos['genero'], 
            $datos['estatus'], 
            $datos['matricula']
        ]);
        $conexion->commit();
        return ['Éxito', 'Alumno actualizado correctamente', 'success'];
    } catch (PDOException $e) {
        $conexion->rollBack();
        return ['Error', $e->getMessage(), 'error'];
    }
}

// Eliminar un alumno
function eliminarAlumno($conexion, $matricula) {
    try {
        $conexion->beginTransaction();
        $stmt = $conexion->prepare("DELETE FROM DETALLE_ALUMNOS WHERE matricula = ?");
        $stmt->execute([$matricula]);
        $stmt = $conexion->prepare("DELETE FROM ALUMNOS WHERE matricula = ?");
        $stmt->execute([$matricula]);
        $conexion->commit();
        return ['Éxito', 'Alumno eliminado correctamente', 'success'];
    } catch (PDOException $e) {
        $conexion->rollBack();
        return ['Error', $e->getMessage(), 'error'];
    }
}
?>
