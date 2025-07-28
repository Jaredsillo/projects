<?php
include('../conexion.php');

// Leer alumnos
function leerMaterias($conexion){
    // $sql = "SELECT * FROM MATERIAS";
    $sql = "SELECT GRUPOS.idGrupo AS grupo, 
            MATERIAS.clave AS materiaC, 
            MATERIAS.nombre AS materiaN, 
            MATERIAS.semestre, 
            CARRERAS.nombre AS carrera
            FROM GRUPOS
            INNER JOIN MATERIAS ON GRUPOS.claveM = MATERIAS.clave
            INNER JOIN CARRERAS ON GRUPOS.claveC = CARRERAS.clave";
    return $conexion->query($sql);
}

//crear (Nombre de las tablas: MATERIAS,CARRERAS,GRUPO)
function crearMaterias($conexion, $datos){
    try{
         $conexion->beginTransaction();
         $stmt = $conexion->prepare("INSERT INTO MATERIAS (clave, nombre, semestre) VALUES (?, ?, ?)");
         $stmt->execute([$datos['clave'], $datos['nombre'], $datos['semestre']]);
         $stmt = $conexion->prepare("INSERT INTO GRUPOS (claveM, claveC) VALUES (?, ?, ?)");
         $stmt->execute([
            $datos['claveM'],
            $datos['claveC']
         ]);
         $conexion->commit();
         return ['Éxito', 'Materia y grupo agregados correctamente', 'success'];
    } catch (PDOException $e) {
        $conexion->rollBack();
        return ['Error', $e->getMessage(), 'error'];
    } 
}
