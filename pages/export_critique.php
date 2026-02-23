<?php
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