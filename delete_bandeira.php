<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
?>
<?php
  $categorie = find_by_id('bandeira',(int)$_GET['id']);
  if(!$categorie){
    $session->msg("d","Não foi encontrado a Bandeira.");
    redirect('bandeira.php');
  }
?>
<?php
  $delete_id = delete_by_id('bandeira',(int)$categorie['id']);
  if($delete_id){
      $session->msg("s","Bandeira deletada.");
      redirect('bandeira.php');
  } else {
      $session->msg("d","Erro ao deletar Bandeira.");
      redirect('bandeira.php');
  }
?>
