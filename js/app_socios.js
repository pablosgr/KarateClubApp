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
            let validaciones=[
                ()=>validarTexto(campo_nombre),
                ()=>validarTexto(campo_usuario),
                ()=>validarEdad(campo_edad),
                ()=>validarTlfn(campo_tlfn),
                ()=>validarPass()
            ];

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
            let validaciones=[
                ()=>validarTexto(campo_nombre_mod),
                ()=>validarTexto(campo_usuario_mod),
                ()=>validarEdad(campo_edad_mod),
                ()=>validarTlfn(campo_tlfn_mod)
            ];
    
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
            if(!validarTestimonio(campo_texto_test)){
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
                ()=>validarServicio(campo_descripcion),
                ()=>validarNum(campo_duracion),
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
                ()=>validarImagen(campo_imagen),
                ()=>validarNoticia(campo_titulo),
                ()=>validarNoticia(campo_noticia),
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


//funciones servicios ----------------------------------

const validarServicio=(campo)=>{
    let contenido=campo.value.trim();
    let span=campo.nextElementSibling;
    let reg_exp=/^[a-zA-Z ]{3,50}$/;

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

const validarUDuracion = (campo)=>{
    let select=campo.value;
    let span=campo.nextElementSibling;

    if(select === ''){
        span.style.display="inline";
        span.innerText="Selecciona una opción válida";
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

const validarFecha = (campo) => {
    let contenido=campo.value.trim();
    let span=campo.nextElementSibling;

    if(!contenido){
        span.style.display="inline";
        span.innerText="Selecciona una fecha para la noticia";
        return false;
    }

    let fecha_seleccionada = new Date(contenido); //uso objeto tipo Date con la fecha escogida
    let fecha_actual = new Date(); //aqui lo creo vacio para darle la fecha actual
    fecha_actual.setHours(0, 0, 0, 0); // ajustar la hora de la fecha actual

    if (fecha_seleccionada < fecha_actual) {
        span.style.display="inline";
        span.innerText="La fecha debe ser igual o posterior a la fecha actual";
        return false;
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

//funciones validacion general --------------------------------

const validarTexto = (contenido)=>{
    if(contenido === ""){
        return false;
    }else{
        return true;
    }
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
    const tipo='image/jpeg';
    const tamaño=5*1024*1024; //el tamaño se compara en bytes
    let span=campo.parentNode.nextElementSibling;

    if(campo.files.length > 0){
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
    }else{
        span.style.display="inline";
        span.innerText="Sube una imágen";
        return false;
    }
    span.style.display="none";
    return true;
};