"use strict"

//variables formularios
let añadir_socio=document.getElementById("formulario");
let modificar_socio=document.getElementById("formulario-mod");
let añadir_testimonio=document.getElementById("formulario-testimonio");
let form_servicio=document.getElementById("formulario-servicios");
let form_noticia=document.getElementById("formulario-noticias");

//campos socios
let campo_foto=document.getElementById("campo-foto");
let campo_nombre=document.getElementById("campo-nombre");
let campo_usuario=document.getElementById("campo-usuario");
let campo_edad=document.getElementById("campo-edad");
let campo_tlfn=document.getElementById("campo-tlfn");
let campo_pass=document.getElementById("campo-pass");

let campo_nombre_mod=document.getElementById("nombre-mod");
let campo_usuario_mod=document.getElementById("user-mod");
let campo_edad_mod=document.getElementById("edad-mod");
let campo_tlfn_mod=document.getElementById("tlfn-mod");

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

//eventos

//compruebo que el formulario esté presente en la página -sea distinto de null- antes de ejecutar el código 
//para evitar que intente ejecutar y falle en páginas donde falte algún formulario
if(añadir_socio){
    añadir_socio.addEventListener("submit",
        (evento)=>{
            let validaciones=[()=>validarTexto(campo_nombre),
                ()=>validarTexto(campo_usuario),
                ()=>validarEdad(campo_edad),
                ()=>validarTlfn(campo_tlfn),
            ()=>validarPass()];

            for(let validacion of validaciones){
                if(!validacion()){
                    evento.preventDefault();
                    break;
                }
            }
    });
}

if(modificar_socio){
    modificar_socio.addEventListener("submit", 
        (evento)=>{
            let validaciones=[()=>validarTexto(campo_nombre_mod),
                ()=>validarTexto(campo_usuario_mod),
                ()=>validarEdad(campo_edad_mod),
            ()=>validarTlfn(campo_tlfn_mod)];
    
            for (let val of validaciones){
                if(!val()){
                    evento.preventDefault();
                    break;
                }
            }
    });
}

if(añadir_testimonio){
    añadir_testimonio.addEventListener("submit",
        (evento)=>{
            if(!validarTexto(campo_texto_test)){
                evento.preventDefault();
            }
        }
    );
}

//este evento actua en los formularios de adición y modificación
if(form_servicio){
    form_servicio.addEventListener("submit",
        (evento)=>{
            let validaciones=[
                ()=>validarTexto(campo_descripcion),
                ()=>validarNum(campo_duracion),
                ()=>validarSelect(campo_u_duracion),
                ()=>validarNum(campo_precio)
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
                ()=>validarImagen(campo_imagen),
                ()=>validarTexto(campo_titulo),
                ()=>validarTexto(campo_noticia),
                ()=>validarFecha(campo_fecha)
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


//funciones

const validarTexto = (campo)=>{
    let contenido=campo.value.trim();
    let span=campo.nextElementSibling;
    if(contenido === ""){
        span.style.display="inline";
        span.innerText="El campo es obligatorio";
        return false;
    }
    span.style.display="none";
    return true;
};

const validarEdad = (campo)=>{
    let contenido=campo.value.trim();
    let span=campo.nextElementSibling;
    let numero=parseInt(contenido);

    if(isNaN(numero)){
        span.style.display="inline";
        span.innerText="Debes escribir un número";
        return false;
    }else if(numero < 1 || numero > 100){
        span.style.display="inline";
        span.innerText="Escribe una edad válida";
        return false;
    }
    span.style.display="none";
    return true;
};

const validarNum = (campo)=>{
    let contenido=campo.value.trim();
    let span=campo.nextElementSibling;
    let numero=parseInt(contenido);

    if(isNaN(numero)){
        span.style.display="inline";
        span.innerText="Debes escribir un número";
        return false;
    }else if(numero < 1){
        span.style.display="inline";
        span.innerText="Escribe un número válido";
        return false;
    }
    span.style.display="none";
    return true;
};

const validarSelect = (campo)=>{
    let select=campo.value;
    let span=campo.nextElementSibling;

    if(select === ''){
        span.style.display="inline";
        span.innerText="Selecciona una opción válida";
        return false;
    }
    span.style.display="none";
    return true;
};

const validarTlfn = (campo)=>{
    let contenido=campo.value.trim();
    let span=campo.nextElementSibling;
    let expresion=/^[0-9]{9}$/;
    if(!expresion.test(contenido)){
        span.style.display="inline";
        span.innerText="Escribe un teléfono válido";
        return false;
    }
    span.style.display="none";
    return true;
};

const validarPass = ()=>{
    let contenido=campo_pass.value.trim();
    let span=campo_pass.nextElementSibling;
    let expresion=/[a-zA-Z0-9]{4}/;
    if(!expresion.test(contenido)){
        span.style.display="inline";
        span.innerText="Escribe una contraseña válida";
        return false;
    }
    span.style.display="none";
    return true;
};

const validarImagen = (campo) => {
    const tipos_admitidos=['image/png', 'image/jpg', 'image/jpeg', 'image/webp'];
    let span=campo.parentNode.nextElementSibling;

    if(campo.files.length > 0){
        let fichero=campo.files[0];
        if(!tipos_admitidos.includes(fichero.type)){
            span.style.display="inline";
            span.innerText="No es un formato de imágen válido";
            return false;
        }
    }else{
        span.style.display="inline";
        span.innerText="Sube una imágen para la noticia";
        return false;
    }
    span.style.display="none";
    return true;
};

const validarFecha = (campo) => {
    let contenido=campo.value.trim();
    let span=campo.nextElementSibling;

    if(!contenido){
        span.style.display="inline";
        span.innerText="Selecciona una fecha para la noticia";
        return false;
    }
    span.style.display="none";
    return true;
};