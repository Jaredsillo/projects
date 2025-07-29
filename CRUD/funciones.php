<?php
include('../conexion.php');

// Leer alumnos
function leerMaterias($conexion){
    // $sql = "SELECT * FROM MATERIAS";
    $sql = "SELECT GRUPOS.idGrupo AS grupo, 
            MATERIAS.clave AS materiaC, 
            MATERIAS.nombre AS materiaN, 
            MATERIAS.semestre, 
            CARRERAS.nombre AS carrera,
            CARRERAS.clave AS claveC
            FROM GRUPOS
            INNER JOIN MATERIAS ON GRUPOS.claveM = MATERIAS.clave
            INNER JOIN CARRERAS ON GRUPOS.claveC = CARRERAS.clave
            ORDER BY GRUPOS.idGrupo ASC";
    return $conexion->query($sql);
}
//crear (Nombre de las tablas: MATERIAS,CARRERAS,GRUPO)
function crearMaterias($conexion, $datos){
    try {
        $conexion->beginTransaction();
        $stmt = $conexion->prepare("INSERT INTO MATERIAS (clave, nombre, semestre) VALUES (?, ?, ?)");
        $stmt->execute([
            $datos['clave'],
            $datos['nombre'],
            $datos['semestre']
        ]);
        $stmt = $conexion->prepare("INSERT INTO GRUPOS (claveM, claveC) VALUES (?, ?)");
        $stmt->execute([
            $datos['clave'],
            $datos['claveC']
        ]);
        $conexion->commit();
        return ['Éxito', 'Materia y grupo agregados correctamente', 'success'];
    } catch (PDOException $e) {
        $conexion->rollBack();
        return ['Error', $e->getMessage(), 'error'];
    } 
}

// Actualizar
function actualizarMaterias($conexion, $datos){
    try {
        $conexion->beginTransaction();
        $stmt = $conexion->prepare("UPDATE MATERIAS SET clave = ?, nombre = ?, semestre = ? WHERE clave = ?");
        $stmt->execute([
            $datos['clave'],
            $datos['nombre'],
            $datos['semestre'],
            $datos['clave']
        ]);
        $stmt = $conexion->prepare("UPDATE GRUPOS SET claveC = ? WHERE claveM = ?");
        $stmt->execute([
            $datos['claveC'],
            $datos['clave']
        ]);
        $conexion->commit();
        return ['Éxito', 'Materia y grupo actualizados correctamente', 'success'];
    } catch (PDOException $e) {
        $conexion->rollBack();
        return ['Error', $e->getMessage(), 'error'];
    }
}

// Eliminar
function eliminarMateria($conexion, $clave){
    try {
        $conexion->beginTransaction();
        $stmt = $conexion->prepare("DELETE FROM GRUPOS WHERE claveM = ?");
        $stmt->execute([$clave]);
        $stmt = $conexion->prepare("DELETE FROM MATERIAS WHERE clave = ?");
        $stmt->execute([$clave]);
        $conexion->commit();
        return ['Éxito', 'Materia eliminada correctamente', 'success'];
    } catch (PDOException $e) {
        $conexion->rollBack();
        return ['Error', $e->getMessage(), 'error'];
    }
}