<?php
    include_once 'config.php';
    
    class GoogleApi {
        static $instance = null;
        var $cmdtoken = "gcloud auth application-default print-access-token";
        var $token;
        var $hostAPI = "https://speech.googleapis.com/v1beta1/speech:syncrecognize";
        
        static function & getInstance() {
            if (null == GoogleApi::$instance) {
                GoogleApi::$instance = new GoogleApi();
            }

            return GoogleApi::$instance;
        }
        function GoogleApi() {
            $this->token = substr(shell_exec($this->cmdtoken), 0, -1);
        }
        function getJsonRequest($encoded_audio) {
            
            $data = array("config" => array(
                            "encoding" => "FLAC",
                            "sampleRate" => 16000,
                            "languageCode" => "es-ES",
                        ), "audio" => array(
                            "content" => $encoded_audio
                        )
                    );
            $response = json_encode($data);
            
            return $response;
        }
        function do_request($data) {
            
            $error = false;
            $ERROR_respuesta = "ERROR, ";
            
            $ch = curl_init($this->hostAPI);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data),
                'Authorization: Bearer '.$this->token)
            );                                                                                                       
            $result = curl_exec($ch);
            //file_put_contents("/home/jlotito/repositories/git/speech_recog/json/response.json", $result);
            
            $array_result = json_decode($result);
            if (isset($array_result->results[0]->alternatives[0]->confidence)){
                $confidence = $array_result->results[0]->alternatives[0]->confidence;
                if ($confidence>0.60){
                    $transcript = $array_result->results[0]->alternatives[0]->transcript;
                } else {
                    $error = true;
                    $ERROR_respuesta .= "COINCIDENCIA NO SUPERIOR AL 60%";
                }
            } else {
                $transcript = $array_result->results[0]->alternatives[0]->transcript;
            }
            $response['error'] = $error;
            if ($error){
                $response['error_msg'] = $ERROR_respuesta;
            } else {
                $response['transcript'] = $transcript;
            }
            return $response;
        }
    }
?>

