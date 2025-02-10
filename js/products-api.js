"use strict";

let datos_productos = recuperarCarrito("lista"); //variable para el localStorage
const contenedor = document.getElementById("contenido-productos");
const btn_buscar = document.getElementById("btn-buscar");
const input_busqueda = document.getElementById("texto-busqueda");
let texto_busqueda;
let url_api = "http://localhost/club_karate/api/api.php";

const pagina_carrito = document.querySelector(".cart-overlay");
const cerrar_carrito = document.querySelector(".cart-close");
const icono_carrito = document.querySelector(".cart-icon");
const btn_vaciar_carrito = document.querySelector(".empty-cart");
const btn_hacer_pedido = document.querySelector(".place-order");

const carrito_productos = document.querySelector(".cart-items");

//Llamo a la API al cargar la página
listarProductos("", url_api);


//EVENTOS

/*
    Obtiene los parámetros de búsqueda y llama a la función lista los productos
*/
btn_buscar.addEventListener("click", ()=>{
    texto_busqueda = input_busqueda.value.trim();
    listarProductos(texto_busqueda, url_api);
    input_busqueda.value = "";
});

/*
    Mostrar y ocultar el carrito
*/
icono_carrito.addEventListener("click",
    () => {
      pagina_carrito.classList.add("show");
    }
);
  
  
cerrar_carrito.addEventListener("click",
    () => {
      pagina_carrito.classList.remove("show");
    }
);

/*
    Vacía el carrito en el DOM y localStorage
*/
btn_vaciar_carrito.addEventListener("click", ()=>{
    datos_productos = [];
    guardarCarrito("lista", datos_productos);
})

//FUNCIONES

/*
    Lista los productos con los parámetros de búsqueda
*/
async function listarProductos(texto, url){
    contenedor.innerHTML = ""; //limpio el contenedor

    if(texto !== ""){
        //Si se ha buscado algo, imprimo un aviso de la búsqueda
        let aviso = document.createElement("h2");
        aviso.classList.add("aviso-prod");
        aviso.innerHTML = `Resultados para "${texto}"`;
        contenedor.appendChild(aviso);
    }

    let url_modificada = modificarUrl(texto, url);
    const datos = await apiRequest(url_modificada);
    renderCards(datos);
}

/*
    Modifica la URL en función de los parámetros de búsqueda
*/
function modificarUrl(texto, url){
    let url_modificada = url;
    let array_params = texto.split(" ");
    if(array_params.length > 0 && array_params[0] !== ""){
        for(let i = 0; i < array_params.length; i++){
            if(isNaN(array_params[i])){
                if(i === 0){
                    url_modificada += `?nombre=${array_params[i]}`;
                } else {
                    url_modificada += `&nombre=${array_params[i]}`;
                }
            } else {
                if(i === 0){
                    url_modificada += `?precioInf=${array_params[i]}`;
                } else {
                    url_modificada += `&precioInf=${array_params[i]}`;
                }
            }
        }
    }
    
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

    if(datos['error']){
        //Controlo si ha habido error para mostrar un mensaje
        let alerta = document.createElement("h2");
        alerta.classList.add("alerta-prod");
        alerta.innerHTML = "No se han encontrado productos";
        contenedor.appendChild(alerta);
    } else {
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

            //botón y evento para el carrito 
            let boton = document.createElement("button");
            boton.classList.add("add-producto");
            boton.setAttribute("id", "add-producto");
            let icono = document.createElement("i");
            icono.classList.add("material-symbols-outlined");
            icono.innerHTML = "add_shopping_cart";
            let texto_boton = document.createElement("span");
            texto_boton.innerHTML = "Añadir al carrito";

            boton.addEventListener("click", ()=>{
                let item = {
                    "id": producto["id"],
                    "nombre": producto["nombre"],
                    "precio": producto["precio"],
                    "categoria": producto["categoria"],
                    "imagen": producto["imagen"],
                    "disponible": producto["disponible"],
                    "cantidad": producto["cantidad"],
                };

                datos_productos.push(item);
                guardarCarrito("lista", datos_productos);
            })

            //añado los elementos hijos a sus padres
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
        renderPages(datos);
    }
}

/*
    Recibe los datos de la API y genera el paginado
*/
function renderPages(datos){
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

//LOCALSTORAGE

/*
    Almacena en el localStorage la variable contenedora de los productos añadidos al carrito
    convertida a String
*/
function guardarCarrito(variable, datos){
    localStorage.setItem(variable, JSON.stringify(datos));
}

/*
    Recupero el carrito y, si no puedo recuperar la variable, la establezco en array vacío
*/
function recuperarCarrito(variable){
    return JSON.parse(localStorage.getItem(variable)) ?? [];
}