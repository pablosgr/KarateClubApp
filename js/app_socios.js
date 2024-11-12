"use strict"

let añadir_socio=document.getElementById("formulario");

let campo_foto=document.getElementById("campo-foto");
let campo_nombre=document.getElementById("campo-nombre");
let campo_usuario=document.getElementById("campo-usuario");
let campo_edad=document.getElementById("campo-edad");
let campo_tlfn=document.getElementById("campo-tlfn");
let campo_pass=document.getElementById("campo-pass");

añadir_socio.addEventListener("submit",
    (evento)=>{
        let validaciones=[()=>validarTexto(campo_nombre), ()=>validarTexto(campo_usuario), validarEdad, validarTlfn, validarPass];
        for(let validacion of validaciones){
            if(!validacion()){
                evento.preventDefault();
                break;
            }
        }
})

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

const validarEdad = ()=>{
    let contenido=campo_edad.value.trim();
    let span=campo_edad.nextElementSibling;
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

const validarTlfn = ()=>{
    let contenido=campo_tlfn.value.trim();
    let span=campo_tlfn.nextElementSibling;
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