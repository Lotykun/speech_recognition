<?php
    define("PROJECT_PATH", "/home/jlotito/repositories/git/speech_recog");
    define("AUDIO_PATH", PROJECT_PATH."/tmp_audios");
    define("AUDIO_FILE", AUDIO_PATH."/temp.flac");
    
    $respuesta = "LOTY IS HERE";
    $contents = file_get_contents(PROJECT_PATH."/request.json"); 
    $contents = utf8_encode($contents); 
    $inputJSON = json_decode($contents);
    
    echo "$respuesta\n"
?>
