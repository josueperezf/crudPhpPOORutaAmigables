<?php 
class Db
{
	private static $instance=NULL;
	function __construct(){}
	public static function  getConnect()
	{
		$dbname		='bodega';
		$host		='localhost';
		$user		='root';
		$password	='';
		//puede ser pgsql para postgres o mysql para mysql
		$tipoDatabase='mysql';
		if (!isset(self::$instance)) {
			$pdo_options[PDO::ATTR_ERRMODE]=PDO::ERRMODE_EXCEPTION;
			//self::$instance= new PDO('mysql:host=localhost;dbname=bodega','root','',$pdo_options);
			self::$instance= new PDO("$tipoDatabase:dbname=$dbname;host=$host", $user, $password, $pdo_options);
		} 
		return self::$instance;
	}
}
?>