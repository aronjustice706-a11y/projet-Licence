<?php
include '../includes/connection.php';
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