<?php

include'../includes/connection.php';
?>
          <!-- Page Content -->
          <div class="col-lg-12">
            <?php
              $pc = $_POST['prodcode'];
              $name = $_POST['name'];
              $desc = $_POST['description'];
              $qty = $_POST['quantity'];
              $oh = $_POST['onhand'];
              $pr = $_POST['price']; 
              $cat = $_POST['category'];
              $supp = $_POST['supplier'];
              $dats = $_POST['datestock']; 
        
              switch($_GET['action']){
                case 'add':  
                    // Vérifier si le PRODUCT_CODE existe déjà
                    $check_query = "SELECT COUNT(*) as count FROM product WHERE PRODUCT_CODE = '$pc'";
                    $check_result = mysqli_query($db, $check_query);
                    $check_data = mysqli_fetch_assoc($check_result);
                    
                    if ($check_data['count'] > 0) {
                        echo '<script type="text/javascript">alert("Le code produit \'$pc\' existe déjà ! Veuillez utiliser un code unique.");window.location = "product.php";</script>';
                    } else {
                        // Insérer un seul produit avec la quantité totale
                        $query = "INSERT INTO product
                                  (PRODUCT_ID, PRODUCT_CODE, NAME, DESCRIPTION, QTY_STOCK, ON_HAND, PRICE, CATEGORY_ID, SUPPLIER_ID, DATE_STOCK_IN)
                                  VALUES (Null,'{$pc}','{$name}','{$desc}',{$qty},{$oh},{$pr},{$cat},{$supp},'{$dats}')";
                        mysqli_query($db,$query)or die ('Erreur lors de la mise à jour du produit dans la base de données '.$query);
                        echo '<script type="text/javascript">alert("Produit ajouté avec succès !");window.location = "product.php";</script>';
                    }
                break;
              }
            ?>
              <script type="text/javascript">window.location = "product.php";</script>
          </div>

<?php
include'../includes/footer.php';
?>