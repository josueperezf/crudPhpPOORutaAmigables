<?php 
namespace App\Controllers;
use App\Model\Bodega;
use App\Librerias\ValidacionesLibreria;
class BodegaController
{
	function __construct()
	{
		
	}

	function index(){
		$paginacion=Bodega::paginacion();
		$bodegas=$paginacion['data'];
		require_once('Views/Bodega/index.php');
	}

	function create(){
		$bodega = new Bodega();
		require_once('Views/Bodega/create.php');
	}

	function store(){
		ValidacionesLibreria::validacionTotal($_POST,
			[
				'nombre'=>[array('longitud'),array('inicio'=>3,'fin'=>'100')],
				'direccion'=>[array('longitud'),array('inicio'=>3,'fin'=>'100')],
			]);
		$nombre=strtoupper(trim($_POST['nombre']));
		$direccion=strtoupper(trim($_POST['direccion']));
		//valido sino exite otra bodega con el mismo nombre
		$bodega=Bodega::find("b.nombre='$nombre'");
		if(count($bodega)>0){
			//nombre repetido
			ValidacionesLibreria::mostrarError('nombre', 'Ya existe un elemento con este valor');
		}

		$bodega= new Bodega(null, $nombre,$direccion,'1');
		if($bodega->save())
			http_response_code(201);
		else
			http_response_code(500);
		//header('Location: '.'?controller=bodega&action=index');
	}

	function edit($id=0){
		ValidacionesLibreria::validacionTotal(['id'=>$id],
			[
				'id'=>[array('entero')],
			]);

		$bodega=Bodega::find("b.id=$id");
		$bodega=$bodega[0];
		if(!$bodega)
			header('Location: '.str_replace("index.php", "", $_SERVER['PHP_SELF']).'error/noEncontrado');
		require_once('Views/Bodega/edit.php');
	}

	function update(){
		//valido los campos que me envian
		ValidacionesLibreria::validacionTotal($_POST,
			[
				'id'=>[array('entero')],
				'nombre'=>[array('longitud'),array('inicio'=>3,'fin'=>'100')],
				'direccion'=>[array('longitud'),array('inicio'=>3,'fin'=>'100')],
			]);
		$estatus=1;
		if (!isset($_POST['estatus']))
			$estatus=0;
		$id=$_POST['id'];
		//le quito los espacios en blanco y coloco todo en mayuscula
		$nombre=strtoupper(trim($_POST['nombre']));
		$direccion=strtoupper(trim($_POST['direccion']));
		//busco en base de datos si ese id existe
		$bodega=Bodega::find("b.id=$id");
		$bodega=$bodega[0];
		if(!$bodega){
			header('Location: '.str_replace("index.php", "", $_SERVER['PHP_SELF']).'error/noEncontrado');
			exit();
		}
		//busco si el nombre esta repetido 
		$bodega=Bodega::find("b.id!=$id and  b.nombre='$nombre'");
		if(count($bodega)>0){
			//nombre repetido
			ValidacionesLibreria::mostrarError('nombre', 'Ya existe un elemento con este valor');
			exit();
		}
		$bodega = new Bodega($_POST['id'],$nombre,$direccion,$estatus);
		if($bodega->update())
			http_response_code(200);
		else
			http_response_code(500);
		//$this->show();
		//header('Location: '.'?controller=bodega&action=index');

	}
	function delete($id=null){
		if(!ValidacionesLibreria::validacionTotal(['id'=>$id],
			[
				'id'=>[array('entero')],
			])
		){
			exit();
		}
		//busco si el id pasado existe en la base de datos
		$bodega=Bodega::find("b.id=$id");
		if(count($bodega)==0){
			header('Location: '.'/crudPhpPOORutaAmigables/bodega/index');
			exit();
		}
		//como la funcion find me retorna un array, tomo solo el primero q es el unico q retornara
		$bodega=$bodega[0];
		if($bodega->delete())
			header('Location: '.'/crudPhpPOORutaAmigables/bodega/index');
		//echo "<script>location.href='/crudPhpPOORutaAmigables/bodega/index/';</script>";
	}
}
?>