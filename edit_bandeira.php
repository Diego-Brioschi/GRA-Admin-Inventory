<?php
  $page_title = 'Editar Bandeira';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
?>
<?php
  //Display all catgories.
  $bandeira = find_by_id('bandeira',(int)$_GET['id']);
  if(!$bandeira){
    $session->msg("d","Não foi encontrado a Bandeira.");
    redirect('bandeira.php');
  }
?>

<?php
if(isset($_POST['edit_ban'])){
  $req_field = array('bandeira-name');
  validate_fields($req_field);
  $ban_name = remove_junk($db->escape($_POST['bandeira-name']));
  if(empty($errors)){
        $sql = "UPDATE bandeira SET nome='{$ban_name}'";
       $sql .= " WHERE id='{$bandeira['id']}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       $session->msg("s", "Sucesso ao atualizar Bandeira !");
       redirect('bandeira.php',false);
     } else {
       $session->msg("d", "Erro! Falhar ao Atualizar !");
       redirect('bandeira.php',false);
     }
  } else {
    $session->msg("d", $errors);
    redirect('bandeira.php',false);
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
           <span>Editar <?php echo remove_junk(ucfirst($bandeira['nome']));?></span>
        </strong>
       </div>
       <div class="panel-body">
         <form method="post" action="edit_bandeira.php?id=<?php echo (int)$bandeira['id'];?>">
           <div class="form-group">
               <input type="text" class="form-control" name="bandeira-name" value="<?php echo remove_junk(ucfirst($bandeira['nome']));?>">
           </div>
           <button type="submit" name="edit_ban" class="btn btn-danger">Atualizar bandeira</button>
       </form>
       </div>
     </div>
   </div>
</div>



<?php include_once('layouts/footer.php'); ?>
