<?php
  $page_title = 'Editar Medida';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
?>
<?php
  //Display all catgories.
  $medida = find_by_id('size',(int)$_GET['id']);
  if(!$medida){
    $session->msg("d","Não foi Encontrada a Medida.");
    redirect('medidas.php');
  }
?>

<?php
if(isset($_POST['edit_med'])){
  $req_field = array('medida-name');
  validate_fields($req_field);
  $med_name = remove_junk($db->escape($_POST['medida-name']));
  if(empty($errors)){
        $sql = "UPDATE size SET medida='{$med_name}'";
       $sql .= " WHERE id='{$medida['id']}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       $session->msg("s", "Sucesso ao Atualizar medida");
       redirect('medidas.php',false);
     } else {
       $session->msg("d", "Erro! Falha ao atualizar");
       redirect('medidas.php',false);
     }
  } else {
    $session->msg("d", $errors);
    redirect('medidas.php',false);
  }
}
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
   </div>
   <div class="col-md-5">
     <div class="panel panel-default">
       <div class="panel-heading">
         <strong>
           <span class="glyphicon glyphicon-th"></span>
           <span>Editar <?php echo remove_junk(ucfirst($medida['medida']));?></span>
        </strong>
       </div>
       <div class="panel-body">
         <form method="post" action="edit_medida.php?id=<?php echo (int)$medida['id'];?>">
           <div class="form-group">
               <input type="text" class="form-control" name="medida-name" value="<?php echo remove_junk(ucfirst($medida['medida']));?>">
           </div>
           <button type="submit" name="edit_med" class="btn btn-danger">Atualizar medida</button>
       </form>
       </div>
     </div>
   </div>
</div>



<?php include_once('layouts/footer.php'); ?>
