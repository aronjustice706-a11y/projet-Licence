<?php
include '../includes/connection.php';

if (isset($_POST['selected_ids']) && is_array($_POST['selected_ids'])) {
    $escaped_ids = array();
    foreach($_POST['selected_ids'] as $id) {
        $escaped_ids[] = "'" . mysqli_real_escape_string($db, $id) . "'";
    }
    $ids_list = implode(',', $escaped_ids);
    $query = "DELETE FROM product WHERE PRODUCT_CODE IN ($ids_list)";
    mysqli_query($db, $query) or die(mysqli_error($db));
    echo '<script type="text/javascript">alert("Produits supprimés de l\'inventaire avec succès.");window.location = "inventory.php";</script>';
} else {
    echo '<script type="text/javascript">alert("Aucun produit sélectionné.");window.location = "inventory.php";</script>';
}
?> 