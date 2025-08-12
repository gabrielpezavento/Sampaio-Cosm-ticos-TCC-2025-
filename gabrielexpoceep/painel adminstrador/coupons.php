<?php
header('Content-Type: application/json');

require_once 'db_connect.php';

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'add':
            addCoupon();
            break;
        case 'delete':
            deleteCoupon();
            break;
        case 'list':
            listCoupons();
            break;
        default:
            throw new Exception('Ação inválida');
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

function addCoupon() {
    global $pdo;
    
    $code = $_POST['code'];
    $discount = $_POST['discount'];
    $type = $_POST['type'];
    $expiresAt = $_POST['expires_at'];
    $maxUses = $_POST['max_uses'] ?? null;
    
    $stmt = $pdo->prepare("INSERT INTO cupons (codigo, desconto, tipo, valido_ate, usos_maximos) 
                          VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$code, $discount, $type, $expiresAt, $maxUses]);
    
    echo json_encode(['success' => true, 'message' => 'Cupom adicionado com sucesso!']);
}

function deleteCoupon() {
    global $pdo;
    
    $id = $_GET['id'];
    
    $stmt = $pdo->prepare("DELETE FROM cupons WHERE id = ?");
    $stmt->execute([$id]);
    
    echo json_encode(['success' => true, 'message' => 'Cupom removido com sucesso!']);
}

function listCoupons() {
    global $pdo;
    
    $stmt = $pdo->query("SELECT * FROM cupons");
    $coupons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($coupons);
}
?>