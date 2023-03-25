<?php
  $page_title = 'Bandeiras';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  
  $all_bandeiras = find_all('bandeira')
?>
<?php
 if(isset($_POST['add_ban'])){
   $req_field = array('bandeira-name');
   validate_fields($req_field);
   $ban_name = remove_junk($db->escape($_POST['bandeira-name']));
   if(empty($errors)){
      $sql  = "INSERT INTO bandeira (nome)";
      $sql .= " VALUES ('{$ban_name}')";
      if($db->query($sql)){
        $session->msg("s", "Bandeira Adicionada!");
        redirect('bandeira.php',false);
      } else {
        $session->msg("d", "Erro ao adicionar.");
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
  </div>
   <div class="row">
    <div class="col-md-5">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Adicionar nova categoria</span>
         </strong>
        </div>
        <div class="panel-body">
          <form method="post" action="bandeira.php">
            <div class="form-group">
                <input type="text" class="form-control" name="bandeira-name" placeholder="Nome da Bandeira">
            </div>
            <button type="submit" name="add_ban" class="btn btn-primary">Adicionar bandeira</button>
        </form>
        </div>
      </div>
    </div>
    <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Todas Bandeiras</span>
       </strong>
      </div>
        <div class="panel-body">
          <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>Bandeira</th>
                    <th class="text-center" style="width: 100px;">Ações</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($all_bandeiras as $ban):?>
                <tr>
                    <td class="text-center"><?php echo count_id();?></td>
                    <td><?php echo remove_junk(ucfirst($ban['nome'])); ?></td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="edit_bandeira.php?id=<?php echo (int)$ban['id'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Editar">
                          <span class="glyphicon glyphicon-edit"></span>
                        </a>
                        <a href="delete_bandeira.php?id=<?php echo (int)$ban['id'];?>"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remover">
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
