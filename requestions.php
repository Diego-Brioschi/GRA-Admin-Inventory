<?php
$page_title = 'Pedidos';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(2);
?>

<?php
date_default_timezone_set('America/Sao_Paulo');
setlocale(LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese');
$numero_dia = date('w') * 1;
$dia_mes = date('d');
$numero_mes = date('m') * 1;
$ano = date('Y');
$hora = date('H:i');
$mes = array('', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
?>
<?php $user = current_user(); ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <title><?php if (!empty($page_title))
            echo remove_junk($page_title);
          elseif (!empty($user))
            echo ucfirst($user['name']);
          else echo "Simple inventory System"; ?>
  </title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
  <!-- Utils CSS -->
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.17/css/bootstrap-select.min.css" integrity="sha512-8IKwXYhvXkrNGaU06NnGsiDqJign94kk5+AAdTu4wR3hkuU5x2Weo1evN3xYSpnRtIJNLwAT2/R4ITAAv0IhdA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- DataTable CSS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" />
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.dataTables.css" />
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.2.1/css/fixedHeader.dataTables.css" />
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/searchpanes/1.4.0/css/searchPanes.dataTables.css" />
  <!-- Main CSS -->
  <link rel="stylesheet" href="libs/css/main.css" />
  <link rel="stylesheet" href="libs/css/sidebar.css" />
</head>

<body>
  <?php if ($session->isUserLoggedIn(true)) : ?>
    <header id="header">
      <div class="logo pull-left"> GRA - Estoque </div>
      <div class="header-content">
        <div class="header-date pull-left">
          <strong><?php echo $dia_mes . " de " . $mes[$numero_mes] . " de " . $ano . " - " . $hora . "."; ?></strong>
        </div>
        <div class="pull-right clearfix">
          <ul class="info-menu list-inline list-unstyled">
            <li class="profile">
              <a href="#" data-toggle="dropdown" class="toggle" aria-expanded="false">
                <img src="uploads/users/<?php echo $user['image']; ?>" alt="user-image" class="img-circle img-inline">
                <span><?php echo remove_junk(ucfirst($user['name'])); ?> <i class="caret"></i></span>
              </a>
              <ul class="dropdown-menu">
                <li>
                  <a href="profile.php?id=<?php echo (int)$user['id']; ?>">
                    <i class="glyphicon glyphicon-user"></i>
                    Perfil
                  </a>
                </li>
                <li>
                  <a href="edit_account.php" title="edit account">
                    <i class="glyphicon glyphicon-cog"></i>
                    Opções
                  </a>
                </li>
                <li class="last">
                  <a href="logout.php">
                    <i class="glyphicon glyphicon-off"></i>
                    Sair
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </header>
    <div class="sidebar" id="mySidebar">
      <?php if ($user['user_level'] === '1') : ?>
        <!-- admin menu -->
        <?php include_once('layouts/admin_menu.php'); ?>

      <?php elseif ($user['user_level'] === '2') : ?>
        <!-- Special user -->
        <?php include_once('special_menu.php'); ?>

      <?php elseif ($user['user_level'] === '3') : ?>
        <!-- User menu -->
        <?php include_once('user_menu.php'); ?>

      <?php endif; ?>

    </div>
  <?php endif; ?>

  <div class="page">
    <div class="container-fluid">

      <div class="row">
        <div class="col-md-12">
          <?php echo display_msg($msg); ?>
        </div>
        <div class="col-md-12">
          <div class="panel panel-default">
            <div class="panel-heading clearfix">
              <div class="form-inline">
                <div class="form-group">
                  <label for="sel_tipo">Tipo:</label>
                  <select class="form-control" id="sel_tipo">
                    <option value="" selected>Selecione...</option>
                    <option value="REQUISIÇÃO">REQUISIÇÃO</option>
                    <option value="SOLICITAÇÃO">SOLICITAÇÃO</option>
                    <option value="COMPRA">COMPRA</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="sel_necessidade">Necessidade:</label>
                  <select class="form-control" id="sel_necessidade">
                    <option value="" selected>Selecione...</option>
                    <option value="REQUISIÇÃO">REQUISIÇÃO</option>
                    <option value="SOLICITAÇÃO">SOLICITAÇÃO</option>
                    <option value="COMPRA">COMPRA</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="sel_entrega">Entrega:</label>
                  <select class="form-control" id="sel_entrega">
                    <option value="Enviado">Enviado</option>
                    <option value="Nao_Enviado" selected>Não Enviado</option>
                  </select>
                </div>
                <button type="submit" class="btn btn-success mt-4" id="btnFilter">Filtrar <i class="bi bi-search"></i></button>
              </div>
            </div>
            <div class="panel-body">

              <table id="table_requestions" class="table stripe row-border" style="width:100%">
                <thead>
                  <th>id</th>
                  <th>Tipo</th>
                  <th>Necessidade</th>
                  <th>Solicitante</th>
                  <th>Loja</th>
                  <th>Produtos Solicitados</th>
                  <th>Requisição</th>
                  <th>Solicitação</th>
                </thead>
                <tbody>
                </tbody>
              </table>

            </div>
          </div>
        </div>
      </div>

      <!-- Modal Usuario -->
      <div class="modal" id="modalUser">
        <div class="modal-dialog">
          <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Informações do Solicitante</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
              <div class="form-group">
                <label for="p_solicitante">Solicitante</label>
                <input type="text" class="form-control" id="p_solicitante" disabled>
              </div>
              <div class="form-group">
                <label for="p_email">Email</label>
                <input type="text" class="form-control" id="p_email" disabled>
              </div>
              <div class="form-group">
                <label for="p_tel">Telefone</label>
                <input type="text" class="form-control" id="p_tel" disabled>
              </div>
              <div class="form-group">
                <label for="p_cargo">Cargo</label>
                <input type="text" class="form-control" id="p_cargo" disabled>
              </div>
              <div class="form-group">
                <label for="p_loja">Loja</label>
                <input type="text" class="form-control" id="p_loja" disabled>
              </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
            </div>

          </div>
        </div>
      </div>

      <!-- Modal Informações do Produto -->
      <div class="modal fade" id="modalInfoProducts" role="dialog">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Produtos Solicitados</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
              <div class="form-row">
                <input type="text" hidden id="idDatatable">
                <div class="form-group col-md-4">
                  <label for="p_necessidade">Necessidade</label>
                  <input type="text" class="form-control" id="p_necessidade" disabled>
                </div>
                <div class="form-group col-md-4">
                  <label for="p_finalidade">Finalidade</label>
                  <input type="text" class="form-control" id="p_finalidade" disabled>
                </div>
                <div class="form-group col-md-4">
                  <label for="p_motivo">Motivo</label>
                  <input type="text" class="form-control" id="p_motivo" disabled>
                </div>
              </div>
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th class="w-25">Produto</th>
                    <th class="text-center w-25">Quantidade</th>
                    <th class="text-center w-25">Valor Un.</th>
                    <th class="text-center w-25">Valor Total</th>
                  </tr>
                </thead>
                <tbody id="tbody_products">
                </tbody>
              </table>
              <hr>
              <form id="formProducts">
                <div class="form-row">
                  <div class="form-group col-md-3">
                    <label for="p_previsao_entrega">Previsão de Entrega</label>
                    <input type="date" class="form-control" id="p_previsao_entrega" required>
                  </div>
                  <div class="form-group col-md-2">
                    <label for="p_todos_val_total">Valor Total</label>
                    <input type="text" class="form-control" id="p_todos_val_total" disabled>
                  </div>
                  <div class="form-group col-md-4 mt-2">
                    <button class="btn btn-success mt-4" type="submit">Enviar</button>
                  </div>
                  <div class="form-group col-md-3 mt-2 d-flex justify-content-end" id="divComprovante">
                    <a class="btn btn-primary mt-4" id="btnComprovante" href="" target="_BLANK"><i class="bi bi-file-check-fill"></i> Visualizar Comprovante</a>
                    <strong class="text-danger mt-4" id="semAnexo">SEM ANEXO !</strong>
                  </div>
                </div>
              </form>

            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
            </div>

          </div>
        </div>
      </div>

      <!-- Modal Termo Requisição -->
      <div class="modal" id="modalTermo">
        <div class="modal-dialog">
          <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Termo de Requisição</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
              <p class="mb-0">Clique no Botão abaixo para visualizar a requisição.</p>
              <button type='button' class='btn btn-primary' id='btnTermo'><i class="bi bi-printer"></i> Imprimir</button>
              <hr>
              <div class="mt-3">
                <input type="text" hidden id="idDatatable">
                <label for="p_signature">Preencha o nome completo para realizar a assinatura.</label>
                <input type="text" class="form-control" id="p_signature" placeholder="Seu nome...">
                <h5 id="r_signature" style="font-family: 'Dancing Script', Verdana; font-size: 50px;"></h5>
                <button type='button' class='btn btn-success' id='btnAssinar'><i class="bi bi-pencil-square"></i> Assinar</button>
              </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Fehar</button>
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

  <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.11.3/datatables.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.bootstrap4.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.colVis.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.print.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.2.1/js/dataTables.fixedHeader.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/searchpanes/1.4.0/js/dataTables.searchPanes.js"></script>

  <script type="text/javascript" src="libs/js/functions.js"></script>
  <script type="text/javascript" src="libs/js/datatable-requestions.js"></script>

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