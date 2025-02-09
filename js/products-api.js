"use strict";

const contenedor = document.getElementById("contenido-productos");
const btn_buscar = document.getElementById("btn-buscar");
const input_busqueda = document.getElementById("texto-busqueda");
let texto_busqueda;
let url_api = "http://localhost/club_karate/api/api.php";

//Llamo a la API al cargar la página
listarProductos("", url_api);

btn_buscar.addEventListener("click", ()=>{
    texto_busqueda = input_busqueda.value.trim();
    listarProductos(texto_busqueda, url_api);
    input_busqueda.value = "";
});

/*
    Lista los productos con los parámetros de búsqueda
*/
async function listarProductos(texto, url){
    contenedor.innerHTML = ""; //limpio el contenedor
    let url_modificada = modificarUrl(texto, url);
    const datos = await apiRequest(url_modificada);
    renderCards(datos);
}

function modificarUrl(texto, url){
    let url_modificada = url + texto;
    return url_modificada;
}

/*
    Gestiona la petición a la API
*/
async function apiRequest(url){
    const respuesta = await fetch(url);
    const datos = await respuesta.json();
    return datos;
}

/*
    Recibe los datos de la API y genera el contenido HTML
    (seccion contenedora y cards de productos)
*/
function renderCards(datos){
    let lista_productos = datos["datos"];

    let seccion = document.createElement("section");
    seccion.classList.add("lista-productos");

    lista_productos.forEach(producto => {
        let articulo = document.createElement("article");
        articulo.classList.add("producto");
        let nombre = document.createElement("h2");
        nombre.innerHTML = producto["nombre"];
        let imagen = document.createElement("img");
        imagen.src = producto["imagen"];
        let precio = document.createElement("p");
        precio.innerHTML = "Precio: " + producto["precio"] + "&#8364";
        let disponible = document.createElement("p");
        if(producto["disponible"] != 1){
            disponible.innerHTML = "No disponible";
        } else {
            disponible.innerHTML = "Disponible";
        }
        let categoria = document.createElement("p");
        categoria.innerHTML = producto["categoria"];

        let seccion_boton = document.createElement("section");
        seccion_boton.classList.add("prod-options");
        let boton = document.createElement("button");
        boton.classList.add("add-producto");
        boton.setAttribute("id", "add-producto");
        let icono = document.createElement("i");
        icono.classList.add("material-symbols-outlined");
        icono.innerHTML = "add_shopping_cart";
        let texto_boton = document.createElement("span");
        texto_boton.innerHTML = "Añadir al carrito";

        boton.appendChild(icono);
        boton.appendChild(texto_boton);
        seccion_boton.appendChild(boton);

        articulo.appendChild(nombre);
        articulo.appendChild(imagen);
        articulo.appendChild(precio);
        articulo.appendChild(disponible);
        articulo.appendChild(categoria);
        articulo.appendChild(seccion_boton);
        
        seccion.appendChild(articulo);
    });

    contenedor.appendChild(seccion);
    renderFooter(datos);
}

/*
    Recibe los datos de la API y genera el paginado
*/
function renderFooter(datos){
    let paginado = document.createElement("section");
    paginado.classList.add("paginado-js")
    let boton_prev = document.createElement("button");
    boton_prev.classList.add("btn-prev");
    let boton_next = document.createElement("button");
    boton_next.classList.add("btn-next");
    
    if(datos["anterior"] === null){
        boton_prev.innerHTML = "<span>Primera página</span>";
    } else {
        boton_prev.innerHTML = "<span>Anterior</span>";
        boton_prev.setAttribute("data-url", datos["anterior"]);
        boton_prev.addEventListener("click", async ()=>{
            listarProductos("", boton_prev.getAttribute("data-url"));
        });
    }

    if(datos["siguiente"] === null){
        boton_next.innerHTML = "<span>Última página</span>";
    } else {
        boton_next.innerHTML = "<span>Siguiente</span>";
        boton_next.setAttribute("data-url", datos["siguiente"]);
        boton_next.addEventListener("click", async ()=>{
            listarProductos("", boton_next.getAttribute("data-url"));
        });
    }

    paginado.appendChild(boton_prev);
    paginado.appendChild(boton_next);
    contenedor.appendChild(paginado);
}