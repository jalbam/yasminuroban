<?php
    //Se configura el BBClone:
    define("_BBC_PAGE_NAME", "english online");
    define("_BBCLONE_DIR", "../bbclone/");
    define("COUNTER", _BBCLONE_DIR."mark_page.php");
    if (is_readable(COUNTER)) include_once(COUNTER);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title>Yasminuroban &copy; (by Joan Alba Maldonado)</title>
        <!-- (c) Yasminuroban - Programa realizado por Joan Alba Maldonado (granvino@granvino.com). Prohibido publicar, reproducir o modificar sin citar expresamente al autor original. -->
        <script language="JavaScript1.2" type="text/javascript">
            <!--
                //(c) Yasminuroban - Programa realizado por Joan Alba Maldonado (granvino@granvino.com). Prohibido publicar, reproducir o modificar sin citar expresamente al autor original.


                //Variable que guarda el primer evento del teclado (razones de compatibilidad):
                var primer_evento = "";
                
                //Matriz que contendra los niveles:
                var niveles = new Array();
                
                //El ancho de las celdas:
                var celda_width = 40;
                
                //El alto de las celdas:
                var celda_height = 40;
    
                //El numero de piezas que deben ser colocadas:
                var numero_piezas_totales = 0;
                //El contador de piezas ya colocadas:
                var numero_piezas_colocadas = 0;

                //El numero de nivel:
                var nivel = 1;
                
                //El numero de niveles que hay en total (se calcula en generar_niveles):
                var numero_niveles = 0;
                
                //Matriz que guarda los movimientos efectuados:
                var movimientos_anteriores = new Array();
                //Variable que define que numero de movimientos se han realizado:
                var numero_movimientos = 0;
                //Variable que muestra el numero de movimientos realizados:
                var numero_movimientos_mostrar = numero_movimientos;
                
                //Variable que contendra el Timeout del movimiento del personaje:
                var movimiento_personaje = setTimeout("", 1); //Se setea para que no de error el primer clearTimeout.
                
                //Variable que indica si ya se ha comprobado el envio de un mapa por url (GET), para usar niveles del editor:
                var mapa_url_recogido = false;

                //Variable que guarda la url que enlaza con el editor (para editar el nivel actual):
                var url_editor = "editor.php";
                
                //Funcion para saber si ya se ha saludado (dando instrucciones de poner todas las botellas en sus copas):
                var se_ha_saludado = false;
                
                //Variable que define si el juego ya se ha iniciado o no (para no dar error al pulsar una tecla mientras carga el juego):
                var juego_iniciado = false;
                
                //Imagenes del juego:
                if (document.images)
                 {
                    //Se crean los objetos:
                    var img_pared1 = new Image(celda_width, celda_height);
                    var img_pared2 = new Image(celda_width, celda_height);
                    var img_pared3 = new Image(celda_width, celda_height);
                    var img_pieza = new Image(celda_width, celda_height);
                    var img_agujero_vacio = new Image(celda_width, celda_height);
                    var img_agujero_lleno = new Image(celda_width, celda_height);
                    var img_personaje_derecha1 = new Image(celda_width, celda_height);
                    var img_personaje_derecha2 = new Image(celda_width, celda_height);
                    var img_personaje_izquierda1 = new Image(celda_width, celda_height);
                    var img_personaje_izquierda2 = new Image(celda_width, celda_height);
                    var img_personaje_arriba1 = new Image(celda_width, celda_height);
                    var img_personaje_arriba2 = new Image(celda_width, celda_height);
                    var img_personaje_abajo1 = new Image(celda_width, celda_height);
                    var img_personaje_abajo2 = new Image(celda_width, celda_height);
                    //Se define el numero total de imagenes de paredes (para poder alternarlas):
                    var numero_img_paredes = 3;
                    //Se crean las imagenes en los objetos:
                    img_pared1.src = "img/piedra1.gif"; //Imagen de la pared #1.
                    img_pared2.src = "img/piedra2.gif"; //Imagen de la pared #2.
                    img_pared3.src = "img/piedra3.gif"; //Imagen de la pared #3.
                    img_pieza.src = "img/botella.gif"; //Imagen de la pieza.
                    img_agujero_vacio.src = "img/copa1.gif"; //Imagen del agujero vacio.
                    img_agujero_lleno.src = "img/copa2.gif"; //Imagen del agujero lleno.
                    img_personaje_derecha1.src = "img/yas_right1.gif"; //Imagen del personaje hacia la derecha #1.
                    img_personaje_derecha2.src = "img/yas_right2.gif"; //Imagen del personaje hacia la derecha #2.
                    img_personaje_izquierda1.src = "img/yas_left1.gif"; //Imagen del personaje hacia la izquierda #1.
                    img_personaje_izquierda2.src = "img/yas_left2.gif"; //Imagen del personaje hacia la izquierda #2.
                    img_personaje_arriba1.src = "img/yas_up1.gif"; //Imagen del personaje hacia arriba #1.
                    img_personaje_arriba2.src = "img/yas_up2.gif"; //Imagen del personaje hacia arriba #2.
                    img_personaje_abajo1.src = "img/yas_down1.gif"; //Imagen del personaje hacia abajo #1.
                    img_personaje_abajo2.src = "img/yas_down2.gif"; //Imagen del personaje hacia abajo #2.
                    //Se crea la imagen que usara el personaje en cada momento:
                    var imagen_personaje = new Image(celda_width, celda_height);
                    imagen_personaje.src = img_personaje_abajo1.src;
                 }
    
                
                //Funcion que inicia el juego:
                function iniciar_juego(numero_nivel)
                 {
                    //Genera los niveles:
                    generar_niveles();
                    
                    //Crea la url para ir al editor y poder editar el nivel actual:
                    generar_url_editor(numero_nivel);
                    
                    //Se pone como imagen inicial del personaje la que mira hacia abajo:
                    document.getElementById("personaje").innerHTML = '<img src="img/yas_down1.gif" id="personaje_imagen" title="You are here" alt="@" width="'+celda_width+'" height="'+celda_height+'" border="0" hspace="0" vspace="0">';
                   
                    //Se borran los movimientos anteriores y se setea el contador de movimientos a cero:
                    movimientos_anteriores = new Array();
                    numero_movimientos = 0;
                    numero_movimientos_mostrar = numero_movimientos;

                    //Se setea el nivel a 1 o al que corresponda:
                    nivel = numero_nivel;

                    //Se adecua el tama�o de las celdas al tama�o del mapa:
                    if (niveles[nivel]["width"] > 48 || niveles[nivel]["height"] > 30) { celda_width = 10; celda_height = 10; }
                    else if (niveles[nivel]["width"] > 32 || niveles[nivel]["height"] > 24) { celda_width = 15; celda_height = 15; }
                    else if (niveles[nivel]["width"] > 26 || niveles[nivel]["height"] > 18) { celda_width = 20; celda_height = 20; }
                    else if (niveles[nivel]["width"] > 20 || niveles[nivel]["height"] > 12) { celda_width = 30; celda_height = 30; }
                    else { celda_width = 40; celda_height = 40; }
                    //Se aplican los cambios de tama�o:
                    document.getElementById("personaje").style.width = celda_width + "px";
                    document.getElementById("personaje").style.height = celda_height + "px";
                    document.getElementById("personaje").innerHTML = '<img src="img/yas_down1.gif" id="personaje_imagen" title="You are here" alt="@" width="'+celda_width+'" height="'+celda_height+'" border="0" hspace="0" vspace="0">';

                    //Se setea el numero de piezas colocadas a cero:
                    numero_piezas_colocadas = 0;

                    //Muestra el mapa en pantalla:
                    pintar_mapa(true);

                    //Guarda el mapa anterior, para poder realizar deshacer:
                    guardar_mapa();

                    //Actualiza la barra de estado:
                    actualizar_barra();

                    //Se informa del nivel en el mensaje:
                    document.getElementById("mensaje").innerHTML = "Welcome to Level: "+nivel;
                    //Se hace visible el mensaje:
                    document.getElementById("mensaje").style.visibility = "visible";
                    //Se oculta el mensaje despues de 3 segundos:
                    setTimeout("document.getElementById('mensaje').style.visibility='hidden'", 3000); 

                    //Si el nivel es el primero y aun no se ha saludado, dando instrucciones, se hace:
                    if (nivel <= 1 && !se_ha_saludado) { alert("Welcome to the first level. Place all the bottles in the glasses."); se_ha_saludado = true; }
                 }
                

                //Funcion que genera los niveles:
                function generar_niveles()
                 {
                    //Leyenda:
                    //   => Vacio.
                    // # => Pared (piedra).
                    // @ => Posicion inicial del personaje (yasmina).
                    // $ => Pieza (botella).
                    // � => Agujero para la pieza (copa vacia).
                    // * => Agujero con una pieza (copa llena).
                    // + => Personaje encima de un agujero para la pieza (mezcla de @ con �).

                    //Nivel 1:
                    niveles[1] = new Array();
                    niveles[1]["forma"] = "    #####          " +
                                          "    #   #          " +
                                          "    #$  #          " +
                                          "  ###  $##         " +
                                          "  #  $ $ #         " +
                                          "### # ## #   ######" +
                                          "#   # ## #####  ��#" +
                                          "# $  $          ��#" +
                                          "##### ### #@##  ��#" +
                                          "    #     #########" +
                                          "    #######        "; //Mapa del nivel 1.
                    niveles[1]["width"] = 19; //Columnas.
                    niveles[1]["height"] = 11; //Filas.

                    //Nivel 2:
                    niveles[2] = new Array();
                    niveles[2]["forma"] = "##########" +
                                          "####   # #" +
                                          "#� $ $�# #" +
                                          "#� +$ ## #" +
                                          "#$ $  $��#" +
                                          "#  #######"; //Mapa del nivel 2.
                    niveles[2]["width"] = 10; //Columnas.
                    niveles[2]["height"] = 6; //Filas.

                    //Nivel 3:
                    niveles[3] = new Array();
                    niveles[3]["forma"] = "############  " +
                                          "#��  #     ###" +
                                          "#��  # $  $  #" +
                                          "#��  #$####  #" +
                                          "#��    @ ##  #" +
                                          "#��  # #  $ ##" +
                                          "###### ##$ $ #" +
                                          "  # $  $ $ $ #" +
                                          "  #    #     #" +
                                          "  ############"; //Mapa del nivel 3.
                    niveles[3]["width"] = 14; //Columnas.
                    niveles[3]["height"] = 10; //Filas.

                    //Nivel 4:
                    niveles[4] = new Array();
                    niveles[4]["forma"] = "##   #   #  ##" +
                                          "#            #" +
                                          "##### $@$ ####" +
                                          "�   ##$$$##  �" +
                                          "#        #   #" +
                                          "��   #      ��" +
                                          "��  ##$$$ # ��" +
                                          "##### $ $ ####" +
                                          "##            " +
                                          "###   #  #  ##"; //Mapa del nivel 4.
                    niveles[4]["width"] = 14; //Columnas.
                    niveles[4]["height"] = 10; //Filas.

                    //Nivel 5:
                    niveles[5] = new Array();
                    niveles[5]["forma"] = "        ######## " +
                                          "        #     @# " +
                                          "        # $#$ ## " +
                                          "        # $  $#  " +
                                          "        ##$ $ #  " +
                                          "######### $ # ###" +
                                          "#����  ## $  $  #" +
                                          "##���    $  $   #" +
                                          "#����  ##########" +
                                          "########         "; //Mapa del nivel 5.
                    niveles[5]["width"] = 17; //Columnas.
                    niveles[5]["height"] = 10; //Filas.

                    //Nivel 6:
                    niveles[6] = new Array();
                    niveles[6]["forma"] = "# # # #     # # #" +
                                          "   $      #      " +
                                          "#        $  �   #" +
                                          " $ $ $ $  #$ # $ " +
                                          " # # # # #�      " +
                                          "  �   �   $# #�#�" +
                                          "# $ $  #$#� $ $ $" +
                                          " � �  �       �  " +
                                          "   #$#$#  $      " +
                                          "#� � �  #�#�#�#+#"; //Mapa del nivel 6.
                    niveles[6]["width"] = 17; //Columnas.
                    niveles[6]["height"] = 10; //Filas.

                    //Nivel 7:
                    niveles[7] = new Array();
                    niveles[7]["forma"] = "           ########" +
                                          "           #  ����#" +
                                          "############  ����#" +
                                          "#    #  $ $   ����#" +
                                          "# $$$#$  $ #  ����#" +
                                          "#  $     $ #  ����#" +
                                          "# $$ #$ $ $########" +
                                          "#  $ #     #       " +
                                          "## #########       " +
                                          "#    #    ##       " +
                                          "#     $   ##       " +
                                          "#  $$#$$  @#       " +
                                          "#    #    ##       " +
                                          "###########        "; //Mapa del nivel 7.
                    niveles[7]["width"] = 19; //Columnas.
                    niveles[7]["height"] = 14; //Filas.

                    niveles[8] = new Array();
                    niveles[8]["forma"] = "#  ####  #### #### " +
                                          "####��####��###��##" +
                                          "+                  " +
                                          "#  #  #  #  # #  ##" +
                                          "#  #  #  #  # #   #" +
                                          "#     #          ##" +
                                          "#  #   #  #  #$$$ #" +
                                          "#     $     #  #  #" +
                                          "##### $   ###  #  #" +
                                          "#    $$$ $  #  $$$#" +
                                          "##  # #  #  #  #  #" +
                                          "      #  #     #   " +
                                          " ####�####��####��#" +
                                          "##  ###  ####  ####"; //Mapa del nivel 8.
                    niveles[8]["width"] = 19; //Columnas.
                    niveles[8]["height"] = 14; //Filas.

                    niveles[9] = new Array();
                    niveles[9]["forma"] = "        #####    " +
                                          "        #   #####" +
                                          "        # #$##  #" +
                                          "        #     $ #" +
                                          "######### ###   #" +
                                          "#����  ## $  $###" +
                                          "#����    $ $$ ## " +
                                          "#����  ##$  $ @# " +
                                          "#########  $  ## " +
                                          "        # $ $  # " +
                                          "        ### ## # " +
                                          "          #    # " +
                                          "          ###### "; //Mapa del nivel 9.
                    niveles[9]["width"] = 17; //Columnas.
                    niveles[9]["height"] = 13; //Filas.

                    niveles[10] = new Array();
                    niveles[10]["forma"] = "################ " +
                                           " + #�            " +
                                           "  ##$#########   " +
                                           "#             # #" +
                                           "#  # #######  # #" +
                                           "# $       � $ #$#" +
                                           "# #  ###### # # #" +
                                           "# # #  $ �  #   #" +
                                           "###  # ####$  # #" +
                                           "# #           # #" +
                                           "#  ##########�#  " +
                                           "#��         $�$  " +
                                           " ############### "; //Mapa del nivel 10.
                    niveles[10]["width"] = 17; //Columnas.
                    niveles[10]["height"] = 13; //Filas.

                    niveles[11] = new Array();
                    niveles[11]["forma"] = "######  ### " +
                                           "#��  # ##@##" +
                                           "#��  ###   #" +
                                           "#��     $$ #" +
                                           "#��  # # $ #" +
                                           "#��### # $ #" +
                                           "#### $ #$  #" +
                                           "   #  $# $ #" +
                                           "   # $  $  #" +
                                           "   #  ##   #" +
                                           "   #########"; //Mapa del nivel 11.
                    niveles[11]["width"] = 12; //Columnas.
                    niveles[11]["height"] = 11; //Filas.

                    niveles[12] = new Array();
                    niveles[12]["forma"] = "    #     ##" +
                                           " �� #$#$ #  " +
                                           " #    #@ #� " +
                                           " # � $$$ # #" +
                                           "  #    �#�$ " +
                                           "  $  #  #�$ " +
                                           "   ## � ##$ " +
                                           "  # $  $ #  " +
                                           "  #�    �# $" +
                                           "  #######   " +
                                           "��       $ �"; //Mapa del nivel 12.
                    niveles[12]["width"] = 12; //Columnas.
                    niveles[12]["height"] = 11; //Filas.

                    niveles[13] = new Array();
                    niveles[13]["forma"] = "       ##### " +
                                           " #######   ##" +
                                           "## # @## $$ #" +
                                           "#    $      #" +
                                           "#  $  ###   #" +
                                           "### #####$###" +
                                           "# $  ### ��# " +
                                           "# $ $ $ ���# " +
                                           "#    ###���# " +
                                           "# $$ # #���# " +
                                           "#  ### ##### " +
                                           "####         "; //Mapa del nivel 13.
                    niveles[13]["width"] = 13; //Columnas.
                    niveles[13]["height"] = 12; //Filas.

                    niveles[14] = new Array();
                    niveles[14]["forma"] = "#   #   # ###" +
                                           " # # # # #   " +
                                           "� #  �#� �#  " +
                                           "$ # $ # $ #  " +
                                           " #  �$   #�� " +
                                           " $�  $+ $ $**" +
                                           "   $� #$     " +
                                           " # #   $ # $ " +
                                           "  #�$$# $ #��" +
                                           "  #   #  �#$ " +
                                           " # #�# #�##  " +
                                           "#   #   #  ##"; //Mapa del nivel 14.
                    niveles[14]["width"] = 13; //Columnas.
                    niveles[14]["height"] = 12; //Filas.

                    niveles[15] = new Array();
                    niveles[15]["forma"] = "  ####          " +
                                           "  #  ###########" +
                                           "  #    $   $ $ #" +
                                           "  # $# $ #  $  #" +
                                           "  #  $ $  #    #" +
                                           "### $# #  #### #" +
                                           "#@#$ $ $  ##   #" +
                                           "#    $ #$#   # #" +
                                           "#   $    $ $ $ #" +
                                           "#####  #########" +
                                           "  #      #      " +
                                           "  #      #      " +
                                           "  #������#      " +
                                           "  #������#      " +
                                           "  #������#      " +
                                           "  ########      "; //Mapa del nivel 15.
                    niveles[15]["width"] = 16; //Columnas.
                    niveles[15]["height"] = 16; //Filas.

                    //Se cambian todos los puntos (.) por puntos volados (�):
                    for (contador_nivel=1; contador_nivel<niveles.length; contador_nivel++)
                     {
                        for (contador=0; contador<niveles[contador_nivel]["forma"].length; contador++)
                        {
                            niveles[contador_nivel]["forma"] = niveles[contador_nivel]["forma"].replace(".", "�");
                        }
                     }
                    
                    //Se comprueba si se ha enviado por la url (GET) un mapa, y si es asi se recoge y se carga:
                    numero_niveles = niveles.length - 1;
                 }


                //Funcion que genera la url que va hacia el editor, para poder editar el nivel actual:
                function generar_url_editor(numero_nivel)
                 {
                    //Se crean las variables necesarias para forma la url:
                    var pagina_generada = "editor.php";
                    var width_generado = niveles[numero_nivel]["width"];
                    var height_generado = niveles[numero_nivel]["height"];
                    var mapa_generado = niveles[numero_nivel]["forma"];
                    
                    //Se refina el mapa cambiando los caracteres para que sean admitidos por la url:
                     // A => (espacio en blanco)
                     // B => #
                     // C => @
                     // D => $
                     // E => �
                     // F => *
                     // G => +
                    for (z=0; z<niveles[numero_nivel]["forma"].length; z++)
                     {
                            mapa_generado = mapa_generado.replace(" ", "A");
                            mapa_generado = mapa_generado.replace("#", "B");
                            mapa_generado = mapa_generado.replace("@", "C");
                            mapa_generado = mapa_generado.replace("$", "D");
                            mapa_generado = mapa_generado.replace("�", "E");
                            mapa_generado = mapa_generado.replace("*", "F");
                            mapa_generado = mapa_generado.replace("+", "G");
                     }
                    
                    //Se guarda en la variable de url del editor:
                    url_editor = pagina_generada + "?mapa="+escape(mapa_generado)+"&width="+width_generado+"&height="+height_generado;
                 }
                

                //Funcion que actualiza la barra de estado:
                function actualizar_barra()
                 {
                    //Se guarda el formulario con el nivel en una variable:
                    var formulario_nivel = generar_formulario_nivel();
                    //Se muestra la informacion en la barra de estado (nivel y numero de movimientos):
                    if (!formulario_nivel) { document.getElementById("barra_estado").innerHTML = "Level: "+nivel+"&nbsp; |&nbsp; Movements: "+eval(numero_movimientos_mostrar); }
                    else { document.getElementById("barra_estado").innerHTML = formulario_nivel; }
                 }


                //Funcion que retorna un formulario para elegir el nivel (para usar en actualizar_barra):
                function generar_formulario_nivel()
                 {
                    //Se crea el formulario:
                    var formulario_nivel = '<form style="display:inline;" id="formulario_nivel">';
                    //Se crea el <select>, con un onChange:
                    formulario_nivel += 'Level: <select name="selector_nivel" onChange="javascript:saltar_nivel(document.getElementById(\'formulario_nivel\').selector_nivel.value);">';
                    //Se hace un bucle para hacer tantos <option> como niveles existan:
                    for (x=1; x<=numero_niveles; x++)
                     {
                        //Si el nivel actual corresponde al <option> que estamos escribiendo, se escribe seleccionado (selected):
                        if (nivel == x) { formulario_nivel += '<option value="'+x+'" onFocus="javascript:iniciar_juego('+x+');" selected>'+x+'</option>'; }
                        //...y si no, se escribe normal (sin seleccionar):
                        else { formulario_nivel += '<option value="'+x+'" onFocus="javascript:iniciar_juego('+x+');">'+x+'</option>'; }
                     }
                    //Se cierra el select:
                    formulario_nivel += '</select>';
                    //Se escriben tambien los movimientos realizados:
                    formulario_nivel += "&nbsp; |&nbsp; Movements: "+eval(numero_movimientos_mostrar)+"</form>";
                    //Sale de la funcion, retornando la variable creada con el formulario:
                    return formulario_nivel;
                 }


                //Funcion que salta a un nivel dado:
                function saltar_nivel(numero_nivel)
                 {
                    //Si el nivel seleccionado es el ultimo, y se ha enviado uno por la url (GET), entonces volvemos a generar el mapa y salimos de la funcion:
                    if (mapa_url_recogido && numero_nivel == numero_niveles) { recoger_mapa_url(); return; }
                    
                    //Si se presiona aceptar a la pregunta:
                    if (confirm("Press ok if you want to change to level "+numero_nivel))
                     {
                        //Se cambia de nivel:
                        iniciar_juego(numero_nivel);
                     }
                    //...y si no (se presiona cancelar):
                    else
                     {
                        //Se vuelve a pintar el formulario de seleccion de niveles (para que se deseleccione el ultimo elegido, ya que lo hemos rechazado):
                        actualizar_barra();
                     }
                 }


                //Funcion que representa el mapa en pantalla (y cuenta cuantas piezas hay para ser colocadas).
                function pintar_mapa(pintar_personaje)
                 {
                    //Si se ha enviado pintar el personaje, se pone de cara (direccion hacia abajo):
                    if (pintar_personaje) { document.getElementById("personaje").innerHTML = '<img src="img/yas_down1.gif" id="personaje_imagen" title="You are here" alt="@" width="'+celda_width+'" height="'+celda_height+'" border="0" hspace="0" vspace="0">'; }
                    
                    //Se esconde el mapa:
                    document.getElementById("mapa").style.visibility = "hidden";
                    
                    //Se esconde el personaje:
                    document.getElementById("personaje").style.visibility = "hidden";

                    //Se borra el mapa:
                    document.getElementById("mapa").innerHTML = "";
                   
                    //Se pone el ancho del mapa que corresponde:
                    document.getElementById("mapa").style.width = niveles[nivel]["width"] * celda_width + "px";
                    
                    //Se pone el alto del mapa que corresponde:
                    document.getElementById("mapa").style.height = niveles[nivel]["height"] * celda_height + "px";
                    
                    //Contador del numero de piezas totales:
                    numero_piezas_totales = 0;
                    
                    //El contador que ira variando para poner distintos tipos de pared:
                    var numero_pared = 1;
                    
                    //La variable donde se guarda el mapa que vamos pintando:
                    var mapa_pintado = "";
                    
                    //El contador de columnas:
                    var contador_columna = 0;

                    //El contador de filas:
                    var contador_fila = 0;

                    //Se realiza el bucle para pintar el mapa:                    
                    for (x=0; x<niveles[nivel]["forma"].length; x++)
                     {
                        //Posicion horizontal del div (celda) a pintar:
                        var celda_left = contador_columna * celda_width;
                        //Posicion vertical del div (celda) a pintar:
                        var celda_top = contador_fila * celda_height;
                        
                        //Pinta una pared en la celda (alternandolas):
                        if (niveles[nivel]["forma"].substring(x,x+1) == "#") { if (numero_pared >= numero_img_paredes) { numero_pared = 1; } numero_pared++; mapa_pintado += '<div id="'+x+'" style="visibility:visible; background:transparent; color:#000000; left:'+celda_left+'px; top:'+celda_top+'px; width:'+celda_width+'px; height:'+celda_height+'px; padding:0px; position:absolute; font-size:1px; line-height:1px;"><img src="'+eval('img_pared' + numero_pared + '.src')+'" width="'+celda_width+'" height="'+celda_height+'"></div>'; }
                        //Pinta al personaje en la celda (situa el DIV del personaje en ella y lo hace visible), siempre que se haya enviado el parametro pintar_personaje=true:
                        else if (pintar_personaje && niveles[nivel]["forma"].substring(x,x+1) == "@") { mapa_pintado += '<div id="'+x+'" style="visibility:visible; background:transparent; color:#000000; left:'+celda_left+'px; top:'+celda_top+'px; width:'+celda_width+'px; height:'+celda_height+'px; padding:0px; position:absolute; font-size:1px; line-height:1px;">&nbsp;</div>'; document.getElementById("personaje").style.left = celda_left + "px"; document.getElementById("personaje").style.top = celda_top + "px"; }
                        //Pinta una pieza en la celda, y suma una en el contador de piezas:
                        else if (niveles[nivel]["forma"].substring(x,x+1) == "$") { numero_piezas_totales++; mapa_pintado += '<div id="'+x+'" style="visibility:visible; background:transparent; color:#000000; left:'+celda_left+'px; top:'+celda_top+'px; width:'+celda_width+'px; height:'+celda_height+'px; padding:0px; position:absolute; font-size:1px; line-height:1px;"><img src="'+img_pieza.src+'" width="'+celda_width+'" height="'+celda_height+'"></div>'; }
                        //Pinta un agujero para la pieza (vacio) en la celda:
                        else if (niveles[nivel]["forma"].substring(x,x+1) == "�") { mapa_pintado += '<div id="'+x+'" style="visibility:visible; background:transparent; color:#000000; left:'+celda_left+'px; top:'+celda_top+'px; width:'+celda_width+'px; height:'+celda_height+'px; padding:0px; position:absolute; font-size:1px; line-height:1px;"><img src="'+img_agujero_vacio.src+'" width="'+celda_width+'" height="'+celda_height+'"></div>'; }
                        //Pinta un agujero (lleno con una pieza) en la celda:
                        else if (niveles[nivel]["forma"].substring(x,x+1) == "*") { mapa_pintado += '<div id="'+x+'" style="visibility:visible; background:transparent; color:#000000; left:'+celda_left+'px; top:'+celda_top+'px; width:'+celda_width+'px; height:'+celda_height+'px; padding:0px; position:absolute; font-size:1px; line-height:1px;"><img src="'+img_agujero_lleno.src+'" width="'+celda_width+'" height="'+celda_height+'"></div>'; }
                        //Pinta un agujero para la pieza (vacio) en la celda, pero con el personaje encima (siempre que asi se haya especificado):
                        else if (pintar_personaje && niveles[nivel]["forma"].substring(x,x+1) == "+")
                         {
                            //Situa al personaje:
                            document.getElementById("personaje").style.left = celda_left + "px";
                            document.getElementById("personaje").style.top = celda_top + "px";
                            //Pinta en el mapa un agujero vacio:
                            mapa_pintado += '<div id="'+x+'" style="visibility:visible; background:transparent; color:#000000; left:'+celda_left+'px; top:'+celda_top+'px; width:'+celda_width+'px; height:'+celda_height+'px; padding:0px; position:absolute; font-size:1px; line-height:1px;"><img src="'+img_agujero_vacio.src+'" width="'+celda_width+'" height="'+celda_height+'"></div>';
                            //Substituye el "+" del mapa por un agujero vacio normal (�):
                            niveles[nivel]["forma"] = niveles[nivel]["forma"].replace("+", "�");
                        
                         }
                        //Pinta un vacio:
                        else
                         {
                            mapa_pintado += '<div id="'+x+'" style="visibility:visible; background:transparent; color:#000000; left:'+celda_left+'px; top:'+celda_top+'px; width:'+celda_width+'px; height:'+celda_height+'px; padding:0px; position:absolute; font-size:1px; line-height:1px;">&nbsp;</div>';
                         }
                        
                        //Se incrementa el contador de columnas:
                        contador_columna++;
                        //Si se ha llegado al tope de columnas, se setea el contador de columnas a cero y se incrementa el contador de filas:
                        if (contador_columna >= niveles[nivel]["width"]) { contador_columna = 0; contador_fila++; }
                     }
                    
                    //Se pone la informacion unos pixels mas abajo del mapa:
                    document.getElementById("informacion_autor").style.top = parseInt(document.getElementById("zona_juego").style.top) + (niveles[nivel]["height"] * celda_height) + parseInt(celda_height/2) + "px";
                    
                    //Se pinta el mapa:
                    document.getElementById("mapa").innerHTML += mapa_pintado;
                     
                    //Se muestra el personaje:
                    document.getElementById("personaje").style.visibility = "visible";

                    //Se muestra el mapa:
                    document.getElementById("mapa").style.visibility = "visible";
                 }


                //Funcion que pasa de nivel:
                function pasar_nivel()
                 {
                    //Si aun no estamos en el ultimo nivel, se pasa de nivel:
                    if (nivel < numero_niveles)
                     {
                        //Se informa del fin del juego en el mensaje:
                        document.getElementById("mensaje").innerHTML = "Congratulations, you finish the level "+nivel;
                        //Se hace visible el mensaje:
                        document.getElementById("mensaje").style.visibility = "visible";
                        //Se oculta el mensaje despues de 3 segundos:
                        setTimeout("document.getElementById('mensaje').style.visibility='hidden'", 3000); 
                        //Se da la enhorabuena con un alert:
                        alert("Congratulations, you finish the level "+nivel);
                        //Se incrementa un nivel:
                        nivel++;

                        //Si el nivel seleccionado es el ultimo, y se ha enviado uno por la url (GET), entonces volvemos a generar el mapa y salimos de la funcion:
                        if (mapa_url_recogido && nivel == numero_niveles) { recoger_mapa_url(); return; }

                        //Se inicia el juego con el siguiente nivel:
                        iniciar_juego(nivel);
                     }
                    //Si ya estamos en el ultimo nivel, se notifica que se ha acabado el juego y se inicia otra vez:
                    else
                     {
                        //Se informa del fin del juego en el mensaje:
                        document.getElementById("mensaje").innerHTML = "You finish all levels. End of game.";
                        //Se hace visible el mensaje:
                        document.getElementById("mensaje").style.visibility = "visible";
                        //Se oculta el mensaje despues de 3 segundos:
                        setTimeout("document.getElementById('mensaje').style.visibility='hidden'", 3000); 
                        //Se alerta de que se han acabado los niveles:
                        alert("You finish all levels. End of game.");
                        //Se vuelve a iniciar el juego por el primer nivel:
                        iniciar_juego(1);
                     }
                 }
                

                //Funcion que reinicia el nivel actual:
                function reiniciar_nivel()
                 {
                    //Pide confirmacion, y si se acepta se reinicia el nivel:
                    if (confirm("Press ok to restart level "+nivel))
                     {
                        //Si el nivel seleccionado es el ultimo, y se ha enviado uno por la url (GET), entonces volvemos a generar el mapa y salimos de la funcion:
                        if (mapa_url_recogido && nivel == numero_niveles) { recoger_mapa_url(); return; }
                        //Vuelve a iniciar el juego en el nivel actual:
                        iniciar_juego(nivel);
                     }
                 }


                //Funcion que guarda el mapa actual, para poder realizar deshacer movimiento:
                function guardar_mapa()
                 {
                    //Se crea la matriz multidimensional, por si antes no existia:
                    movimientos_anteriores[numero_movimientos] = new Array();
                    //Se guarda el mapa en la matriz para movimientos anteriores:
                    movimientos_anteriores[numero_movimientos]["mapa_forma"] = niveles[nivel]["forma"]; //Se guarda el mapa.
                    movimientos_anteriores[numero_movimientos]["personaje_left"] = document.getElementById("personaje").style.left; //Se guarda la posicion horizontal del personaje.
                    movimientos_anteriores[numero_movimientos]["personaje_top"] = document.getElementById("personaje").style.top; //Se guarda la posicion vertical del personaje.
                    movimientos_anteriores[numero_movimientos]["personaje_imagen"] = imagen_personaje.src; //Se guarda la posicion vertical del personaje.
                 }


                //Funcion que deshace una accion (gracias a guardar_mapa):
                function deshacer_accion()
                 {
                     //Si al restarle un movimiento, todavia es mayor o igual que cero (existe un movimiento anterior):
                     if (numero_movimientos-1 >= 0)
                      {
                        //Si el numero de movimiento en el que nos encontramos es el ultimo realizado, se restan una unidad (otra mas) al contador de movimientos:
                        if (numero_movimientos == movimientos_anteriores.length && numero_movimientos > 2) { numero_movimientos--; } //Esto es porque el ultimo movimiento guardado es el actual, y entonces se tendria que deshacer dos veces.
                        //Se resta una unidad al contador de movimientos:
                        numero_movimientos--;
                        //El mapa vuelve a ser como en el movimiento anterior:
                        niveles[nivel]["forma"] = movimientos_anteriores[numero_movimientos]["mapa_forma"];
                        //La posicion horizontal del personaje vuelve a estar como en el movimiento anterior:
                        document.getElementById("personaje").style.left = movimientos_anteriores[numero_movimientos]["personaje_left"];
                        //La posicion vertical del personaje vuelve a estar como en el movimiento anterior:
                        document.getElementById("personaje").style.top = movimientos_anteriores[numero_movimientos]["personaje_top"];
                        //Se pone la imagen del personaje que correspondia a la anterior jugada:
                        document.getElementById('personaje_imagen').src = movimientos_anteriores[numero_movimientos]["personaje_imagen"];
                        //Se representa el nuevo mapa:
                        pintar_mapa(false);
                        //Se resta un movimiento al contador:
                        numero_movimientos_mostrar--;
                        //Se actualiza la barra (para que se reste un movimiento):
                        actualizar_barra();
                      }
                 }


                //Funcion que rehace una accion (gracias a guardar_mapa):
                function rehacer_accion()
                 {
                     //Si al sumarle un movimiento, todavia es menor o igual que el tama�o de la matriz que guarda los movimientos anteriores (existe un movimiento posterior):
                     if (movimientos_anteriores.length > 0 && numero_movimientos+1 < movimientos_anteriores.length)
                      {
                        //Si el numero de movimiento en el que nos encontramos es el ultimo realizado, se restan una unidad (otra mas) al contador de movimientos:
                        //if (numero_movimientos == movimientos_anteriores.length-1 && numero_movimientos > 1) { numero_movimientos++; } //Esto es porque el ultimo movimiento guardado es el actual, y entonces saltaria dos casillas al rehacer.
                        //Se suma una unidad al contador de movimientos:
                        numero_movimientos++;
                        //El mapa vuelve a ser como en el movimiento anterior:
                        niveles[nivel]["forma"] = movimientos_anteriores[numero_movimientos]["mapa_forma"];
                        //La posicion horizontal del personaje vuelve a estar como en el movimiento posterior:
                        document.getElementById("personaje").style.left = movimientos_anteriores[numero_movimientos]["personaje_left"];
                        //La posicion vertical del personaje vuelve a estar como en el movimiento posterior:
                        document.getElementById("personaje").style.top = movimientos_anteriores[numero_movimientos]["personaje_top"];
                        //Se pone la imagen del personaje que correspondia a la posterior jugada:
                        document.getElementById('personaje_imagen').src = movimientos_anteriores[numero_movimientos]["personaje_imagen"];
                        //Se representa el nuevo mapa:
                        pintar_mapa(false);
                        //Se suma un movimiento la contador:
                        numero_movimientos_mostrar++;
                        //Se actualiza la barra (para que se reste un movimiento):
                        actualizar_barra();
                      }
                 }


                //Funcion que captura la tecla pulsada y realiza la funcion necesaria:
                function pulsar_tecla(e, evento_actual)
                 {
                    //Si el primer evento esta vacio, se le introduce como valor el evento actual (el que ha llamado a esta funcion):
                    if (primer_evento == "") { primer_evento = evento_actual; }
                    //Si el primer evento no es igual al evento actual (el que ha llamado a esta funcion), se vacia el primer evento (para que a la proxima llamada entre en la funcion) y se sale de la funcion:
                    if (primer_evento != evento_actual) { primer_evento = ""; return; }

                    //Capturamos la tacla pulsada, segun navegador:
                    if (e.keyCode) { var unicode = e.keyCode; }
                    //else if (event.keyCode) { var unicode = event.keyCode; }
                    else if (window.Event && e.which) { var unicode = e.which; }
                    else { var unicode = 40; } //Si no existe, por defecto se utiliza la flecha hacia abajo.

                    //Si aun no se ha iniciado el juego, sale de la funcion:
                    if (!juego_iniciado) { return; }

                    //Se obtiene la posicion actual del personaje:
                    var posicion_x = parseInt(document.getElementById("personaje").style.left); //Posicion horizontal.
                    var posicion_y = parseInt(document.getElementById("personaje").style.top); //Posicion vertical.

                    //Si se pulsa la flecha hacia abajo, se suma el alto de una celda (y se alterna la imagen del personaje):
                    if (unicode == 40) { posicion_y += celda_height; imagen_personaje = (imagen_personaje == img_personaje_abajo1) ? img_personaje_abajo2 : img_personaje_abajo1; }
                    //...y si se pulsa la flecha hacia la derecha, se suma el ancho de una celda (y se alterna la imagen del personaje):
                    else if (unicode == 39) { posicion_x += celda_width; imagen_personaje = (imagen_personaje == img_personaje_derecha1) ? img_personaje_derecha2 : img_personaje_derecha1; }
                    //...y si se pulsa la flecha hacia la izquierda, se resta el ancho de una celda (y se alterna la imagen del personaje):
                    else if (unicode == 37) { posicion_x -= celda_width; imagen_personaje = (imagen_personaje == img_personaje_izquierda1) ? img_personaje_izquierda2 : img_personaje_izquierda1; }
                    //...y si se pulsa flecha arriba, se resta el alto de una celda (y se alterna la imagen del personaje):
                    else if (unicode == 38) { posicion_y -= celda_height; imagen_personaje = (imagen_personaje == img_personaje_arriba1) ? img_personaje_arriba2 : img_personaje_arriba1; }
                    //...y si se pulsa el backspace (tecla borrar), o la tecla U (85), se deshace la jugada y vuelve al estado anterior:
                    else if (unicode == 8 || unicode == 85 || unicode == 117)
                     {
                        //Deshace la accion:
                        deshacer_accion();
                        //Sale de la funcion:
                        return;
                     }
                    //...y si se pulsa la tecla Y, se rehace la jugada y se vuelve al estado ulterior:
                    else if (unicode == 89 || unicode == 121)
                     {
                        //Rehace la accion:
                        rehacer_accion();
                        //Sale de la funcion:
                        return;
                     }
                    //...y si se pulsa la tecla R, se reinicia el nivel:
                    else if (unicode == 82 || unicode == 114)
                     {
                        //Reiniciar el nivel:
                        reiniciar_nivel();
                        //Sale de la funcion:
                        return;
                     }

                    //Se mueve la pieza:
                    mover_personaje(posicion_x, posicion_y, imagen_personaje);
                 }


                //Funcion que mueve al personaje:
                function mover_personaje(posicion_x, posicion_y, imagen_personaje)
                 {
                    //Si no se ha enviado una imagen de personaje, se sale de la funcion:
                    if (imagen_personaje.src == "") { return; }
                    
                    //Si el personaje se sale del margen horizontal, se sale de la funcion:
                    if (posicion_x < 0 || posicion_x > niveles[nivel]["width"] * celda_width - celda_width) { return; }
                    //Si el personaje se sale del margen vertical, se sale de la funcion:
                    if (posicion_y < 0 || posicion_y > niveles[nivel]["height"] * celda_height - celda_height) { return; }
                    
                    //Posicion del personaje antes de que lo movamos mas tarde (si es que lo movemos):
                    posicion_x_anterior = parseInt(document.getElementById("personaje").style.left);
                    posicion_y_anterior = parseInt(document.getElementById("personaje").style.top);
                    
                    //Calcular la X y la Y actuales del personaje en el mapa:
                    posicion_x_mapa = niveles[nivel]["width"] - posicion_x_anterior / celda_width;
                    posicion_y_mapa = posicion_y_anterior / celda_height + 1;
                    posicion_anterior_mapa = niveles[nivel]["width"] * posicion_y_mapa - posicion_x_mapa;
                   
                    //Variables para saber las casillas de enfrente y siguiente, y sus posiciones en el mapa:
                    var casilla_enfrente = ""; //Variable que va a contenter el simbolo que hay en el mapa de la casilla de enfrente.
                    var casilla_enfrente_posterior = ""; //Variable que va a contenter el simbolo que hay en el mapa de la casilla siguiente a la de enfrente.
                    var posicion_mapa_casilla_enfrente = 0; //Variable que va a contenter la posicion en el mapa de la casilla de enfrente.
                    var posicion_mapa_casilla_enfrente_posterior = 0; //Variable que va a contenter la posicion en el mapa de la casilla siguiente a la de enfrente.
                                                           
                    //Si el personaje va hacia la derecha, guardar la casilla que tiene enfrente y la siguiente:
                    if (posicion_x > posicion_x_anterior)
                     {
                        //Si la casilla de enfrente y la siguiente no exceden del maximo del mapa, calcularlas:
                        if (posicion_anterior_mapa+2 <= niveles[nivel]["forma"].length) { posicion_mapa_casilla_enfrente = posicion_anterior_mapa+1; casilla_enfrente = niveles[nivel]["forma"].substring(posicion_anterior_mapa+1, posicion_anterior_mapa+2); } //Casilla de enfrente.
                        if (posicion_anterior_mapa+3 <= niveles[nivel]["forma"].length) { posicion_mapa_casilla_enfrente_posterior = posicion_anterior_mapa+2; casilla_enfrente_posterior = niveles[nivel]["forma"].substring(posicion_anterior_mapa+2, posicion_anterior_mapa+3); } //Casilla siguiente a la de enfrente.
                        //Si en la casilla de enfrente hay una pieza y se acaba el mapa justo despues, salir de la funcion:
                        if (casilla_enfrente == "$" && posicion_x + celda_width > niveles[nivel]["width"] * celda_width - celda_width) { return; }
                        //Si en la casilla de enfrente hay un agujero lleno y se acaba el mapa justo despues, salir de la funcion:
                        if (casilla_enfrente == "*" && posicion_x + celda_width > niveles[nivel]["width"] * celda_width - celda_width) { return; }
                     }
                    //...o si el personaje va hacia la iquierda, guardar la casilla que tiene enfrente y la siguiente:
                    else if (posicion_x < posicion_x_anterior)
                     {
                        //Si la casilla de enfrente y la siguiente no exceden del minimo del mapa, calcularlas:
                        if (posicion_anterior_mapa-1 >= 0) { posicion_mapa_casilla_enfrente = posicion_anterior_mapa-1; casilla_enfrente = niveles[nivel]["forma"].substring(posicion_anterior_mapa, posicion_anterior_mapa-1); } //Casilla de enfrente.
                        if (posicion_anterior_mapa-2 >= 0) { posicion_mapa_casilla_enfrente_posterior = posicion_anterior_mapa-2; casilla_enfrente_posterior = niveles[nivel]["forma"].substring(posicion_anterior_mapa-1, posicion_anterior_mapa-2); } //Casilla siguiente a la de enfrente.
                        //Si en la casilla de enfrente hay una pieza y se acaba el mapa justo despues, salir de la funcion:
                        if (casilla_enfrente == "$" && posicion_x - celda_width < 0) { return; }
                        //Si en la casilla de enfrente hay un agujero y se acaba el mapa justo despues, salir de la funcion:
                        if (casilla_enfrente == "*" && posicion_x - celda_width < 0) { return; }
                     }
                    //...o si el personaje va hacia arriba, guardar la casilla que tiene enfrente y la siguiente:
                    else if (posicion_y < posicion_y_anterior)
                     {
                        //Si la casilla de enfrente y la siguiente no exceden del minimo del mapa, calcularlas:
                        if (posicion_anterior_mapa-niveles[nivel]["width"]+1 >= 0) { posicion_mapa_casilla_enfrente = posicion_anterior_mapa-niveles[nivel]["width"]; casilla_enfrente = niveles[nivel]["forma"].substring(posicion_anterior_mapa-niveles[nivel]["width"], posicion_anterior_mapa-niveles[nivel]["width"]+1); } //Casilla de enfrente.
                        if (posicion_anterior_mapa-niveles[nivel]["width"]-niveles[nivel]["width"]+1 >= 0) { posicion_mapa_casilla_enfrente_posterior = posicion_anterior_mapa-niveles[nivel]["width"]-niveles[nivel]["width"]; casilla_enfrente_posterior = niveles[nivel]["forma"].substring(posicion_anterior_mapa-niveles[nivel]["width"]-niveles[nivel]["width"], posicion_anterior_mapa-niveles[nivel]["width"]-niveles[nivel]["width"]+1); } //Casilla siguiente a la de enfrente.
                        //Si en la casilla de enfrente hay una botella y se acaba el mapa justo despues, salir de la funcion:
                        if (casilla_enfrente == "$" && posicion_y - celda_height < 0) { return; }
                        //Si en la casilla de enfrente hay un agujero lleno y se acaba el mapa justo despues, salir de la funcion:
                        if (casilla_enfrente == "*" && posicion_y - celda_height < 0) { return; }
                     }
                    //...o si el personaje va hacia abajo, guardar la casilla que tiene enfrente y la siguiente:
                    else if (posicion_y > posicion_y_anterior)
                     {
                        //Si la casilla de enfrente y la siguiente no exceden del maximo del mapa, calcularlas:
                        if (posicion_anterior_mapa+niveles[nivel]["width"]+1 <= niveles[nivel]["forma"].length) { posicion_mapa_casilla_enfrente = posicion_anterior_mapa+niveles[nivel]["width"]; casilla_enfrente = niveles[nivel]["forma"].substring(posicion_anterior_mapa+niveles[nivel]["width"], posicion_anterior_mapa+niveles[nivel]["width"]+1); } //Casilla de enfrente.
                        if (posicion_anterior_mapa+niveles[nivel]["width"]+niveles[nivel]["width"]+1 <= niveles[nivel]["forma"].length) { posicion_mapa_casilla_enfrente_posterior = posicion_anterior_mapa+niveles[nivel]["width"]+niveles[nivel]["width"]; casilla_enfrente_posterior = niveles[nivel]["forma"].substring(posicion_anterior_mapa+niveles[nivel]["width"]+niveles[nivel]["width"], posicion_anterior_mapa+niveles[nivel]["width"]+niveles[nivel]["width"]+1); } //Casilla siguiente a la de enfrente.
                        //Si en la casilla de enfrente hay una botella y se acaba el mapa justo despues, salir de la funcion:
                        if (casilla_enfrente == "$" && posicion_y + celda_height > niveles[nivel]["height"] * celda_height - celda_height) { return; }
                        //Si en la casilla de enfrente hay un agujero lleno y se acaba el mapa justo despues, salir de la funcion:
                        if (casilla_enfrente == "*" && posicion_y + celda_height > niveles[nivel]["height"] * celda_height - celda_height) { return; }
                     }
                    //...o si no, sale de la funcion:
                    else { return; }

                    //Si no hay casilla de enfrente, sa le de la funcion:                
                    if (casilla_enfrente == "") { return; }

                    //Si en la casilla de enfrente hay una pared, salir de la funcion:
                    if (casilla_enfrente == "#") { return; }
                    
                    //Si en la casilla de enfrente hay una pieza y en la siguiente hay una pared, salir de la funcion:
                    if (casilla_enfrente == "$" && casilla_enfrente_posterior == "#") { return; }

                    //Si en la casilla de enfrente hay una pieza y en la siguiente tambien, salir de la funcion:
                    if (casilla_enfrente == "$" && casilla_enfrente_posterior == "$") { return; }

                    //Si en la casilla de enfrente hay una pieza y en la siguiente un agujero lleno, salir de la funcion:
                    if (casilla_enfrente == "$" && casilla_enfrente_posterior == "*") { return; }

                    //Si en la casilla de enfrente hay una agujerno lleno y en la siguiente una pieza, salir de la funcion:
                    if (casilla_enfrente == "*" && casilla_enfrente_posterior == "$") { return; }

                    //Si en la casilla de enfrente hay una agujerno lleno y en la siguiente una pared, salir de la funcion:
                    if (casilla_enfrente == "*" && casilla_enfrente_posterior == "#") { return; }

                    //Si en la casilla de enfrente hay un agujero lleno y en la siguiente tambien, salir de la funcion:
                    if (casilla_enfrente == "*" && casilla_enfrente_posterior == "*") { return; }

                    //Si en la casilla de enfrente hay una pieza y en la siguiente ya no hay nada, salir de la funcion:
                    if (casilla_enfrente == "$" && casilla_enfrente_posterior == "") { return; }

                    //Se substituyen todas las Y (personaje) del mapa por un espacio en blanco (vacio):
                    niveles[nivel]["forma"] = niveles[nivel]["forma"].replace("@"," ");

                    //Mover el div del personaje:
                    document.getElementById("personaje").style.left = posicion_x + "px";
                    document.getElementById("personaje").style.top = posicion_y + "px";
                    
                    //Variable para saber si se ha movido una pieza:
                    var se_ha_movido_pieza = false;

                    //Si en la casilla de enfrente hay una pieza ($) y en la siguiente hay un agujero vacio (�), restar un tanto a las piezas totales y cambiar el agujero vacio por un agujero lleno (*):
                    if (casilla_enfrente == "$" && casilla_enfrente_posterior == "�")
                     {
                        //Se setea como que se ha movido una pieza:
                        se_ha_movido_pieza = true;                     
                        //Se pone un 0 en el mapa, donde antes estaba la pieza:
                        niveles[nivel]["forma"] = niveles[nivel]["forma"].substring(0,posicion_mapa_casilla_enfrente) + " " + niveles[nivel]["forma"].substring(posicion_mapa_casilla_enfrente+1,niveles[nivel]["forma"].length);
                        //Se pone un agujero lleno (*) donde va a estar la pieza despues de moverla:
                        niveles[nivel]["forma"] = niveles[nivel]["forma"].substring(0,posicion_mapa_casilla_enfrente_posterior) + "*" + niveles[nivel]["forma"].substring(posicion_mapa_casilla_enfrente_posterior+1,niveles[nivel]["forma"].length);
                        //Se borra donde antes estaba la pieza:
                        document.getElementById(posicion_mapa_casilla_enfrente).innerHTML = "&nbsp";
                        //Se pone un agujero lleno donde va a estar la pieza despues de moverla:
                        document.getElementById(posicion_mapa_casilla_enfrente_posterior).innerHTML = '<img src="'+img_agujero_lleno.src+'" width="'+celda_width+'" height="'+celda_height+'">';
                        //Se incrementa el numero de piezas colocadas:
                        numero_piezas_totales--;
                     }
                    
                    //...pero si hay una pieza en la casilla de enfrente y nada (espacio en blanco) en la posterior, mover tambien la pieza en el mapa:
                    else if (casilla_enfrente == "$" && casilla_enfrente_posterior == " " || casilla_enfrente == "$" && casilla_enfrente_posterior == "0")
                     {
                        //Se setea como que se ha movido una pieza:
                        se_ha_movido_pieza = true;
                        //Se pone un 0 en el mapa, donde antes estaba la pieza:
                        niveles[nivel]["forma"] = niveles[nivel]["forma"].substring(0,posicion_mapa_casilla_enfrente) + " " + niveles[nivel]["forma"].substring(posicion_mapa_casilla_enfrente+1,niveles[nivel]["forma"].length);
                        //Se pone una pieza ($) donde va a estar la pieza despues de moverla:
                        niveles[nivel]["forma"] = niveles[nivel]["forma"].substring(0,posicion_mapa_casilla_enfrente_posterior) + "$" + niveles[nivel]["forma"].substring(posicion_mapa_casilla_enfrente_posterior+1,niveles[nivel]["forma"].length);
                        //Se borra donde antes estaba la pieza:
                        document.getElementById(posicion_mapa_casilla_enfrente).innerHTML = "&nbsp";
                        //Se pone una pieza donde va a estar la pieza despues de moverla:
                        document.getElementById(posicion_mapa_casilla_enfrente_posterior).innerHTML = '<img src="'+img_pieza.src+'" width="'+celda_width+'" height="'+celda_height+'">';
                     }
                    //...y si en la casilla de enfrente hay un agujero lleno, y en la siguiente hay un vacio (espacio en blanco), vaciar el agujero y poner una pieza donde antes estaba vacio:
                    else if (casilla_enfrente == "*" && casilla_enfrente_posterior == " " || casilla_enfrente == "*" && casilla_enfrente_posterior == "0")
                     {
                        //Se setea como que se ha movido una pieza:
                        se_ha_movido_pieza = true;
                        //Se pone un agujero vacio (�) en el mapa, donde antes estaba el agujero lleno (*):
                        niveles[nivel]["forma"] = niveles[nivel]["forma"].substring(0,posicion_mapa_casilla_enfrente) + "�" + niveles[nivel]["forma"].substring(posicion_mapa_casilla_enfrente+1,niveles[nivel]["forma"].length);
                        //Se pone una pieza ($) donde va a estar la pieza despues de sacarla del agujero:
                        niveles[nivel]["forma"] = niveles[nivel]["forma"].substring(0,posicion_mapa_casilla_enfrente_posterior) + "$" + niveles[nivel]["forma"].substring(posicion_mapa_casilla_enfrente_posterior+1,niveles[nivel]["forma"].length);
                        //Se pone un agujero vacio donde antes estaba la pieza:
                        document.getElementById(posicion_mapa_casilla_enfrente).innerHTML = '<img src="'+img_agujero_vacio.src+'" width="'+celda_width+'" height="'+celda_height+'">';
                        //Se pone una pieza donde va a estar la pieza despues de moverla:
                        document.getElementById(posicion_mapa_casilla_enfrente_posterior).innerHTML = '<img src="'+img_pieza.src+'" width="'+celda_width+'" height="'+celda_height+'">';
                        //Se decrementa el numero de piezas colocadas:
                        numero_piezas_totales++;
                     }
                    //...y si en la casilla de enfrente hay un agujero lleno (*), y en la siguiente hay un agujero vacio (�), vaciar el agujero y poner una pieza donde antes estaba el agujero vacio (poner un agujero lleno):
                    else if (casilla_enfrente == "*" && casilla_enfrente_posterior == "�")
                     {
                        //Se setea como que se ha movido una pieza:
                        se_ha_movido_pieza = true;
                        //Se pone un agujero vacio (�) en el mapa, donde antes estaba el agujero lleno (*):
                        niveles[nivel]["forma"] = niveles[nivel]["forma"].substring(0,posicion_mapa_casilla_enfrente) + "�" + niveles[nivel]["forma"].substring(posicion_mapa_casilla_enfrente+1,niveles[nivel]["forma"].length);
                        //Se pone una agujero lleno (*) donde va a estar la pieza despues de sacarla del agujero y meterlo en el otro:
                        niveles[nivel]["forma"] = niveles[nivel]["forma"].substring(0,posicion_mapa_casilla_enfrente_posterior) + "*" + niveles[nivel]["forma"].substring(posicion_mapa_casilla_enfrente_posterior+1,niveles[nivel]["forma"].length);
                        //Se pone un agujero vacio donde antes estaba la pieza:
                        document.getElementById(posicion_mapa_casilla_enfrente).innerHTML = '<img src="'+img_agujero_vacio.src+'" width="'+celda_width+'" height="'+celda_height+'">';
                        //Se pone un agujero lleno donde va a estar la pieza despues de moverla:
                        document.getElementById(posicion_mapa_casilla_enfrente_posterior).innerHTML = '<img src="'+img_agujero_lleno.src+'" width="'+celda_width+'" height="'+celda_height+'">';
                     }

                    //Borrar movimiento el personaje, por si existia anteriormente:
                    clearTimeout(movimiento_personaje);
                    //Crear movimiento del personaje:
                    movimiento_personaje = setTimeout("document.getElementById('personaje_imagen').src = imagen_personaje.src;", 150);
                    
                    //Se incrementa el numero de movimientos:
                    numero_movimientos++;
                    //Se suma un movimiento al contador:
                    numero_movimientos_mostrar++;
                    //Guarda el mapa anterior, para poder realizar deshacer:
                    guardar_mapa();
                    
                    //Se actualiza la barra de informacion:
                    actualizar_barra();
                    
                    //Si se han colocado todas las piezas, pasa de nivel:
                    if (numero_piezas_totales <= 0) { pasar_nivel(); }
                 }


                //Funcion que recoge el mapa del editor, enviado por la url (variables enviadas por GET: mapa o cualquier otra que sea la primera, height y width):
                function recoger_mapa_url()
                 {
                    //Se setea conforme el juego ya se ha iniciado:
                    juego_iniciado = true;
                    
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

                    //Si alguna de las variables esta vacia o el width o el height no son numericos, inicia el juego normalmente y sale de la funcion:
                    if (mapa_url == "" || mapa_width_url == "" || mapa_height_url == "" || isNaN(mapa_width_url) || isNaN(mapa_height_url)) { iniciar_juego(nivel); return; }
                    //...y si no, procede:
                    else
                     {
                        //Genera los niveles:
                        generar_niveles();
                        
                        //Incrementa en una unidad el numero de niveles, siempre que no se haya recogido antes el mapa por la url:
                        if (!mapa_url_recogido) { numero_niveles++; }

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
                        if (mapa_url_refinado.length != parseInt(unescape(mapa_width_url))*parseInt(unescape(mapa_height_url))) { alert("Failed to load map send by url because width and height are erroneous."); mapa_width = 10; mapa_height = 10; preparar_mapa(); return; }

                        //Quita las posibles almohadillas de la url (aparecen al probar mapa, etc.):
                        for (x=0; x<=mapa_url_refinado.length; x++) { mapa_url_refinado = mapa_url_refinado.replace("#", ""); }
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
                         
                        //Crea el nuevo nivel enviado por url (GET), como si fuera el ultimo:
                        niveles[numero_niveles] = new Array();
                        niveles[numero_niveles]["forma"] = mapa_url_refinado;
                        niveles[numero_niveles]["width"] = parseInt(unescape(mapa_width_url));
                        niveles[numero_niveles]["height"] = parseInt(unescape(mapa_height_url));
           
                        //Se setea la variable conforme ya se ha comprobado si hay o no un mapa enviado por url (GET):
                        mapa_url_recogido = true;

                        //Inicia el nivel enviado por url:
                        iniciar_juego(numero_niveles);
                     }
                 }
                
            //-->
        </script>
    </head>
    <body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="javascript:recoger_mapa_url();" onKeyDown="javascript:pulsar_tecla(event, 'onkeypress');" onKeyPress="javascript:pulsar_tecla(event, 'onkeydown');">
        <!-- Botones -->
        <div id="botones" style="visibility:visible; background:transparent; color:#000000; left:10px; top:10px; width:600px; height:50px; padding:0px; position:absolute; font-size:12px; font-family:arial; line-height:18px; z-index:5;">
            <a href="javascript:deshacer_accion();" title="Undo movement (U)"><img src="img/deshacer.gif" title="Undo movement (U)" alt="Undo movement" border="0" hspace="0" vspace="0" width="40" height="40"></a>
            <a href="javascript:rehacer_accion();" title="Redo movement (Y)"><img src="img/rehacer.gif" title="Redo movement (Y)" alt="Redo movement" border="0" hspace="0" vspace="0" width="40" height="40"></a>
            <a href="javascript:reiniciar_nivel();" title="Restart level (R)"><img src="img/reiniciar.gif" title="Restart level (R)" alt="Restart level" border="0" hspace="0" vspace="0" width="40" height="40"></a>
            &nbsp;&nbsp; <img src="img/editor.gif" title="Level editor" alt="Level editor" border="0" hspace="0" vspace="0" width="40" height="40" style="cursor: pointer; cursor: hand;" onClick="javascript:if (confirm('Press ok if you want to load level editor (actual game will be end)')) { window.location.href=url_editor; }"></a>
            <div id="informacion" style="visibility:visible; background:transparent; color:#000000; left:190px; top:0px; width:480px; height:50px; padding:0px; position:absolute; font-size:12px; font-family:arial; line-height:18px; z-index:6;">
                <div id="barra_estado" style="visibility:visible; background:transparent; color:#000000; left:0px; top:0px; width:480px; height:20px; padding:0px; position:absolute; font-size:12px; font-family:verdana; font-weight:bold; line-height:18px; z-index:7;">
                    Loading...
                </div>
                <br>
                &copy; Yasminuroban by Joan Alba Maldonado
            </div>
        </div>
        <!-- Fin de Botones. -->
        <!-- Zona de Juego: -->
        <div id="zona_juego" style="visibility:visible; background:transparent; color:#000000; left:10px; top:60px; width:340px; height:40px; padding:0px; position:absolute; font-size:1px; line-height:1px; z-index:1;">
            <!-- Mapa: -->
            <div id="mapa" style="visibility:hidden; background:transparent; color:#000000; left:0px; top:0px; width:340px; height:40px; padding:0px; position:absolute; font-size:1px; line-height:1px; z-index:2;">
            </div>
            <!-- Fin de Mapa. -->
            <!-- Personaje: -->
            <div id="personaje" style="visibility:hidden; background:transparent; color:#000000; left:0px; top:0px; width:40px; height:40px; padding:0px; position:absolute; font-size:1px; line-height:1px; z-index:3;">
              <img src="img/yas_down1.gif" id="personaje_imagen" title="You are here" alt="@" width="40" height="40" border="0" hspace="0" vspace="0">
            </div>
            <!-- Fin de Personaje. -->
            <!-- Mensaje: -->
            <div id="mensaje" style="visibility:visible; background:#e28000; color:#000000; left:0px; top:30px; width:320px; height:20px; padding:0px; position:absolute; font-size:12px; font-weight:bold; font-family:verdana; line-height:20px; text-align:center; font-style:italic; filter:alpha(opacity=80); opacity:0.8; -moz-opacity:0.8; z-index:4;">
                Loading...
            </div>
            <!-- Fin de Mensaje. -->
        </div>
        <!-- Fin de Zona de Juego. -->
        <!-- Informacion: -->
        <div id="informacion_autor" style="left:10px; top:530px; height:0px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:20px; text-decoration:none; font-family:verdana; font-size:10px; z-index:10;">
            &copy; <b>Yasminuroban</b> 0.68a by <i>Joan Alba Maldonado</i> (<a href="mailto:granvino@granvino.com;">granvino@granvino.com</a>) &nbsp;<sup>(DHTML 100%)</sup>
            <br>&nbsp;&nbsp;- Prohibited to publish, reproduce or modify without maintain original author's name.
            <br>
            &nbsp;&nbsp;<tt>* Keyboard arrows to move and push, 'U' to undo movement, 'Y' to redo movement, and 'R' to restart level. Under Opera, leave the mouse cursor over game zone.</tt>
            <br>
            &nbsp;&nbsp;<i>Dedicated to Yasmina Llaveria del Castillo</i>
        <!-- Fin de Informacion. -->
    </body>
</html>
