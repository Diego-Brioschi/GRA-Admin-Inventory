<?php include('connection.php'); ?>
<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="libs/css/bootstrap5.0.1.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="libs/css/datatables-1.10.25.min.css" />
  <title>Server Side CRUD Ajax Operations</title>
  <style type="text/css">
    .btnAdd {
      text-align: right;
      width: 83%;
      margin-bottom: 20px;
    }
  </style>
</head>

<body>
  <div class="container-fluid">
    <h2 class="text-center">Entrada do Produto</h2>
    <div class="row">
      <div class="container">
        <div class="btnAdd">
          <a href="#!" data-id="" data-bs-toggle="modal" data-bs-target="#addUserModal" class="btn btn-success btn-sm">Add User</a>
          <form id="form_search">  
            <div class="input-group" style="max-width:25%">
              <input type="text" class="form-control" placeholder="Pesquisar" id="input_search">
              <div class="input-group-btn">
                <button class="btn btn-success" type="submit">Pesquisar</button>
              </div>
            </div>
          </form>
        </div>
        <div class="row">
          <div class="col-md-2"></div>
          <div class="col-md-8">
            <table id="example" class="table">
              <thead>
                <th>#</th>
                <th>Nome</th>
                <th>Estoque</th>
                <th>Preço de Compra</th>
                <th>Preço de Venda</th>
                <th>Opções</th>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
          <div class="col-md-2"></div>
        </div>
      </div>
    </div>
  </div>
  <!-- Optional JavaScript; choose one of the two! -->
  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="libs/js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <script src="libs/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="libs/js/dt-1.10.25datatables.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('#example').DataTable({
        //'serverSide': 'true',
        'processing': 'true',
        'paging': 'true',
        'order': [],
        "language": {
             "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json"
         },
        'ajax': {
          'url': 'scripts/fetch_data_entrada.php',
          'type': 'post',
        },
        "aoColumnDefs": [{
            "bSortable": false,
            "aTargets": [5]
          },

        ]
      });
    });
    $(document).on('submit', '#addUser', function(e) {
      e.preventDefault();
      var city = $('#addCityField').val();
      var username = $('#addUserField').val();
      var mobile = $('#addMobileField').val();
      var email = $('#addEmailField').val();
      if (city != '' && username != '' && mobile != '' && email != '') {
        $.ajax({
          url: "scripts/add_user.php",
          type: "post",
          data: {
            city: city,
            username: username,
            mobile: mobile,
            email: email
          },
          success: function(data) {
            var json = JSON.parse(data);
            var status = json.status;
            if (status == 'true') {
              mytable = $('#example').DataTable();
              mytable.draw();
              $('#addUserModal').modal('hide');
            } else {
              alert('failed');
            }
          }
        });
      } else {
        alert('Fill all the required fields');
      }
    });

    $(document).on('submit', '#updateUser', function(e) {
      e.preventDefault();
      //var tr = $(this).closest('tr');
      var salePrice = $('#salePriceField').val();
      var name = $('#nameField').val();
      var buyPrice = $('#buyPriceField').val();
      var quantity = $('#quantityField').val();
      var trid = $('#trid').val();
      var id = $('#id').val();
      if (salePrice != '' && name != '' && buyPrice != '' && quantity != '') {
        $.ajax({
          url: "scripts/update_entrada.php",
          type: "post",
          data: {
            salePrice: salePrice,
            name: name,
            buyPrice: buyPrice,
            quantity: quantity,
            id: id
          },
          success: function(data) {
            var json = JSON.parse(data);
            var status = json.status;
            if (status == 'true') {
              table = $('#example').DataTable();

              var button = '<td><a href="javascript:void();" data-id="' + id + '" class="btn btn-info btn-sm editbtn">Edit</a>  <a href="#!"  data-id="' + id + '"  class="btn btn-danger btn-sm deleteBtn">Delete</a></td>';
              var row = table.row("[id='" + trid + "']");
              //row.row("[id='" + trid + "']").data([id, name, email, mobile, city, button]);             
              $('#exampleModal').modal('hide');
              $('#example').DataTable().ajax.reload();
            } else {
              alert('failed');
            }
          }
        });
      } else {
        alert('Fill all the required fields');
      }
    });
    $('#example').on('click', '.editbtn ', function(event) {
      var table = $('#example').DataTable();
      var trid = $(this).closest('tr').attr('id');
      // console.log(selectedRow);
      var id = $(this).data('id');
      $('#exampleModal').modal('show');

      $.ajax({
        url: "scripts/get_single_data.php",
        data: {
          id: id
        },
        type: 'post',
        success: function(data) {
          var json = JSON.parse(data);
          $('#nameField').val(json.name);
          $('#quantityField').val(json.quantity);
          $('#buyPriceField').val(json.buy_price);
          $('#salePriceField').val(json.sale_price);
          $('#id').val(id);
          $('#trid').val(trid);
        }
      })
    });

    $(document).on('submit', '#form_search', function(e) {
      e.preventDefault();
      //var tr = $(this).closest('tr');
      var input_search = $('#input_search').val();
      if (input_search != '') {
        $.ajax({
          url: "scripts/fetch_data_entrada.php",
          type: "post",
          data: {
            'p_search': input_search
          },
          success: function(data) {
            //$('#example').DataTable().ajax.reload();
          }
        });
      } else {
        alert('Preencha');
      }
    });

    $(document).on('click', '.deleteBtn', function(event) {
      var table = $('#example').DataTable();
      event.preventDefault();
      var id = $(this).data('id');
      if (confirm("Are you sure want to delete this User ? ")) {
        $.ajax({
          url: "scripts/delete_user.php",
          data: {
            id: id
          },
          type: "post",
          success: function(data) {
            var json = JSON.parse(data);
            status = json.status;
            if (status == 'success') {
              //table.fnDeleteRow( table.$('#' + id)[0] );
              //$("#example tbody").find(id).remove();
              //table.row($(this).closest("tr")) .remove();
              $("#" + id).closest('tr').remove();
            } else {
              alert('Failed');
              return;
            }
          }
        });
      } else {
        return null;
      }



    })
  </script>
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Atualizar Estoque</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="updateUser">
            <input type="hidden" name="id" id="id" value="">
            <input type="hidden" name="trid" id="trid" value="">
            <div class="mb-3 row">
              <label for="nameField" class="col-md-3 form-label">Nome</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="nameField" name="name" disabled>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="quantity" class="col-md-3 form-label">Estoque</label>
              <div class="col-md-9">
                <input type="number" class="form-control" id="quantityField" name="quantity">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="buyPriceField" class="col-md-3 form-label">Preço de Compra</label>
              <div class="col-md-9">
                <input type="number" step="0.01" class="form-control" id="buyPriceField" name="buyPrice">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="salePriceField" class="col-md-3 form-label">Preço de Venda</label>
              <div class="col-md-9">
                <input type="number" step="0.01" class="form-control" id="salePriceField" name="salePrice">
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-success">Salvar</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Add user Modal -->
  <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addUser" action="">
            <div class="mb-3 row">
              <label for="addUserField" class="col-md-3 form-label">Name</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="addUserField" name="name">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addEmailField" class="col-md-3 form-label">Email</label>
              <div class="col-md-9">
                <input type="email" class="form-control" id="addEmailField" name="email">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addMobileField" class="col-md-3 form-label">Mobile</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="addMobileField" name="mobile">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addCityField" class="col-md-3 form-label">City</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="addCityField" name="City">
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</body>

</html>