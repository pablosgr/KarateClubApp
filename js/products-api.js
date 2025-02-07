"use strict";

const contenedor = document.getElementById("contenido-productos");
const btn_buscar = document.getElementById("btn-buscar");
const input_busqueda = document.getElementById("texto-busqueda");
let texto_busqueda;
let pagina_actual = 1;

let url_api = "http://localhost/club_karate/api/api.php";

btn_buscar.addEventListener("click", ()=>{
    texto_busqueda = input_busqueda.value.trim();
    listarProductos(texto_busqueda);
});

/*
    Lista los productos con el texto de búsqueda
*/
async function listarProductos(texto){
    contenedor.innerHTML = ""; //limpio el contenedor
    const datos = await apiRequest(texto);

    renderCards(datos["datos"]);
}

/*
    Gestiona la petición a la API
*/
async function apiRequest(texto){
    const respuesta = await fetch(`http://localhost/club_karate/api/api.php`);
    const datos = await respuesta.json();
    return datos;
}

/*
    Recibe los datos de la API y genera el contenido HTML
    (seccion contenedora y cards de productos)
*/
function renderCards(datos){

    let seccion = document.createElement("section");
    seccion.classList.add("lista-productos");

    datos.forEach(producto => {
        let articulo = document.createElement("article");
        articulo.classList.add("producto");
        let nombre = document.createElement("h2");
        nombre.innerHTML = producto["nombre"];
        
        contenedor.appendChild(articulo);
    });
}