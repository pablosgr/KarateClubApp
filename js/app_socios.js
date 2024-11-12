"use strict"

//variables

let añadir_socio=document.getElementById("formulario");
let modificar_socio=document.getElementById("formulario-mod");

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

//eventos

console.log(campo_usuario_mod);

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
            let validaciones_mod=[()=>validarTexto(campo_nombre_mod),
                ()=>validarTexto(campo_usuario_mod),
                ()=>validarEdad(campo_edad_mod),
            ()=>validarTlfn(campo_tlfn_mod)];
    
            for (let val of validaciones_mod){
                if(!val()){
                    evento.preventDefault();
                    break;
                }
            }
    });
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
    }else if(numero < 0 || numero > 100){
        span.style.display="inline";
        span.innerText="Escribe una edad válida";
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
}

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
}