<?php
$page_title = 'Impressão de Etiquetas';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(2);
$products = join_product_table('A', 'c.id');
?>
<?php include_once('layouts/header.php'); ?>

<link href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <div class="input-group">
        <span class="input-group-btn">
          <button type="button" class="btn btn-primary" id="btnSearchImpressao">Procurar</button>
        </span>
        <input type="text" id="p_product_impressao" class="form-control" placeholder="Digite o nome do Produto">
      </div>
      <div style="margin-top: 7px;">
        <label class="radio-inline">
          <input type="radio" name="radioImp" checked value="N">Não Impressos
        </label>
        <label class="radio-inline">
          <input type="radio" name="radioImp" value="S">Impressos
        </label>
      </div>
    </div>
  </div>
</div>


<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <div class="pull-right">
        </div>
      </div>
      <div class="panel-body">
        <table class="table table-condensed table-bordered ">
          <thead>
            <tr>
              <th hidden> ID </th>
              <th class="text-center"> Produto </th>
              <th class="text-center">Qtd. Etiquetas</th>
              <th class="text-center"> Opções</th>
            </tr>
          </thead>
          <tbody id="tbodyImpressaoEtiquetas"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal Entrada do Produto -->
<div class="modal" id="modalImpressaoEtiquetas">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Etiquetas</h4>
      </div>

      <!-- Modal body -->
      <div class="modal-body">

        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Código de Barras</th>
              <th class='text-center'>Impressão</th>
            </tr>
          </thead>
          <tbody id="tbodySelEan">
          </tbody>
        </table>

        <div class="form-check">
          <label class="form-check-label" for="checkTotal">
            <input type="checkbox" class="form-check-input" id="checkTotal"> Selecionar Todas</label>
        </div>
        <button type="button" class="btn btn-success" id="btnImprimir">Imprimir</button>
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
  $(document).ready(function() {
    $('#btnSearchImpressao').on('click', function(e) {
      var ean = [];
      var name_product = [];
      var bandeira_product = [];
      var size_product = [];
      var p_product = $('#p_product_impressao').val();
      var p_imp = $('input[name=radioImp]:checked').val();

      $.ajax({
        url: "scripts/impressao_etiquetas/get_eans.php",
        data: {
          p_product: p_product,
          p_imp: p_imp
        },
        type: 'post',
        success: function(data) {
          var json = JSON.parse(data);
          var td = '';

          json.forEach(function(e, index) {
            ean[index] = e[3];
            name_product[index] = e[1];
            bandeira_product[index] = e[4];
            size_product[index] = e[5];
            td += '<tr>';
            td += '   <td>' + e[0] + '</td>';
            td += '   <td>' + e[1] + '</td>';
            td += '   <td class="text-center">' + e[2] + '</td>';
            td += '   <td class="text-center"><button class="btn btn-primary btnSelEan">Selecionar EAN</button></td>';
            td += '</tr>';

          });
          $('#tbodyImpressaoEtiquetas').html(td).show();
          $(document).ready(function() {
            $('tr td:nth-child(1)').hide();
          });
        }
      });

      $(document).on('click', '.btnSelEan', function() {
        //$('#tbodySelEan').empty();
        $('#modalImpressaoEtiquetas').modal('show');
        var p_id = $(this).closest('td').parent()[0].sectionRowIndex;
        var result;
        var eans = ean[p_id].split(",");
        name_product = name_product[p_id];
        bandeira_product = bandeira_product[p_id];
        size_product = size_product[p_id];


        eans.forEach(function(e, index) {

          result += "<tr>";
          result += " <td>" + e + "</td>";
          result += "   <td class='text-center'>";
          result += "   <div class='form-check'>";
          result += "     <label class='form-check-label' for='check'>";
          result += "     <input type='checkbox' class='form-check-input checkEan' value='" + e + "'></label>";
          result += "   </div>";
          result += " </td>";
          result += "</tr>";
        });
        $('#tbodySelEan').html(result).show();

        $('#checkTotal').click(function(event) {
          if (this.checked) {
            $('.checkEan').each(function() {
              $(this).prop('checked', true);
            });
          } else {
            $('.checkEan').each(function() {
              $(this).prop('checked', false);
            });
          }
        });

        $('#btnImprimir').on('click', function(e) {
          checkedEan = [];
          $(".checkEan:checked").each(function() {
            checkedEan.push($(this).val());
          });
          if (checkedEan.length === 0) {
            alert('Selecionar um Código de Barras para imprimir');
          } else {
            //Alerta de Impressão Iniciada
            Swal.fire({
              position: 'top-end',
              icon: 'info',
              title: 'Impressão Iniciada !!',
              showConfirmButton: false,
              timer: 2000
            });
            var urlRequestion = "http://localhost/sistema%20Estoque/estoque/scripts/impressao_etiquetas/generate_etiquetas.php?";
            urlRequestion += "array_ean=" + JSON.stringify(checkedEan);
            window.open(urlRequestion, 'PDF');
            $.ajax({
              url: "scripts/impressao_etiquetas/update_ean_impressos.php",
              data: {
                checkedEan: checkedEan
              },
              type: 'post',
              success: function(data) {
                var json = JSON.parse(data);
                var status = json.status;
                if (status == 'true') {
                  $('#modalImpressaoEtiquetas').modal('hide');
                  var p_ean_update = $('#p_product_impressao').val();
                  $('#p_product_impressao').val(p_ean_update);
                  $('#btnSearchImpressao').trigger('click');
                  Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Impressão finalizada !!',
                    showConfirmButton: false,
                    timer: 1500
                  });
                }
              }
            });
            //Mandar Ajax para impressão enviando array com os códigos
            /*$.ajax({
              url: "scripts/impressao_etiquetas/argox_imprimir.php",
              data: {
                checkedEan: checkedEan,
                name_product: name_product,
                bandeira_product: bandeira_product,
                size_product: size_product
              },
              type: 'post',
              success: function(data) {

                  //Update EAN Impressos
                  $.ajax({
                    url: "scripts/impressao_etiquetas/update_ean_impressos.php",
                    data: {
                      checkedEan: checkedEan
                    },
                    type: 'post',
                    success: function(data) {
                        var json = JSON.parse(data);
                        var status = json.status;
                        if (status == 'true') {
                          $('#modalImpressaoEtiquetas').modal('hide');  
                          var p_ean_update = $('#p_product_impressao').val();
                          $('#p_product_impressao').val(p_ean_update);
                          $('#btnSearchImpressao').trigger('click');
                          Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Impressão finalizada !!',
                            showConfirmButton: false,
                            timer: 1500
                          });
                        }          
                    }
                  });
              }
            });*/
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