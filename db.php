<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'config.php';

function db_connect(){
    $mysqli = new mysqli(DB_HOSTNAME, DB_USER, DB_PASSWORD, DB_NAME);
    if ($mysqli->connect_errno) {
        $response['error'] = true;
        $response['msg'] = "Error: Fallo al conectarse a MySQL debido a: \n";
        $response['msg'] .= "Errno: " . $mysqli->connect_errno . "\n";
        $response['msg'] .= "Error: " . $mysqli->connect_error . "\n";
    } else {
        $response['error'] = false;
        $response['conexion'] = $mysqli;
    }
    return $response;
}
function db_close($mysqli){
    $mysqli->close();
}

function db_query_select($query_select,$mysqli){
    if (!$resultado = $mysqli->query($query_select)) {
        $response['error'] = true;
        $response['msg'] = "La ejecución de la consulta falló debido a: \n";
        $response['msg'] .= "Query, " . $query_select . "\n";
        $response['msg'] .= "Errno, " . $mysqli->errno . "\n";
        $response['msg'] .= "Error, " . $mysqli->error . "\n";
    }
    if ($resultado->num_rows === 0) {
        $response['error'] = true;
        $response['msg'] = "cero resultados\n";
    } else {
        $response['error'] = false;
        $response['result'] = $resultado->fetch_assoc();
    }
    return $response;
}
function db_query_insert($query_insert,$mysqli){
    if (!$resultado = $mysqli->query($query_insert)) {
        $response['error'] = true;
        $response['msg'] = "La ejecución de la consulta falló debido a: \n";
        $response['msg'] .= "Query, " . $query_insert . "\n";
        $response['msg'] .= "Errno, " . $mysqli->errno . "\n";
        $response['msg'] .= "Error, " . $mysqli->error . "\n";
    } else {
        $response['error'] = false;
        $response['respuesta'] = "New record created successfully";
    }
    
    return $response;
}
?>

