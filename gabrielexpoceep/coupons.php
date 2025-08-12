<?php
require_once 'config.php';

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'list':
            $stmt = $pdo->query("SELECT * FROM coupons");
            $coupons = $stmt->fetchAll(PDO::FETCH_ASSOC);
            header('Content-Type: application/json');
            echo json_encode($coupons);
            break;

        case 'search':
            $code = $_GET['code'] ?? '';
            if (empty($code)) {
                throw new Exception("Código do cupom é obrigatório.");
            }
            $stmt = $pdo->prepare("SELECT * FROM coupons WHERE code LIKE ?");
            $stmt->execute(['%' . $code . '%']);
            $coupons = $stmt->fetchAll(PDO::FETCH_ASSOC);
            header('Content-Type: application/json');
            echo json_encode($coupons);
            break;

        default:
            throw new Exception("Ação inválida.");
    }
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>