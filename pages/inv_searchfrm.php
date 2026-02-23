<?php
include'../includes/connection.php';
include'../includes/sidebar.php';

$query = 'SELECT PRODUCT_CODE, NAME, SUM(QTY_STOCK) AS QTY_STOCK, SUM(ON_HAND) AS ON_HAND, CNAME, SUPPLIER_ID, MAX(DATE_STOCK_IN) AS DATE_STOCK_IN FROM product p JOIN category c ON p.CATEGORY_ID=c.CATEGORY_ID WHERE PRODUCT_CODE = "'.$_GET['id'].'" GROUP BY PRODUCT_CODE';
$result = mysqli_query($db, $query) or die (mysqli_error($db));

?>
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h4 class="m-2 font-weight-bold text-primary">Inventaire pour : <?php echo htmlspecialchars($_GET['id']); ?></h4>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
          <tr>
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
          echo '<td align="right">';
          echo '<a type="button" class="btn btn-warning bg-gradient-warning" href="inv_edit.php?action=edit&id='.$row['PRODUCT_CODE'].'"><i class="fas fa-fw fa-edit"></i> Modifier</a>';
          echo '</td>';
          echo '</tr>';
        }
        ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php
include'../includes/footer.php';
?>
