<?php
  $page_title = 'Medidas';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  
  $all_medidas = find_all('size')
?>
<?php
 if(isset($_POST['add_med'])){
   $req_field = array('medida-name');
   validate_fields($req_field);
   $med_name = remove_junk($db->escape($_POST['medida-name']));
   if(empty($errors)){
      $sql  = "INSERT INTO size (medida)";
      $sql .= " VALUES ('{$med_name}')";
      if($db->query($sql)){
        $session->msg("s", "Medida Adicionada!");
        redirect('medidas.php',false);
      } else {
        $session->msg("d", "Erro ao adicionar.");
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
  </div>
   <div class="row">
    <div class="col-md-5">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Adicionar nova medida</span>
         </strong>
        </div>
        <div class="panel-body">
          <form method="post" action="medidas.php">
            <div class="form-group">
                <input type="text" class="form-control" name="medida-name" placeholder="Nome da Medida">
            </div>
            <button type="submit" name="add_med" class="btn btn-primary">Adicionar medida</button>
        </form>
        </div>
      </div>
    </div>
    <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Todas Medidas</span>
       </strong>
      </div>
        <div class="panel-body">
          <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>Medida</th>
                    <th class="text-center" style="width: 100px;">Ações</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($all_medidas as $med):?>
                <tr>
                    <td class="text-center"><?php echo count_id();?></td>
                    <td><?php echo remove_junk(ucfirst($med['medida'])); ?></td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="edit_medida.php?id=<?php echo (int)$med['id'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Editar">
                          <span class="glyphicon glyphicon-edit"></span>
                        </a>
                        <a href="delete_medida.php?id=<?php echo (int)$med['id'];?>"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remover">
                          <span class="glyphicon glyphicon-trash"></span>
                        </a>
                      </div>
                    </td>

                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
       </div>
    </div>
    </div>
   </div>
  </div>
  <?php include_once('layouts/footer.php'); ?>
