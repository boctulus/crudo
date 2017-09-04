<form role="form" method="post" enctype="multipart/form-data" action="../categorias/process">
    <div class="panel">
        <div class="panel-body">				          
            
            <div class="form-group">
				<label>Nombre de la Categoria</label>
                <input class="form-control" type="text" name="NombreCategoria" />
            </div>			
						
			<p/>
            <div class="form-group">
                <label>Foto</label><p/>
                <input name="imagenes[]" type="file" class="multi" accept="gif|jpg|png" multiple/>
			</div>
			<p/>
			<input type="submit" />
		</div>	
    </div>
	
</form>