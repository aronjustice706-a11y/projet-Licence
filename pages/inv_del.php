<?php
include'../includes/connection.php';

          
	if (!isset($_GET['do']) || $_GET['do'] != 1) {
		
		// Vérifier si le paramètre type existe
		if (!isset($_GET['type'])) {
			echo '<script type="text/javascript">alert("Paramètre type manquant.");window.location = "inventory.php";</script>';
			exit();
		}
						
    	switch ($_GET['type']) {
    		case 'product':
    			$query = 'DELETE FROM product WHERE PRODUCT_CODE = "' . $_GET['id'] . '"';
    			$result = mysqli_query($db, $query) or die(mysqli_error($db));				
            ?>
    			<script type="text/javascript">alert("Produit supprimé avec succès.");window.location = "inventory.php";</script>					
            <?php
    			//break;
            }
	}
?>