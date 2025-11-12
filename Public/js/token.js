//Esto va en la plantilla base para que se aplique a todos
fetch("url/obtenerToken.php").
    then((res)=>res.json()).
    then((json)=>{
        sessionStorage.setItem("token",json.token)
    })



//



//En las peticione ajax sus fetchs hay que meter ahora en el header el token
//y en la api  alumno preguntar por el token antes de hacer nada
let f=new FormData(formulario);

fetch("url",
    {
        headers: {Authotization:'Bearer {'+ sessionStorage.getItem("token") +'}'},
        method:post,
        body:f
    }

)


//El token se crea despues de iniciar sesion
//Clase security que genera el toekn y lo verifica


//Despues del crus las ofertas con php luego se hace las solicitudes
//a las ofertas con js y luego hacer desde el rol de empresario el apartado de valoracion