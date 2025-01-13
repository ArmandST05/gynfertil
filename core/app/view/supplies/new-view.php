    <?php 
$categories = CategoryData::getAll();
    ?>
    <script type="text/javascript">
    /*****NAME*******/
    $(document).ready(function(){
                        
      var consulta      
      //hacemos focus
      $("#name").focus();                                         
      //comprobamos si se pulsa una tecla
      $("#name").keyup(function(e){
             //obtenemos el texto introducido en el campo
             consulta = $("#name").val();                        
             //hace la búsqueda
             $("#resultado").delay(1000).queue(function(n) {      
                                    
                  $("#resultado").html();
                                           
                        $.ajax({
                              type: "POST",
                              url: "./?action=comprobarProN",
                              data: "na="+consulta,
                              dataType: "html",
                              error: function(){
                                    alert("error petición ajax");
                              },
                              success: function(data){                                                      
                                    
                                    n();
                                    var r=data;
                                    $("#resultado").html(data);
                                    if(r==""){
                                       document.getElementById("insumos").style.display='block';
                                    }else{
                                       document.getElementById("insumos").style.display='none';
                                    }
                                    
                              }
                  });
                                           
             });
                                
      });
                          
});
</script>

<div class="row">
	<div class="col-md-12">
	<h1>Nuevo insumo</h1>
    <div id="resultado"></div>
	<br>
		<form class="form-horizontal" method="post" enctype="multipart/form-data" id="addsupplies" action="index.php?view=addsupplies" role="form" autocomplete="off">

 
  
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre*</label>
    <div class="col-md-6">
      <input type="text" name="name" required class="form-control" id="name" placeholder="Nombre insumo" autofocus>
    </div>
  </div>
   <div style="display:none;" id="insumos" name="insumos">
    <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Minima en inventario:</label>
    <div class="col-md-6">
      <input type="text" name="inventary_min" class="form-control" id="inputEmail1" placeholder="">
    </div>
  </div>
   <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Inventario inicial:</label>
    <div class="col-md-6">
      <input type="text" name="q" class="form-control" id="inputEmail1" placeholder="Inventario inicial">
    </div>
  </div>


  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-primary">Agregar insumo</button>
    </div>
  </div>
</form>

	</div>
</div>
</div>
