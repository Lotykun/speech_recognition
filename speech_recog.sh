#!/bin/bash  

PROJECT_PATH=/home/jlotito/repositories/local/speech_recog
AUDIO_PATH=$PROJECT_PATH/tmp_audios
AUDIO_FILE=$AUDIO_PATH/"temp.flac"

while [  $RESPONSE != "Salir programa" ]; do
    rec -r 16000 -c 1 $AUDIO_FILE trim 0 2
    AUDIO_FILE_AMPLITUDE=$(sox $AUDIO_FILE -n stat 2>&1 | grep "Maximum amplitude" | cut -d ":" -f 2 | sed 's/^[ \t]*//;s/[ \t]*$//')

    if [ $AUDIO_FILE_AMPLITUDE -gt 0.120 ]
    then

        TOKEN=$(gcloud auth application-default print-access-token)

        ENCODED_AUDIO=$(base64 $AUDIO_FILE -w 0)

        echo "{
          'config': {
              'encoding':'FLAC',
              'sampleRate': 16000,
              'languageCode': 'es-ES'
          },
          'audio': {
              'content':'$ENCODED_AUDIO'
          }
        }" > $PROJECT_PATH/request.json

        curl -s -k -H "Content-Type: application/json" -H "Authorization: Bearer $TOKEN" https://speech.googleapis.com/v1beta1/speech:syncrecognize -d @request.json > $PROJECT_PATH/response.json
        #curl -s -k -H "Content-Type: application/json" -H "Authorization: Bearer $TOKEN" https://speech.googleapis.com/v1beta1/speech:syncrecognize -d @request.json
        #TRANSCRIPT=$(curl -s -k -H "Content-Type: application/json" -H "Authorization: Bearer $TOKEN" https://speech.googleapis.com/v1beta1/speech:syncrecognize -d @request.json | jq '.results[0].alternatives[0].transcript')
        #echo $TRANSCRIPT
        $RESPONSE=$(php $PROJECT_PATH/index.php)
        espeak -ves+m2 -a 200 $RESPONSE
    else
        echo "ERROR "$AUDIO_FILE" is an empty file" 
    fi
done

echo "Gracias Adios"
espeak -ves+m2 -a 200 "Gracias Adios"