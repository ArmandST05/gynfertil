    <?php 
    $categories = CategorySpend::getAllCatSpend();
    
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
	<h1>Agregar concepto</h1>
  <div id="resultado"></div>
	<br>
		<form class="form-horizontal" method="post" enctype="multipart/form-data" autocomplete='off' id="addconceptspend" action="index.php?view=addconceptspend" role="form">
    
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre*</label>
    <div class="col-md-6">
      <input type="text" name="name" class="form-control" id="name" autofocus placeholder="Nombre" required>
    </div>
  </div>
<div style="display:none;" id="insumos" name="insumos">
   <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Categoría gastos</label>
    <div class="col-md-6">
    <select name="cate" class="form-control" required>
    <option value="">-- SELECCIONE --</option>      
    <?php foreach($categories as $cat):?>
    <option value="<?php echo $cat->id; ?>"><?php echo $cat->name; ?></option>      
    <?php endforeach;?>
    </select>
    </div>
  </div>

    <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-primary">Agregar concepto</button>
    </div>
  </div>



</form>
</div>
</div>
  </div>
</div></div>