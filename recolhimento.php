<?php
$page_title = 'Recolhimento de Produto';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(2);

?>
<?php include_once('layouts/header.php'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" integrity="sha512-xmGTNt20S0t62wHLmQec2DauG9T+owP9e6VU8GigI0anN7OXLip9i7IwEhelasml2osdxX71XcYm6BQunTQeQg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Recolhimento de SOLICITAÇÃO</span>
        </strong>
      </div>
      <div class="panel-body">
        <table class="table table-condensed table-bordered ">
          <thead>
            <tr>
              <th> ID </th>
              <th class="text-center"> Data Baixa </th>
              <th class="text-center"> Produto </th>
              <th class="text-center"> Qtd.</th>
              <th class="text-center"> Opções</th>
            </tr>
          </thead>
          <tbody id="tbodyRecolhimento"></tbody>
          </tabel>
      </div>
    </div>
  </div>
</div>

<!-- Modal Recolhimento -->
<div class="modal" id="modalRecolhimento">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Recolhimento de Produto</h4>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <input type="text" id="p_id_sale" hidden>
        <div class="container divRadio">
          <div class="form-check-inline">
            <label class="form-check-label">
              <input type="radio" class="form-check-input" name="radioRecolhimento" value="Sim">Sim
            </label>
          </div>
          <div class="form-check-inline">
            <label class="form-check-label">
              <input type="radio" class="form-check-input" name="radioRecolhimento" value="Nao">Não
            </label>
          </div>
        </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js" integrity="sha512-9UR1ynHntZdqHnwXKTaOm1s6V9fExqejKvg5XMawEMToW4sSw+3jtLrYfZPijvnwnnE8Uol1O9BcAskoxgec+g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="libs/js/functions.js"></script>

<script>
  $(document).ready(function() {
    var id;
    populateRecolhas();
    function populateRecolhas(){
      $.ajax({
        url: "scripts/recolhimento/get_recolhimentos.php",
        data: {

        },
        type: 'post',
        success: function(data) {
          var json = JSON.parse(data);

          var td = '';
          json.forEach(function(e, index) {

            td += '<tr>';
            td += '   <td>' + e[0] + '</td>';
            td += '   <td class="text-center">' + e[4] + '</td>';
            td += '   <td>' + e[1] + '</td>';
            td += '   <td class="text-center">' + e[3] + '</td>';
            td += '   <td class="text-center"><button class="btn btn-primary btnRecolhimento">Recolhimento</button></td>';
            td += '</tr>';

          });
          $('#tbodyRecolhimento').html(td).show();
          //$(' table thead tr th:eq(0), #tbodyEntrada tr td:nth-child(0)').hide();
          //$('td:nth-child(0)').hide();
        }
      });
    }

    $(document).on('click', '.btnRecolhimento', function() {
      var product_id = [];
      var product_name = [];
      var product_medida = [];
      var product_bandeira = [];
      var product_stock = [];

      $.ajax({
        url: "scripts/recolhimento/get_products_recolha.php",
        data: {

        },
        type: 'post',
        success: function(data) {

          var products = JSON.parse(data);

          products.forEach(function(e, index) {
            product_id[index] = e['id'];   
            product_name[index] = e['name'];     
            product_medida[index] = e['medida'];   
            product_bandeira[index] = e['bandeira'];
            product_stock[index] = e['quantity'];
          });

        }
      });
     
      var currentRow = $(this).closest("tr");
      id = currentRow.find("td:eq(0)").text();
      $('#modalRecolhimento').modal('show');
      $('#p_id_sale').val(id);
      $(document).on('change', 'input:radio[name=radioRecolhimento]', function() {
        if (this.value == 'Sim') {
          $('.divRadio').empty();
          var div = '';
          div += '<div class="form-group" style="max-width:40%">';
          div += '  <label for="qtd_p_recolha">Qtd.</label>';
          div += '  <input type="number" class="form-control" id="qtd_p_recolha">';
          div += '  <label for="selProduct_recolha">Selecione o Produto:</label>';
          div += '  <select class="form-control" id="selProduct_recolha">';
          product_name.forEach(function(prod, index) {
          div += '<option value="'+product_id[index]+'" data-stock="'+product_stock[index]+'">'+prod+' - '+product_medida[index]+' - '+product_bandeira[index]+'</option>';
          });
          div += '  </select>';
          div += '</div>';
          div += '<button type="button" class="btn btn-success btnConfirmaRecolhimento">Confirmar</button>';
          $('.divRadio').html(div).show();
        } else if (this.value == 'Nao') {
          var div = '';
          div += '<h5>Confirma o não recolhimento deste item ?</h5>';
          div += '<button type="button" class="btn btn-success btnConfirmaRecolhimento">Confirmar</button>';
          $('.divRadio').html(div).show();
        }
      });

    });

    $(document).on('click', '.btnConfirmaRecolhimento', function() {
      var id_sale = $('#p_id_sale').val();
      var product_recolha = $('#selProduct_recolha').find(":selected").val();
      var qtd_recolha = $('#qtd_p_recolha').val();
      var product_recolha_stock = $('#selProduct_recolha').find(":selected").attr('data-stock');

      $.ajax({
        url: "scripts/recolhimento/add_recolhimento.php",
        data: {
          product_recolha: product_recolha,
          qtd_recolha: qtd_recolha,
          product_recolha_stock: product_recolha_stock,
          id: id
        },
        type: 'post',
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'true') {
            $('#modalRecolhimento').modal('hide');
            $('.divRadio').empty();
            populateRecolhas();
          } else {
            alert('Ocorreu uma Falha Tente Novamente !');
          }
        }
      });
    });

  });
</script>

</body>

</html>