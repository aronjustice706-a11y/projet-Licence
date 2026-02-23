<?php
include '../includes/connection.php';
// session_start();
include '../includes/sidebar.php';

// Export CSV ventes
if (isset($_POST['export_csv'])) {
    $labels = isset($_POST['labels']) ? json_decode($_POST['labels'], true) : [];
    $data = isset($_POST['data']) ? json_decode($_POST['data'], true) : [];
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=ventes.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Date', 'Total (XAF)']);
    foreach ($labels as $i => $date) {
        fputcsv($output, [$date, $data[$i]]);
    }
    fclose($output);
    exit;
}
// Export CSV top 10
if (isset($_POST['export_csv_top'])) {
    $query = "SELECT PRODUCTS, SUM(QTY) as total_vendu FROM transaction_details GROUP BY PRODUCTS ORDER BY total_vendu DESC LIMIT 10";
    $result = mysqli_query($db, $query);
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=top10_produits.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Produit', 'Quantité vendue']);
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, [$row['PRODUCTS'], $row['total_vendu']]);
    }
    fclose($output);
    exit;
}
// Export CSV stock
if (isset($_POST['export_csv_stock'])) {
    include '../includes/connection.php';
    $query = "SELECT PRODUCT_CODE, NAME, ON_HAND, CNAME FROM product p JOIN category c ON p.CATEGORY_ID=c.CATEGORY_ID";
    $result = mysqli_query($db, $query);
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=stock.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Code produit', 'Nom', 'Quantité en stock', 'Catégorie']);
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, [$row['PRODUCT_CODE'], $row['NAME'], $row['ON_HAND'], $row['CNAME']]);
    }
    fclose($output);
    exit;
}
// Export CSV ruptures
if (isset($_POST['export_csv_rupture'])) {
    include '../includes/connection.php';
    $query = "SELECT PRODUCT_CODE, NAME, CNAME FROM product p JOIN category c ON p.CATEGORY_ID=c.CATEGORY_ID WHERE ON_HAND = 0";
    $result = mysqli_query($db, $query);
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=ruptures.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Code produit', 'Nom', 'Catégorie']);
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, [$row['PRODUCT_CODE'], $row['NAME'], $row['CNAME']]);
    }
    fclose($output);
    exit;
}
// Export CSV seuil critique
if (isset($_POST['export_csv_critique'])) {
    include '../includes/connection.php';
    session_start();
    $query = "SELECT PRODUCT_ID, PRODUCT_CODE, NAME, ON_HAND, CNAME FROM product p JOIN category c ON p.CATEGORY_ID=c.CATEGORY_ID";
    $result = mysqli_query($db, $query);
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=seuil_critique.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Code produit', 'Nom', 'Quantité en stock', 'Catégorie', 'Seuil critique']);
    while ($row = mysqli_fetch_assoc($result)) {
        $seuil = isset($_SESSION['seuils_critiques'][$row['PRODUCT_ID']]) ? $_SESSION['seuils_critiques'][$row['PRODUCT_ID']] : 5;
        if ($row['ON_HAND'] <= $seuil && $row['ON_HAND'] > 0) {
            fputcsv($output, [$row['PRODUCT_CODE'], $row['NAME'], $row['ON_HAND'], $row['CNAME'], $seuil]);
        }
    }
    fclose($output);
    exit;
}

// Gestion seuil critique personnalisé (en session)
if (!isset($_SESSION['seuils_critiques'])) {
    $_SESSION['seuils_critiques'] = [];
}
if (isset($_POST['set_seuil']) && isset($_POST['product_id']) && isset($_POST['seuil_critique'])) {
    $_SESSION['seuils_critiques'][$_POST['product_id']] = intval($_POST['seuil_critique']);
}

// Alerte rupture de stock
$ruptures = [];
$query = "SELECT PRODUCT_CODE, NAME FROM product WHERE ON_HAND = 0";
$result = mysqli_query($db, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $ruptures[] = $row;
}
if (count($ruptures) > 0) {
    echo '<div class="alert alert-danger"><strong>Attention !</strong> Les produits suivants sont en rupture de stock :<ul>';
    foreach ($ruptures as $prod) {
        echo '<li>' . htmlspecialchars($prod['PRODUCT_CODE']) . ' - ' . htmlspecialchars($prod['NAME']) . '</li>';
    }
    echo '</ul></div>';
}
?>
<div class="container-fluid">
  <h2 class="mt-4 mb-4">Rapports & Statistiques</h2>

  <!-- Ventes par période -->
  <div class="card mb-4">
    <div class="card-header">Ventes par période</div>
    <div class="card-body">
      <form method="get" class="form-inline mb-3">
        <label for="start" class="mr-2">Du</label>
        <input type="date" name="start" id="start" class="form-control mr-2" value="<?php echo isset($_GET['start']) ? $_GET['start'] : ''; ?>">
        <label for="end" class="mr-2">au</label>
        <input type="date" name="end" id="end" class="form-control mr-2" value="<?php echo isset($_GET['end']) ? $_GET['end'] : ''; ?>">
        <button type="submit" class="btn btn-primary">Afficher</button>
      </form>
      <?php
      $total = 0;
      $labels = [];
      $data = [];
      if (isset($_GET['start']) && isset($_GET['end']) && $_GET['start'] && $_GET['end']) {
          $start = $_GET['start'];
          $end = $_GET['end'];
          $query = "SELECT SUM(GRANDTOTAL) as total, DATE FROM transaction WHERE DATE BETWEEN '$start' AND '$end' GROUP BY DATE ORDER BY DATE ASC";
      } else {
          $query = "SELECT SUM(GRANDTOTAL) as total, DATE FROM transaction GROUP BY DATE ORDER BY DATE ASC";
      }
      $result = mysqli_query($db, $query);
      $total = 0;
      while ($row = mysqli_fetch_assoc($result)) {
          $labels[] = $row['DATE'];
          $data[] = floatval(str_replace(',', '', $row['total']));
          $total += floatval(str_replace(',', '', $row['total']));
      }
      echo '<div class="alert alert-info mb-0">Total des ventes sur la période : <strong>' . number_format($total, 0, ',', ' ') . ' XAF</strong></div>';
      ?>
      <canvas id="chartVentes" height="80"></canvas>
      <script src="../vendor/chart.js/Chart.min.js"></script>
      <script>
        var ctx = document.getElementById('chartVentes').getContext('2d');
        var chart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
              label: 'Ventes (XAF)',
              data: <?php echo json_encode($data); ?>,
              borderColor: 'rgba(54, 162, 235, 1)',
              backgroundColor: 'rgba(54, 162, 235, 0.2)',
              fill: true
            }]
          },
          options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
          }
        });
      </script>
      <a href="export_ventes.php<?php echo (isset($_GET['start']) && isset($_GET['end']) && $_GET['start'] && $_GET['end']) ? '?start=' . $_GET['start'] . '&end=' . $_GET['end'] : ''; ?>" class="btn btn-success">Exporter les ventes (CSV)</a>
    </div>
  </div>

  <!-- Produits les plus vendus -->
  <div class="card mb-4">
    <div class="card-header">Top 10 des produits les plus vendus</div>
    <div class="card-body">
      <table class="table table-bordered table-sm">
        <thead>
          <tr>
            <th>Produit</th>
            <th>Quantité vendue</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $query = "SELECT PRODUCTS, SUM(QTY) as total_vendu FROM transaction_details GROUP BY PRODUCTS ORDER BY total_vendu DESC LIMIT 10";
        $result = mysqli_query($db, $query);
        while ($row = mysqli_fetch_assoc($result)) {
          echo '<tr>';
          echo '<td>' . $row['PRODUCTS'] . '</td>';
          echo '<td>' . $row['total_vendu'] . '</td>';
          echo '</tr>';
        }
        ?>
        </tbody>
      </table>
      <a href="export_top10.php" class="btn btn-success">Exporter le top 10 (CSV)</a>
    </div>
  </div>

  <!-- Etat du stock actuel -->
  <div class="card mb-4">
    <div class="card-header">État du stock actuel</div>
    <div class="card-body">
      <table class="table table-bordered table-sm">
        <thead>
          <tr>
            <th>Code produit</th>
            <th>Nom</th>
            <th>Quantité en stock</th>
            <th>Catégorie</th>
            <th>Seuil critique</th>
            <th>Modifier seuil</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $query = "SELECT PRODUCT_ID, PRODUCT_CODE, NAME, ON_HAND, CNAME FROM product p JOIN category c ON p.CATEGORY_ID=c.CATEGORY_ID";
        $result = mysqli_query($db, $query);
        while ($row = mysqli_fetch_assoc($result)) {
          $seuil = isset($_SESSION['seuils_critiques'][$row['PRODUCT_ID']]) ? $_SESSION['seuils_critiques'][$row['PRODUCT_ID']] : 5;
          echo '<tr>';
          echo '<td>' . $row['PRODUCT_CODE'] . '</td>';
          echo '<td>' . $row['NAME'] . '</td>';
          echo '<td>' . $row['ON_HAND'] . '</td>';
          echo '<td>' . $row['CNAME'] . '</td>';
          echo '<td>' . $seuil . '</td>';
          echo '<td><form method="post" class="form-inline"><input type="hidden" name="product_id" value="'.$row['PRODUCT_ID'].'"><input type="number" min="1" name="seuil_critique" value="'.$seuil.'" class="form-control form-control-sm mr-2" style="width:70px;"><button type="submit" name="set_seuil" class="btn btn-sm btn-primary">OK</button></form></td>';
          echo '</tr>';
        }
        ?>
        </tbody>
      </table>
      <a href="export_stock.php" class="btn btn-success">Exporter le stock (CSV)</a>
    </div>
  </div>

  <!-- Produits en rupture de stock -->
  <div class="card mb-4">
    <div class="card-header">Produits en rupture de stock</div>
    <div class="card-body">
      <table class="table table-bordered table-sm">
        <thead>
          <tr>
            <th>Code produit</th>
            <th>Nom</th>
            <th>Catégorie</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $query = "SELECT PRODUCT_CODE, NAME, CNAME FROM product p JOIN category c ON p.CATEGORY_ID=c.CATEGORY_ID WHERE ON_HAND = 0";
        $result = mysqli_query($db, $query);
        while ($row = mysqli_fetch_assoc($result)) {
          echo '<tr>';
          echo '<td>' . $row['PRODUCT_CODE'] . '</td>';
          echo '<td>' . $row['NAME'] . '</td>';
          echo '<td>' . $row['CNAME'] . '</td>';
          echo '</tr>';
        }
        ?>
        </tbody>
      </table>
      <a href="export_rupture.php" class="btn btn-success">Exporter ruptures (CSV)</a>
    </div>
  </div>

  <!-- Produits sous le seuil critique -->
  <div class="card mb-4">
    <div class="card-header">Produits sous le seuil critique</div>
    <div class="card-body">
      <table class="table table-bordered table-sm">
        <thead>
          <tr>
            <th>Code produit</th>
            <th>Nom</th>
            <th>Quantité en stock</th>
            <th>Catégorie</th>
            <th>Seuil critique</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $query = "SELECT PRODUCT_ID, PRODUCT_CODE, NAME, ON_HAND, CNAME FROM product p JOIN category c ON p.CATEGORY_ID=c.CATEGORY_ID";
        $result = mysqli_query($db, $query);
        while ($row = mysqli_fetch_assoc($result)) {
          $seuil = isset($_SESSION['seuils_critiques'][$row['PRODUCT_ID']]) ? $_SESSION['seuils_critiques'][$row['PRODUCT_ID']] : 5;
          if ($row['ON_HAND'] <= $seuil && $row['ON_HAND'] > 0) {
            echo '<tr>';
            echo '<td>' . $row['PRODUCT_CODE'] . '</td>';
            echo '<td>' . $row['NAME'] . '</td>';
            echo '<td>' . $row['ON_HAND'] . '</td>';
            echo '<td>' . $row['CNAME'] . '</td>';
            echo '<td>' . $seuil . '</td>';
            echo '</tr>';
          }
        }
        ?>
        </tbody>
      </table>
      <a href="export_critique.php" class="btn btn-success">Exporter seuil critique (CSV)</a>
    </div>
  </div>

</div>
<?php
include '../includes/footer.php';
?> 