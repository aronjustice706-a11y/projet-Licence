<?php

include'../includes/connection.php';
include'../includes/topp.php';
// session_start();
$product_ids = array();
//session_destroy();

//check if Add to Cart button has been submitted
if(filter_input(INPUT_POST, 'addpos')){
    $product_id = filter_input(INPUT_GET, 'id');
    $product_name = filter_input(INPUT_POST, 'name');
    $requested_quantity = filter_input(INPUT_POST, 'quantity');
    
    // Récupérer le PRODUCT_CODE et le stock total
    $product_info_query = "SELECT PRODUCT_CODE, SUM(ON_HAND) as total_stock FROM product WHERE PRODUCT_ID = $product_id GROUP BY PRODUCT_CODE";
    $product_info_result = mysqli_query($db, $product_info_query);
    
    if ($product_info_result && mysqli_num_rows($product_info_result) > 0) {
        $product_data = mysqli_fetch_assoc($product_info_result);
        $available_stock = intval($product_data['total_stock']);
        $product_code = $product_data['PRODUCT_CODE'];
        
        // Calculer la quantité déjà dans le panier par PRODUCT_CODE
        $cart_quantity = 0;
        if(isset($_SESSION['pointofsale'])){
            foreach($_SESSION['pointofsale'] as $item){
                // Récupérer le PRODUCT_CODE de l'item dans le panier
                $item_product_query = "SELECT PRODUCT_CODE FROM product WHERE PRODUCT_ID = " . $item['id'];
                $item_product_result = mysqli_query($db, $item_product_query);
                if($item_product_result && mysqli_num_rows($item_product_result) > 0) {
                    $item_product_data = mysqli_fetch_assoc($item_product_result);
                    if($item_product_data['PRODUCT_CODE'] == $product_code){
                        $cart_quantity += $item['quantity'];
                    }
                }
            }
        }
        
        // Vérifier si la quantité demandée + quantité en panier ne dépasse pas le stock
        if(($requested_quantity + $cart_quantity) > $available_stock){
            echo '<script type="text/javascript">alert("Stock insuffisant ! Stock disponible : ' . $available_stock . ' unités.");</script>';
        } else {
            if(isset($_SESSION['pointofsale'])){
                
                //keep track of how mnay products are in the shopping cart
                $count = count($_SESSION['pointofsale']);
                
                //create sequantial array for matching array keys to products id's
                $product_ids = array_column($_SESSION['pointofsale'], 'id');

                if (!in_array($product_id, $product_ids)){
                $_SESSION['pointofsale'][$count] = array
                    (
                        'id' => $product_id,
                        'name' => $product_name,
                        'price' => filter_input(INPUT_POST, 'price'),
                        'quantity' => $requested_quantity
                    );   
                }
                else { //product already exists, increase quantity
                    //match array key to id of the product being added to the cart
                    for ($i = 0; $i < count($product_ids); $i++){
                        if ($product_ids[$i] == $product_id){
                            //add item quantity to the existing product in the array
                            $_SESSION['pointofsale'][$i]['quantity'] += $requested_quantity;
                        }
                    }
                }
                
            }
            else { //if shopping cart doesn't exist, create first product with array key 0
                //create array using submitted form data, start from key 0 and fill it with values
                $_SESSION['pointofsale'][0] = array
                (
                    'id' => $product_id,
                    'name' => $product_name,
                    'price' => filter_input(INPUT_POST, 'price'),
                    'quantity' => $requested_quantity
                );
            }
        }
    } else {
        echo '<script type="text/javascript">alert("Produit non trouvé !");</script>';
    }
}

if(filter_input(INPUT_GET, 'action') == 'delete'){
    //loop through all products in the shopping cart until it matches with GET id variable
    foreach($_SESSION['pointofsale'] as $key => $product){
        if ($product['id'] == filter_input(INPUT_GET, 'id')){
            //remove product from the shopping cart when it matches with the GET id
            unset($_SESSION['pointofsale'][$key]);
        }
    }
    //reset session array keys so they match with $product_ids numeric array
    $_SESSION['pointofsale'] = array_values($_SESSION['pointofsale']);
}

//pre_r($_SESSION);

function pre_r($array){
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}
                ?>
                <div class="row">
                <div class="col-lg-12">
                  <div class="card shadow mb-0">
                  <div class="card-header py-2">
                    <h4 class="m-1 text-lg text-primary">Catégorie de produit</h4>
                  </div>
                        <!-- /.panel-heading -->
                        <div class="card-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                              <li class="nav-item">
                                <a class="nav-link active" href="#reactifsdelaboratoire" data-toggle="tab">Reactifs de Laboratoire</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="#appareildelaboratoire" data-toggle="tab">Appareil de Laboratoire</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="#solutionsdelaboratoire" data-toggle="tab">Solutions de Laboratoire</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="#testsrapides" data-toggle="tab">Tests Rapides</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="#materielchirurgicaletaccouchement" data-toggle="tab">Matériel chirurgical et Accouchement</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="#dispositifdechirurgieeturologie" data-toggle="tab">Dispositif de chirurgie et urologie</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="#premierssoins" data-toggle="tab">Premiers soins</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="#appareildechirurgie" data-toggle="tab">Appareil de chirurgie</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="#autres" data-toggle="tab">Autres....</a>
                              </li>
                            </ul>

<!-- TAB PANE AREA - ANG UNOD KA TABS ARA SA TABPANE.PHP -->
<?php include 'postabpane.php'; ?>
<!-- END TAB PANE AREA - ANG UNOD KA TABS ARA SA TABPANE.PHP -->

        <div style="clear:both"></div>  
        <br />  
        <div class="card shadow mb-4 col-md-12">
        <div class="card-header py-3 bg-white">
          <h4 class="m-2 font-weight-bold text-primary">Point de vente</h4>
        </div>
        
      <div class="row">    
      <div class="card-body col-md-9">
        <div class="table-responsive">

        <!-- trial form lang   -->
<form role="form" method="post" action="pos_transac.php?action=add" onsubmit="return checkStockBeforeSubmit();">
  <input type="hidden" name="employee" value="<?php echo $_SESSION['FIRST_NAME']; ?>">
  <input type="hidden" name="role" value="<?php echo $_SESSION['JOB_TITLE']; ?>">
  
        <table class="table">    
        <tr>  
             <th width="55%">Nom du produit</th>  
             <th width="10%">Quantité</th>  
             <th width="15%">Prix</th>  
             <th width="15%">Total</th>  
             <th width="5%">Action</th>  
        </tr>  
        <?php  

        if(!empty($_SESSION['pointofsale'])):  
            
             $total = 0;  
        
             foreach($_SESSION['pointofsale'] as $key => $product): 
        ?>  
        <tr>  
          <td>
            <input type="hidden" name="name[]" value="<?php echo $product['name']; ?>">
            <?php echo $product['name']; ?>
          </td>  

           <td>
            <input type="hidden" name="quantity[]" value="<?php echo $product['quantity']; ?>">
            <?php echo $product['quantity']; ?>
          </td>  

           <td>
            <input type="hidden" name="price[]" value="<?php echo $product['price']; ?>">
            <?php echo number_format($product['price']); ?> XAF
          </td>  

           <td>
            <input type="hidden" name="total" value="<?php echo $product['quantity'] * $product['price']; ?>">
            <?php echo number_format($product['quantity'] * $product['price'], 2); ?> XAF
            </td>  
           <td>
               <a href="pos.php?action=delete&id=<?php echo $product['id']; ?>">
                    <div class="btn bg-gradient-danger btn-danger"><i class="fas fa-fw fa-trash"></i></div>
               </a>
           </td>  
        </tr>
        <?php  
                  $total = $total + ($product['quantity'] * $product['price']);
             endforeach;  
        ?>


        <?php  
        endif;
        ?>  
        </table> 
         </div>
       </div> 

<?php
include 'posside.php';
include'../includes/footer.php';
?>
