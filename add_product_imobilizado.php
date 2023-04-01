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
$codigobarras = generateEAN();
$last_id_product = last_id('products','id');

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
    $p_size  = '11';
    $p_stock_min  = remove_junk($db->escape($_POST['product-stock-min']));
    $p_estado  = remove_junk($db->escape($_POST['sel_estado']));
    $p_observacoes  = remove_junk($db->escape($_POST['observacoes']));
    $p_subcategoria = remove_junk($db->escape($_POST['sel_subcategoria']));

    foreach ($_POST['product_validate'] as $input_p_validate) {
      $p_validate[] = $input_p_validate;
    }
    $p_validate = implode(' ', $p_validate);

    if (!empty($_FILES["file"])) {
      $photo = new Media();
      $photo->upload($_FILES['file']);
      if($photo->process_media()){
        $queryFoto = "SELECT id FROM media order by id desc limit 1;";
        $queryFoto = mysqli_query($conn,$queryFoto);
        $row = mysqli_fetch_all($queryFoto,MYSQLI_ASSOC);
        $media_id = remove_junk($row[0]['id']);
      } 
    } else {
      $media_id = '0';
    }

    $date   = make_date();

    //Inseri o Produto
    $query .= "INSERT INTO products (";
    $query .= " status,name,quantity,stock_min,buy_price,sale_price,categorie_id,media_id,date,validate,size_id,bandeira_id,estado,observacoes,subcategorie";
    $query .= ") VALUES (";
    $query .= " 'A', '{$p_name}', '{$p_qty}', '{$p_stock_min}', '{$p_buy}', '{$p_sale}', '{$p_cat}', '{$media_id}', '{$date}', '{$p_validate}','{$p_size}','{$p_bandeira}','{$p_estado}','{$p_observacoes}','{$p_subcategoria}'";
    $query .= ")";
    $query .= " ON DUPLICATE KEY UPDATE name='{$p_name}';";
    $queryaa = $query;
    mysqli_query($conn, $query);

    //Inseri a Movimentação de Estoque
    $queryMovimentacaoEstoque = "INSERT INTO movimentacaoestoque (tipomovimentacaoestoqueid, produtoid, quantidade, estoqueanterior, estoque, datahora)
                                  VALUES ('1','$last_id','$p_qty','0','$p_qty','$dataAtual')";

    if ($db->query($queryMovimentacaoEstoque)) {
      $session->msg('s', "Produto adicionado com Sucesso. ");
      redirect('add_product_imobilizado.php', false);
    } else {
      $session->msg('d', 'Erro ao adicionar o Produto.');
      redirect('product.php', false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('add_product_imobilizado.php', false);
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
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Cadastrar Produto (ATIVO IMOBILIZADO)</span>
        </strong>
      </div>
      <div class="panel-body">
        <div class="col-md-12">
          <form method="post" enctype="multipart/form-data" action="add_product_imobilizado.php" class="clearfix">
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
                  <select class="form-control selectpicker" id='product-categorie' name="product-categorie">
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
                  <label for='product-bandeira'>Selecione a Loja</label>
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
                  <label for='file'>Selecione ou Capture a Imagem</label>
                  <input type="file" class="form-control" name="file" id="file" accept="image/png, image/gif, image/jpeg, image/jpg"/>
                </div>
                <!-- Select Validade do Produto -->
                <div class="col-md-6">
                  <label for='product_validate'>Selecione a Validade do Produto</label>
                  <br>
                  <select class="selectpicker form-control" id="product_validate" name="product_validate[]" multiple>
                    <option value="" disabled>Selecione a validade do Produto</option>
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
                <div class="col-md-6">
                  <label for="sel_estado">Estado de Uso:</label>
                  <select class="form-control" id="sel_estado" name="sel_estado">
                    <option value="Novo">Novo</option>
                    <option value="Usado">Usado</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label for="observacoes">Observações:</label>
                  <textarea class="form-control" rows="5" id="observacoes" name="observacoes"></textarea>
                </div>
                <div class="col-md-6">
                  <label for="sel_subcategoria">Subcategoria:</label>
                  <select class="form-control" id="sel_subcategoria" name="sel_subcategoria">
                    <option value="Prédios / Imóveis em Geral">Prédios / Imóveis em Geral</option>
                    <option value="Máquinas e equipamentos">Máquinas e equipamentos</option>
                    <option value="Veículos">Veículos</option>
                    <option value="Móveis e utensílios">Móveis e utensílios</option>
                    <option value="Equipamentos de informática">Equipamentos de informática</option>
                    <option value="Eletrônicos">Eletrônicos</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label for="modal_patrimonio" style="margin-top: 20px;">Números de Patrimônios:</label>
                  <br>
                  <button type="button" class="btn btn-info btn_modal_patrimonio" data-toggle="modal" data-target="#modal_patrimonio">N° Patrimônios</button>
                </div>
              </div>
            </div>

            <!-- Modal Patrimonio -->
            <div class="modal" id="modal_patrimonio">
              <div class="modal-dialog">
                <div class="modal-content">

                  <!-- Modal Header -->
                  <div class="modal-header">
                    <h4 class="modal-title">Números de Patrimônios</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>

                  <!-- Modal body -->
                  <div class="modal-body">
                    <input hidden id="last_id" value="<?php echo $last_id_product[0]['id']; ?>">
                    <div class="input_patrimonio">

                    </div>
                  </div>

                  <!-- Modal footer -->
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                  </div>

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
                    <input type="number" class="form-control" name="product-quantity" id="product-quantity" placeholder="Quantidade">
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
            <button type="submit" name="add_product" class="btn btn-primary btn_add_product">Cadastrar Produto</button>
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

</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.17/js/bootstrap-select.min.js" integrity="sha512-/xmIG37mK4F8x9kBvSoZjbkcQ4/y2AbV5wv+lr/xYhdZRjXc32EuRasTpg7yIdt0STl6xyIq+rwb4nbUmrU/1g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="libs/js/functions.js"></script>
<script>
  $(document).ready(function() {
    $('#product-size').find('option[text="NÃO CONSTA"]').val();
    $("#product-size").val("11").change();
    $("#product-size").prop('disabled', true);
    $(".btn_modal_patrimonio").prop('disabled', true);

    $('.btn_modal_patrimonio').on('click', function() {
      var count_quantidade = $('#product-quantity').val();
      var result = '';
      result += '<h5>Preencha os números dos patrimônios</h5>';
      for (var i = 0; i < count_quantidade; i++) {
        result += '<div class="form-group">';
        result += ' <input type="number" class="form-control p_patrimonios" placeholder="Número do Patrimônio">';
        result += '</div>';
      }
      $('.input_patrimonio').html(result).show();
    });

    $('#product-quantity').keypress(function() {
      if ($('#product-quantity').length >= 1) {
        $(".btn_modal_patrimonio").prop('disabled', false);
      }
    });

    $('.btn_add_product').on('click', function() {
      var n_patrimonios = [];
      $('.p_patrimonios').each(function(i) {
        n_patrimonios[i] = $(this).val();
      });

      var last_id = $('#last_id').val();

      $.ajax({
        url: "scripts/patrimonios/add_product.php",
        data: {
          n_patrimonios: n_patrimonios,
          last_id: last_id
        },
        type: 'post',
        success: function(data) {}
      });
      console.log(n_patrimonios);
    });


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
