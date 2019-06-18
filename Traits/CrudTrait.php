<?php
namespace Traits;
use Db;
trait CrudTrait
{
    public static function paginar($query){
        $requestUrl=$_SERVER['REQUEST_URI'];
        $porciones = explode("&", $requestUrl);
        $ruta=$porciones[0]; // porción1
        
        $db=Db::getConnect();
        $busqueda='';
        $campoOrdernar='id';
        $orderBy='desc';
        $pagina=1;
        if(array_key_exists('busqueda',$_REQUEST))
            $busqueda=strtoupper($_REQUEST['busqueda']);
        if(array_key_exists('campoOrdernar',$_REQUEST))
            $campoOrdernar=$_REQUEST['campoOrdernar'];
        if(array_key_exists('orderBy',$_REQUEST))
            $orderBy=$_REQUEST['orderBy'];
        if(array_key_exists('page',$_REQUEST))
            $pagina=$_REQUEST['page'];	
        if($pagina==0)
            $pagina=1;
        $pagina--;
        $sql= $query."
                        like '%$busqueda%' 
                    order by $campoOrdernar $orderBy
                    ";
        //echo $sql;
        $select=$db->query($sql);
        $total = $select->rowCount();
        //Define el número 0 para empezar a paginar multiplicado por la cantidad de resultados por página
        $cantidad_resultados_por_pagina = 10;
        //$empezar_desde = ($pagina-1) * $cantidad_resultados_por_pagina;
        $final=$pagina* $cantidad_resultados_por_pagina;
        //$total_registros = pg_num_rows($resHMN);
        $total_registros=$total;
        $total_paginas = ceil($total_registros / $cantidad_resultados_por_pagina); 
        $sql.="
        LIMIT $cantidad_resultados_por_pagina offset $final
        ";
        //echo $sql;
        //$resHMN=$conexHM->sentencia($sql);
        $select=$db->query($sql);
        //$porPagina=$total = $select->rowCount();
        $porPagina= $select->rowCount();

         //$porPagina = pg_num_rows($resHMN);
         if(!$porPagina)
            $porPagina=1;
        
        $data=array();
        
        foreach($select->fetchAll() as $reg){
            array_push($data, $reg);
        }
            $pagMostrar=$pagina+1;	
        $aux=array(
                'data'=>$data,
                'total'=>$total,
                'path'=>$ruta,
                'pagination'=>array(
                                    'path'=>$ruta,
                                    'current_page'=>$pagina++,
                                    'per_page'=>$porPagina,
                                    //ultima pagina
                                    'last_page'=>ceil($total / $cantidad_resultados_por_pagina),
                                    ),
                //pagina actual
                'current_page'=>$pagina++,
                'per_page'=>$porPagina,
                //ultima pagina
                'last_page'=>ceil(($total / $cantidad_resultados_por_pagina)),
                //mostrando desde
                'from'=>($pagMostrar*$cantidad_resultados_por_pagina)-$cantidad_resultados_por_pagina,
                //mostrando hasta
                'to'=>(($pagMostrar*$cantidad_resultados_por_pagina)-$cantidad_resultados_por_pagina)+count($data),
                'campoOrdernar'=>$campoOrdernar,
                'orderBy'=> $orderBy
            );
            //echo('<pre>');
        //echo json_encode( $aux);
        return $aux;
    }
    public static function find($query='', $imprimirSql=false){
        //con la siguiente linea obtengo el nombre de la clase q invoca a este metodo sin importar q el mismo sea estatico,
        // solo funciona de php 5.3 en adelante
        $nombreDelModelo = get_called_class();
        $db=Db::getConnect();
        $instancia= new $nombreDelModelo;
        $tabla=$instancia->table;
        $primaryKey='id';
        if(property_exists($instancia, 'primaryKey')){
            $primaryKey=$instancia->primaryKey;
        }
        $respuesta=[];
        if($query!='')
            $query=' WHERE '.$query;
        $sql="SELECT * FROM $tabla as b $query order by b.id desc";
        if($imprimirSql)
            echo $sql;
        if(!$select=$db->query($sql)){
			http_response_code(500);
            return false;
        }
        $i=0;
		foreach($select->fetchAll() as $data){
            $respuesta[]= new $nombreDelModelo;
            foreach($instancia->atributos as $atributo):
                $respuesta[$i]->$atributo=$data[$atributo];
            endforeach;
            $i++;
		}
        return $respuesta;
        //retorna array de instancias del metodo que hizo la llamda
    }
    public function save(){
        //echo get_class($this) . "<br>";
        //echo $this->nombre . "<br>";
        $db=Db::getConnect();
        $tabla=$this->table;
        $atributosTexto='';
        $conData=false;
        //busco q atributos de la clase tiene valores y no estan nulos, solo ellos debo insertar
        foreach($this->atributos as $atributo):
            if($this->$atributo !==null){
                $atributosTexto.=$atributo.' ';
                $conData=true;
            }
        endforeach;
        $atributosTexto= str_replace(" ", ", ", trim($atributosTexto));
        $atributosTextoParametros=str_replace(", ", ",:",$atributosTexto);
        $sql='INSERT INTO '.$tabla.' ('.$atributosTexto.') VALUES (:'.$atributosTextoParametros.')';
        $insert=$db->prepare($sql);
        foreach($this->atributos as $atributo):
            if($this->$atributo !==null){
                $insert->bindValue($atributo,$this->$atributo);
            }
        endforeach;
        if($conData)
            return $insert->execute();
        else
            return false;
    }
    public function update(){
        $db=Db::getConnect();
        $tabla=$this->table;
        $primaryKey='id';
        if(property_exists($this, 'primaryKey')){
            $primaryKey=$this->primaryKey;
        }
        $atributosTexto='';
        $conData=false;
        foreach($this->atributos as $atributo):
            if($this->$atributo !==null){
                if($atributo !==$primaryKey){
                    $atributosTexto.=$atributo.'=:'.$atributo.',';
                    $conData=true;
                }
            }
        endforeach;
        //elimino el la ultima coma ',' en el string
        $atributosTexto= trim($atributosTexto, ', ');
        $sql='UPDATE '.$tabla.' SET '.$atributosTexto.' WHERE '.$primaryKey.'=:'.$primaryKey;
        $update=$db->prepare($sql);
        foreach($this->atributos as $atributo):
            if($this->$atributo !==null){
                if($atributo !==$primaryKey)
                    $update->bindValue($atributo,$this->$atributo);
            }
        endforeach;
        $update->bindValue($primaryKey,$this->$primaryKey);
        if($conData)
            return $update->execute();
        else 
            return false;
    }
    public function delete(){
        $db=Db::getConnect();
        $tabla=$this->table;
        $primaryKey='id';
        if(property_exists($this, 'primaryKey')){
            $primaryKey=$this->primaryKey;
        }
        if(!$this->$primaryKey)
            return false;
        $sql="DELETE  FROM $tabla WHERE $primaryKey=:$primaryKey";
        $delete=$db->prepare($sql);
        $delete->bindValue($primaryKey,$this->$primaryKey);
		return $delete->execute();
    }
}
?>