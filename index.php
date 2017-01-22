<?php
    
    include_once 'config.php';
    include_once 'functions.php';
    
    $contents = file_get_contents(PROJECT_PATH."/response.json"); 
    $inputJSON = json_decode($contents);
    $transcript = $inputJSON->results[0]->alternatives[0]->transcript;
    $confidence = $inputJSON->results[0]->alternatives[0]->confidence;
    $error = false;
    $ERROR_respuesta = "ERROR, ";
    
    if ($confidence>0.60){
        $respuesta = $transcript;
        $respuesta = get_iRespuesta($transcript);
        if ($respuesta['error']){
            $error = true;
            $ERROR_respuesta .= $respuesta['msg'];
        } else {
            $OK_respuesta = $respuesta['result'];
        }
    } else {
        $error = true;
        $ERROR_respuesta .= "COINCIDENCIA NO SUPERIOR AL 60%";
    }
    
    if (!$error){
        echo "$OK_respuesta";
    } else {
        echo "$ERROR_respuesta";
    }
?>
