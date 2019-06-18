<?php
namespace Model;
use Db;
class Bodega extends AppModel
{
	protected $id;
	protected $nombre;
	protected $direccion;
	protected $estatus;
	protected $primaryKey='id';
	//atributo obligatorio
	protected $table='bodegas';
	//atributo obligatorio
	protected $atributos=['id','nombre','direccion','estatus'];
	function __construct($id=null, $nombre=null,$direccion=null, $estatus=0)
	{
		$this->setId($id);
		$this->setNombre($nombre);
		$this->setDireccion($direccion);
		$this->setEstatus($estatus);
	}
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getNombre(){
		return $this->nombre;
	}

	public function setNombre($nombre){
		$this->nombre = $nombre;
	}

	public function getDireccion(){
		return $this->direccion;
	}

	public function setDireccion($direccion){
		$this->direccion = $direccion;
	}

	public function getEstatus(){

		return $this->estatus;
	}

	public function setEstatus($estatus=1){
		
		$this->estatus = $estatus;
	}
	public static function paginacion(){
		$sql="select * from bodegas where UPPER(concat(nombre, ' ',direccion,' ',
		case
			when (estatus) = '1' then 'ACTIVO'
			when (estatus) = '0' then 'INACTIVO'
		end) )";
		return parent::paginar($sql);
	}
}
?>