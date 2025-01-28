"use strict";

let apiKey = "";

fetch('../../php/apikey_request.php')
  .then(respuesta => respuesta.json())
  .then(datos => {
    apiKey = datos.apikey;
    console.log(apiKey);
  });

//Si hago el console.log() fuera del then, no imprime nada porque aun no se ha resuelto la promesa*