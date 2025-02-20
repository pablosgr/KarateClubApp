"use strict";

const contenedor = document.getElementById("contenido-productos");
const btn_buscar = document.getElementById("btn-buscar");
const input_busqueda = document.getElementById("texto-busqueda");
let texto_busqueda;
let url_api = "http://localhost/club_karate/api/api.php";

const pagina_carrito = document.querySelector(".cart-overlay");
const cart_aside = document.querySelector(".cart");
const cerrar_carrito = document.querySelector(".cart-close");
const icono_carrito = document.querySelector(".cart-icon");
const btn_vaciar_carrito = document.querySelector(".empty-cart");
const btn_hacer_pedido = document.querySelector(".place-order");
const alerta_producto = document.querySelector(".add-alert");

const contenedor_carrito = document.querySelector(".cart-items-list");
const id_cliente = document.getElementById("id_cliente");
const nombre_carrito = "lista" + id_cliente.getAttribute("value"); //creo una variable propia por cada usuario
let datos_productos = recuperarCarrito(nombre_carrito); //variable para el localStorage


//Llamo a la API al cargar la página
listarProductos("", url_api);

//renderizo en el carritop todos los productos en localStorage
datos_productos.forEach(
    (p) => {
        renderProductCart(p);
    }
)

//----------------------------EVENTOS

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

const cartOverlay = document.querySelector(".cart-overlay");

/*
    Cierra el carrito si se hace click fuera del div carrito -aside-
*/
pagina_carrito.addEventListener("click", (event) => {
    if (!cart_aside.contains(event.target)) {
        pagina_carrito.classList.remove("show");
    }
});

/*
    Obtiene los parámetros de búsqueda y llama a la función que lista e imprime los productos
*/
btn_buscar.addEventListener("click", ()=>{
    texto_busqueda = input_busqueda.value.trim();
    listarProductos(texto_busqueda, url_api);
    input_busqueda.value = "";
});

/*
    Vacía el carrito en el DOM y localStorage
*/
btn_vaciar_carrito.addEventListener("click", ()=>{
    datos_productos = [];
    guardarCarrito(nombre_carrito, datos_productos);
    contenedor_carrito.innerHTML = "";
    renderTotal();
});

/*
    Realiza el pedido
*/
btn_hacer_pedido.addEventListener("click", ()=>{
    if(datos_productos.length > 0) {
        //compruebo que el carrito no esté vacío
        datos_productos = [];
        guardarCarrito(nombre_carrito, datos_productos);
        contenedor_carrito.innerHTML = "";
        renderTotal();
        pagina_carrito.classList.remove("show");
        renderAlert("Pedido realizado");
    }
    
});

//----------------------------FUNCIONES

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

//----------------------------FUNCIONES RENDER

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
            precio.innerHTML = "Precio: <b>" + producto["precio"] + " &#8364</b>";
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

            //botón y evento para añadir al carrito
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
                    "detalles": {
                        "id": producto["id"],
                        "nombre": producto["nombre"],
                        "precio": producto["precio"],
                        "categoria": producto["categoria"],
                        "imagen": producto["imagen"],
                        "disponible": producto["disponible"],
                        "cantidad": producto["cantidad"]
                    },
                    "numero": 1
                };

                renderAlert("Producto añadido");

                //Compruebo si el id del producto ya esta presente en la lista para añadir uno al numero de productos
                let exists = false;
                if(datos_productos.length > 0){
                    let result = datos_productos.findIndex(i => i["detalles"]["id"] == item["detalles"]["id"]);
                    if(result !== -1){
                        datos_productos[result]["numero"]++;
                        renderTotal();
                        exists = true;

                        //Lo actualizo también en el elemento HTML
                        let array_items = document.querySelectorAll(".item");
                        array_items.forEach(
                            (p) => {
                                if(p.getAttribute("data-id") == item["detalles"]["id"]){
                                    let cantidad_elemento = p.querySelector(".number");
                                    cantidad_elemento.innerText = parseInt(cantidad_elemento.innerText) + 1;
                                }
                            }
                        );
                    }
                }

                //Si no estaba previamente, lo añado
                if(!exists){
                    datos_productos.push(item);
                    renderProductCart(item);
                }
                
                guardarCarrito(nombre_carrito, datos_productos);
                
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

/*
    Renderiza en el carrito el producto pasado por parámetro
*/
function renderProductCart(producto){

        let articulo = document.createElement("article");
        articulo.classList.add("item");
        articulo.setAttribute("data-id", producto["detalles"]["id"]);
        articulo.innerHTML = `
            <section class="item-body">
                <img src="${producto["detalles"]["imagen"]}">
                <section class="item-details">
                    <h3>${producto["detalles"]["nombre"]}</h3>
                    <p>${producto["detalles"]["categoria"]}</p>
                    <p>Precio: ${producto["detalles"]["precio"]} &#8364</p>
                </section>
            </section>
            <section class="item-foot">
                <button class='restar-prod'>-</button>
                <p class='number'>${producto["numero"]}</p>
                <button class='sumar-prod'>+</button>
            </section>
        `;

        contenedor_carrito.appendChild(articulo);
        renderTotal();

        //selecciono los botones del propio articulo para que sólo le afecten a él
        let boton_sumar = articulo.querySelector(".sumar-prod");
        let boton_restar = articulo.querySelector(".restar-prod");

        boton_sumar.addEventListener("click", (event)=>{
                    event.stopPropagation();
                    let result = datos_productos.findIndex(i => i["detalles"]["id"] == producto["detalles"]["id"]);
                    if(result !== -1){
                        //actualizo en el carrito
                        datos_productos[result]["numero"]++;
                        guardarCarrito(nombre_carrito, datos_productos);
                        //actualizo en el elemento HTML
                        let cantidad_elemento = articulo.querySelector(".number");
                        cantidad_elemento.innerText = parseInt(cantidad_elemento.innerText) + 1;

                        renderTotal();
                    }
                }
            );
            
        
        
        boton_restar.addEventListener("click", (event)=>{
                    event.stopPropagation();
                    let result = datos_productos.findIndex(i => i["detalles"]["id"] == producto["detalles"]["id"]);
                    if(result !== -1){
                        //actualizo en el carrito
                        datos_productos[result]["numero"]--;
                        guardarCarrito(nombre_carrito, datos_productos);
                        if(datos_productos[result]["numero"] < 1){
                            datos_productos.splice(result, 1);
                            guardarCarrito(nombre_carrito, datos_productos);
                        }
                        //actualizo en el elemento HTML
                        let cantidad_elemento = articulo.querySelector(".number");
                        cantidad_elemento.innerText = parseInt(cantidad_elemento.innerText) - 1;
                        if(cantidad_elemento.innerText < 1){
                            articulo.remove();
                        }

                        renderTotal();
                    }
                }
            );
            
}
    
/*
    Renderiza el precio total cada vez que se añade o elimina un producto
*/
function renderTotal(){
    let total = document.querySelector(".total span");
    let total_calculo = 0;
    datos_productos.forEach(
        (prod) => {
            total_calculo += prod["detalles"]["precio"] * prod["numero"];
        }
    )
    total.innerHTML = total_calculo;
}

/*
    Renderiza el precio total cada vez que se añade o elimina un producto
*/
function renderAlert(mensaje){
    alerta_producto.innerText = mensaje;
    alerta_producto.classList.add("show-alert");
    setTimeout(()=>{
        alerta_producto.classList.remove("show-alert");
    }, 1200);
}

//LOCALSTORAGE

/*
    Almacena en el localStorage la variable contenedora de los productos añadidos al carrito
    convertida a String
*/
function guardarCarrito(variable, datos){
    //incluyo el id del usuario en el objeto (no es necesario)
    // datos.id_cliente ??= id_cliente.getAttribute("value");
    localStorage.setItem(variable, JSON.stringify(datos));
}

/*
    Recupero el carrito y, si no puedo recuperar la variable, la establezco en array vacío
*/
function recuperarCarrito(variable){
    return JSON.parse(localStorage.getItem(variable)) ?? [];
}