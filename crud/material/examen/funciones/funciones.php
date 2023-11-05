<?php
//Función para obtener el registro de la configuración del sitio
function obtenerConfiguracion(){
    include '../../../php/conexion_be.php';

    function obtenerexamenPorId($id){
        include '../../../php/conexion_be.php'
        $query = "SELECT id FROM examenes WHERE id = $id";
        $result = mysqli_query($conexion, $query);
        $pregunta = mysqli_fetch_array($result);
        return $pregunta;
    }
    

    function obtenerPreguntaPorId($id){
        include '../../../php/conexion_be.php'
        $query = "SELECT * FROM examen_preguntas";
        $result = mysqli_query($conexion, $query);
        $pregunta = mysqli_fetch_array($result);
        
        return $pregunta;
    }
    
}
?>