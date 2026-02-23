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
	if (!isset($_GET['type'])) {
       echo '<script type="text/javascript">alert("Paramètre type manquant.");window.location = "employee.php";</script>';
       exit();
   }
	if (!isset($_GET['do']) || $_GET['do'] != 1) {
						
    	switch ($_GET['type']) {
    		case 'employee':
    			$query = 'DELETE FROM employee WHERE EMPLOYEE_ID = ' . $_GET['id'];
    			$result = mysqli_query($db, $query) or die(mysqli_error($db));				
            ?>
    			<script type="text/javascript">alert("Employé supprimé avec succès.");window.location = "employee.php";</script>					
            <?php
    			//break;
            }
	}
?>