<script src="assets/jquery.btechco.excelexport.js"></script>
<script src="assets/jquery.base64.js"></script>

<?php

   $pro=ProductData::getInventoryProducts();
   	

 ?>
<div class="row">
<div class="col-md-3">
 <input type="submit" class="btn btn-primary btn-block" value="Exportar" id="btnExport">

</div>
	<div class="col-md-12">
<!--div class="btn-group  pull-right">
	<a href="index.php?view=re" class="btn btn-default">Entradas de Medicamentos e insumos</a>
</div-->

							<h1>Inventario Medicamento</h1>
							<div class="clearfix"></div>
								<div class="col-md-8">
								</div>
							
							<hr>
                                    <table   id='datosexcel' border='1' class="table table-bordered table-hover">  
	                                   <thead bgcolor="#eeeeee" align="center">
                                        <tr>
                                       <th>ID</th>
                                       <th>Nombre</th>
                                       <th>Disponible</th>
                                       <!--th>Mínimo</th-->
                                       <th>Tipo</th>
                                       <th>Fecha caducidad</th>
                                       <th></th>
                                       </tr>
                                      </thead>
                                      	<?php
             foreach($pro as $p){
                  

		       $q=OperationData::getStockByProduct($p->id);
		        $qF=OperationData::getAllExpirationDatesByProduct($p->id);
		        $sal=OperationData::getTotalSalesByProduct($p->id);
		        

				?>
				<tr>
				<td><?php echo $p->id?></td>
				<td><?php echo $p->name?></td>
				<td><?php echo $q?></td>
				<!--td><?php echo $p->inventary_min?></td-->
				<td><?php echo $p->type?></td>
				
				<td>
				<?php 
               $sumq=0; $sumac=0;$conta=0;$conta2=0;$chido=0;$res=0; $can=0;
				foreach ($qF as $key) {
					 $dateNow = date('Ym');
					
					 $month = ["1" => "Enero","2" => "Febrero","3" => "Marzo","4" => "Abril","5" => "Mayo","6" => "Junio","7" => "Julio","8" => "Agosto","9" => "Septiembre","10" => "Octubre","11" => "Noviembre","12" => "Diciembre"];
	


                $sumq=$sumq+$key->q;//45
					$conta=$conta+$key->q; 

             	   // if($key->exp <= $dateNow){
                   

					//echo "<span class='label label-danger'><b>".$can." </b> ".$key->dateExpiry."<br></span>";

				//} else{
                 if($sumq <= $key->q){
                 	$can=$key->q-$sal->q;
                 	

                    if($can>=0){
                      if($key->con >= $dateNow)	
			    	  echo "<b> ".$can." </b> ".$key->exp."-".$month[$key->mes]."<br>";
                      else
                      echo "<span class='label label-danger'><b> ".$can." </b> ".$key->exp."-".$month[$key->mes]."<br>";
			        }else{
			        //echo "<b>1.1: ".$can." </b> ".$key->dateExpiry."<br>";
			        	$res=$res+$key->q;
			        	$r=$res-$sal->q;

			        } 
			      }
			       else if($sumq >= $key->q){
                    $conta2=$conta2+$key->q;
                    $sumac=$sumac+$key->q;
                    $canT=$sumq-$sal->q;
                    $can2=$sumac-$sal->q;//44
                   
                    // if($can2>=0){
                      
                     $tot=$sumq-$sumac;
                     if($canT <= $key->q){
                     	if($canT>=0){

                     	if($key->con >= $dateNow)	
			    	       echo "<b> ".$canT." </b> ".$key->exp."-".$month[$key->mes]."<br>";
                           else
                           echo "<span class='label label-danger'><b> ".$canT." </b> ".$key->exp."-".$month[$key->mes]."<br>";
                          //echo "<b> ".$canT." </b> ".$key->dateExpiry."<br>";
                      }
                       }else{
                       if($key->con >= $dateNow)	
			    	       echo "<b> ".$key->q." </b> ".$key->exp."-".$month[$key->mes]."<br>";
                           else
                           echo "<span class='label label-danger'><b> ".$key->q." </b> ".$key->exp."-".$month[$key->mes]."<br>";
                      //echo "<b> ".$key->q." </b> ".$key->dateExpiry."<br>";
                     }
                      
                     /*}
                     else{
                      $chido=$sumac +($r);

                     if($canT>=0){

                     echo "<b>4: ".$canT." </b> ".$key->dateExpiry."<br>";
                     }
                     


                    }*/
                     
                 
			      }
               
             
			     
		     	//}
			}?>
				</td>
		
				</tr>
				<?php
?>

<?php
			



		
		}


		?>
</table>
</div>
</div>
	</div>
</div>
<script type="text/javascript">
  $(document).ready(function () {

      $("#btnExport").click(function (e) {

          $("#datosexcel").btechco_excelexport({
                  containerid: "datosexcel"
                 , datatype: $datatype.Table
                 , filename: 'Reporte inventario'
          });

      });

  });
</script>