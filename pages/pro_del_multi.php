<?php
include '../includes/connection.php';
if (isset($_POST['selected_ids']) && is_array($_POST['selected_ids'])) {
    $ids = array_map('intval', $_POST['selected_ids']);
    $ids_list = implode(',', $ids);
    $query = "DELETE FROM product WHERE PRODUCT_ID IN ($ids_list)";
    mysqli_query($db, $query) or die(mysqli_error($db));
    echo '<script type="text/javascript">alert("Produits supprimés avec succès.");window.location = "product.php";</script>';
} else {
    echo '<script type="text/javascript">alert("Aucun produit sélectionné.");window.location = "product.php";</script>';
} 