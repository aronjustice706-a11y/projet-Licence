<?php
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