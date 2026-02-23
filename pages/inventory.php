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
            ?>
            
            <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h4 class="m-2 font-weight-bold text-primary">Inventaire</h4>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <form method="post" action="inv_del_multi.php" id="multiDeleteForm" style="display:inline;">
                  <button type="submit" class="btn btn-danger mb-2 mr-2" onclick="return confirm('Supprimer les produits sélectionnés de l\'inventaire ?');">Supprimer la sélection</button>
                  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0"> 
               <thead>
                   <tr>
                     <th><input type="checkbox" id="select_all"></th>
                     <th>Code du produit</th>
                     <th>Nom du produit</th>
                     <th>Quantité</th>
                     <th>En stock</th>
                      <th>Disponibilité</th>
                     <th>Catégorie</th>
                     <th>Fournisseur</th>
                     <th>Date de stockage</th>
                     <th>Action</th>
                   </tr>
               </thead>
          <tbody>

<?php                  
    $query = 'SELECT PRODUCT_CODE, NAME, SUM(QTY_STOCK) AS QTY_STOCK, SUM(ON_HAND) AS ON_HAND, CNAME, SUPPLIER_ID, MAX(DATE_STOCK_IN) AS DATE_STOCK_IN FROM product p JOIN category c ON p.CATEGORY_ID=c.CATEGORY_ID GROUP BY PRODUCT_CODE';
        $result = mysqli_query($db, $query) or die (mysqli_error($db));
      
            while ($row = mysqli_fetch_assoc($result)) {                                
                // Récupérer le nom du fournisseur
                $supplier = '-';
                if (!empty($row['SUPPLIER_ID'])) {
                    $res2 = mysqli_query($db, "SELECT COMPANY_NAME FROM supplier WHERE SUPPLIER_ID = '".$row['SUPPLIER_ID']."'");
                    if ($r2 = mysqli_fetch_assoc($res2)) {
                        $supplier = $r2['COMPANY_NAME'];
                    }
                }
                echo '<tr>';
                echo '<td><input type="checkbox" name="selected_ids[]" value="'.$row['PRODUCT_CODE'].'"></td>';
                echo '<td>'. $row['PRODUCT_CODE'].'</td>';
                echo '<td>'. $row['NAME'].'</td>';
                echo '<td>'. $row['QTY_STOCK'].'</td>';
                echo '<td>'. $row['ON_HAND'].'</td>';
                // Disponibilité
                echo '<td>';
                if ($row['ON_HAND'] > 0) {
                    echo '<span class="badge badge-success">Disponible</span>';
                } else {
                    echo '<span class="badge badge-danger">Rupture de stock</span>';
                }
                echo '</td>';
                echo '<td>'. $row['CNAME'].'</td>';
                echo '<td>'. $supplier.'</td>';
                echo '<td>'. ($row['DATE_STOCK_IN'] ?? '-') .'</td>';
                echo '<td align="right"> <div class="btn-group">';
                echo '<a type="button" class="btn btn-primary bg-gradient-primary" href="inv_searchfrm.php?action=edit&id='.$row['PRODUCT_CODE'] . '"><i class="fas fa-fw fa-th-list"></i> View</a>';
                echo '<div class="btn-group">';
                echo '<a type="button" class="btn btn-primary bg-gradient-primary dropdown no-arrow" data-toggle="dropdown" style="color:white;">... <span class="caret"></span></a>';
                echo '<ul class="dropdown-menu text-center" role="menu">';
                echo '<li><a type="button" class="btn btn-warning bg-gradient-warning btn-block" style="border-radius: 0px;" href="inv_edit.php?action=edit&id='.$row['PRODUCT_CODE'].'"><i class="fas fa-fw fa-edit"></i> Modifier</a></li>';
                echo '<li><a type="button" class="btn btn-danger bg-gradient-danger btn-block" style="border-radius: 0px;" href="inv_del.php?id='.$row['PRODUCT_CODE'].'&type=product" onclick="return confirm(\'Voulez-vous vraiment supprimer ce produit de l\\\'inventaire ?\');"><i class="fas fa-fw fa-trash"></i> Supprimer</a></li>';


                echo '</ul></div></div></td>';
                echo '</tr> ';
                        }
?> 
                                    
                                </tbody>
                            </table>
                  </form>
                        </div>
                    </div>
                  </div>

<form method="get" class="form-inline mb-3">
  <label for="start" class="mr-2">Du</label>
  <input type="date" name="start" id="start" class="form-control mr-2" value="<?php echo isset($_GET['start']) ? $_GET['start'] : ''; ?>">
  <label for="end" class="mr-2">au</label>
  <input type="date" name="end" id="end" class="form-control mr-2" value="<?php echo isset($_GET['end']) ? $_GET['end'] : ''; ?>">
  <button type="submit" class="btn btn-primary">Afficher</button>
</form>
<?php
$total = 0;
if (isset($_GET['start']) && isset($_GET['end']) && $_GET['start'] && $_GET['end']) {
    $start = $_GET['start'];
    $end = $_GET['end'];
    $query = "SELECT SUM(GRANDTOTAL) as total FROM transaction WHERE DATE BETWEEN '$start' AND '$end'";
} else {
    $query = "SELECT SUM(GRANDTOTAL) as total FROM transaction";
}
$result = mysqli_query($db, $query);
if ($row = mysqli_fetch_assoc($result)) {
    $total = $row['total'] ? $row['total'] : 0;
}
echo '<div class="alert alert-info mb-4">Coût total des ventes sur la période : <strong>' . number_format($total, 0, ',', ' ') . ' XAF</strong></div>';
?>

<?php
include'../includes/footer.php';
?>
