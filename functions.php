<?php
    include_once 'config.php';
    include_once 'db.php';
    
    function elimina_acentos($text){
        $text = htmlentities($text, ENT_QUOTES, 'UTF-8');
        $text = strtolower($text);
        $patron = array (
            // Espacios, puntos y comas por guion
            //'/[\., ]+/' => ' ',
 
            // Vocales
            '/\+/' => '',
            '/&agrave;/' => 'a',
            '/&egrave;/' => 'e',
            '/&igrave;/' => 'i',
            '/&ograve;/' => 'o',
            '/&ugrave;/' => 'u',
 
            '/&aacute;/' => 'a',
            '/&eacute;/' => 'e',
            '/&iacute;/' => 'i',
            '/&oacute;/' => 'o',
            '/&uacute;/' => 'u',
 
            '/&acirc;/' => 'a',
            '/&ecirc;/' => 'e',
            '/&icirc;/' => 'i',
            '/&ocirc;/' => 'o',
            '/&ucirc;/' => 'u',
 
            '/&atilde;/' => 'a',
            '/&etilde;/' => 'e',
            '/&itilde;/' => 'i',
            '/&otilde;/' => 'o',
            '/&utilde;/' => 'u',
 
            '/&auml;/' => 'a',
            '/&euml;/' => 'e',
            '/&iuml;/' => 'i',
            '/&ouml;/' => 'o',
            '/&uuml;/' => 'u',
 
            '/&auml;/' => 'a',
            '/&euml;/' => 'e',
            '/&iuml;/' => 'i',
            '/&ouml;/' => 'o',
            '/&uuml;/' => 'u',
 
            // Otras letras y caracteres especiales
            '/&aring;/' => 'a',
            '/&ntilde;/' => 'n',
 
            // Agregar aqui mas caracteres si es necesario
 
        );
 
        $text = preg_replace(array_keys($patron),array_values($patron),$text);
        return $text;
    }

    function get_iRespuesta($transcript){
        
        $db = db_connect();
        if ($db['error']){
            $response['error'] = true;
            $response['error_msg'] = $db['msg'];
        } else{
            $sql = 'SELECT type, pregunta, respuesta FROM sp_dialogo WHERE pregunta="'.$transcript.'"';
            $query_response = db_query_select($sql, $db['conexion']);
            if ($query_response['error']){
                $response['error'] = true;
                $response['error_msg'] = $query_response['msg'];
            } else {
                $response['error'] = false;
                if ($response['result']['type'] == 0){
                    $response['result'] = $query_response['result']['respuesta'];
                } else if ($response['result']['type'] == 1){
                    $response['result'] = $query_response['result']['pregunta'];
                }
                db_close($db['conexion']);
            }
        }
        return $response;
    }
    
    function insert_encoded_audio($transcript,$encoded_audio,$audio_file){
        $retocado = elimina_acentos($transcript);
        $db = db_connect();
        if ($db['error']){
            $response['error'] = true;
            $response['msg'] = $db['msg'];
        } else{
            $sql = "INSERT INTO sp_encoded_audio (transcript, encoded_audio, audio_file) VALUES ('".$retocado."', '".$encoded_audio."', '".$audio_file."')";
            $query_response = db_query_insert($sql, $db['conexion']);
            if ($query_response['error']){
                $response['error'] = true;
                $response['msg'] = $query_response['msg'];
            } else {
                $response['error'] = false;
                $response['result'] = $query_response['respuesta'];
                db_close($db['conexion']);
            }
        }
        return $response;
    }
?>

