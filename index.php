<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
//AUTOR INGENERIO JOSUE PEREZ
//las siguientes lineas verifican si la llamada se hace con ajax, si es asi entonces solo se carga el codigo nada del css o html del index
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{
	//no se carga la plantilla 
	require_once('correr.php');
	}else
	{	//se carga la plantilla y dentro de ella se carga el correr.php
		require_once('Views/Layouts/layout.php');	
	}
 ?>