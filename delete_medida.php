<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
?>
<?php
  $medida = find_by_id('size',(int)$_GET['id']);
  if(!$medida){
    $session->msg("d","Não foi encontrado a Medida.");
    redirect('medidas.php');
  }
?>
<?php
  $medida_id = delete_by_id('size',(int)$medida['id']);
  if($medida_id){
      $session->msg("s","Medida deletada.");
      redirect('medidas.php');
  } else {
      $session->msg("d","Erro ao deletar Medida.");
      redirect('medidas.php');
  }
?>
