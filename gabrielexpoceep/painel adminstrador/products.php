<?php
header('Content-Type: application/json');

require_once 'db_connect.php';

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'add':
            addProduct();
            break;
        case 'edit':
            editProduct();
            break;
        case 'delete':
            deleteProduct();
            break;
        case 'list':
            listProducts();
            break;
        case 'search':
            searchProduct();
            break;
        default:
            throw new Exception('Ação inválida');
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

function addProduct() {
    global $pdo;
    
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $brand = $_POST['brand'] ?? null;
    $category = $_POST['category'];
    
    // Upload da imagem
    $imagePath = uploadImage();
    
    $stmt = $pdo->prepare("INSERT INTO produtos (nome, preco, estoque, marca, categoria, imagem) 
                          VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $price, $stock, $brand, $category, $imagePath]);
    
    echo json_encode(['success' => true, 'message' => 'Produto adicionado com sucesso!']);
}

function editProduct() {
    global $pdo;
    
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $brand = $_POST['brand'] ?? null;
    $category = $_POST['category'];
    
    // Verificar se uma nova imagem foi enviada
    $imagePath = null;
    if (!empty($_FILES['image']['name'])) {
        $imagePath = uploadImage();
    }
    
    // Atualizar produto
    $sql = "UPDATE produtos SET nome = ?, preco = ?, estoque = ?, marca = ?, categoria = ?";
    $params = [$name, $price, $stock, $brand, $category];
    
    if ($imagePath) {
        $sql .= ", imagem = ?";
        $params[] = $imagePath;
    }
    
    $sql .= " WHERE id = ?";
    $params[] = $id;
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    echo json_encode(['success' => true, 'message' => 'Produto atualizado com sucesso!']);
}

function deleteProduct() {
    global $pdo;
    
    $id = $_POST['id'];
    
    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
    $stmt->execute([$id]);
    
    echo json_encode(['success' => true, 'message' => 'Produto removido com sucesso!']);
}

function listProducts() {
    global $pdo;
    
    $stmt = $pdo->query("SELECT * FROM produtos");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($products);
}

function searchProduct() {
    global $pdo;
    
    $name = $_GET['name'];
    
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE nome LIKE ? LIMIT 1");
    $stmt->execute(["%$name%"]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        throw new Exception('Produto não encontrado');
    }
    
    echo json_encode($product);
}

function uploadImage() {
    $targetDir = "uploads/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
    $targetFile = $targetDir . $fileName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
    // Verificar se é uma imagem real
    $check = getimagesize($_FILES['image']['tmp_name']);
    if ($check === false) {
        throw new Exception('O arquivo não é uma imagem.');
    }
    
    // Verificar tamanho do arquivo (max 2MB)
    if ($_FILES['image']['size'] > 2000000) {
        throw new Exception('O arquivo é muito grande (máximo 2MB).');
    }
    
    // Permitir apenas certos formatos
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        throw new Exception('Apenas arquivos JPG, JPEG, PNG e GIF são permitidos.');
    }
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        return $targetFile;
    } else {
        throw new Exception('Erro ao fazer upload da imagem.');
    }
}
?>