<?php
$page_title = 'Entrada de Produto';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(2);
$products = join_product_table('A','c.id');
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
          <input class="form-control" id="p_search" type="text" placeholder="Busca..">
        </div>
        <div class="pull-right">
        </div>
      </div>
      <div class="panel-body">
        <table class="table table-condensed table-bordered ">
          <thead>
            <tr>
              <th hidden> ID </th>
              <th class="text-center"> Produto (Nome,Tamanho,Loja)</th>
              <th class="text-center"> Estoque </th>
              <th class="text-center"> Preço de Compra </th>
              <th class="text-center"> Preço de Venda </th>
              <th class="text-center"> Opções</th>
            </tr>
          </thead>
          <tbody id="tbodyEntrada"></tbody>
          </tabel>
      </div>
    </div>
  </div>
</div>

<!-- Modal Entrada do Produto -->
<div class="modal" id="modalEntradaProduto">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Entrada do Produto</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="p_product">Produto: (Nome,Tamanho,Loja)</label>
            <input type="text" class="form-control" id="p_product" disabled>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="p_stock">Estoque:</label>
              <input type="text" class="form-control" id="p_stock" disabled>
            </div>
            <div class="form-group col-md-6">
              <label for="p_entrada">Qtd. Entrada:</label>
              <input type="number" class="form-control" id="p_entrada" required>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="p_compra">Valor Compra:</label>
              <input type="number" class="form-control" id="p_compra">
            </div>
            <div class="form-group col-md-6">
              <label for="p_venda">Valor Venda:</label>
              <input type="number" class="form-control" id="p_venda">
            </div>
          </div>
          <div class="form-group">
            <label for="p_data">Data:</label>
            <input type="text" class="form-control" id="p_data" disabled>
          </div>
          <button class="btn btn-success" id="btnConfirmar">Confirmar</button>
        </form>

      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
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
  $("#p_search").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#tbodyEntrada tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>

<script>
  $(document).ready(function() {
    populateEntrada();
    function populateEntrada() {
      $.ajax({
        url: "scripts/entrada/fetch_data_entrada.php",
        data: {

        },
        type: 'post',
        success: function(data) {
          var json = JSON.parse(data);

          var td = '';
          json.data.forEach(function(e, index) {

            td += '<tr>';
            td += '   <td>' + e[0] + '</td>';
            td += '   <td>' + e[1] + ' - ' + e[5] + ' - ' + e[6] + '</td>';
            td += '   <td class="text-center">' + e[2] + '</td>';
            td += '   <td class="text-center">' + e[3] + '</td>';
            td += '   <td class="text-center">' + e[4] + '</td>';
            td += '   <td><button class="btn btn-primary btnEntrada">Entrada</button></td>';
            td += '</tr>';

          });
          $('#tbodyEntrada').html(td).show();
          $(document).ready(function() {
              $('tr td:nth-child(1)').hide();
          });
        }
      });
    }

    $(document).on('click', '.btnEntrada', function() {
      var p_id = $(this).closest('td').parent()[0].sectionRowIndex;
      var currentRow = $(this).closest("tr");
      var id = currentRow.find("td:eq(0)").text();
      var product = currentRow.find("td:eq(1)").text();
      var stock = currentRow.find("td:eq(2)").text();
      var compra = currentRow.find("td:eq(3)").text();
      var venda = currentRow.find("td:eq(4)").text();
      $('#modalEntradaProduto').modal('show');
      $('#p_product').val(product);
      $('#p_stock').val(stock);
      $('#p_data').val('<?php echo date('d/m/Y H:i'); ?>');
      $('#p_compra').val(compra);
      $('#p_venda').val(venda);

      $('#btnConfirmar').on('click', function(e) {
        e.preventDefault();
        compra = $('#p_compra').val();
        venda = $('#p_venda').val();
        var entrada = $('#p_entrada').val();
        $.ajax({
          url: "scripts/entrada/add_entrada.php",
          data: {
            id: id,
            stock: stock,
            compra: compra,
            venda: venda,
            entrada: entrada
          },
          type: 'post',
          success: function(data) {
            var json = JSON.parse(data);
            var status = json.status;
            if (status == 'true') {
              //$('#modalEntradaProduto').modal('hide');
              //populateEntrada();
              window.location.reload();
            } else {
              alert('Ocorreu uma Falha Tente Novamente !');
            }
          }
        });
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

</body>

</html>