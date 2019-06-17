<?php 
//recibo parametros de la url
require_once('connection.php');
require_once('routing.php');
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
/*
if (isset($_GET['controller'])&&isset($_GET['action'])) {	
	$controller=$_GET['controller'];
	$action=$_GET['action'];
}
*/
//guardo los controladores disponibles con sus acciones y los metodos con que los pueden llamar 'get post'
$controllers=Routing::controladorMetodos();
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
	require_once('Controllers/'.$controller.'Controller.php');
	$nombreControlador=ucfirst($controller.'Controller');
	//cargar todas las librerias
	$directorio = 'Librerias';
	$ficheros = array_diff(scandir($directorio), array('..', '.'));
	foreach ($ficheros as $key => $value) {
		require_once($directorio.'/'.$value);
	}
	//fin de la carga de las librerias

	//cargar todos los modelos
	$directorio = 'Model';
	$ficheros = array_diff(scandir($directorio), array('..', '.'));
	foreach ($ficheros as $key => $value) {
		require_once($directorio.'/'.$value);
	}
	//creo instancia del controlador
	$controller = new $nombreControlador;
	//llamo al metod del controladr
	//$controller->{$action}();
	//call_user_func(array($controller, $action));
	if( count($parametros) >0){
		call_user_func_array(array($controller, $action), $parametros );
	}
	else{
		call_user_func(array($controller, $action));
	}
}
?>