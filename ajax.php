<?php
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
?>

<?php
 // Auto suggetion
    $html = '';
   if(isset($_POST['product_name']) && strlen($_POST['product_name']))
   {
     $products = find_product_by_title($_POST['product_name']);    
     if($products){
        foreach ($products as $product):
           $html .= "<li class=\"list-group-item\">";
           $html .= $product['codigobarras'];
           $html .= "</li>";
         endforeach;
      } else {

        $html .= '<li onClick=\"fill(\''.addslashes().'\')\" class=\"list-group-item\">';
        $html .= 'Não encontrado';
        $html .= "</li>";

      }

      echo json_encode($html);
   }
 ?>
 <?php
  $colabs = find_all_colaboradores();
 // find all product
  if(isset($_POST['p_name']) && strlen($_POST['p_name']))
  {
    $product_title = remove_junk($db->escape($_POST['p_name']));
    if($results = find_all_product_info_by_title($product_title)){
        foreach ($results as $result) {

          $html .= "<tr>";

          $html .= "<td id=\"s_name\">".$result['name']."</td>";
          $html .= "<input type=\"hidden\" name=\"s_id\" value=\"{$result['id']}\">";
          $html .= "<input type=\"hidden\" name=\"s_ean\" value=\"{$result['codigobarras']}\">";
          $html  .= "<td>";
          $html  .= "<input type=\"text\" class=\"form-control\" name=\"price\" value=\"{$result['sale_price']}\">";
          $html  .= "</td>";
          $html .= "<td id=\"s_qty\">";
          $html .= "<input type=\"text\" class=\"form-control\" name=\"quantity\" value=\"1\">";
          $html  .= "</td>";
          $html  .= "<td>";
          $html  .= "<button type=\"button\" class=\"btn btn-warning\" data-toggle=\"modal\" data-target=\"#modalProduct\">Produto</button>";
          $html  .= "</td>";
          $html  .= "<td>";
          $html  .= "<input type=\"text\" class=\"form-control\" name=\"total\" value=\"{$result['sale_price']}\">";
          $html  .= "</td>";
          $html  .= "<td>";
          $html  .= "<input type=\"date\" class=\"form-control datePicker\" name=\"date\" data-date data-date-format=\"yyyy-mm-dd\">";
          $html  .= "</td>";
          $html  .= "<td>";
          $html  .= "<button type=\"submit\" name=\"add_sale\" class=\"btn btn-primary\">Baixar</button>";
          $html  .= "</td>";
          $html  .= "<td>";
          $html  .= '          
          <!-- Modal -->
          <div class="modal fade" id="modalProduct" role="dialog">
            <div class="modal-dialog">
            
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Características do Produto</h4>
                </div>
                <div class="modal-body">
                  <div class="form-group row">
                    <div class="col-xs-6">
                      <label for="product-size">Tamanho</label>
                      <input type="text" class="form-control" id="tamanho" name="product-size" value="'.$result['medida'].'" disabled>
                    </div>
                    <div class="col-xs-6">
                      <label for="product-bandeira">Bandeira</label>
                      <input type="text" class="form-control" id="bandeira" name="product-bandeira" value="'.$result['bandeira'].'" disabled>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-xs-12">
                      <label for="product-colaborador">Selecione o Colaborador</label>
                      <select class="form-control" id="product-colaborador" name="product-colaborador">
                        <option value="" selected disabled>Selecione o Colaborador</option>';
                        foreach($colabs as $colaboradores):
          $html  .=    '<option value="'.$colaboradores['id'].'"> '.$colaboradores['nome'].'</option>';
                        endforeach;
          $html  .=  '</select>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                </div>
              </div>
              
            </div>
          </div>';
          $html  .= "</td>";
          $html  .= "</tr>";

        }
    } else {
        $html ='<tr><td>O produto não foi encontrado na base de dados.</td></tr>';
    }

    echo json_encode($html);
  }
 ?>
