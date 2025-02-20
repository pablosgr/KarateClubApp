"use strict";

const contenedor_video = document.getElementById("dojo-content");
const boton_buscar = document.getElementById("search");
const botones_topics = document.querySelectorAll(".search-topic");
let topics = "";
let selected_topics = "";
let apiKey = "";
let language_preference = false;
const maxResults = 15;

fetch('../../php/apikey_request.php')
  .then(respuesta => respuesta.json())
  .then(datos => {
    //obtengo la key y genero un vídeo automáticamente
    if(datos){
      apiKey = datos.apikey;
      generarVideo(); //window.onload = function;
    } else {
      console.log("Error al obtener la API Key");
    }
  });

botones_topics.forEach(
  (boton) => {
    boton.addEventListener("click", ()=>{
      boton.classList.toggle("active");
      boton.style.scale = "1.05";
      setTimeout(()=>{
        boton.style.scale = "1";
      }, 90);
    })
  }
)

boton_buscar.addEventListener("click", async ()=>{
    await generarVideo();
});

//FUNCIONES

async function generarVideo(){
  //recojo los datos de los tópicos de búsqueda
  selected_topics = document.querySelectorAll(".active");

  if(selected_topics.length > 0) {
    topics = "karate ";
    selected_topics.forEach(
      (topic) => {
          if(topic.getAttribute("data-value") === "español"){
            language_preference = true;
          }
          topics += (topic.getAttribute("data-value")) + " ";
      }
    )
  } else {
    //si no hay topicos de búsqueda seleccionado, hago la petición a Gemini para que genere uno
    topics = await geminiQuery(apiKey);
    console.log(topics);
  }
  

  contenedor_video.innerHTML = "";
  const datos = await youtubeQuery(apiKey, topics.trim(), language_preference);

  if(datos.items !== undefined){
    let array_videos = datos.items;
  
    if(array_videos.length > 0){
      let random = Math.floor(Math.random() * array_videos.length);
      const video = array_videos[random];

      //creo los elementos HTML del video seleccionado
      let titulo = document.createElement("h2");
      titulo.innerHTML = video.snippet.title;
      let parrafo = document.createElement("p");
      parrafo.innerHTML = video.snippet.description;
      let iframe = document.createElement("iframe");
      iframe.src = "https://www.youtube.com/embed/" + video.id.videoId;
      iframe.classList.add("frame-video");

      let video_info = document.createElement("section");
      video_info.classList.add("video-info");
      video_info.appendChild(titulo);
      video_info.appendChild(parrafo);

      contenedor_video.appendChild(iframe);
      contenedor_video.appendChild(video_info);
    } else {
      let parrafo = document.createElement("p");
      parrafo.innerHTML = "<p>No se han encontrado resultados</p>";
      contenedor_video.appendChild(parrafo);
    }
  } else {
    let parrafo = document.createElement("p");
    parrafo.innerHTML = "<p class='quota'>No se pueden buscar videos actualmente</p>";
    contenedor_video.appendChild(parrafo);
  }
    
}

async function youtubeQuery(key, topics, lang_preference) {
  const order_options = ["relevance", "rating"]; //opcional rating, viewCount
  let random = Math.floor(Math.random()*2);
  topics = encodeURIComponent(topics); //codifico el texto para evitar los espacios en la URL
  let url = `https://www.googleapis.com/youtube/v3/search?key=${key}&part=snippet&type=video&q=${topics}&maxResults=${maxResults}&order=${order_options[random]}&videoEmbeddable=true`;
  if(lang_preference){
    url += "&relevanceLanguage=es";
  }

  const response = await fetch(url);
  const datos = await response.json();
  return datos;
}

async function geminiQuery(key) {
  let url = `https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=${key}`;

  const peticion = {
    contents: [{
      parts: [{ text: "Dame un texto de búsqueda interesante sobre contenido de karate en YouTube. Sin decoraciones de texto, sólo la frase" }]
    }]
  };

  try {
    const response = await fetch(url, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(peticion)
    });

    const datos = await response.json();
    if (datos && datos.candidates && datos.candidates.length > 0) {
      return datos.candidates[0].content.parts[0].text.trim();
    } else {
      console.log("Error al obtener el texto de Gemini");
      return "karate ";
    }
  } catch (error) {
    console.error("Error en la petición a Gemini:", error);
    return "karate ";
  }
}