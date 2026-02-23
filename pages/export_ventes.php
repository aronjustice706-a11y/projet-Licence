<?php
include '../includes/connection.php';
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
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=ventes.csv');
$output = fopen('php://output', 'w');
fputcsv($output, ['Date', 'Total (XAF)']);
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [$row['DATE'], $row['total']]);
}
fclose($output);
exit; 