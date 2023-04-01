<?php
$conn = mysqli_connect();
$page_title = 'Adicionar Produto';
require_once('includes/load.php');
$dataAtual = date('Y-m-d H:i:s');
// Checkin What level user has permission to view this page
page_require_level(2);
$all_categories = find_all('categories');
$all_photo = find_all('media');
$all__sizes = find_all('size');
$all__bandeiras = find_all('bandeira');


?>
<?php
if (isset($_POST['add_product'])) {
  $req_fields = array('product-title', 'product-categorie', 'product-quantity', 'buying-price', 'saleing-price');
  validate_fields($req_fields);
  if (empty($errors)) {
    $p_name  = strtoupper(remove_junk($db->escape($_POST['product-title'])));
    $p_cat   = remove_junk($db->escape($_POST['product-categorie']));
    $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
    $p_buy   = remove_junk($db->escape($_POST['buying-price']));
    $p_sale  = remove_junk($db->escape($_POST['saleing-price']));
    $p_bandeira  = remove_junk($db->escape($_POST['product-bandeira']));
    $p_size  = remove_junk($db->escape($_POST['product-size']));
    $p_stock_min  = remove_junk($db->escape($_POST['product-stock-min']));
    
    

    foreach ($_POST['product_validate'] as $input_p_validate) {
      $p_validate[] = $input_p_validate;
    }
    $p_validate = implode(' ', $p_validate);

    if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
      $media_id = '0';
    } else {
      $media_id = remove_junk($db->escape($_POST['product-photo']));
    }
    $date   = make_date();

    //Inseri o Produto
    $query .= "INSERT INTO products (";
    $query .= " status,name,quantity,stock_min,buy_price,sale_price,categorie_id,media_id,date,validate,size_id,bandeira_id";
    $query .= ") VALUES (";
    $query .= " 'A', '{$p_name}', '{$p_qty}', '{$p_stock_min}', '{$p_buy}', '{$p_sale}', '{$p_cat}', '{$media_id}', '{$date}', '{$p_validate}','{$p_size}','{$p_bandeira}'";
    $query .= ")";
    $query .= " ON DUPLICATE KEY UPDATE name='{$p_name}';";
    mysqli_query($conn, $query);

    //Pega o Ultimo ID Inserido
    $last_id = mysqli_insert_id($conn);

    for($i=0; $i<$p_qty; $i++){
      $codigobarras = testarcode(randCode(8));
      $queryCodigoBarras = "INSERT INTO product_codigobarras (product_id, codigobarras, impresso) VALUES ('$last_id', '$codigobarras','N')";
      $db->query($queryCodigoBarras);
    }

    //Inseri a Movimentação de Estoque
    $queryMovimentacaoEstoque = "INSERT INTO movimentacaoestoque (tipomovimentacaoestoqueid, produtoid, quantidade, estoqueanterior, estoque, datahora)
                                  VALUES ('1','$last_id','$p_qty','0','$p_qty','$dataAtual')";

    if ($db->query($queryMovimentacaoEstoque)) {
      $session->msg('s', "Produto adicionado com Sucesso. ");
      redirect('add_product.php', false);
    } else {
      $session->msg('d', 'Erro ao adicionar o Produto.');
      redirect('product.php', false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('add_product.php', false);
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
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="row">
          <div class="col-sm-8">
            <strong>
              <span class="glyphicon glyphicon-th"></span>
              <span>Cadastrar Produto</span>
            </strong>
          </div>
          <div class="col-sm-1">
            <a href="add_product_imobilizado.php" class="btn btn-info">Cadastrar Produtos Imobilizados</a>
          </div>
        </div>
      </div>
      <div class="panel-body">
        <div class="col-md-12">
          <form method="post" action="add_product.php" class="clearfix">
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-th-large"></i>
                </span>
                <input type="text" class="form-control" name="product-title" placeholder="Nome do produto">
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <!-- Select Categoria do Produto -->
                <div class="col-md-6">
                  <label for='product-categorie' selected disabled>Selecione a Categoria</label>
                  <select class="form-control selectpicker" id='product-categorie' name="product-categorie" >
                    <option value="" selected disabled>Selecione a Categoria do Produto</option>
                    <?php foreach ($all_categories as $cat) : ?>
                      <option value="<?php echo (int)$cat['id'] ?>">
                        <?php echo $cat['name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <!-- Select Tamanho do Produto -->
                <div class="col-md-6 mb-3">
                  <label for='product-size'>Selecione o Tamanho</label>
                  <select class="form-control selectpicker" aria-label=".form-select-lg example" data-live-search="true" id='product-size' name="product-size">
                  <option value="" selected disabled>Selecione o Tamanho do Produto</option>
                    <?php foreach ($all__sizes as $size) : ?>
                      <option value="<?php echo (int)$size['id'] ?>">
                        <?php echo $size['medida'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <!-- Select Bandeira do Produto -->
                <div class="col-md-6">
                  <label for='product-bandeira'>Selecione a Bandeira</label>
                  <select class="form-control selectpicker" aria-label=".form-select-lg example" data-live-search="true" id='product-bandeira' name="product-bandeira">
                    <option value="" selected disabled>Selecione a Bandeira do Produto</option>
                    <?php foreach ($all__bandeiras as $bandeiras) : ?>
                      <option value="<?php echo (int)$bandeiras['id'] ?>">
                        <?php echo $bandeiras['nome'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <!-- Select Imagem do Produto -->
                <div class="col-md-6">
                  <label for='product-photo'>Selecione a Imagem</label>
                  <select class="form-control" id='product-photo' name="product-photo">
                    <option value="">Selecione a imagem do Produto</option>
                    <?php foreach ($all_photo as $photo) : ?>
                      <option value="<?php echo (int)$photo['id'] ?>">
                        <?php echo $photo['file_name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <!-- Select Validade do Produto -->
                <div class="col-md-6">
                  <label for='product_validate'>Selecione a Validade do Produto</label>
                  <br>
                  <select class="selectpicker" id="product_validate" name="product_validate[]" multiple>
                    <option value="" disabled selected>Selecione a validade do Produto</option>
                    <optgroup label="Quantidade" data-max-options="1">
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                    </optgroup>
                    <optgroup label="Periodo" data-max-options="1">
                      <option value="month">Meses</option>
                      <option value="year">Anos</option>
                    </optgroup>
                  </select>
                </div>
              </div>
            </div>

            <hr>

            <div class="form-group">
              <div class="row">
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                    </span>
                    <input type="number" class="form-control" name="product-quantity" placeholder="Quantidade">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-plus-sign"></i>
                    </span>
                    <input type="number" class="form-control" name="product-stock-min" placeholder="Estoque Minimo">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-usd"></i>
                    </span>
                    <input type="number" class="form-control" name="buying-price" placeholder="Preço de Compra">
                    <span class="input-group-addon">.00</span>
                  </div>
                </div>
                <br><br><br>
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-usd"></i>
                    </span>
                    <input type="number" class="form-control" name="saleing-price" placeholder="Preço de Venda">
                    <span class="input-group-addon">.00</span>
                  </div>
                </div>
              </div>
            </div>
            <button type="submit" name="add_product" class="btn btn-primary">Cadastrar Produto</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(function() {
    $('select').selectpicker();
  });
</script>

<?php include_once('layouts/footer.php'); ?>
