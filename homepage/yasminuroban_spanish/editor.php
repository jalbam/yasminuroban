<?php
    //Se configura el BBClone:
    define("_BBC_PAGE_NAME", "spanish editor online");
    define("_BBCLONE_DIR", "../bbclone/");
    define("COUNTER", _BBCLONE_DIR."mark_page.php");
    if (is_readable(COUNTER)) include_once(COUNTER);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title>Editor de niveles para Yasminuroban &copy; por Joan Alba Maldonado</title>
        <!-- (c) Edidor de niveles para Yasminuroban - Programa realizado por Joan Alba Maldonado (granvino@granvino.com). Prohibido publicar, reproducir o modificar sin citar expresamente al autor original. -->
        <script language="JavaScript1.2" type="text/javascript">
            <!--
                //(c) Editor de niveles para Yasminuroban - Programa realizado por Joan Alba Maldonado (granvino@granvino.com). Prohibido publicar, reproducir o modificar sin citar expresamente al autor original.


                //Ancho del mapa (numero de paneles):
                var mapa_width = 10;
                //Alto del mapa (numero de paneles):
                var mapa_height = 10;
            
                //Ancho del mapa anterior (numero de paneles):
                var mapa_width_anterior = mapa_width;
                //Alto del mapa anterior (numero de paneles):
                var mapa_height_anterior = mapa_height;

                //El ancho de la celda (pixels):
                var celda_width = 40;
                //El alto de la celda (pixels):
                var celda_height = 40;

                //Variable que guarda la opcion seleccionada:
                var opcion_seleccionada = "";

                //Matriz para saber si una celda esta pintada:
                var celdas_pintadas = new Array();

                //Variable que guarda el codigo del mapa:
                var mapa_codigo = "";


                //Crea las imagenes:
                if (document.images)
                 {
                     //Imagenes usadas:
                     var personaje_imagen = new Image(celda_width, celda_height);
                     nombre_personaje_imagen = "img/yas_down1.gif";
                     personaje_imagen.src = nombre_personaje_imagen; //Imagen del personaje.
                     var personaje_copa_imagen = new Image(celda_width, celda_height);
                     nombre_personaje_copa_imagen = "img/yas_copa_editor.gif";
                     personaje_copa_imagen.src = nombre_personaje_copa_imagen; //Imagen del personaje encima de una copa vacia (agujero).
                     var pared_imagen = new Image(celda_width, celda_height);
                     nombre_pared_imagen = "img/piedra1.gif";
                     pared_imagen.src = nombre_pared_imagen; //Imagen de la pared.
                     var agujero_vacio_imagen = new Image(celda_width, celda_height);
                     nombre_agujero_vacio_imagen = "img/copa1.gif";
                     agujero_vacio_imagen.src = nombre_agujero_vacio_imagen; //Imagen del agujero vacio.
                     var agujero_lleno_imagen = new Image(celda_width, celda_height);
                     nombre_agujero_lleno_imagen = "img/copa2.gif"
                     agujero_lleno_imagen.src = nombre_agujero_lleno_imagen; //Imagen del agujero lleno.
                     var pieza_imagen = new Image(celda_width, celda_height);
                     nombre_pieza_imagen = "img/botella.gif";
                     pieza_imagen.src = nombre_pieza_imagen; //Imagen de la pieza.
                     var borrar_imagen = new Image(celda_width, celda_height);
                     nombre_borrar_imagen = "img/borrar.gif";
                     borrar_imagen.src = nombre_borrar_imagen; //Imagen de borrar.
                 }
                

                //Funcion que inicia el editor:
                function iniciar_editor()
                 {
                     //Se aplican las imagenes al menu:
                     document.getElementById("imagen_personaje").src = personaje_imagen.src;
                     document.getElementById("imagen_personaje_copa").src = personaje_copa_imagen.src;
                     document.getElementById("imagen_pared").src = pared_imagen.src;
                     document.getElementById("imagen_agujero_vacio").src = agujero_vacio_imagen.src;
                     document.getElementById("imagen_agujero_lleno").src = agujero_lleno_imagen.src;
                     document.getElementById("imagen_pieza").src = pieza_imagen.src;
                     document.getElementById("imagen_borrar").src = borrar_imagen.src;

                     //Se prepara el mapa con las medidas y con los divs que tiene dentro:
                     preparar_mapa();
                 }


                //Funcion que marca un div:
                function marcar_div(nombre_div)
                 {
                      //Si el div no esta seleccionado:
                      if (opcion_seleccionada != nombre_div) { document.getElementById(nombre_div).style.border = "2px dotted #000000"; } //Le pone al div el borde definido.
                 }
                     

                 //Funcion que desmarca un div:
                 function desmarcar_div(nombre_div)
                  {
                      //Si el div no esta seleccionado:
                      if (opcion_seleccionada != nombre_div) { document.getElementById(nombre_div).style.border = ""; } //Le quita el borde al div.
                  }
                  

                //Funcion que selecciona un div:
                function seleccionar_div(nombre_div)
                 {
                      //Se deselecciona el div anterior, si existe:
                      if (opcion_seleccionada != "") { document.getElementById(opcion_seleccionada).style.border = ""; }
                      //Se selecciona el div enviado:
                      document.getElementById(nombre_div).style.border = "3px dotted #ff0000"; //Le pone al div el borde definido.
                      //Se pone como opcion seleccionada el div enviado:
                      opcion_seleccionada = nombre_div;
                 }
                

                //Funcion que prepara el mapa con las medidas y con los divs que tiene dentro:
                function preparar_mapa()
                 {
                    //Se borra el contenido del mapa:    
                    document.getElementById("mapa").innerHTML = "";
                    
                    //Cambia las medidas del mapa:
                    document.getElementById("mapa").style.width = celda_width * mapa_width + 6 + "px";
                    document.getElementById("mapa").style.height = celda_height * mapa_height + 6 + "px";
                    
                    //Pone en el formulario los nuevos valores:
                    document.getElementById("formulario_size").mapa_x.value = mapa_width;
                    document.getElementById("formulario_size").mapa_y.value = mapa_height;
                    
                    //Crea los divs dentro del mapa:
                    var contador_columnas = 0;
                    var contador_filas = 0;
                    var celda_bg = "";
                    var mapa_temporal = ""; //Variable donde se guardara el mapa temporal, para ir poniendo los DIV.
                    for (x=0; x<mapa_width*mapa_height; x++)
                     {
                        celda_bg = (celda_bg == "#ffffff") ? "#f5f5f5" : "#ffffff";
                        var celda_left = contador_columnas * celda_width;
                        var celda_top = contador_filas * celda_height;
                        //Se escribe el la casilla el div que contendra la imagen al hacer click:
                        mapa_temporal += '<div id="celda_'+x+'" style="background:'+celda_bg+'; color:#bbbbbb; left:'+celda_left+'px; top:'+celda_top+'px; width:'+celda_width+'px; height:'+celda_height+'px; position:absolute; text-align:center; font-family:verdana; font-size:12px; line-height:20px; padding:0px; z-index:2;">'+x+'</div>';
                        //Se escribe en la casilla el div que contendra la imagen provisional al posicionarse el cursor encima:
                        mapa_temporal += '<div id="celda_'+x+'_provisional" style="background:url(\'img/blank.gif\'); color:#bbbbbb; left:'+celda_left+'px; top:'+celda_top+'px; width:'+celda_width+'px; height:'+celda_height+'px; position:absolute; text-align:center; font-family:verdana; font-size:12px; line-height:20px; padding:0px; z-index:3;"><img src="img/blank.gif" width="'+celda_width+'" height="'+celda_height+'" hspace="0" vspace="0"></div>';
                        //Se escribe en la casilla el div que se utilizara para llamar a los eventos de javascript (onMouseOver, onMouseOut, onMouseDown):
                        mapa_temporal += '<div id="celda_'+x+'_eventos" style="background:url(\'img/blank.gif\'); color:#bbbbbb; left:'+celda_left+'px; top:'+celda_top+'px; width:'+celda_width+'px; height:'+celda_height+'px; position:absolute; text-align:center; font-family:verdana; font-size:12px; line-height:20px; padding:0px; z-index:4;" onMouseDown="javascript:pintar_celda(event, \'celda_'+x+'\');" onMouseOver="javascript:posicionar_celda(\'celda_'+x+'\');" onMouseOut="javascript:desposicionar_celda(\'celda_'+x+'\');" onContextMenu="javascript:return false;"><img src="img/blank.gif" width="'+celda_width+'" height="'+celda_height+'" hspace="0" vspace="0"></div>';
                        contador_columnas++;
                        celdas_pintadas["celda_"+x] = " ";
                        if (contador_columnas >= mapa_width) { contador_filas++; contador_columnas = 0; celda_bg = (celda_bg == "#ffffff") ? "#dddddd" : "#ffffff"; }
                     }

                    //Se crea el mapa volcandole el mapa temporal:
                    document.getElementById("mapa").innerHTML = mapa_temporal;

                    //Se actualiza el codigo del mapa:
                    actualizar_mapa_codigo();

                    //Se deja de avisar de que se espere:
                    document.getElementById('mensaje_espera').style.visibility='hidden';
                 }


                //Funcion que cambia las medidas del mapa:
                function resizear_mapa()
                 {
                    //Si se presiona cancelar, esconde el mensaje de espera, restaura el ancho y alto anteriores y se sale de la funcion:
                    if (!confirm("Presiona aceptar si deseas cambiar la capacidad del mapa (y perder el actual)"))
                     {
                        document.getElementById('mensaje_espera').style.visibility='hidden';
                        document.getElementById("formulario_size").mapa_x.value = mapa_width_anterior;
                        document.getElementById("formulario_size").mapa_y.value = mapa_height_anterior;
                        return;
                     }

                    //Si no se ha escrito un numero, se restaura el valor de ancho y alto anterior, se esconde el mensaje de espera, y sale de la funcion:
                    if (parseInt(document.getElementById("formulario_size").mapa_x.value) < 1 || isNaN(parseInt(document.getElementById("formulario_size").mapa_x.value)) || isNaN(parseInt(document.getElementById("formulario_size").mapa_y.value)))
                     {
                        //alert("Debes escribir un valor numerico"); //este alert da error en Firefox 1.0.3! xD
                        document.getElementById('mensaje_espera').style.visibility='hidden';
                        document.getElementById("formulario_size").mapa_x.value = mapa_width_anterior;
                        document.getElementById("formulario_size").mapa_y.value = mapa_height_anterior;
                        return;
                     }

                    //Cambia las medidas del mapa, segun los valores escritos en el formulario:
                    mapa_width = document.getElementById("formulario_size").mapa_x.value;
                    mapa_height = document.getElementById("formulario_size").mapa_y.value;

                    //Se define el ancho y alto anterior (para restaurarlo en caso de que se inserte un valor no numerico o erroneo):
                    mapa_width_anterior = mapa_width;
                    mapa_height_anterior = mapa_height;
                    
                    //Aplica los cambios:
                    preparar_mapa();
                 }


                //Funcion que pinta una celda temporalmente, al pasar el cursor:
                function posicionar_celda(nombre_celda)
                 {
                    //Si no se ha seleccionado ninguna opcion a pintar, se sale de la funcion:
                    if (opcion_seleccionada == "") { return; }

                    //Se muestra provisionalmente la imagen de la opcion seleccionada en la celda:
                    document.getElementById(nombre_celda+"_provisional").innerHTML = document.getElementById(opcion_seleccionada).innerHTML;
                 }
                 

                //Funcion que borra una celda temporal, al sacar el cursor:
                function desposicionar_celda(nombre_celda)
                 {
                    //Si no se ha seleccionado ninguna opcion a pintar, se sale de la funcion:
                    if (opcion_seleccionada == "") { return; }
                    
                    //Se borra la imagen provisional de la celda:
                    document.getElementById(nombre_celda+"_provisional").innerHTML = '<img src="img/blank.gif" width="'+celda_width+'" height="'+celda_height+'" hspace="0" vspace="0">';
                 }


                //Funcion que pinta una celda definitivamente:
                function pintar_celda(e, nombre_celda)
                 {
                    //Se recoge el numero de boton segun el navegador:
                    if (e != 0)
                     {
                        //Se guarda el boton del raton segun el navegador:
                        var boton_raton = (e.which) ? e.which : event.button;                     
                        //Si se ha pulsado el boton derecho, borra la celda y sale de la funcion:
                        if (boton_raton == 2 || boton_raton == 3) { borrar_celda(nombre_celda); return; }
                     }

                    //Si no se ha seleccionado ninguna opcion a pintar, se sale de la funcion:
                    if (opcion_seleccionada == "") { alert("Debes elegir una opcion del menu de arriba para poder pintar."); return; }
                    
                    //Si no se ha seleccionado la goma de borrar, se pinta la celda:
                    if (opcion_seleccionada != "div_borrar") { document.getElementById(nombre_celda).innerHTML = document.getElementById(opcion_seleccionada).innerHTML; }
                    //...pero si se ha seleccionado la goma de borrar, se borra la celda (y se le pone su numero):
                    else { var numero_celda_borrar = nombre_celda.substring(6, nombre_celda.length); document.getElementById(nombre_celda).innerHTML = numero_celda_borrar; }

                    //Se actualiza el codigo del mapa con el caracter que corresponde a la opcion elegida:
                    if (opcion_seleccionada == "div_personaje") { celdas_pintadas[nombre_celda] = "@"; }
                    else if (opcion_seleccionada == "div_personaje_copa") { celdas_pintadas[nombre_celda] = "+"; }
                    else if (opcion_seleccionada == "div_pared") { celdas_pintadas[nombre_celda] = "#"; }
                    else if (opcion_seleccionada == "div_agujero_vacio") { celdas_pintadas[nombre_celda] = "�"; }
                    else if (opcion_seleccionada == "div_agujero_lleno") { celdas_pintadas[nombre_celda] = "*"; }
                    else if (opcion_seleccionada == "div_pieza") { celdas_pintadas[nombre_celda] = "$"; }
                    else { celdas_pintadas[nombre_celda] = " "; }

                    //Se actualiza el codigo del mapa:
                    actualizar_mapa_codigo();
                 }


                //Funcion que borra una celda:
                function borrar_celda(nombre_celda)
                 {
                    //Se guarda la opcion seleccionada:
                    var opcion_anterior = opcion_seleccionada;
                    //Se cambia la opcion seleccionada a la de borrar:
                    opcion_seleccionada = "div_borrar";
                    //Se borra la celda:
                    pintar_celda(0, nombre_celda);
                    //Se restaura la opcion seleccionada anteriormente:
                    opcion_seleccionada = opcion_anterior;
                 }

                //Funcion que recoge el mapa pasado por url y lo representa:
                function recoger_mapa_url()
                 {
                    //Se guarda en una variable el contenido de la url:
                    //var url = unescape(window.location.href);
                    var url = window.location.href;

                    //Si la url esta vacia, sale de la funcion:
                    if (url == "") { return; }

                    //Se extrae el contenido de la primera variable enviada por GET (puede tener cualquier nombre, pero correspondera al MAPA):
                    var mapa_url_sucio = url.substring(url.indexOf("?")+1, url.length);
                    var mapa_url = mapa_url_sucio.substring(mapa_url_sucio.indexOf("=")+1, mapa_url_sucio.indexOf("&width="));
    
                    //Se extrae el contenido de la variable "width" enviada por GET:
                    var mapa_width_url_sucio = mapa_url_sucio.substring(mapa_url_sucio.indexOf("&")+1, mapa_url_sucio.indexOf("&height="));
                    var mapa_width_url = mapa_width_url_sucio.substring(mapa_width_url_sucio.indexOf("=")+1, mapa_url_sucio.indexOf("&height="));

                    //Se extrae el contenido de la variable "height" enviada por GET:
                    var mapa_height_url_sucio = mapa_url_sucio.substring(mapa_url_sucio.indexOf("&height=")+1, mapa_url_sucio.length);
                    var mapa_height_url = mapa_height_url_sucio.substring(mapa_height_url_sucio.indexOf("=")+1, mapa_height_url_sucio.length);

                    //Si alguna de las variables esta vacia o el width o el height no son numericos, sale de la funcion:
                    if (mapa_url == "" || mapa_width_url == "" || mapa_height_url == "" || isNaN(mapa_width_url) || isNaN(mapa_height_url)) { return; }
                    //...y si no, procede:
                    else
                     {
                            //Se setea el ancho recogido:
                            mapa_width = parseInt(unescape(mapa_width_url));
                            //Se setea el alto recogido:
                            mapa_height = parseInt(unescape(mapa_height_url));
                            
                            //Se quita los caracteres de escape del mapa, y se remplazan los simbolos enviados por los correctos:
                            var mapa_url_refinado = unescape(mapa_url);
                            // A => (espacio en blanco)
                            // B => #
                            // C => @
                            // D => $
                            // E => �
                            // F => *
                            // G => +
                            
                            //Si el mapa es superior o inferior a como deberia ser (no corresponde al ancho y alto enviados), se sale de la funcion:
                            if (mapa_url_refinado.length != mapa_width*mapa_height) { alert("Imposible cargar el mapa enviado por url ya que no corresponde al alto y ancho enviados."); mapa_width = 10; mapa_height = 10; preparar_mapa(); return; }
                            
                            //Quita las posibles almohadillas de la url (aparecen al probar mapa, etc.):
                            for (x=0; x<=mapa_url_refinado.length; x++) { mapa_url_refinado = mapa_url_refinado.replace("#", ""); }
                            
                            //Cambia el codigo:
                            for (x=0; x<=mapa_url_refinado.length; x++)
                             {
                                mapa_url_refinado = mapa_url_refinado.replace("A", " ");
                                mapa_url_refinado = mapa_url_refinado.replace("B", "#");
                                mapa_url_refinado = mapa_url_refinado.replace("C", "@");
                                mapa_url_refinado = mapa_url_refinado.replace("D", "$");
                                mapa_url_refinado = mapa_url_refinado.replace("E", "�");
                                mapa_url_refinado = mapa_url_refinado.replace("F", "*");
                                mapa_url_refinado = mapa_url_refinado.replace("G", "+");
                             }

                            //Se introducen dentro del mapa:
                            preparar_mapa();

                            //Se guarda el mapa en la matriz y se representa en el div:
                            var contador_indice = 0;
                            var div_actual = "";
                            for (y=0; y<mapa_url_refinado.length; y++)
                             {
                                if (mapa_url_refinado.substring(y,y+1) == "#") { div_actual = "div_pared"; }
                                else if (mapa_url_refinado.substring(y,y+1) == "@") { div_actual = "div_personaje"; }
                                else if (mapa_url_refinado.substring(y,y+1) == "+") { div_actual = "div_personaje_copa"; }
                                else if (mapa_url_refinado.substring(y,y+1) == "�") { div_actual = "div_agujero_vacio"; }
                                else if (mapa_url_refinado.substring(y,y+1) == "*") { div_actual = "div_agujero_lleno"; }
                                else if (mapa_url_refinado.substring(y,y+1) == "$") { div_actual = "div_pieza"; }

                                celdas_pintadas["celda_"+contador_indice] = mapa_url_refinado.substring(y,y+1);

                                if (div_actual != "" && mapa_url_refinado.substring(y,y+1) != " ") { document.getElementById("celda_"+contador_indice).innerHTML = document.getElementById(div_actual).innerHTML; }
                                
                                actualizar_mapa_codigo();

                                contador_indice++;
                                div_actual = "";
                             }                   
                            
                     }
                    
                 }


                //Funcion que actualiza el codigo del mapa:
                function actualizar_mapa_codigo()
                 {
                    mapa_codigo = "";
                    var mapa_codigo_refinado = "";
                    for (x=0; x<mapa_width*mapa_height; x++)
                     {
                        if (celdas_pintadas["celda_"+x] == " ") { mapa_codigo += "&nbsp;"; mapa_codigo_refinado += "A"; }
                        else if (celdas_pintadas["celda_"+x] == "@") { mapa_codigo += "@"; mapa_codigo_refinado += "C"; }
                        else if (celdas_pintadas["celda_"+x] == "#") { mapa_codigo += "#"; mapa_codigo_refinado += "B"; }
                        else if (celdas_pintadas["celda_"+x] == "�") { mapa_codigo += "�"; mapa_codigo_refinado += "E"; }
                        else if (celdas_pintadas["celda_"+x] == "*") { mapa_codigo += "*"; mapa_codigo_refinado += "F"; }
                        else if (celdas_pintadas["celda_"+x] == "$") { mapa_codigo += "$"; mapa_codigo_refinado += "D"; }
                        else if (celdas_pintadas["celda_"+x] == "+") { mapa_codigo += "+"; mapa_codigo_refinado += "G"; }
                        else { mapa_codigo += "&nbsp;"; mapa_codigo_refinado += "A"; }
                     }
            
                    document.getElementById("mapa_codigo").style.top = mapa_height * celda_height + parseInt(document.getElementById("mapa").style.top) + parseInt(celda_height/2) + "px";
                    document.getElementById("mapa_codigo").innerHTML = '&nbsp; <font size="1" face="arial">//(c) Editor de niveles para Yasminuroban - Programa realizado por Joan Alba Maldonado (granvino@granvino.com). Prohibido publicar, reproducir o modificar sin citar expresamente al autor original.</font><br>&nbsp; niveles[0] = new Array(); &nbsp;<br>&nbsp; niveles[0]["forma"] = "'+mapa_codigo+'"; &nbsp;<br>&nbsp; niveles[0]["width"] = '+mapa_width+'; &nbsp;<br>&nbsp; niveles[0]["height"] = '+mapa_height+'; &nbsp;';

                    //Se actualiza el enlace para probar el juego:
                    var url_probar = 'index.php?mapa='+escape(mapa_codigo_refinado)+'&width='+escape(mapa_width)+'&height='+escape(mapa_height);
                    document.getElementById("probar_juego").innerHTML = '[ <a href="javascript:vaciar_mapa();" style="color:#ff0000;">Vaciar mapa</a> - <a href="javascript:probar_mapa(\''+url_probar+'\');" style="color:#ff0000;">Probar mapa</a> ]';
                 }


                //Funcion que calcula si el mapa es correcto, y lo prueba si es asi:
                function probar_mapa(url_probar)
                 {
                    //Variable para saber si han habido errores:
                    var probar_mapa_errores = false;
                    //Variable que guarda los mensajes de errores para luego mostrarlos en un alert:
                    var probar_mapa_errores_texto = "";
                    
                    //Bucle que cuenta cuantos personajes, personaje+copa, botellas y copas hay en el mapa:
                    var contador_agujeros = 0;
                    var contador_piezas = 0;
                    var contador_personajes = 0;

                    for (x=0; x<mapa_width*mapa_height; x++)
                     {
                        if (celdas_pintadas["celda_"+x] == "@") { contador_personajes++; } //Se ha encontrado un personaje.
                        if (celdas_pintadas["celda_"+x] == "+") { contador_personajes++; contador_agujeros++; } //Se ha encontrado un personaje+copa.
                        if (celdas_pintadas["celda_"+x] == "$") { contador_piezas++; } //Se ha encontrado una pieza (botella).
                        if (celdas_pintadas["celda_"+x] == "�") { contador_agujeros++; } //Se ha encontrado un agujero (copa vacia).
                     }
                    
                    //Si hay mas de un personaje (personaje o personaje+copa), ponerlo en el log de errores y setear como que hay error:
                    if (contador_personajes > 1) { probar_mapa_errores_texto += "\n* Hay mas de un personaje."; probar_mapa_errores = true; }
                    
                    //Si hay mas botellas que copas vacias, ponerlo en el log de errores y setear como que hay error:
                    if (contador_piezas > contador_agujeros) { probar_mapa_errores_texto += "\n* El numero de piezas (botellas) es superior al numero de agujeros (copas vacias)."; probar_mapa_errores = true; }
                    
                    //Si no hay personaje, no hay copas vacias o no hay botellas, ponerlo en el log de errores y setear como que hay error:
                    if (contador_personajes <= 0) { probar_mapa_errores_texto += "\n* No hay personaje."; probar_mapa_errores = true; }
                    if (contador_piezas <= 0) { probar_mapa_errores_texto += "\n* No hay piezas (botellas), se necesita al menos una."; probar_mapa_errores = true; }
                    if (contador_agujeros <= 0) { probar_mapa_errores_texto += "\n* No hay agujeros (copas vacias), se necesita al menos una."; probar_mapa_errores = true; }
                    
                    //Si han habido errores, se muestra en un alert y sale de la funcion:
                    if (probar_mapa_errores) { alert("Imposible probar el mapa porque:"+probar_mapa_errores_texto); return; }
                    
                    //Si se pulsa aceptar, ir a probar el mapa:
                    if (confirm('Pulsa aceptar para probar el mapa actual')) { window.location = url_probar; }
                 }


                //Funcion que borra todo el mapa:
                function vaciar_mapa()
                 {
                    //Si se presiona cancelar, salir de la funcion:
                    if (!confirm("Presiona aceptar si deseas borrar el mapa actual")) { return; }
                    //Se vuelve a crear el mapa, para vaciarlo:
                    //iniciar_editor();
                    document.getElementById('mensaje_espera').style.visibility='visible';
                    setTimeout("preparar_mapa(); document.getElementById('mensaje_espera').style.visibility='hidden';", 10);
                 }

            //-->
        </script>
    </head>
    <body bgcolor="#ffffff" onLoad="javascript:document.getElementById('mensaje_espera').style.visibility='visible'; setTimeout('iniciar_editor(); recoger_mapa_url(); document.getElementById(\'mensaje_espera\').style.visibility=\'hidden\'', 10);">
        <!-- Mensaje de espera: -->
        <div style="left:10px; top:30px; position:absolute; color:#000000; background:#ddaadd; font-weight:bold; font-family:arial; font-size:20px; padding:0px; z-index:30; visibility=visible; filter:alpha(opacity=70); opacity:0.7; -moz-opacity:0.7;" id="mensaje_espera">&nbsp; <i>Cargando...</i> Por favor, espera. &nbsp;</div>
        <!-- Fin de Mensaje de espera. -->
        <div style="left:10px; top:10px; width:240px; height:20px; position:absolute; font-weight:bold; font-family:verdana; font-size:14px;">
            Elige una imagen:
        </div>
        <!-- Botones: -->
        <div id="div_personaje" style="left:10px; top:40px; width:44px; height:40px; text-align:center; position:absolute;" onMouseOver="javascript:marcar_div('div_personaje');" onMouseOut="javascript:desmarcar_div('div_personaje');" onClick="javascript:seleccionar_div('div_personaje');">
            <img src="img/yas_down1.gif" width="40" height="40" hspace="0" vspace="0" title="Click aqu&iacute; para seleccionar" alt="personaje" id="imagen_personaje">
        </div>
        <div id="div_personaje_copa" style="left:90px; top:40px; width:44px; height:40px; text-align:center; position:absolute;" onMouseOver="javascript:marcar_div('div_personaje_copa');" onMouseOut="javascript:desmarcar_div('div_personaje_copa');" onClick="javascript:seleccionar_div('div_personaje_copa');">
            <img src="img/yas_copa_editor.gif" width="40" height="40" hspace="0" vspace="0" title="Click aqu&iacute; para seleccionar" alt="personaje encima de copa vac&iacute;a (agujero vac&iacute;o)" id="imagen_personaje_copa">
        </div>
        <div id="div_pared" style="left:170px; top:40px; width:44px; height:40px; text-align:center; position:absolute;" onMouseOver="javascript:marcar_div('div_pared');" onMouseOut="javascript:desmarcar_div('div_pared');" onClick="javascript:seleccionar_div('div_pared');">
            <img src="img/piedra1.gif" width="40" height="40" hspace="0" vspace="0" title="Click aqu&iacute; para seleccionar" alt="piedra (ladrillo)" id="imagen_pared">
        </div>
        <div id="div_agujero_vacio" style="left:250px; top:40px; width:44px; height:40px; text-align:center; position:absolute;" onMouseOver="javascript:marcar_div('div_agujero_vacio');" onMouseOut="javascript:desmarcar_div('div_agujero_vacio');" onClick="javascript:seleccionar_div('div_agujero_vacio');">
            <img src="img/copa1.gif" width="40" height="40" hspace="0" vspace="0" title="Click aqu&iacute; para seleccionar" alt="copa vac&iacute;a (agujero vac&iacute;o)" id="imagen_agujero_vacio">
        </div>
        <div id="div_agujero_lleno" style="left:330px; top:40px; width:44px; height:40px; text-align:center; position:absolute;" onMouseOver="javascript:marcar_div('div_agujero_lleno');" onMouseOut="javascript:desmarcar_div('div_agujero_lleno');" onClick="javascript:seleccionar_div('div_agujero_lleno');">
            <img src="img/copa2.gif" width="40" height="40" hspace="0" vspace="0" title="Click aqu&iacute; para seleccionar" alt="copa llena (agujero lleno)" id="imagen_agujero_lleno">
        </div>
        <div id="div_pieza" style="left:410px; top:40px; width:44px; height:40px; text-align:center; position:absolute;" onMouseOver="javascript:marcar_div('div_pieza');" onMouseOut="javascript:desmarcar_div('div_pieza');" onClick="javascript:seleccionar_div('div_pieza');">
            <img src="img/botella.gif" width="40" height="40" hspace="0" vspace="0" title="Click aqu&iacute; para seleccionar" alt="botella (pieza)" id="imagen_pieza">
        </div>
        <div id="div_borrar" style="left:490px; top:40px; width:44px; height:40px; text-align:center; position:absolute;" onMouseOver="javascript:marcar_div('div_borrar');" onMouseOut="javascript:desmarcar_div('div_borrar');" onClick="javascript:seleccionar_div('div_borrar');">
            <img src="img/borrar.gif" width="40" height="40" hspace="0" vspace="0" title="Click aqu&iacute; para seleccionar" alt="borrar (goma)" id="imagen_borrar">
        </div>
        <!-- Formulario para resizear: -->
        <div id="menu_size" style="left:570px; top:45px; width:340px; height:40px; position:absolute; font-weight:bold; font-family:verdana; font-size:14px;">
            <form style="display:inline;" id="formulario_size">
                X: <input type="text" name="mapa_x" value="10" size="2" maxlength="2" title="Escribir aqu&iacute; el ancho del mapa y presionar 'Aplicar'" onChange="document.getElementById('mensaje_espera').style.visibility='visible'; setTimeout('resizear_mapa();',200);">
                <br>
                Y: <input type="text" name="mapa_y" value="10" size="2" maxlength="2" title="Escribir aqu&iacute; el alto del mapa y presionar 'Aplicar'" onChange="document.getElementById('mensaje_espera').style.visibility='visible'; setTimeout('resizear_mapa();',200);">
                <input type="button" value="Aplicar" name="boton_aplicar" style="cursor: pointer; cursor: hand;">
            </form>
        </div>
        <!-- Fin de Formulario para resizear. -->
        <!-- Fin de Botones. -->
        <div style="left:10px; top:90px; width:400px; height:400px; position:absolute; font-weight:bold; font-family:verdana; font-size:14px; padding:0px; z-index:1;">
            Mapa: <div id="probar_juego" style="left:60px; top:2px; position:absolute; font-weight:normal; font-family:arial; font-size:12px; padding:0px; z-index:69;">[ <a href="" style="color:#ff0000;">Vaciar mapa</a> - <a href="" style="color:#ff0000;">Probar mapa</a> ]</div>
        </div>
        <!-- Mapa: -->
        <div id="mapa" style="left:10px; top:110px; width:400px; height:400px; position:absolute; border:2px dashed #000000;">
        </div>
        <!-- Fin de Mapa. -->
        <!-- Codigo del Mapa: -->
        <div id="mapa_codigo" style="background:#dddddd; color:#550000; left:10px; top:510px; position:absolute; border:0px; font-weight:bold; font-family:courier; letter-spacing:0px; word-spacing:0px; font-size:12px; line-height:20px; text-align:left;">
        </div>
        <!-- Fin de Codigo del Mapa. -->
    </body>
</html>
