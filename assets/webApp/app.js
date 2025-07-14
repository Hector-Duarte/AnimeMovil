//desplegar menu de servidores
function menuServidores(){
  document.getElementById("servidorPush").addEventListener("click",function(){
     
     //mostrar servers
     document.getElementById("servidores").classList.toggle("x-on");

     //rotar boton
     document.getElementById("servidorPush").classList.toggle("x-on");

  });


}



//Activar boton de menu de usuarios
function menuUsuarioBar(){
    document.getElementById("usuarioNavegacion").getElementsByTagName("button")[0].addEventListener("click", function (){
        document.getElementById("usuarioNavegacion").classList.toggle("x-active");
    })
}


//Activar boton de buscador para celular
function buscadorMobile(){
    document.getElementById("buscadorBotonView").addEventListener("click", function (){
        document.getElementById("buscadorView").classList.toggle("x-active");
    })
}


//Activar boton para mostrar opciones de la barra menu
function OptionsMenuBar(){
    document.getElementById("navegacionOptionsButton").addEventListener("click", function (){
        document.getElementById("navegacionOptions").classList.toggle("x-active");
    })
}


//mostar o ucultar comentarios
function showComentarios(){


//localStorage
if(localStorage){ //comprobar si es compatible

//avaluar
if(localStorage.comentarios=="false"){

     //mostar o ucultar ojo icon
     document.getElementById("showComentarios").classList.toggle("x-off");

     //mostar o ucultar comentarios
     document.getElementById("comentarios").classList.toggle("x-off");

}else{
localStorage.comentarios="true";
}


}//fin de comparobar si es compatible localStorage

//evento
  document.getElementById("showComentarios").addEventListener("click",function(){
     
     //alterar localStorage
     if(localStorage){

     if(localStorage.comentarios=="true"){
         
         localStorage.comentarios="false";
         }else if(localStorage.comentarios=="false"){
         localStorage.comentarios="true";
         }
    


     }//fin localStorage


     //mostar o ucultar ojo icon
     document.getElementById("showComentarios").classList.toggle("x-off");

     //mostar o ucultar comentarios
     document.getElementById("comentarios").classList.toggle("x-off");

  });



}//fin funcion







//activar servidores stream
function serversStream(){


servers_stream=document.getElementById("servidores").getElementsByTagName("li");


//armar requests function para servidores
function requestStream(elementoNodo){

//guardar en favorito
localStorage.miServidor = elementoNodo.getAttribute("node");


     //cambiar server activo css     
            for(i=0;servers_stream[i];i++){
            servers_stream[i].classList.remove("x-active");
            }

     elementoNodo.classList.add("x-active");
     //cambiar server activo css



     //conexion a stream
xmlhttp=new XMLHttpRequest();
xmlhttp.onreadystatechange=function(){
if(xmlhttp.readyState==4){
if(xmlhttp.status>=200 && xmlhttp.status<300){

//recibir valores
apiStream=JSON.parse(xmlhttp.responseText);



                    //ejecutar segun el kind
                     switch(apiStream.result.kind) {
                         case 'jwplayer':
                                         //vaciar reproductor
                                         document.getElementById("parentPlayer").innerHTML='<div id="player"></div>';
                                         //ejecutar player
                                         configJW = apiStream.result.setup;



                                      if(episodio_info.imgCustom == 1){

                                                configJW.tracks = [{ 
                                                   "file": "/assets/media/episodio-"+ episodio_info.id +"_sprite.vtt", 
                                                   "kind": "thumbnails"
                                               }]

                                         }


                                         jwplayer("player").setup(configJW);
                             break;

                         case 'javascript':
                                         //vaciar reproductor
                                         document.getElementById("parentPlayer").innerHTML='';
                                         //ejecutar
                                         eval(apiStream.result.jsCode);
                             break;

                         case 'iframe':
                                         //ejecutar
                                         document.getElementById("parentPlayer").innerHTML='<iframe src="' + apiStream.result.src + '"></iframe>';
                             break;

                         default:
                             //code block

                     }





}else{
//error

alert("Error al obtener enlaces");


}}}


xmlhttp.withCredentials=true;

//vars gets
sendVars = "expire=" + episodio_info.stream.expire + "&callback=" + episodio_info.stream.callback + "&signature=" + episodio_info.stream.signature;

xmlhttp.open("GET", episodio_info.stream.accessPoint + episodio_info.id + "/" + elementoNodo.getAttribute('node') + "?" + sendVars,true);
xmlhttp.send();


     //conexion a stream
     }

//fin de conexion requests function






for(i=0;servers_stream[i];i++){

     servers_stream[i].addEventListener("click", function (){
        requestStream(document.querySelector("li[node="+this.getAttribute("node")+"]"))
     });//fin click
}


//lanzar servidor preferido
if(!localStorage.miServidor){

  //no tiene server favorito
  requestStream(document.querySelector("li[node='akiba']"));

}else if( document.querySelector("li[node=" + localStorage.miServidor + "]") ){

  //si tiene server y se ejecutara si se encuentra disponible
  requestStream(document.querySelector("li[node=" + localStorage.miServidor + "]"));

}else{
    //si tiene server favorito, pero no esta disponible
    requestStream(document.querySelector("li[node='akiba']"));
}


//fin de lanzar servidor AKIBA



}
//FIN activar servidores stream






//Buscado ajax

function buscadorAjax(){

inputSearch = document.getElementById("formNav").getElementsByTagName("input")[0]; //declarar input tag

inputSearch.addEventListener("keyup", function(){
//esta escribiendo

document.getElementsByClassName("buscadorResultados")[0].getElementsByTagName("ul")[0].innerHTML="";

xmlhttp=new XMLHttpRequest();
xmlhttp.onreadystatechange=function(){
if(xmlhttp.readyState==4){
if(xmlhttp.status>=200&&xmlhttp.status<300){

//recibir valores
apiResponse=JSON.parse(xmlhttp.responseText);

searchNum = 0;
                 while( searchNum < apiResponse.result.count ){

                       document.getElementsByClassName("buscadorResultados")[0].getElementsByTagName("ul")[0].innerHTML+=' <li><a href="/AnimeMovil/pagues/animeIndex.php?id=' + apiResponse.result.items[searchNum].id + '"><img src="/AnimeMovil/assets/media/anime-' + apiResponse.result.items[searchNum].id + '_pequena.jpg"></img> ' + apiResponse.result.items[searchNum].title + ' </a></li> ';


                       searchNum+=1;

                 }



}}}



//vars gets
sendVars = "q=" + inputSearch.value + "&limit=4";

xmlhttp.open("GET","/AnimeMovil/api/api.php?node=buscador&" + sendVars, true);


if(inputSearch.value != ""){

        xmlhttp.send();

}



//FIN esta escribiendo
});


inputSearch.addEventListener("blur", function(){
//quito el focus del input tag
setTimeout(function(){ 
  document.getElementsByClassName("buscadorResultados")[0].getElementsByTagName("ul")[0].innerHTML="";
}, 1000);

});


}


//FIN Buscador ajax


//Activar boton de borrar en la pogina favoritos

function favoritosBorrar(){
    botonesBorrar = document.getElementsByClassName("borrarFavorito");
    num = 0;

    while(num < botonesBorrar.length){
        botonesBorrar[num].addEventListener("click",function(){
            //lanzar solicitud para borrar
            xmlhttp=new XMLHttpRequest();
            xmlhttp.open("DELETE","/api/favoritos/" + this.getAttribute("node"), true);
            xmlhttp.send();

            //borrar elemento
            tag = document.getElementById(this.getAttribute("nodeId"));
            tag.parentNode.removeChild(tag);

        });

    num+=1;
    }
}

//FIN de Activar boton de borrar en la pogina favoritos




//ejecutar funciones solicitadas
functions_requerid();
