<?php
$page_title = 'Estoque de Produtos';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(2);

if (isset($_POST['sel_status'])) {
  $status = $_POST['sel_status'];
} else {
  $status = 'A';
}
if (isset($_POST['sel_categoria'])) {
  if ($_POST['sel_categoria'] == 'all') {
    $categoria = 'c.id';
  } else {
    $categoria = "'" . $_POST['sel_categoria'] . "'";
  }
} else {
  $categoria = 'c.id';
}
$products = join_product_table($status, $categoria);
$all_categories = find_all('categories');
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <div class="pull-left">
          <input class="form-control" id="myInput" type="text" placeholder="Busca..">
        </div>
        <form method="POST">
          <div class="pull-left" style="margin-left: 20px;">
            <select class="form-control" id="sel_status" name="sel_status">
              <option value="A">ATIVO</option>
              <option value="I">INATIVO</option>
            </select>
          </div>
          <div class="pull-left" style="margin-left: 20px;">
            <select class="form-control" id="sel_categoria" name="sel_categoria">
              <option value="all" selected>Todos</option>
              <?php foreach ($all_categories as $cat) : ?>
                <option value="<?php echo (int)$cat['id']; ?>"><?php echo remove_junk(ucfirst($cat['name'])); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="pull-left" style="margin-left: 10px;">
            <button type="submit" class="btn btn-success">Filtro</button>
          </div>
        </form>
        <div class="pull-right">
          <a href="add_product.php" class="btn btn-primary">Adicionar Produto</a>
        </div>
      </div>
      <div class="panel-body">
        <table class="table table-condensed table-bordered ">
          <thead>
            <tr>
              <th> Produto </th>
              <th class="text-center" style="width: 10%;"> Medida </th>
              <th class="text-center" style="width: 10%;"> Categoria </th>
              <th class="text-center" style="width: 10%;"> Bandeira </th>
              <th class="text-center" style="width: 10%;"> Estoque </th>
              <th class="text-center" style="width: 10%;"> Preço de Compra </th>
              <th class="text-center" style="width: 10%;"> Código de Barras </th>
              <th class="text-center" style="width: 100px;"> Ações </th>
            </tr>
          </thead>
          <tbody id="tdbodyProducts">
            <?php foreach ($products as $product) : ?>
              <tr>
                <td><?php if($product['image'] != ''){?><img src="uploads/products/<?php echo remove_junk($product['image']); ?>" class="img-rounded" width="50" height="50"><?php } ?> <?php echo remove_junk($product['name']);?> <span class="badge"><?php if($product['name']!=null){echo $product['estado'];} ?></span></td>
                <td class="text-center"> <?php echo remove_junk($product['medida']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['categorie']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['bandeira']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['quantity']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['buy_price']); ?></td>
                <td class="text-center"> <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalCodigobarras<?php echo (int)$product['id']; ?>">Conferir</button></td>
                <td class="text-center">
                  <div class="btn-group">
                    <?php if(remove_junk($product['observacoes']) != null){ ?>
                    <button class="btn btn-warning btn-xs" title="Observações" data-toggle="modal" data-target="#modalObservacoes<?php echo (int)$product['id']; ?>">
                      <span class="glyphicon glyphicon-asterisk"></span>
                    </button>
                    <?php } ?>
                    <a href="edit_product.php?id=<?php echo (int)$product['id']; ?>" class="btn btn-info btn-xs" title="Editar" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_product.php?id=<?php echo (int)$product['id']; ?>" class="btn btn-danger btn-xs" title="Deletar" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
                <td>
                  <!-- Modal Codigo Barras -->
                  <div class="modal" id="modalCodigobarras<?php echo (int)$product['id']; ?>">
                    <div class="modal-dialog">
                      <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                          <h4 class="modal-title">Listagem Código de Barras</h4>
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th>Código de Barras</th>
                                <th class="text-center">Etiqueta</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php 
                              $codigobarra = explode(",", $product['codigobarras']);
                              $cod_impresso = explode(",", $product['codigo_impresso']);
                              $index = 0;
                              foreach ($codigobarra as $codigobarras) : ?>
                                <tr>
                                  <td><?php echo $codigobarras; ?></td>
                                  <?php if($cod_impresso[$index]=='N'){$text='NÃO IMPRESSO';$color='danger';}else{$text='IMPRESSO';$color='success';} ?>
                                  <td class="text-center"><span class="badge alert-<?php echo $color;?>"><?php echo $text;?></span></td>
                                </tr>
                              <?php $index++; ?>
                              <?php endforeach; ?>
                            </tbody>
                          </table>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                        </div>

                      </div>
                    </div>
                  </div>

                  <!-- Modal Observações -->
                  <div class="modal" id="modalObservacoes<?php echo (int)$product['id']; ?>">
                    <div class="modal-dialog">
                      <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                          <h4 class="modal-title">Observações do Produto</h4>
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                          <textarea class="form-control" rows="5" disabled><?php echo remove_junk($product['observacoes']); ?></textarea>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                        </div>

                      </div>
                    </div>
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
</div>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.17/js/bootstrap-select.min.js" integrity="sha512-/xmIG37mK4F8x9kBvSoZjbkcQ4/y2AbV5wv+lr/xYhdZRjXc32EuRasTpg7yIdt0STl6xyIq+rwb4nbUmrU/1g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="libs/js/functions.js"></script>

<script>
  $(document).ready(function() {
    $("#myInput").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $("#tdbodyProducts tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
  });

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