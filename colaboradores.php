<?php
$page_title = 'Colaboradores';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(3);

$conn = mysqli_connect('mysql.dodoifarma.com.br:3306', 'dodoifarma', 'dodoifarma2021', 'dodoifarma');
$sql = "select u.name, date_format(rue.ultima_entrega,'%d/%m/%Y') as ultima_entrega
from users u 
left join requestions_ultima_entrega rue on rue.userid = u.id
where rue.ultima_entrega is null
	OR rue.ultima_entrega = (
    select max(rue2.ultima_entrega)
    from requestions_ultima_entrega rue2
    where rue2.userid = u.id
    )
order by 1 asc";
$query = mysqli_query($conn,$sql);
$all_colabs = mysqli_fetch_all($query,MYSQLI_ASSOC);
?>



<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="col-md-12">
  <div class="panel panel-default">
    <div class="panel-heading clearfix">
      <div class="pull-right">
        <input class="form-control" id="search_colaborador" type="text" placeholder="Busca..">
      </div>
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>Colaboradores</span>
      </strong>
    </div>
    <div class="panel-body">
      <button id="btnUpdateColab" class="btn btn-info mb-3">Atualizar Colaboradores</button>
      <table class="table table-bordered" id="tableColaborador">
        <thead>
          <tr>
            <th>Nome</th>
            <th class="text-center">Ultimo Recebimento</th>
          </tr>
        </thead>
        <tbody id="tdbodyColaboradores">
          <?php foreach ($all_colabs as $row) : ?>
            <tr>
              <td><?php echo remove_junk($row['name']); ?></td>
              <td class="text-center"><?php echo $row['ultima_entrega']; ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>

<script>
  $(document).ready(function() {
    $('#btnUpdateColab').on('click', function() {
      $.ajax({
        url: "scripts/colaboradores/insert_colaboradores.php",
        data: {},
        type: 'post',
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'true') {

          } else {
            alert('Ocorreu uma Falha Tente Novamente !');
          }
        }
      });
    });

    $("#search_colaborador").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $("#tdbodyColaboradores tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });

  });
</script>

<script>
  $(document).ready(function() {

  });
</script>