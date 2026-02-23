<?php
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