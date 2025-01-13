<?php
  $con = CategorySpend::getAllExpense();
    //if(count($con)>0){

      foreach($con as $conc){
        $sta=$conc->status;
       
        echo '<tr>
        <td><?php echo $conc->id; ?></td>
        <td ><?php echo number_format($conc->total,2)?></td>
        <td><?php echo $conc->created_at; ?></td>';
        
        }
           ?>

       
<?php


   /*

/*<td><?php  if($conc->fac==1){
         echo '<input  type="checkbox" class="fac" name="fac" id="fac" value="'.$conc->id.'" checked="true"> ';
                 }else{
                  echo '<input type="checkbox" class="fac" name="fac" id="fac" value="'.$conc->id.'">';
                 }
         ?></td>
        <?php 

                 if($sta==1){
                   echo "<td style= background-color:#5FD561;><b>PAGADA</b></td>";
                 }else{
                     echo "<td style= background-color:#FC8383;><b>PENDIENTE</b></td>";
                 }
        
         ?>
        <td style="width:80px;" class="td-actions">
        <a href="index.php?view=buyUpd&id=<?php echo $conc->id;?>" rel="tooltip" title="Editar" class="btn btn-simple btn-warning btn-xs"><i class='fa fa-pencil'></i></a-->
          </tr>*
   }else{
      echo "<p class='alert alert-danger'>No hay gastos</p>";
    }*/


 
                    
?>