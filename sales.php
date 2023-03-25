<?php
$page_title = 'Baixas';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(3);
?>
<?php
$sales = find_all_sale();
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <div class="pull-left">
          <input class="form-control" id="search_sale" type="text" placeholder="Busca..">
        </div>
        <div class="pull-right">
          <a href="add_sale.php" class="btn btn-primary">Baixa de Estoque</a>
        </div>
      </div>
      <div class="panel-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th> Produto/EAN </th>
              <th class="text-center" style="width: 15%;"> Pessoa </th>
              <th class="text-center" style="width: 15%;"> Quantidade</th>
              <th class="text-center" style="width: 15%;"> Valor </th>
              <th class="text-center" style="width: 15%;"> Data de Saída </th>
              <th class="text-center" style="width: 100px;"> Ações </th>
            </tr>
          </thead>
          <tbody id="tdbodySales">
            <?php foreach ($sales as $sale) : ?>
              <tr>
                <td><?php echo remove_junk($sale['name'] . ' - ' . $sale['product_codigobarras']); ?></td>
                <td class="text-center"><button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalSale<?php echo (int)$sale['id']; ?>">Informações</button></td>
                <td class="text-center"><?php echo (int)$sale['qty']; ?></td>
                <td class="text-center"><?php echo remove_junk($sale['price']); ?></td>
                <td class="text-center"><?php echo read_date($sale['date']); ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_sale.php?id=<?php echo (int)$sale['id']; ?>" class="btn btn-warning btn-xs" title="Editar" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_sale.php?id=<?php echo (int)$sale['id']; ?>" class="btn btn-danger btn-xs" title="Deletar" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
                <td>
                  <!-- Modal Sales -->
                  <div class="modal" id="modalSale<?php echo (int)$sale['id']; ?>">
                    <div class="modal-dialog">
                      <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                          <h4 class="modal-title">Informações</h4>
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th> Pessoa </th>
                                <th> Tamanho </th>
                                <th> Bandeira/Loja </th>
                              </tr>
                            </thead>
                            <tr>
                              <td class="text-center"><?php echo remove_junk($sale['nome']); ?></td>
                              <td class="text-center"><?php echo remove_junk($sale['medida']); ?></td>
                              <td class="text-center"><?php echo $sale['bandeira']; ?></td>
                            </tr>
                          </table>  
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
$(document).ready(function(){
  $("#search_sale").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#tdbodySales tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
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