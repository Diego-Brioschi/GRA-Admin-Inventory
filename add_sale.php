<?php
  $page_title = 'Adicionar Saída';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<?php
  $dataAtual = date('Y-m-d H:i:s');
  if(isset($_POST['add_sale'])){
    //Search Description  Product

    $req_fields = array('s_id','quantity','price','total','date','product-colaborador');
    validate_fields($req_fields);
        if(empty($errors)){
          $p_id               = $db->escape((int)$_POST['s_id']);
          $p_ean              = $db->escape($_POST['s_ean']);
          $s_qty              = $db->escape((int)$_POST['quantity']);
          $s_total            = $db->escape($_POST['total']);
          $date               = $db->escape($_POST['date']);
          $s_date             = make_date();
          $product_size       = $db->escape($_POST['product-size']);
          $product_bandeira   = $db->escape($_POST['product-bandeira']);
          $product_user       = $db->escape($_POST['product-colaborador']);
         
          $sql = "DELETE FROM product_codigobarras WHERE codigobarras = '$p_ean'";
          $db->query($sql);

          $product_stock_atual = find_stock_product($p_id);
          $product_stock_atual = $product_stock_atual[0]['quantity'];
          $prduct_stock_new =  $product_stock_atual - $s_qty;
          //Inseri a Movimentação de Estoque
          $queryMovimentacaoEstoque = "INSERT INTO movimentacaoestoque (tipomovimentacaoestoqueid, produtoid, quantidade, estoqueanterior, estoque, datahora)
                                       VALUES ('2','$p_id','$s_qty','$product_stock_atual','$prduct_stock_new','$dataAtual')";
          $db->query($queryMovimentacaoEstoque);
  
          $sql  = "INSERT INTO sales (";
          $sql .= " product_id,product_codigobarras,qty,price,date,product_size,product_bandeira,product_user";
          $sql .= ") VALUES (";
          $sql .= "'{$p_id}','{$p_ean}','{$s_qty}','{$s_total}','{$s_date}','{$product_size}','{$product_bandeira}','{$product_user}'";
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
            <th> Total </th>
            <th> Data Entrega</th>
            <th> Ações</th>
           </thead>
              <tbody  id="product_info"> </tbody>
         </table>
       </form>
      </div>
    </div>
  </div>

<!-- END ROW -->
</div>

</div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.17/js/bootstrap-select.min.js" integrity="sha512-/xmIG37mK4F8x9kBvSoZjbkcQ4/y2AbV5wv+lr/xYhdZRjXc32EuRasTpg7yIdt0STl6xyIq+rwb4nbUmrU/1g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="libs/js/functions.js"></script>

<script>
$(window).on('load', (e) => { 
   $("#sug_input").focus();  
});
</script>

<script>  
    $(function () {
      $('select').selectpicker();
  });
</script>

<script>
  $(document).on('click', '.btn_toogle_menu', function() {
    $('#mySidebar').toggle();
    $(".page").removeClass("page");
    $('.container-fluid').prepend('<button class="btn btn-primary btn-lg btn_right_toogle" style="margin-bottom:10px;"><span class="glyphicon glyphicon-circle-arrow-right"></span></button>');
    $(".container-fluid").addClass("container_fluid_menu");
  });
  $(document).on('click', '.btn_right_toogle', function() {
    $('#mySidebar').toggle();
    $("#page").addClass("page");
    $('.btn_right_toogle').hide();
    $(".container-fluid").removeClass("container_fluid_menu");
  });
</script>

</body>

</html>
