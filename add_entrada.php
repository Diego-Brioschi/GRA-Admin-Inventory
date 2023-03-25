<?php
  $page_title = 'Adicionar Entrada';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
   $all__sizes = find_size_by_product('3');
   $all__sexos = find_sexo_by_product('0');
?>
<?php

  if(isset($_POST['add_sale'])){
    $req_fields = array('s_id','quantity','price','total', 'date', 'product-size' );
    validate_fields($req_fields);
        if(empty($errors)){
          $p_id               = $db->escape((int)$_POST['s_id']);
          $s_qty              = $db->escape((int)$_POST['quantity']);
          $s_total            = $db->escape($_POST['total']);
          $date               = $db->escape($_POST['date']);
          $s_date             = make_date();
          $product_size       = $db->escape($_POST['product-size']);
          $product_bandeira   = $db->escape($_POST['product-bandeira']);
          $product_sex        = $db->escape($_POST['product-sex']);
          $product_user       = $db->escape($_POST['product-colaborador']);


          $sql  = "INSERT INTO sales (";
          $sql .= " product_id,qty,price,date,product_size,product_bandeira,product_sex,product_user";
          $sql .= ") VALUES (";
          $sql .= "'{$p_id}','{$s_qty}','{$s_total}','{$s_date}','{$product_size}','{$product_bandeira}','{$product_sex}','{$product_user}'";
          $sql .= ")";

                if($db->query($sql)){
                  update_product_qty($s_qty,$p_id);
                  $session->msg('s',"Baixa confirmada. ");
                  redirect('add_sale.php', false);
                } else {
                  $session->msg('d',' Erro ao inserir!');
                  redirect('add_sale.php', false);
                }
        } else {
           $session->msg("d", $errors);
           redirect('add_sale.php',false);
        }
  }

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
    <form method="post" action="ajax.php" autocomplete="off" id="sug-form">
        <div class="form-group">
          <div class="input-group">
            <span class="input-group-btn">
              <button type="submit" class="btn btn-primary">Procurar</button>
            </span>
            <input type="text" id="sug_input" class="form-control" name="title"  placeholder="Digite o nome do Produto">
         </div>
         <div id="result" class="list-group"></div>
        </div>
    </form>
  </div>
</div>
<div class="row">

  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Saídas</span>
       </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="add_sale.php">
         <table class="table table-bordered">
           <thead>
            <th> Produto </th>
            <th> Preço </th>
            <th> Quantidade </th>
            <th> Estoque </th>
            <th> Tipo Produto </th>
            <th> Data Entrega</th>
            <th> Ações</th>
           </thead>
             <tbody  id="product_info"> </tbody>
         </table>
           <!-- Modal Product -->
          <div class="modal" id="modalProduct">
            <div class="modal-dialog">
              <div class="modal-content">
              
                <!-- Modal Header -->
                <div class="modal-header">
                  <h4 class="modal-title">Características do Produto</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <!-- Modal body -->
                <div class="modal-body">
                  <div class="col-md-6">
                    <label for='product-size'>Selecione o Tamanho</label>
                    <select class="form-control selectpicker" id='product-size' name="product-size" aria-label=".form-select-lg example" data-live-search="true">
                      <option value="">Select o Tamanho do Produto</option>
                      <?php  foreach ($all__sizes as $size): ?>
                      <option value="<?php echo $size['id']; ?>"><?php echo $size['medida']; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label for='product-bandeira'>Selecione a Bandeira</label>
                    <select class="form-control selectpicker" aria-label=".form-select-lg example" data-live-search="true" id='product-bandeira' name="product-bandeira">
                      <option value="">Selecione a Bandeira do Produto</option>
                      <option value="1"> FARMAIS</option>
                      <option value="2"> BIG FARMA</option>
                      <option value="3">ULTRA POPULAR</option>
                      <option value="4">GRUPO REIS ALVES</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label for='product-colaborador'>Selecione o Colaborador</label>
                    <select class="form-control selectpicker" aria-label=".form-select-lg example" data-live-search="true" id='product-colaborador' name="product-colaborador">
                      <option value="">Selecione a Bandeira do Produto</option>
                      <option value="2515"> Diego</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label for='product-sex'>Selecione o Sexo do Produto</label>
                    <select class="form-control selectpicker" id='product-sex' name="product-sex" aria-label=".form-select-lg example" data-live-search="true">
                      <option value="">Select o Sexo do Produto</option>
                      <?php  foreach ($all__sexos as $sexo): ?>
                      <option value="<?php echo $sexo['id']; ?>"><?php echo $sexo['sexo']; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                
                <!-- Modal footer -->
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                </div>
                
              </div>
            </div>
          </div>
          <!-- END MODAL -->
       </form>
      </div>
    </div>
  </div>

<!-- END ROW -->
</div>

<script>  
    $(function () {
      $('select').selectpicker();
  });
</script>

<?php include_once('layouts/footer.php'); ?>
