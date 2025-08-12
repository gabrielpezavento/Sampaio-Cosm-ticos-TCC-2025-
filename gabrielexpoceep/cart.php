<?php
header('Content-Type: application/json');

session_start();

// Configurações do banco de dados
$host = 'localhost';
$dbname = 'sampaio_cosmeticos';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['error' => 'Erro na conexão com o banco de dados: ' . $e->getMessage()]));
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'add':
        addToCart($pdo);
        break;
    case 'get':
        getCart();
        break;
    case 'update':
        updateCartItem();
        break;
    case 'remove':
        removeCartItem();
        break;
    case 'apply_coupon':
        applyCoupon();
        break;
    case 'checkout':
        checkout();
        break;
    default:
        echo json_encode(['error' => 'Ação não especificada']);
        break;
}

function addToCart($pdo) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'] ?? 1;
    
    try {
        // Busca informações do produto
        $stmt = $pdo->prepare("SELECT id, nome, preco, imagem, categoria FROM produtos WHERE id = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$product) {
            throw new Exception("Produto não encontrado.");
        }
        
        // Inicializa o carrinho se não existir
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
            $_SESSION['discount'] = 0;
        }
        
        // Verifica se o produto já está no carrinho
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $productId) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $product['id'],
                'name' => $product['nome'],
                'price' => $product['preco'],
                'image' => $product['imagem'],
                'category' => $product['categoria'],
                'quantity' => $quantity
            ];
        }
        
        echo json_encode(['success' => true, 'message' => 'Produto adicionado ao carrinho!']);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function getCart() {
    $cart = $_SESSION['cart'] ?? [];
    $discount = $_SESSION['discount'] ?? 0;
    
    echo json_encode(['cart' => $cart, 'discount' => $discount]);
}

function updateCartItem() {
    $productId = $_GET['id'];
    $change = intval($_GET['change']);
    
    if (!isset($_SESSION['cart'])) {
        echo json_encode(['error' => 'Carrinho vazio']);
        return;
    }
    
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            $newQuantity = $item['quantity'] + $change;
            
            if ($newQuantity < 1) {
                // Remove o item se a quantidade for menor que 1
                $_SESSION['cart'] = array_filter($_SESSION['cart'], function($i) use ($productId) {
                    return $i['id'] != $productId;
                });
            } else {
                $item['quantity'] = $newQuantity;
            }
            
            break;
        }
    }
    
    getCart();
}

function removeCartItem() {
    $productId = $_GET['id'];
    
    if (!isset($_SESSION['cart'])) {
        echo json_encode(['error' => 'Carrinho vazio']);
        return;
    }
    
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($productId) {
        return $item['id'] != $productId;
    });
    
    getCart();
}

function applyCoupon() {
    $code = strtoupper($_GET['code']);
    
    // Simples validação de cupom (substitua por sua lógica real)
    if ($code === 'PROMO10') {
        $_SESSION['discount'] = calculateDiscount(10);
        echo json_encode(['success' => true, 'discount' => $_SESSION['discount']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Cupom inválido']);
    }
}

function calculateDiscount($percentage) {
    $total = 0;
    foreach ($_SESSION['cart'] ?? [] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total * ($percentage / 100);
}

function checkout() {
    if (empty($_SESSION['cart'])) {
        echo json_encode(['success' => false, 'message' => 'Seu carrinho está vazio']);
        return;
    }
    
    // Aqui você implementaria a lógica de finalização de compra
    // Por exemplo, registrar o pedido no banco de dados
    
    // Limpa o carrinho após a compra
    $_SESSION['cart'] = [];
    $_SESSION['discount'] = 0;
    
    echo json_encode(['success' => true, 'message' => 'Compra finalizada com sucesso!']);
}
?>