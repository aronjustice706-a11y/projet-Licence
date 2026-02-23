<?php
include '../includes/connection.php';

// Vérifier que l'utilisateur est connecté et est admin
session_start();
if (!isset($_SESSION['MEMBER_ID'])) {
    header('Location: login.php');
    exit();
}

// Vérifier le type de compte
$query = 'SELECT ID, t.TYPE
          FROM users u
          JOIN type t ON t.TYPE_ID=u.TYPE_ID WHERE ID = '.$_SESSION['MEMBER_ID'].'';
$result = mysqli_query($db, $query) or die (mysqli_error($db));

while ($row = mysqli_fetch_assoc($result)) {
    $Aa = $row['TYPE'];
}

// Seuls les admins peuvent activer/désactiver des employés
if ($Aa != 'Admin') {
    echo '<script type="text/javascript">
            alert("Accès refusé ! Seuls les administrateurs peuvent activer/désactiver des employés.");
            window.location = "employee.php";
          </script>';
    exit();
}

// Vérifier les paramètres
if (!isset($_GET['action']) || !isset($_GET['id'])) {
    echo '<script type="text/javascript">
            alert("Paramètres manquants.");
            window.location = "employee.php";
          </script>';
    exit();
}

$action = $_GET['action'];
$employee_id = $_GET['id'];

// Valider l'action
if ($action !== 'activate' && $action !== 'deactivate') {
    echo '<script type="text/javascript">
            alert("Action invalide.");
            window.location = "employee.php";
          </script>';
    exit();
}

// Déterminer le nouveau statut
$new_status = ($action === 'activate') ? 'ACTIVE' : 'INACTIVE';

// Mettre à jour le statut de l'employé
$update_query = "UPDATE employee SET STATUS = '$new_status' WHERE EMPLOYEE_ID = $employee_id";
$result = mysqli_query($db, $update_query);

if ($result) {
    $status_text = ($action === 'activate') ? 'activé' : 'désactivé';
    echo '<script type="text/javascript">
            alert("Employé '.$status_text.' avec succès.");
            window.location = "employee.php";
          </script>';
} else {
    echo '<script type="text/javascript">
            alert("Erreur lors de la mise à jour du statut : ' . mysqli_error($db) . '");
            window.location = "employee.php";
          </script>';
}
?> 