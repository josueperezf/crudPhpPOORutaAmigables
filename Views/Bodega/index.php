<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{
	// es ajax
	}
	else{
		?>
		<h2>SALUDOS LEOOOOOOOO </h2>
		<div class="container">
			<h2>Bodegas</h2>
			<button class="waves-effect waves-light btn modal-trigger"  href="#modal1" onClick="$('.modal').modal();libAjaxGet('bodega/create','divModal')">
				Nuevo
			</button>
			<div class="input-field">
      			<label for="text">Busqueda</label>
				<input class="form-control" id="busqueda" name='busqueda' name="id" type="text" onkeyup="libAjaxGet('bodega/index&busqueda='+this.value ,'divIndex',function(){}) ">
			</div>
		</div>
		<?php
	}
?>
<div id='divIndex'>
	<div class="container">
		<div class="table-responsive">
			<table class="table table-hover responsive-table highlight striped">
				<thead>
					<tr>
						<th onclick="ordenar('id')">Id</th>
						<th onclick="ordenar('nombre')">Nombre</th>
						<th onclick="ordenar('direccion')">Direccion</th>
						<th onclick="ordenar('estatus')">Estatus</th>
						<th>Accion</th>
					</tr>
					<tbody>
						<?php foreach ($bodegas as $bodega) {?>
						<tr>
							<td><?php echo $bodega['id']; ?> </td>
							<td><?php echo $bodega['nombre']; ?></td>
							<td><?php echo $bodega['direccion']; ?></td>
							<td>
								<?php if ( $bodega['estatus']==1):?>
									ACTIVO
								<?php  else:?>
									INACTIVO
								<?php endif; ?></td>
							<td>
							<button class="waves-effect waves-light btn modal-trigger"  href="#modal1" onClick="$('.modal').modal();libAjaxGet('bodega/edit/<?php echo $bodega['id'] ?>','divModal')">
								Editar
							</button>
								<a class='waves-effect waves-light btn red' href="bodega/delete/<?php echo $bodega['id'] ?>">Eliminar</a>
							</td>
						</tr>
						<?php } ?>
					</tbody>

				</thead>
			</table>
			<?php
				require_once('Views/Helpers/paginacion.php');
			?>
		</div>
	</div>
</div>