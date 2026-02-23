<?php
include'../includes/connection.php';

include'../includes/sidebar.php';
  $query = 'SELECT ID, t.TYPE
            FROM users u
            JOIN type t ON t.TYPE_ID=u.TYPE_ID WHERE ID = '.$_SESSION['MEMBER_ID'].'';
  $result = mysqli_query($db, $query) or die (mysqli_error($db));
  
  while ($row = mysqli_fetch_assoc($result)) {
            $Aa = $row['TYPE'];
                   
  if ($Aa=='User'){
?>
  <script type="text/javascript">
    //then it will be redirected
    alert("Page restreinte ! Vous allez être redirigé vers le POS");
    window.location = "pos.php";
  </script>
<?php
  }           
}
$sql = "SELECT DISTINCT CNAME, CATEGORY_ID FROM category order by CNAME asc";
$result = mysqli_query($db, $sql) or die ("Bad SQL: $sql");

$opt = "<select class='form-control' name='category'>
        <option disabled selected>Select Category</option>";
  while ($row = mysqli_fetch_assoc($result)) {
    $opt .= "<option value='".$row['CATEGORY_ID']."'>".$row['CNAME']."</option>";
  }

$opt .= "</select>";

  $query = 'SELECT PRODUCT_ID,PRODUCT_CODE, NAME,QTY_STOCK, ON_HAND,COMPANY_NAME, c.CNAME FROM product p LEFT JOIN category c on p.CATEGORY_ID=c.CATEGORY_ID LEFT JOIN supplier s ON p.SUPPLIER_ID=s.SUPPLIER_ID WHERE PRODUCT_CODE = "'.$_GET['id'].'"';
  
  // Debug: afficher la requête et l'ID
  echo "<!-- Debug: ID reçu = " . $_GET['id'] . " -->";
  echo "<!-- Debug: Requête = " . $query . " -->";
  
  $result = mysqli_query($db, $query) or die(mysqli_error($db));
  
  // Initialiser les variables par défaut
  $zz = '';
  $zzz = '';
  $A = '';
  $B = '';
  $C = '';
  $D = '';
  $E = '';
  
  if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_array($result))
    {   
      $zz = $row['PRODUCT_ID'];
      $zzz = $row['PRODUCT_CODE'];
      $A = $row['NAME'];
      $B = $row['QTY_STOCK'];
      $C = $row['ON_HAND'];
      $D = $row['COMPANY_NAME'];
      $E = $row['CNAME'];
    }
  } else {
    echo '<script type="text/javascript">alert("Produit non trouvé.");window.location = "inventory.php";</script>';
    exit();
  }
      $id = $_GET['id'];
?>

  <center><div class="card shadow mb-4 col-xs-12 col-md-8 border-bottom-primary">
            <div class="card-header py-3">
              <h4 class="m-2 font-weight-bold text-primary">Modifier l'inventaire pour : <?php echo $A ?></h4>
            </div>
            <a type="button" class="btn btn-primary bg-gradient-primary" href="inv_searchfrm.php?action=edit & id='<?php echo $zzz; ?>'"><i class="fas fa-fw fa-flip-horizontal fa-share"></i> Retour</a>
                
            <div class="card-body">

            <form role="form" method="post" action="inv_edit1.php">
              <input type="hidden" name="idd" value="<?php echo $zz; ?>" />
              <div class="form-group row text-left text-warning">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Code du produit:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" value="<?php echo $zzz; ?>" readonly>
                </div>
              </div>
              <div class="form-group row text-left text-warning">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Nom du produit:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" value="<?php echo $A; ?>" readonly>
                </div>
              </div>
              <div class="form-group row text-left text-warning">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Quantité:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" placeholder="Quantity" name="qty" value="<?php echo $B; ?>" required>
                </div>
              </div>
              <div class="form-group row text-left text-warning">
                <div class="col-sm-3" style="padding-top: 5px;">
                 En stock:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" placeholder="On Hand" name="oh" value="<?php echo $C; ?>" required>
                </div>
              </div>
              <div class="form-group row text-left text-warning">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Fournisseur:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" value="<?php echo $D; ?>" readonly>
                </div>
              </div>
              <div class="form-group row text-left text-warning">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Catégorie:
                </div>
                <div class="col-sm-9">
                   <input class="form-control"value="<?php echo $E; ?>" readonly>
                </div>
              </div>
              <hr>

                <button type="submit" class="btn btn-warning btn-block"><i class="fa fa-edit fa-fw"></i>Mettre a jours</button>    
              </form>  
            </div>
          </div></center>

<?php
include'../includes/footer.php';
?>