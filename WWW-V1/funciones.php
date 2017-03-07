<?php

	/**
	* Autor: Camilo Figueroa
	* Este archivo contiene un compendio de funciones básicas para construir los ABML de una base de datos.
	*
	*/

	
	/**
	* Esta función se encarga de traer información sin importar las tablas.
	* Ojo, el primer campo que se debe procesar es la llave primaria, esto para el efecto del borrado.
	* @param 		caracteres 			Es el nombre de la tabla.
	* @param 		número 				Es el índice o lugar de la llave primaria con respecto de los otros campos. 
	*									Si el valor es negativo, no se imprimirá el enlace para borrar.
	* @return  		caracteres			Retorna html con los datos de una tabla. 
 	*/
	function traer_informacion( $tabla, $indice_llave_primaria = null, $condicion = null, $campos_a_mostrar = null )
	{
		include( "config.php" ); //Aquí se traen los parámetros de la base de datos.
		//Hay que recordar que solo debería existir un archivo que permita dicha configuración.

		$salida = "";

		//Si al llamar la función no se ingresa el campo dos o segundo parámetro, se usará como llave primaria el 
		//primer elemento del recorset o vector que retorna la selección del sql.
		if( $indice_llave_primaria == null ) $indice_llave_primaria = 0;

		if( $campos_a_mostrar == null ) $campos_a_mostrar = "*";

		//------------SQL Se traen datos----------------------------------------------------
		$sql = "SELECT $campos_a_mostrar FROM  $tabla ";
		if( $condicion != null ) $sql .= " WHERE ".$condicion;

		if( $sn_pruebas == "s" ) echo "<div class='contenedor-sql-pruebas'>".$sql."</div>";

		$conexion = mysqli_connect( $servidor, $usuario, $clave, $bd );
		$resultado = $conexion->query( $sql );	

		$salida .= "<table border='1px'>";

		while( $fila = mysqli_fetch_array( $resultado ) )
		{
			$salida .= "<tr>";

				for( $i = 0; $i < mysqli_num_fields( $resultado ); $i ++ )
				$salida .= "<td>".$fila[ $i ]."</td>"; //Este es el dato impreso
					
				//El borrado de un dato se hará por llave primaria. Debería ser el primer campo de la tabla.
				if( $indice_llave_primaria != -1 )
				$salida .= "<td><a href='borrar_datos.php?id=".$fila[ 0 ]."&tabla=".$tabla."'>Borrar</a></td>";

			$salida .= "</tr>";
		}

		$salida .= "</table>";

		return $salida;	
	}

	/**
	*
	*
	*/
	function traer_lista_informacion( $nombre_lista, $tabla, $campo_llave_primaria, $campo_a_mostrar )
	{
		include( "config.php" ); //Aquí se traen los parámetros de la base de datos.
		//Hay que recordar que solo debería existir un archivo que permita dicha configuración.

		$salida = "";

		//------------SQL Se traen datos----------------------------------------------------
		$sql = "SELECT * FROM  $tabla ";
		
		if( $sn_pruebas == "s" ) echo "<div class='contenedor-sql-pruebas'>".$sql."</div>";

		$conexion = mysqli_connect( $servidor, $usuario, $clave, $bd );
		$resultado = $conexion->query( $sql );	

		$salida .= "<SELECT NAME='$nombre_lista'>";
		$salida .= "<OPTION VALUE='-1'>Seleccionar</OPTION>";

		while( $fila = mysqli_fetch_assoc( $resultado ) )
		{
			$salida .= "<OPTION VALUE='".$fila[ $campo_llave_primaria ]."'>".$fila[ $campo_a_mostrar ]."</OPTION>";
		}

		$salida .= "</SELECT>";

		return $salida;	
	}

	/**
	* Esta función se encarga de guardar información sin importar la tabla.
	* @param 		texto 			Es el nombre de la tabla que guardará la información.
	* @param 		texto 			Es el texto que contiene todos los campos separados por comas, generalmente desde un formulario.
	* @param 		texto 			Son los valores de los campos que también deberán estar separados por comas.
	* @return 		texto 			Es el mensaje de alerta para saber si se afectaron filas de la tabla o no.
	*/
	function guardar_informacion( $tabla, $campos, $datos )
	{
		include( "config.php" ); //Aquí se traen los parámetros de la base de datos.
		//Hay que recordar que solo debería existir un archivo que permita dicha configuración.

		$salida = "";

		//------------SQL para ingresar datos----------------------------------------------------
		$sql = "INSERT INTO  $tabla ( $campos ) VALUES( $datos )";

		if( $sn_pruebas == "s" ) echo "<div class='contenedor-sql-pruebas'>".$sql."</div>";

		$conexion = mysqli_connect( $servidor, $usuario, $clave, $bd );
		$resultado = $conexion->query( $sql );

		//Si se han afectado filas, entonces se procederá a informar.
		if( $conexion->affected_rows > 0 )
		{
			$salida = "Los datos se han guardado correctamente.";

		}else{
				$salida = "Error: los datos no se han guardado. Es probale que la información ya se encuentre en el sistema.";
			}

		//echo $sql; //Al habilitar esta línea se puede observar el SQL que ha sido formado para la inserción. 

		return $salida;	
	}

	/**
	*
	*
	*/
	function actualizar_datos()
	{
		
	}

	/**
	* Retorna un dato de una tabla, de acuerdo a unas condiciones.
	* @param 		texto 		Es el nombre de la tabla de la cual se traerán los datos.
	* @param 		texto 		Es el campo a retornar o un SQL válido que represente un campo, como un COUNT o un SUM.
	* @param 		texto 		Es la condición opcional para traer la información.
	* @return 		texto 		Se retornará el resultado como un único valor de texto, podrían ser números también.
	*/
	function retornar_dato_tabla( $tabla, $campo_a_retornar, $condicion = null )
	{
		include( "config.php" ); //Aquí se traen los parámetros de la base de datos.
		//Hay que recordar que solo debería existir un archivo que permita dicha configuración.

		$salida = "";

		//------------SQL Se traen datos----------------------------------------------------
		$sql = "SELECT $campo_a_retornar AS dato_de_salida FROM $tabla ";
		if( $condicion != null ) $sql .= " WHERE $condicion ";

		if( $sn_pruebas == "s" ) echo "<div class='contenedor-sql-pruebas'>".$sql."</div>";

		$conexion = mysqli_connect( $servidor, $usuario, $clave, $bd );
		$resultado = $conexion->query( $sql );	

		//Si se encuentran datos se retornarán. De lo contrario la función no retornará o retornará vacío.
		if( mysqli_num_rows( $resultado ) > 0 )
		{
			while( $fila = mysqli_fetch_assoc( $resultado ) )
			{
				$salida = $fila[ 'dato_de_salida' ];
			}
		}

		return $salida;
	}

	/**
	* Esta función se encarga de borrar datos de una tabla.
	* @param 		texto 			El nombre de la tabla a la cual se le van a borrar registros.
	* @param 		texto 			Condición que alterará uno o varios registros.
	* @return 		texto 			Alerta o mensaje que indicará si se han afectado registros de la tabla.
	*/
	function borrar_datos( $tabla, $condicion = null )
	{
		include( "config.php" ); //Aquí se traen los parámetros de la base de datos.
		//Hay que recordar que solo debería existir un archivo que permita dicha configuración.

		$salida = "";

		//------------SQL para ingresar datos----------------------------------------------------
		$sql = "DELETE FROM $tabla ";
		if( $condicion != null ) $sql .= " WHERE $condicion ";
		
		if( $sn_pruebas == "s" ) echo "<div class='contenedor-sql-pruebas'>".$sql."</div>";

		$conexion = mysqli_connect( $servidor, $usuario, $clave, $bd );
		$resultado = $conexion->query( $sql );

		//Si se han afectado filas, entonces se procederá a informar.
		if( $conexion->affected_rows > 0 )
		{
			$salida = "Los datos se han eliminado correctamente.";

		}else{
				$salida = "Error: no se han afectado o borrado filas de la tabla en la base de datos.";
			}

		return $salida;
	}

	/**
	* Esta función se encarga de retornar los menús para que sean impresos desde afuera.
	* El menú debe estar presente en todos los archivos que muestren resultados al usuario. Es decir, en todas las secciones.
	*/
	function imprimir_menus( $des = null  )
	{
		$salida = "";

		$salida .= "<UL>";
		$salida .= "<li><a href='index.php'>Inicio</a></li>";
		$salida .= "<li><a href='usuarios.php'>Usuarios</a></li>";
		$salida .= "<li><a href='vehiculos.php'>Vehiculos</a></li>";
		$salida .= "</UL>";

		return $salida;
	}

	/**
	* Una función puede no tener código, lo cual indicaría que no hace algo. Esto no afectaría el sistema siempre y cuando
	* la función como tal esté bién construida, que es algo a lo que un programador se debe acostumbrar. De allí en 
	* adelante lo que se construya es ganancia.
	*/
	function yo_no_hago_nada()
	{

	}

