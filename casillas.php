<?php
	$entidad = 9;
	$secciones = 6000;

	$dblink = mysql_connect("localhost", "root", "root");
	mysql_select_db("casillas", $dblink);
	
	$remote = curl_init();
	curl_setopt($remote, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($remote, CURLOPT_POST, 1);
	curl_setopt($remote, CURLOPT_URL, 'http://ubicatucasilla.appspot.com/llamada');

	
	for($seccion=0; $seccion<$secciones; $seccion++){
		echo "<br> $seccion ... ";
		// Datos que se van a enviar por POST
		$data = array(
			'action' => 'v3_get_ncasilla',
			/*'sessid' => '"'. $sessid .'"',*/
			'ent' => $entidad,
			'sec' => $seccion
		);
		
		// se prepara y se hace la petición
		curl_setopt($remote, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec($remote);
		$dataresult = json_decode($result);
		
		if($dataresult->mensaje == 1){
		
			$result_ent = $dataresult->entidad;
			$result_sec = $dataresult->seccion;
		
			foreach($dataresult->valores as $valores){
				$query = "INSERT INTO `casillas`.`casillas` VALUES (NULL, '$result_ent', '$result_sec', '$valores->rr', '$valores->entidad', '$valores->distrito', '$valores->seccion', '$valores->localidad', '$valores->manzana', '$valores->domicilio', '$valores->ubicacion', '$valores->referencia', '$valores->tipo', '$valores->desc', '$valores->observaciones', '$valores->punto', '$valores->aux1', '$valores->aux2', '$valores->estatus')";
				//echo $query;
				if(mysql_query($query, $dblink))
					echo "OK <br>";
				else
					echo "ERROR <br>";
			}
		}
		else
			echo "NO EXISTE <br>";
	}
	
	
	
		
	
	
	
?>