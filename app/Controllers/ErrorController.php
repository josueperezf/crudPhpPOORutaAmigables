<?php
namespace App\Controllers;
use App\Model\Alumno;
class ErrorController
{
    public function noEncontrado(){
		require_once('Views/Error/noEncontrado.php');
    }
}
?>