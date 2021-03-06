<?php 
//AUTOR INGENERIO JOSUE PEREZ

/***  IMPORTANTE PARA Q NO SE OLVIDE */
/*
si hago cambios en la estructura de carpetas de app o config, o en sus namespace debo ejecutar el siguiente comando
composer dump-autoload
**/

require 'vendor/autoload.php';
$controllerDefault='bodega';
$actionDefault='index';
$parametros='';
$url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
$url = explode('/', $url);
$url = array_filter($url);
	
$controller = strtolower(array_shift($url));
$action = strtolower(array_shift($url));
$parametros = $url;
$metodo=$_SERVER['REQUEST_METHOD'];
if(!$controller)
	$controller=$controllerDefault;
if(!$action)
	$action=$actionDefault;
//guardo los controladores disponibles con sus acciones y los metodos con que los pueden llamar 'get post'
$controllers=Config\Routing::controladorMetodos();
if (array_key_exists($controller,  $controllers)) {
	if (array_key_exists($action,  $controllers[$controller] )){
		if ($controllers[$controller][$action] == $metodo)
		{
			call($controller, $action, $parametros);
		}else{
			//echo 'metodo no encotrado';
		}
	}
	else{
		call('error','noEncontrado');
	}		
}else{
	call('error','noEncontrado');
}


function call($controller, $action,$parametros=null){
	require_once('app/Controllers/'.ucfirst($controller).'Controller.php');
	$nombreControlador=ucfirst($controller.'Controller');
	//creo instancia del controlador
	$nombreControlador='App\\Controllers\\'.$nombreControlador;
	$controller = new $nombreControlador;
	//llamo al metod del controladr
	//$controller->{$action}();
	if( count($parametros) >0){
		call_user_func_array(array($controller, $action), $parametros );
	}
	else{
		call_user_func(array($controller, $action));
	}
}
?>