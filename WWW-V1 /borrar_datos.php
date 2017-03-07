<?php

    /**
	* Autor: Camilo Figueroa
	* El siguiente módulo y archivo del programa se encarga de realizar el borrado de los datos en el sistema invocando a la respectiva función 
    * del archivo funciones, que es el núcleo central del sistema. 
    * Para lo anterior hay que tener en cuenta que el sistema basa su funcionamiento en algunos principios de las bases de datos como lo es la 
    * existencia de las llaves primarias. Este mecanismo permite la escogencia del registro a borrar, sin ello el borrar no funcionará.
    * 
	*/
    
    include( "funciones.php" );
    include( "config.php" );
    
    $tabla = "";
    $llave_primaria_tabla = "";
    $id_o_valor_llave_primaria = "";
    
    echo imprimir_menus();
    echo "<br>";

    //Si el nombre de la tabla se ha suministrado y el valor del campo para borrar, entonces se procederá a operar.
    if( isset( $_GET[ 'tabla' ] ) && isset( $_GET[ 'id' ] ) )
    {
        $tabla = $_GET[ 'tabla' ]; //Hay que traer la tabla para poder hacer el borrado.
        $id_o_valor_llave_primaria = $_GET[ 'id' ]; //hay que trae el valor del campo que permitirá hacer el borrado.
        
        //Aquí se extrae el nombre del campo o llave primaria que permitirá, para este caso, hacer el borrado.
        //En otros casos el borrado puede depender de otros valores.
        $llave_primaria_tabla = retornar_dato_tabla( "INFORMATION_SCHEMA.COLUMNS", "COLUMN_NAME", " TABLE_SCHEMA = '$bd' AND TABLE_NAME = '$tabla' AND COLUMN_KEY = 'PRI' " );
        
        echo borrar_datos( $tabla, " $llave_primaria_tabla = '$id_o_valor_llave_primaria' " );
    }
    
    

