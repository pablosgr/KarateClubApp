"use strict"

//variables formularios
let form_socio=document.getElementById("formulario-socios");
let añadir_testimonio=document.getElementById("formulario-testimonio");
let form_servicio=document.getElementById("formulario-servicios");
let form_noticia=document.getElementById("formulario-noticias");
let form_busqueda=document.getElementById("buscador");
let form_cita=document.getElementById("form-citas");

//campos socios
let campo_foto=document.getElementById("campo-foto");
let campo_nombre=document.getElementById("campo-nombre");
let campo_usuario=document.getElementById("campo-usuario");
let campo_edad=document.getElementById("campo-edad");
let campo_tlfn=document.getElementById("campo-tlfn");
let campo_pass=document.getElementById("campo-pass");

//campos testimonios
let campo_texto_test=document.getElementById("contenido-testimonio");

//campos servicios
let campo_descripcion=document.getElementById("contenido-servicio");
let campo_duracion=document.getElementById("duracion-servicio");
let campo_u_duracion=document.getElementById("u-duracion-servicio");
let campo_precio=document.getElementById("precio-servicio");

//campos noticias
let campo_imagen=document.getElementById("pic-not");
let campo_titulo=document.getElementById("titulo-not");
let campo_noticia=document.getElementById("contenido-not");
let campo_fecha=document.getElementById("fecha-not");

//campo buscador
let campo_buscador=document.getElementById("texto-buscado");

//campos citas
let fecha_cita=document.getElementById("fecha-cita");
let hora_cita=document.getElementById("hora-cita");
let socio_cita=document.getElementById("socio-cita");
let servicio_cita=document.getElementById("servicio-cita");

//variables para el menú hamburguesa
const menuBtn=document.getElementById("menu-btn");
const navMenu=document.querySelector('nav ul');

//eventos

//evento para mostrar el menú escondido en resoluciones menores
menuBtn.addEventListener("click", () => {
    navMenu.classList.toggle("active"); //añade y elimina la clase active
});

//compruebo que cada formulario esté presente en la página -sea distinto de null- antes de ejecutar el código 
//para evitar que intente ejecutar y falle en páginas donde falte algún formulario

//este evento actua en los formularios de adición y modificación de socios
if(form_socio){
    form_socio.addEventListener("submit",
        (evento)=>{
            let validaciones=[
                ()=>validarImagenSocio(campo_foto),
                ()=>validarNombreSocio(campo_nombre),
                ()=>validarUsuario(campo_usuario),
                ()=>validarEdadSocio(campo_edad),
                ()=>validarTlfn(campo_tlfn),
                ()=>validarPass(campo_pass)
            ];

            for(let validacion of validaciones){
                if(!validacion()){
                    evento.preventDefault();
                    break;
                }
            }
    });
}

if(añadir_testimonio){
    añadir_testimonio.addEventListener("submit",
        (evento)=>{
            if(!validarTestimonio(campo_texto_test)){
                evento.preventDefault();
            }
        }
    );
}

//este evento actua en los formularios de adición y modificación de servicios
if(form_servicio){
    form_servicio.addEventListener("submit",
        (evento)=>{
            let validaciones=[
                ()=>validarServicio(campo_descripcion),
                ()=>validarDuracion(campo_duracion),
                ()=>validarUDuracion(campo_u_duracion),
                ()=>validarPrecioServicio(campo_precio)
            ];

            for (let val of validaciones){
                if(!val()){
                    evento.preventDefault();
                    break;
                }
            }
        }
    );
}

if(form_noticia){
    form_noticia.addEventListener("submit",
        (evento)=>{
            let validaciones_n=[
                ()=>validarImagenNoticia(campo_imagen),
                ()=>validarNoticia(campo_titulo),
                ()=>validarNoticia(campo_noticia),
                ()=>validarFecha(campo_fecha, "noticia")
            ];

            for (let val of validaciones_n){
                if(!val()){
                    evento.preventDefault();
                    break;
                }
            }
        }
    );
}

if(form_busqueda){
    form_busqueda.addEventListener("submit",
        (evento)=>{
            if(!validarBuscador(campo_buscador)){
                evento.preventDefault();
            }
        }
    )
}

if(form_cita){
    form_cita.addEventListener("submit",
        (evento)=>{
            let validaciones_c=[
                ()=>validarFecha(fecha_cita, "cita"),
                ()=>validarHora(hora_cita),
                ()=>validarSelect(socio_cita),
                ()=>validarSelect(servicio_cita)
            ];

            for (let val of validaciones_c){
                if(!val()){
                    evento.preventDefault();
                    break;
                }
            }
        }
    )
}


//funciones buscador --------------------------------

const validarBuscador = (campo)=>{
    let contenido=campo.value.trim();
    let span=campo.nextElementSibling;

    if(!validarTexto(contenido)){
        span.style.display="inline";
        span.innerText="El campo no puede estar vacío";
        return false;
    }

    span.style.display="none";
    return true;
}

//funciones socios ----------------------------------

const validarImagenSocio = (campo) => {
    let span=campo.parentNode.nextElementSibling;

    if(campo.files.length > 0){
        if(!validarImagen(campo, span)){
            return false;
        }
    }
    
    span.style.display="none";
    return true;
};

const validarNombreSocio = (campo) =>{
    let contenido=campo.value.trim();
    let span=campo.nextElementSibling;
    let reg_exp=/^[a-zA-Z áéíóúÁÉÍÓÚ]{4,50}$/;

    if(!validarTexto(contenido)){
        span.style.display="inline";
        span.innerText="El campo no puede estar vacío";
        return false;
    }else if(!reg_exp.test(contenido)){
        span.style.display="inline";
        span.innerText="Debe tener entre 4 y 50 caracteres, sin números";
        return false;
    }

    span.style.display="none";
    return true;
};

const validarUsuario = (campo) =>{
    let contenido=campo.value.trim();
    let span=campo.nextElementSibling;
    let reg_exp=/^[a-zA-Z][a-zA-Z0-9]{4,19}$/;

    if(!validarTexto(contenido)){
        span.style.display="inline";
        span.innerText="El campo no puede estar vacío";
        return false;
    }else if(!reg_exp.test(contenido)){
        span.style.display="inline";
        span.innerText="Debe tener entre 5 y 20 caracteres, sin especiales";
        return false;
    }

    span.style.display="none";
    return true;
};

const validarEdadSocio = (campo) =>{
    let contenido=campo.value.trim();
    let span=campo.nextElementSibling;

    if(!validarNum(campo, span)){
        return false;
    }else if(contenido < 18){
        span.style.display="inline";
        span.innerText="Debe ser mayor de edad";
        return false;
    }

    span.style.display="none";
    return true;
};

const validarTlfn = (campo)=>{
    let contenido=campo.value.trim();
    let span=campo.nextElementSibling;
    let expresion=/^\+34[0-9]{9}$/;

    if(!expresion.test(contenido)){
        span.style.display="inline";
        span.innerText="El teléfono no es válido";
        return false;
    }
    span.style.display="none";
    return true;
};

const validarPass = (campo)=>{
    let contenido=campo.value.trim();
    let span=campo.nextElementSibling;
    let expresion=/^[a-zA-Z][a-zA-Z0-9_]{7,15}/;

    if(!expresion.test(contenido)){
        span.style.display="inline";
        span.innerText="Escribe una contraseña válida (mín. 8 máx. 16 carac. empieza por letra)";
        return false;
    }
    span.style.display="none";
    return true;
};

//funciones servicios ----------------------------------

const validarServicio=(campo)=>{
    let contenido=campo.value.trim();
    let span=campo.nextElementSibling;
    let reg_exp=/^[a-zA-Z \´]{3,50}$/;

    if(!validarTexto(contenido)){
        span.style.display="inline";
        span.innerText="El campo no puede estar vacío";
        return false;
    }else if(!reg_exp.test(contenido)){
        span.style.display="inline";
        span.innerText="El nombre debe tener entre 3 y 50 caracteres";
        return false;
    }else{
        span.style.display="none";
        return true;
    }
}

const validarDuracion = (campo)=>{
    let span=campo.nextElementSibling;

    if(!validarNum(campo, span)){
        return false;
    }
    
    span.style.display="none";
    return true;
}

const validarUDuracion = (campo)=>{
    let select=campo.value;
    let span=campo.nextElementSibling;

    if(select === ''){
        span.style.display="inline";
        span.innerText="Selecciona una opción";
        return false;
    }else if(select === 'minutos'){
        if(campo_duracion.value.trim() < 15){
            span.style.display="inline";
            span.innerText="La duración mínima es de 15 min.";
            return false;
        }
    }
    span.style.display="none";
    return true;
};

const validarPrecioServicio=(campo)=>{
    let contenido=campo.value.trim();
    let span=campo.nextElementSibling;
    let numero=parseInt(contenido);

    if(isNaN(numero) || numero < 1){
        span.style.display="inline";
        span.innerText="Escribe un precio válido";
        return false;
    }
    span.style.display="none";
    return true;
}

//funciones noticias --------------------------------

const validarImagenNoticia = (campo) => {
    let span=campo.parentNode.nextElementSibling;

    if(campo.files.length > 0){
        if(!validarImagen(campo, span)){
            return false;
        }
    }else{
        span.style.display="inline";
        span.innerText="Sube una imágen";
        return false;
    }

    span.style.display="none";
    return true;
};

const validarNoticia=(campo)=>{
    let contenido=campo.value.trim();
    let span=campo.nextElementSibling;
    let reg_exp=/^.{3,}$/;

    if(!validarTexto(contenido)){
        span.style.display="inline";
        span.innerText="El campo no puede estar vacío";
        return false;
    }else if(!reg_exp.test(contenido)){
        span.style.display="inline";
        span.innerText="Debes escribir al menos 3 caracteres";
        return false;
    }else{
        span.style.display="none";
        return true;
    }
}

const validarFecha = (campo, accion) => {
    let contenido=campo.value.trim();
    let span=campo.nextElementSibling;

    if(!contenido){
        span.style.display="inline";
        span.innerText="Selecciona una fecha";
        return false;
    }

    let fecha_seleccionada = new Date(contenido); //uso objeto tipo Date con la fecha escogida
    let fecha_actual = new Date(); //aqui lo creo vacio para darle la fecha actual
    fecha_actual.setHours(0, 0, 0, 0); // ajusto la hora de la fecha actual

    if(accion==="noticia"){
        if (fecha_seleccionada < fecha_actual) {
            span.style.display="inline";
            span.innerText="La fecha debe ser igual o posterior a la fecha actual";
            return false;
        }
    }else if(accion==="cita"){
        if (fecha_seleccionada <= fecha_actual) {
            span.style.display="inline";
            span.innerText="La fecha debe ser posterior a la actual";
            return false;
        }
    }
    span.style.display="none";
    return true;
};

//funciones testimonios --------------------------------

const validarTestimonio=(campo)=>{
    let contenido=campo.value.trim();
    let span=campo.nextElementSibling;
    
    if(!validarTexto(contenido)){
        span.style.display="inline";
        span.innerText="El campo no puede estar vacío";
        return false;
    }
    span.style.display="none";
    return true;
}

//funciones citas ----------------------------------------

const validarSelect = (campo)=>{
    let select=campo.value;
    let span=campo.nextElementSibling;

    if(select === ''){
        span.style.display="inline";
        span.innerText="Selecciona una opción";
        return false;
    }
    span.style.display="none";
    return true;
};

const validarHora = (campo)=>{
    let contenido=campo.value.trim();
    let span=campo.nextElementSibling;

    if(!contenido){
        span.style.display="inline";
        span.innerText="Selecciona una hora";
        return false;
    }
    span.style.display="none";
    return true;
}

//funciones de validación generales (llamadas por otras funciones en el código) --------------------------------

const validarTexto = (contenido)=>{ //gestiono el valor del campo directamente
    if(contenido === ""){
        return false;
    }else{
        return true;
    }
};

const validarImagen = (campo, span)=>{
    const tipo='image/jpeg';
    const tamaño=5*1024*1024; //lo calculo en bytes pues el tamaño se compara en bytes con .size
    let fichero=campo.files[0];

        if(fichero.type !== tipo){
            span.style.display="inline";
            span.innerText="No es un formato de imágen válido";
            return false;
        }else if(fichero.size > tamaño){
            span.style.display="inline";
            span.innerText="El tamaño máximo es de 5 MB";
            return false;
        }
        
        return true;
};

const validarNum = (contenido, span)=>{ //gestiono el campo y el span del mismo en esta función
    let numero=parseInt(contenido.value); //obtengo el value del campo
    console.log("funcionNum");
    if(isNaN(numero)){
        span.style.display="inline";
        span.innerText="Escribe un número";
        return false;
    }else if(numero < 1){
        span.style.display="inline";
        span.innerText="No puede ser negativo";
        return false;
    }
    span.style.display="none";
    return true;
};