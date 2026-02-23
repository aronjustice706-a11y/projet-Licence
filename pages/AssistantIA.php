<?php
include '../includes/connection.php';
header('Content-Type: application/json');

$question = isset($_POST['question']) ? strtolower(trim($_POST['question'])) : '';
$response = "Je n'ai pas compris votre question. Essayez par exemple : 'stock P001', 'rupture', 'ventes', 'produits sous seuil', 'prix P001', 'top ventes', 'clients', 'fournisseurs', 'catégorie CPU', etc.";

// 1. Produits disponibles
if (preg_match('/produits? (disponible|en stock)/', $question) || $question === 'produits disponibles') {
    $query = "SELECT NAME FROM product WHERE ON_HAND > 0";
    $result = mysqli_query($db, $query);
    $produits = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $produits[] = $row['NAME'];
    }
    $response = count($produits) ? "Produits disponibles :\n- " . implode("\n- ", $produits) : "Aucun produit disponible.";
}
// 2. Prix d'un produit
elseif (preg_match('/prix (\w+)/', $question, $m)) {
    $code = mysqli_real_escape_string($db, strtoupper($m[1]));
    $query = "SELECT NAME, PRICE FROM product WHERE PRODUCT_CODE = '$code' OR NAME LIKE '%$code%'";
    $result = mysqli_query($db, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        $response = "Prix de " . $row['NAME'] . " : " . number_format($row['PRICE'], 0, ',', ' ') . " XAF";
    } else {
        $response = "Produit $code introuvable.";
    }
}
// 3. Produits coûtant plus de X XAF
elseif (preg_match('/produits? (cher|coûtant|coutant) plus de (\d+)/', $question, $m)) {
    $prix = intval($m[2]);
    $query = "SELECT NAME, PRICE FROM product WHERE PRICE > $prix";
    $result = mysqli_query($db, $query);
    $produits = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $produits[] = $row['NAME'] . " (" . number_format($row['PRICE'], 0, ',', ' ') . " XAF)";
    }
    $response = count($produits) ? "Produits coûtant plus de $prix XAF :\n- " . implode("\n- ", $produits) : "Aucun produit ne coûte plus de $prix XAF.";
}
// 4. Produits d'une catégorie
elseif (preg_match('/cat[ée]gorie (.+)/', $question, $m)) {
    $cat = mysqli_real_escape_string($db, ucfirst($m[1]));
    $query = "SELECT p.NAME FROM product p JOIN category c ON p.CATEGORY_ID=c.CATEGORY_ID WHERE c.CNAME LIKE '%$cat%'";
    $result = mysqli_query($db, $query);
    $produits = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $produits[] = $row['NAME'];
    }
    $response = count($produits) ? "Produits de la catégorie $cat :\n- " . implode("\n- ", $produits) : "Aucun produit trouvé dans la catégorie $cat.";
}
// 5. Produits avec stock < X
elseif (preg_match('/stock < ?(\d+)/', $question, $m)) {
    $seuil = intval($m[1]);
    $query = "SELECT NAME, ON_HAND FROM product WHERE ON_HAND < $seuil AND ON_HAND > 0";
    $result = mysqli_query($db, $query);
    $produits = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $produits[] = $row['NAME'] . " (" . $row['ON_HAND'] . ")";
    }
    $response = count($produits) ? "Produits avec stock < $seuil :\n- " . implode("\n- ", $produits) : "Aucun produit avec stock < $seuil.";
}
// 6. Produits en stock aujourd'hui
elseif (strpos($question, 'stock aujourd') !== false) {
    $query = "SELECT NAME FROM product WHERE ON_HAND > 0";
    $result = mysqli_query($db, $query);
    $produits = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $produits[] = $row['NAME'];
    }
    $response = count($produits) ? "Produits en stock aujourd'hui :\n- " . implode("\n- ", $produits) : "Aucun produit en stock aujourd'hui.";
}
// 7. Produits ajoutés récemment
elseif (strpos($question, 'ajout') !== false || strpos($question, 'récemment') !== false) {
    $query = "SELECT NAME, DATE_STOCK_IN FROM product ORDER BY DATE_STOCK_IN DESC LIMIT 5";
    $result = mysqli_query($db, $query);
    $produits = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $produits[] = $row['NAME'] . " (" . $row['DATE_STOCK_IN'] . ")";
    }
    $response = count($produits) ? "Produits ajoutés récemment :\n- " . implode("\n- ", $produits) : "Aucun produit récent.";
}
// 8. Nombre de ventes aujourd'hui/semaine/mois
elseif (preg_match('/ventes (aujourd|jour)/', $question)) {
    $today = date('Y-m-d');
    $query = "SELECT COUNT(*) as nb FROM transaction WHERE DATE LIKE '$today%'";
    $result = mysqli_query($db, $query);
    $row = mysqli_fetch_assoc($result);
    $response = "Nombre de ventes aujourd'hui : " . $row['nb'];
}
elseif (preg_match('/ventes (semaine)/', $question)) {
    $week = date('W');
    $year = date('Y');
    $query = "SELECT COUNT(*) as nb FROM transaction WHERE YEAR(DATE) = $year AND WEEK(DATE, 1) = $week";
    $result = mysqli_query($db, $query);
    $row = mysqli_fetch_assoc($result);
    $response = "Nombre de ventes cette semaine : " . $row['nb'];
}
elseif (preg_match('/ventes (mois|mensuel)/', $question)) {
    $month = date('m');
    $year = date('Y');
    $query = "SELECT COUNT(*) as nb FROM transaction WHERE YEAR(DATE) = $year AND MONTH(DATE) = $month";
    $result = mysqli_query($db, $query);
    $row = mysqli_fetch_assoc($result);
    $response = "Nombre de ventes ce mois-ci : " . $row['nb'];
}
// 9. Chiffre d'affaires du mois
elseif (strpos($question, 'chiffre d\'affaires') !== false || strpos($question, 'ca mois') !== false) {
    $month = date('m');
    $year = date('Y');
    $query = "SELECT SUM(GRANDTOTAL) as total FROM transaction WHERE YEAR(DATE) = $year AND MONTH(DATE) = $month";
    $result = mysqli_query($db, $query);
    $row = mysqli_fetch_assoc($result);
    $response = "Chiffre d'affaires du mois : " . number_format($row['total'], 0, ',', ' ') . " XAF";
}
// 10. Produits les plus vendus
elseif (strpos($question, 'top ventes') !== false || strpos($question, 'plus vendus') !== false) {
    $query = "SELECT PRODUCTS, SUM(QTY) as total_vendu FROM transaction_details GROUP BY PRODUCTS ORDER BY total_vendu DESC LIMIT 5";
    $result = mysqli_query($db, $query);
    $produits = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $produits[] = $row['PRODUCTS'] . " (" . $row['total_vendu'] . ")";
    }
    $response = count($produits) ? "Top produits vendus :\n- " . implode("\n- ", $produits) : "Aucune vente enregistrée.";
}
// 11. Nombre de clients
elseif (strpos($question, 'clients') !== false && strpos($question, 'plus') === false) {
    $query = "SELECT COUNT(*) as nb FROM customer";
    $result = mysqli_query($db, $query);
    $row = mysqli_fetch_assoc($result);
    $response = "Nombre de clients enregistrés : " . $row['nb'];
}
// 12. Clients ayant acheté le plus
elseif (strpos($question, 'clients') !== false && strpos($question, 'plus') !== false) {
    $query = "SELECT c.FIRST_NAME, c.LAST_NAME, COUNT(t.TRANS_ID) as nb FROM transaction t JOIN customer c ON t.CUST_ID=c.CUST_ID GROUP BY t.CUST_ID ORDER BY nb DESC LIMIT 5";
    $result = mysqli_query($db, $query);
    $clients = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $clients[] = $row['FIRST_NAME'] . ' ' . $row['LAST_NAME'] . ' (' . $row['nb'] . ' achats)';
    }
    $response = count($clients) ? "Top clients :\n- " . implode("\n- ", $clients) : "Aucun client trouvé.";
}
// 13. Fournisseurs disponibles
elseif (strpos($question, 'fournisseurs') !== false) {
    $query = "SELECT COMPANY_NAME FROM supplier";
    $result = mysqli_query($db, $query);
    $fournisseurs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $fournisseurs[] = $row['COMPANY_NAME'];
    }
    $response = count($fournisseurs) ? "Fournisseurs :\n- " . implode("\n- ", $fournisseurs) : "Aucun fournisseur trouvé.";
}
// 14. Produits d'un fournisseur
elseif (preg_match('/produits? (de|par|fournis par) (.+)/', $question, $m)) {
    $f = mysqli_real_escape_string($db, $m[2]);
    $query = "SELECT p.NAME FROM product p JOIN supplier s ON p.SUPPLIER_ID=s.SUPPLIER_ID WHERE s.COMPANY_NAME LIKE '%$f%'";
    $result = mysqli_query($db, $query);
    $produits = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $produits[] = $row['NAME'];
    }
    $response = count($produits) ? "Produits fournis par $f :\n- " . implode("\n- ", $produits) : "Aucun produit trouvé pour ce fournisseur.";
}
// 15. Nombre d'employés
elseif (strpos($question, 'employés') !== false || strpos($question, 'employes') !== false) {
    $query = "SELECT COUNT(*) as nb FROM employee";
    $result = mysqli_query($db, $query);
    $row = mysqli_fetch_assoc($result);
    $response = "Nombre d'employés : " . $row['nb'];
}
// 16. Liste des managers/cashiers
elseif (strpos($question, 'manager') !== false || strpos($question, 'cashier') !== false) {
    $job = strpos($question, 'manager') !== false ? 'Manager' : 'Cashier';
    $query = "SELECT FIRST_NAME, LAST_NAME FROM employee e JOIN job j ON e.JOB_ID=j.JOB_ID WHERE j.JOB_TITLE = '$job'";
    $result = mysqli_query($db, $query);
    $emps = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $emps[] = $row['FIRST_NAME'] . ' ' . $row['LAST_NAME'];
    }
    $response = count($emps) ? "$job(s) :\n- " . implode("\n- ", $emps) : "Aucun $job trouvé.";
}
// 17. Produits sous le seuil critique
elseif (strpos($question, 'seuil') !== false) {
    $query = "SELECT NAME, ON_HAND FROM product WHERE ON_HAND <= 5 AND ON_HAND > 0";
    $result = mysqli_query($db, $query);
    $produits = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $produits[] = $row['NAME'] . " (" . $row['ON_HAND'] . ")";
    }
    $response = count($produits) ? "Produits sous le seuil critique :\n- " . implode("\n- ", $produits) : "Aucun produit sous le seuil critique.";
}
// 18. Produits vendus aujourd'hui
elseif (strpos($question, 'vendus aujourd') !== false) {
    $today = date('Y-m-d');
    $query = "SELECT PRODUCTS, SUM(QTY) as qte FROM transaction_details td JOIN transaction t ON td.TRANS_D_ID=t.TRANS_D_ID WHERE t.DATE LIKE '$today%' GROUP BY PRODUCTS";
    $result = mysqli_query($db, $query);
    $produits = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $produits[] = $row['PRODUCTS'] . " (" . $row['qte'] . ")";
    }
    $response = count($produits) ? "Produits vendus aujourd'hui :\n- " . implode("\n- ", $produits) : "Aucun produit vendu aujourd'hui.";
}
// 19. Stock total d'une catégorie
elseif (preg_match('/stock total (de|cat[ée]gorie) (.+)/', $question, $m)) {
    $cat = mysqli_real_escape_string($db, ucfirst($m[2]));
    $query = "SELECT SUM(ON_HAND) as total FROM product p JOIN category c ON p.CATEGORY_ID=c.CATEGORY_ID WHERE c.CNAME LIKE '%$cat%'";
    $result = mysqli_query($db, $query);
    $row = mysqli_fetch_assoc($result);
    $response = "Stock total de la catégorie $cat : " . ($row['total'] ?? 0);
}
// 20. Produits jamais vendus
elseif (strpos($question, 'jamais vendu') !== false) {
    $query = "SELECT NAME FROM product WHERE PRODUCT_CODE NOT IN (SELECT DISTINCT PRODUCTS FROM transaction_details)";
    $result = mysqli_query($db, $query);
    $produits = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $produits[] = $row['NAME'];
    }
    $response = count($produits) ? "Produits jamais vendus :\n- " . implode("\n- ", $produits) : "Tous les produits ont été vendus au moins une fois.";
}
// Fallback : rupture, stock produit, ventes, seuil critique (déjà gérés plus haut)
elseif (strpos($question, 'rupture') !== false) {
    $query = "SELECT NAME FROM product WHERE ON_HAND = 0";
    $result = mysqli_query($db, $query);
    $produits = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $produits[] = $row['NAME'];
    }
    $response = count($produits) ? "Produits en rupture de stock :\n- " . implode("\n- ", $produits) : "Aucun produit n'est en rupture de stock.";
}
elseif (preg_match('/stock (\w+)/', $question, $matches)) {
    $code = mysqli_real_escape_string($db, strtoupper($matches[1]));
    $query = "SELECT NAME, ON_HAND FROM product WHERE PRODUCT_CODE = '$code'";
    $result = mysqli_query($db, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        $response = "Stock actuel de " . $row['NAME'] . " ($code) : " . $row['ON_HAND'];
    } else {
        $response = "Produit $code introuvable.";
    }
}
elseif (strpos($question, 'ventes') !== false) {
    $query = "SELECT SUM(GRANDTOTAL) as total FROM transaction";
    $result = mysqli_query($db, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        $response = "Total des ventes : " . number_format($row['total'], 0, ',', ' ') . " XAF";
    }
}
// Chiffre d'affaires global
elseif (strpos($question, "chiffre d'affaire") !== false || strpos($question, "chiffre d’affaires") !== false || strpos($question, "chiffre d affaire") !== false) {
    $query = "SELECT SUM(GRANDTOTAL) as total FROM transaction";
    $result = mysqli_query($db, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        $response = "Le chiffre d'affaires total de l'entreprise est de : " . number_format($row['total'], 0, ',', ' ') . " XAF";
    } else {
        $response = "Impossible de calculer le chiffre d'affaires.";
    }
}
// Fournisseurs : qui sont nos fournisseur(s), leurs noms
elseif (preg_match('/(qui sont nos fournisseur|leurs noms|fournisseurs\??)/', $question)) {
    $query = "SELECT COMPANY_NAME FROM supplier";
    $result = mysqli_query($db, $query);
    $fournisseurs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $fournisseurs[] = $row['COMPANY_NAME'];
    }
    $response = count($fournisseurs) ? "Fournisseurs :\n- " . implode("\n- ", $fournisseurs) : "Aucun fournisseur trouvé.";
}
// Clients : qui sont nos client(s), leurs noms
elseif (preg_match('/(qui sont nos client|leurs noms|clients\??)/', $question)) {
    $query = "SELECT FIRST_NAME, LAST_NAME FROM customer";
    $result = mysqli_query($db, $query);
    $clients = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $clients[] = $row['FIRST_NAME'] . ' ' . $row['LAST_NAME'];
    }
    $response = count($clients) ? "Clients :\n- " . implode("\n- ", $clients) : "Aucun client trouvé.";
}
// Produits : montre moi nos differents produit(s)
elseif (preg_match('/(montre|affiche|liste).*(produit|différents produits)/', $question)) {
    $query = "SELECT NAME FROM product";
    $result = mysqli_query($db, $query);
    $produits = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $produits[] = $row['NAME'];
    }
    $response = count($produits) ? "Produits :\n- " . implode("\n- ", $produits) : "Aucun produit trouvé.";
}
// Réapprovisionnement : faut'il deja reaprovisionner, quel stock de produit
elseif (preg_match('/(faut.?il.*reaprovisionner|quel stock de produit|produits? sous seuil|stock faible)/', $question)) {
    $query = "SELECT NAME, ON_HAND FROM product WHERE ON_HAND <= 5 AND ON_HAND > 0";
    $result = mysqli_query($db, $query);
    $produits = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $produits[] = $row['NAME'] . " (" . $row['ON_HAND'] . ")";
    }
    $response = count($produits) ? "Produits à réapprovisionner (stock faible) :\n- " . implode("\n- ", $produits) : "Aucun produit à réapprovisionner pour l'instant.";
}
// Mise à jour du stock - format: "ajouter stock P001 50" ou "mettre à jour stock P001 100"
elseif (preg_match('/(ajouter|mettre à jour|update|modifier).*stock\s+(\w+)\s+(\d+)/', $question, $matches)) {
    $action = strtolower($matches[1]);
    $product_code = mysqli_real_escape_string($db, strtoupper($matches[2]));
    $new_quantity = intval($matches[3]);
    
    // Vérifier si le produit existe
    $check_query = "SELECT PRODUCT_ID, NAME, ON_HAND FROM product WHERE PRODUCT_CODE = '$product_code' LIMIT 1";
    $check_result = mysqli_query($db, $check_query);
    
    if ($check_result && mysqli_num_rows($check_result) > 0) {
        $product_data = mysqli_fetch_assoc($check_result);
        $product_id = $product_data['PRODUCT_ID'];
        $current_stock = intval($product_data['ON_HAND']);
        $product_name = $product_data['NAME'];
        
        // Mettre à jour le stock
        $update_query = "UPDATE product SET ON_HAND = $new_quantity WHERE PRODUCT_ID = $product_id";
        $update_result = mysqli_query($db, $update_query);
        
        if ($update_result) {
            $response = "✅ Stock mis à jour avec succès !\n\nProduit : $product_name ($product_code)\nAncien stock : $current_stock\nNouveau stock : $new_quantity";
        } else {
            $response = "❌ Erreur lors de la mise à jour du stock : " . mysqli_error($db);
        }
    } else {
        $response = "❌ Produit avec le code '$product_code' introuvable. Vérifiez le code produit.";
    }
}
// Ajouter du stock - format: "ajouter 50 P001" ou "ajouter stock P001 50"
elseif (preg_match('/(ajouter|ajoute)\s+(\d+)\s+(\w+)/', $question, $matches)) {
    $quantity_to_add = intval($matches[2]);
    $product_code = mysqli_real_escape_string($db, strtoupper($matches[3]));
    
    // Vérifier si le produit existe
    $check_query = "SELECT PRODUCT_ID, NAME, ON_HAND FROM product WHERE PRODUCT_CODE = '$product_code' LIMIT 1";
    $check_result = mysqli_query($db, $check_query);
    
    if ($check_result && mysqli_num_rows($check_result) > 0) {
        $product_data = mysqli_fetch_assoc($check_result);
        $product_id = $product_data['PRODUCT_ID'];
        $current_stock = intval($product_data['ON_HAND']);
        $product_name = $product_data['NAME'];
        $new_stock = $current_stock + $quantity_to_add;
        
        // Mettre à jour le stock
        $update_query = "UPDATE product SET ON_HAND = $new_stock WHERE PRODUCT_ID = $product_id";
        $update_result = mysqli_query($db, $update_query);
        
        if ($update_result) {
            $response = "✅ Stock ajouté avec succès !\n\nProduit : $product_name ($product_code)\nAncien stock : $current_stock\nQuantité ajoutée : $quantity_to_add\nNouveau stock : $new_stock";
        } else {
            $response = "❌ Erreur lors de l'ajout du stock : " . mysqli_error($db);
        }
    } else {
        $response = "❌ Produit avec le code '$product_code' introuvable. Vérifiez le code produit.";
    }
}
// Réapprovisionner un produit spécifique - format: "réapprovisionner P001" ou "reapprovisionner P001"
elseif (preg_match('/(réapprovisionner|reapprovisionner)\s+(\w+)/', $question, $matches)) {
    $product_code = mysqli_real_escape_string($db, strtoupper($matches[2]));
    
    // Vérifier si le produit existe
    $check_query = "SELECT PRODUCT_ID, NAME, ON_HAND FROM product WHERE PRODUCT_CODE = '$product_code' LIMIT 1";
    $check_result = mysqli_query($db, $check_query);
    
    if ($check_result && mysqli_num_rows($check_result) > 0) {
        $product_data = mysqli_fetch_assoc($check_result);
        $product_id = $product_data['PRODUCT_ID'];
        $current_stock = intval($product_data['ON_HAND']);
        $product_name = $product_data['NAME'];
        
        // Réapprovisionner avec 50 unités par défaut
        $new_stock = $current_stock + 50;
        $update_query = "UPDATE product SET ON_HAND = $new_stock WHERE PRODUCT_ID = $product_id";
        $update_result = mysqli_query($db, $update_query);
        
        if ($update_result) {
            $response = "✅ Produit réapprovisionné avec succès !\n\nProduit : $product_name ($product_code)\nAncien stock : $current_stock\nQuantité ajoutée : 50\nNouveau stock : $new_stock";
        } else {
            $response = "❌ Erreur lors du réapprovisionnement : " . mysqli_error($db);
        }
    } else {
        $response = "❌ Produit avec le code '$product_code' introuvable. Vérifiez le code produit.";
    }
}
// Recherche intelligente pour toute question non reconnue
else {
    // Analyser la question pour extraire des mots-clés
    $keywords = explode(' ', $question);
    $relevant_results = [];
    
    // Recherche dans les produits
    foreach($keywords as $keyword) {
        if (strlen($keyword) > 2) { // Ignorer les mots trop courts
            $search_query = "SELECT DISTINCT NAME, PRODUCT_CODE, ON_HAND, PRICE, DESCRIPTION FROM product WHERE 
                           NAME LIKE '%$keyword%' OR 
                           PRODUCT_CODE LIKE '%$keyword%' OR 
                           DESCRIPTION LIKE '%$keyword%'";
            $search_result = mysqli_query($db, $search_query);
            while ($row = mysqli_fetch_assoc($search_result)) {
                $relevant_results['products'][] = $row;
            }
        }
    }
    
    // Recherche dans les catégories
    foreach($keywords as $keyword) {
        if (strlen($keyword) > 2) {
            $cat_query = "SELECT DISTINCT c.CNAME, COUNT(p.PRODUCT_ID) as nb_produits, SUM(p.ON_HAND) as total_stock 
                         FROM category c LEFT JOIN product p ON c.CATEGORY_ID = p.CATEGORY_ID 
                         WHERE c.CNAME LIKE '%$keyword%' GROUP BY c.CATEGORY_ID";
            $cat_result = mysqli_query($db, $cat_query);
            while ($row = mysqli_fetch_assoc($cat_result)) {
                $relevant_results['categories'][] = $row;
            }
        }
    }
    
    // Recherche dans les fournisseurs
    foreach($keywords as $keyword) {
        if (strlen($keyword) > 2) {
            $sup_query = "SELECT DISTINCT s.COMPANY_NAME, COUNT(p.PRODUCT_ID) as nb_produits 
                         FROM supplier s LEFT JOIN product p ON s.SUPPLIER_ID = p.SUPPLIER_ID 
                         WHERE s.COMPANY_NAME LIKE '%$keyword%' GROUP BY s.SUPPLIER_ID";
            $sup_result = mysqli_query($db, $sup_query);
            while ($row = mysqli_fetch_assoc($sup_result)) {
                $relevant_results['suppliers'][] = $row;
            }
        }
    }
    
    // Recherche dans les clients
    foreach($keywords as $keyword) {
        if (strlen($keyword) > 2) {
            $cust_query = "SELECT DISTINCT FIRST_NAME, LAST_NAME, PHONE_NUMBER FROM customer 
                          WHERE FIRST_NAME LIKE '%$keyword%' OR LAST_NAME LIKE '%$keyword%'";
            $cust_result = mysqli_query($db, $cust_query);
            while ($row = mysqli_fetch_assoc($cust_result)) {
                $relevant_results['customers'][] = $row;
            }
        }
    }
    
    // Recherche dans les employés
    foreach($keywords as $keyword) {
        if (strlen($keyword) > 2) {
            $emp_query = "SELECT DISTINCT e.FIRST_NAME, e.LAST_NAME, e.EMAIL, j.JOB_TITLE 
                         FROM employee e LEFT JOIN job j ON e.JOB_ID = j.JOB_ID 
                         WHERE e.FIRST_NAME LIKE '%$keyword%' OR e.LAST_NAME LIKE '%$keyword%' OR j.JOB_TITLE LIKE '%$keyword%'";
            $emp_result = mysqli_query($db, $emp_query);
            while ($row = mysqli_fetch_assoc($emp_result)) {
                $relevant_results['employees'][] = $row;
            }
        }
    }
    
    // Recherche dans les transactions
    foreach($keywords as $keyword) {
        if (strlen($keyword) > 2) {
            $trans_query = "SELECT DISTINCT t.DATE, t.GRANDTOTAL, c.FIRST_NAME, c.LAST_NAME 
                           FROM transaction t LEFT JOIN customer c ON t.CUST_ID = c.CUST_ID 
                           WHERE t.DATE LIKE '%$keyword%' OR c.FIRST_NAME LIKE '%$keyword%' OR c.LAST_NAME LIKE '%$keyword%'
                           ORDER BY t.DATE DESC LIMIT 5";
            $trans_result = mysqli_query($db, $trans_query);
            while ($row = mysqli_fetch_assoc($trans_result)) {
                $relevant_results['transactions'][] = $row;
            }
        }
    }
    
    // Construire la réponse intelligente
    if (!empty($relevant_results)) {
        $response = "🔍 Voici ce que j'ai trouvé pour votre question :\n\n";
        
        if (isset($relevant_results['products'])) {
            $response .= "📦 **Produits trouvés :**\n";
            foreach($relevant_results['products'] as $product) {
                $response .= "• " . $product['NAME'] . " (" . $product['PRODUCT_CODE'] . ") - Stock: " . $product['ON_HAND'] . " - Prix: " . number_format($product['PRICE']) . " XAF\n";
            }
            $response .= "\n";
        }
        
        if (isset($relevant_results['categories'])) {
            $response .= "📂 **Catégories trouvées :**\n";
            foreach($relevant_results['categories'] as $cat) {
                $response .= "• " . $cat['CNAME'] . " (" . $cat['nb_produits'] . " produits, stock total: " . $cat['total_stock'] . ")\n";
            }
            $response .= "\n";
        }
        
        if (isset($relevant_results['suppliers'])) {
            $response .= "🏢 **Fournisseurs trouvés :**\n";
            foreach($relevant_results['suppliers'] as $sup) {
                $response .= "• " . $sup['COMPANY_NAME'] . " (" . $sup['nb_produits'] . " produits)\n";
            }
            $response .= "\n";
        }
        
        if (isset($relevant_results['customers'])) {
            $response .= "👥 **Clients trouvés :**\n";
            foreach($relevant_results['customers'] as $cust) {
                $response .= "• " . $cust['FIRST_NAME'] . " " . $cust['LAST_NAME'] . " (" . $cust['PHONE_NUMBER'] . ")\n";
            }
            $response .= "\n";
        }
        
        if (isset($relevant_results['employees'])) {
            $response .= "👨‍💼 **Employés trouvés :**\n";
            foreach($relevant_results['employees'] as $emp) {
                $response .= "• " . $emp['FIRST_NAME'] . " " . $emp['LAST_NAME'] . " (" . $emp['JOB_TITLE'] . ")\n";
            }
            $response .= "\n";
        }
        
        if (isset($relevant_results['transactions'])) {
            $response .= "💰 **Transactions récentes :**\n";
            foreach($relevant_results['transactions'] as $trans) {
                $response .= "• " . $trans['DATE'] . " - " . $trans['FIRST_NAME'] . " " . $trans['LAST_NAME'] . " - " . number_format($trans['GRANDTOTAL']) . " XAF\n";
            }
            $response .= "\n";
        }
        
        $response .= "💡 **Suggestions :**\n";
        $response .= "• Pour plus de détails, précisez votre question\n";
        $response .= "• Essayez : 'stock [code_produit]', 'prix [produit]', 'ventes [période]'\n";
        $response .= "• Ou posez une question spécifique sur un élément trouvé ci-dessus";
        
    } else {
        // Si aucune correspondance trouvée, donner des suggestions
        $response = "🤔 Je n'ai pas trouvé d'informations spécifiques pour votre question.\n\n";
        $response .= "💡 **Suggestions de questions :**\n";
        $response .= "• 'Quels sont nos produits en stock ?'\n";
        $response .= "• 'Combien de ventes avons-nous faites ce mois ?'\n";
        $response .= "• 'Qui sont nos meilleurs clients ?'\n";
        $response .= "• 'Quels produits sont en rupture de stock ?'\n";
        $response .= "• 'Quel est le chiffre d\'affaires total ?'\n";
        $response .= "• 'Combien d\'employés avons-nous ?'\n";
        $response .= "• 'Quels sont nos fournisseurs ?'\n";
        $response .= "• 'Ajouter stock P001 100' (pour mettre à jour le stock)\n";
        $response .= "• 'Réapprovisionner P001' (ajoute 50 unités automatiquement)";
    }
}

echo json_encode(["response" => $response]); 