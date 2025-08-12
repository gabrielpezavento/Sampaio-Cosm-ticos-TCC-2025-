<?php
require_once 'config.php';
session_start();

// Comente ou remova esta parte para desenvolvimento
// if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
//     header('Location: login.php');
//     exit;
// }

// Ou defina manualmente a sessão para desenvolvimento
$_SESSION['admin_logged_in'] = true;

// Processar ações do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    try {
        switch ($action) {
            case 'add_product':
                // Validação dos dados
                $requiredFields = ['name', 'stock', 'price', 'category'];
                foreach ($requiredFields as $field) {
                    if (empty($_POST[$field])) {
                        throw new Exception("O campo $field é obrigatório.");
                    }
                }
                
                // Processar upload da imagem
                $imagePath = processImageUpload($_FILES['image']);
                
                // Inserir no banco de dados
                $stmt = $pdo->prepare("INSERT INTO products (name, stock, brand, price, category, image) 
                                      VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $_POST['name'],
                    $_POST['stock'],
                    $_POST['brand'] ?? null,
                    $_POST['price'],
                    $_POST['category'],
                    $imagePath
                ]);
                
                $_SESSION['success_message'] = 'Produto cadastrado com sucesso!';
                break;
                
            case 'add_promo':
                // Validação adicional para promoções
                if (empty($_POST['original_price'])) {
                    throw new Exception("Preço original é obrigatório para promoções.");
                }
                
                if ($_POST['price'] >= $_POST['original_price']) {
                    throw new Exception("O preço promocional deve ser menor que o original.");
                }
                
                $imagePath = processImageUpload($_FILES['image']);
                
                $stmt = $pdo->prepare("INSERT INTO products (name, stock, brand, price, original_price, category, image) 
                                      VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $_POST['name'],
                    $_POST['stock'],
                    $_POST['brand'] ?? null,
                    $_POST['price'],
                    $_POST['original_price'],
                    $_POST['category'],
                    $imagePath
                ]);
                
                $_SESSION['success_message'] = 'Promoção cadastrada com sucesso!';
                break;
                
            case 'edit_product':
                if (empty($_POST['id'])) {
                    throw new Exception("ID do produto é obrigatório para edição.");
                }
                
                $updateFields = [
                    'name' => $_POST['name'],
                    'stock' => $_POST['stock'],
                    'brand' => $_POST['brand'] ?? null,
                    'price' => $_POST['price'],
                    'original_price' => !empty($_POST['original_price']) ? $_POST['original_price'] : null,
                    'category' => $_POST['category']
                ];
                
                // Se uma nova imagem foi enviada
                if (!empty($_FILES['image']['name'])) {
                    $imagePath = processImageUpload($_FILES['image']);
                    $updateFields['image'] = $imagePath;
                }
                
                // Construir a query dinamicamente
                $setParts = [];
                $values = [];
                foreach ($updateFields as $field => $value) {
                    $setParts[] = "$field = ?";
                    $values[] = $value;
                }
                $values[] = $_POST['id'];
                
                $sql = "UPDATE products SET " . implode(', ', $setParts) . " WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute($values);
                
                $_SESSION['success_message'] = 'Produto atualizado com sucesso!';
                break;
                
            case 'delete_product':
                if (empty($_POST['id'])) {
                    throw new Exception("ID do produto é obrigatório para exclusão.");
                }
                
                // Primeiro obtemos o caminho da imagem para deletar
                $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
                $stmt->execute([$_POST['id']]);
                $product = $stmt->fetch();
                
                if ($product && file_exists($product['image'])) {
                    unlink($product['image']);
                }
                
                // Depois deletamos o produto
                $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
                $stmt->execute([$_POST['id']]);
                
                $_SESSION['success_message'] = 'Produto excluído com sucesso!';
                break;
        }
        
        // Redirecionar para evitar reenvio do formulário
        header('Location: gerenciarprodutos.php');
        exit;
        
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header('Location: gerenciarprodutos.php');
        exit;
    }
}

// Função para processar upload de imagens
function processImageUpload($file) {
    $uploadDir = 'uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Erro no upload da imagem: ' . $file['error']);
    }
    
    // Validar tipo de arquivo
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowedTypes)) {
        throw new Exception('Tipo de arquivo não permitido. Use apenas JPEG, PNG ou GIF.');
    }
    
    // Validar tamanho do arquivo (máximo 2MB)
    if ($file['size'] > 2097152) {
        throw new Exception('O tamanho da imagem não pode exceder 2MB.');
    }
    
    // Gerar nome único para o arquivo
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $extension;
    $destination = $uploadDir . $filename;
    
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new Exception('Falha ao salvar a imagem.');
    }
    
    return $destination;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gerenciamento de Produtos</title>zz
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <a href="index.php" style="cursor: pointer;" class="voltar">⬅️ Voltar</a>
        <h1>Gerenciamento de Produtos</h1>
    </header>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success_message'] ?>
            <?php unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-error">
            <?= $_SESSION['error_message'] ?>
            <?php unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <div class="dashboard-cards">
        <div class="card" onclick="showForm('edit')">
            <i class="fas fa-edit"></i>
            <h4>Edição de Produto</h4>
        </div> 
        <div class="card" onclick="showForm('promo')">
            <i class="fas fa-tag"></i>
            <h4>Cadastro de Promoção</h4>
        </div>  
        <div class="card" onclick="showForm('add')">
            <i class="fas fa-plus"></i>
            <h4>Cadastro de Produto</h4>
        </div>  
        <div class="card" onclick="showForm('delete')">
            <i class="fas fa-trash"></i>
            <h4>Exclusão de Produto</h4>
        </div>
        <div class="card" onclick="showForm('list')">
            <i class="fas fa-list"></i>
            <h4>Listar Produtos</h4>
        </div>
        <!-- Adicionar dentro de <div class="dashboard-cards"> -->
<div class="card" onclick="showForm('coupon')">
    <i class="fas fa-ticket-alt"></i>
    <h4>Cadastro de Cupom</h4>
</div>
<div class="card" onclick="showForm('edit-coupon')">
    <i class="fas fa-edit"></i>
    <h4>Edição de Cupom</h4>
</div>
<div class="card" onclick="showForm('delete-coupon')">
    <i class="fas fa-trash"></i>
    <h4>Exclusão de Cupom</h4>
</div>
<div class="card" onclick="showForm('coupon-list')">
    <i class="fas fa-list"></i>
    <h4>Listar Cupons</h4>
</div>
    </div>

    <div id="form-container" class="form-container">
        <div id="edit-form" class="form" style="display: none;">
            <h3>Edição de Produto</h3>
            <form id="edit-product-form" enctype="multipart/form-data">
                <input type="hidden" name="id" id="edit-id">
                <input type="text" name="name" id="edit-name" placeholder="Nome do Produto" required>
                <input type="number" name="stock" id="edit-stock" placeholder="QTD estoque" min="0" required>
                <input type="text" name="brand" id="edit-brand" placeholder="Marca">
                <input type="number" name="price" id="edit-price" placeholder="Preço Atual" step="0.01" min="0" required>
                <input type="number" name="original_price" id="edit-original-price" placeholder="Preço Anterior (opcional para promoção)" step="0.01" min="0">
                <select name="category" id="edit-category" required>
                    <option value="">Selecione a categoria</option>
                    <option value="maquiagem">Maquiagem</option>
                    <option value="perfumes">Perfumes</option>
                    <option value="cuidados">Cuidados</option>
                    <option value="sem-promocao">Sem promoção</option>
                </select>
                <input type="file" name="image" id="edit-image" accept="image/*">
                <img id="edit-image-preview" class="image-preview" src="" alt="Pré-visualização">
                <button type="submit">Salvar Edição</button>
            </form>
            <input type="text" id="search-product-edit" placeholder="Pesquisar produto para editar...">
        </div>

        <div id="promo-form" class="form" style="display: none;">
            <h3>Cadastro de Promoção</h3>
            <form id="promo-product-form" enctype="multipart/form-data">
                <input type="text" name="name" placeholder="Nome do Produto" required>
                <input type="number" name="stock" placeholder="QTD estoque" min="0" required>
                <input type="text" name="brand" placeholder="Marca">
                <input type="number" name="price" placeholder="Preço Atual" step="0.01" min="0" required>
                <input type="number" name="original_price" placeholder="Preço Anterior" step="0.01" min="0" required>
                <select name="category" required>
                    <option value="">Selecione a categoria</option>
                    <option value="maquiagem">Maquiagem</option>
                    <option value="perfumes">Perfumes</option>
                    <option value="cuidados">Cuidados</option>
                </select>
                <input type="file" name="image" accept="image/*" required>
                <img id="promo-image-preview" class="image-preview" src="" alt="Pré-visualização">
                <button type="submit">Cadastrar Promoção</button>
            </form>
        </div>

        <div id="add-form" class="form" style="display: none;">
            <h3>Cadastro de Produto</h3>
            <form id="add-product-form" enctype="multipart/form-data">
                <input type="text" name="name" placeholder="Nome do Produto" required>
                <input type="number" name="stock" placeholder="QTD estoque" min="0" required>
                <input type="text" name="brand" placeholder="Marca">
                <input type="number" name="price" placeholder="Preço Atual" step="0.01" min="0" required>
                <select name="category" required>
                    <option value="">Selecione a categoria</option>
                    <option value="maquiagem">Maquiagem</option>
                    <option value="perfumes">Perfumes</option>
                    <option value="cuidados">Cuidados</option>
                    <option value="sem-promocao">Sem promoção</option>
                </select>
                <input type="file" name="image" accept="image/*" required>
                <img id="add-image-preview" class="image-preview" src="" alt="Pré-visualização">
                <button type="submit">Cadastrar Produto</button>
            </form>
        </div>

        <div id="delete-form" class="form" style="display: none;">
            <h3>Exclusão de Produto</h3>
            <form id="delete-product-form">
                <input type="hidden" name="id" id="delete-id">
                <input type="text" id="delete-name" placeholder="Nome do Produto" readonly>
                <button type="submit">Excluir Produto</button>
            </form>
            <input type="text" id="search-product-delete" placeholder="Pesquisar produto para excluir...">
        </div>

        <div id="list-form" class="form" style="display: none;">
            <h3>Lista de Produtos</h3>
            <div id="product-list"></div>
        </div>
        <div id="coupon-form" class="form" style="display: none;">
    <h3>Cadastro de Cupom</h3>
    <form id="add-coupon-form" enctype="multipart/form-data">
        <input type="text" name="code" placeholder="Código do Cupom (ex: DESCONTO10)" required>
        <input type="number" name="discount" placeholder="Valor do Desconto" step="0.01" min="0" required>
        <select name="type" required>
            <option value="">Selecione o tipo de desconto</option>
            <option value="percentage">Porcentagem (%)</option>
            <option value="fixed">Valor Fixo (R$)</option>
        </select>
        <input type="date" name="valid_until" placeholder="Data de Validade" required>
        <button type="submit">Cadastrar Cupom</button>
    </form>
</div>

<div id="edit-coupon-form" class="form" style="display: none;">
    <h3>Edição de Cupom</h3>
    <form id="edit-coupon" enctype="multipart/form-data">
        <input type="hidden" name="id" id="edit-coupon-id">
        <input type="text" name="code" id="edit-coupon-code" placeholder="Código do Cupom" required>
        <input type="number" name="discount" id="edit-coupon-discount" placeholder="Valor do Desconto" step="0.01" min="0" required>
        <select name="type" id="edit-coupon-type" required>
            <option value="">Selecione o tipo de desconto</option>
            <option value="percentage">Porcentagem (%)</option>
            <option value="fixed">Valor Fixo (R$)</option>
        </select>
        <input type="date" name="valid_until" id="edit-coupon-valid-until" placeholder="Data de Validade" required>
        <button type="submit">Salvar Edição</button>
    </form>
    <input type="text" id="search-coupon-edit" placeholder="Pesquisar cupom para editar...">
</div>

<div id="delete-coupon-form" class="form" style="display: none;">
    <h3>Exclusão de Cupom</h3>
    <form id="delete-coupon">
        <input type="hidden" name="id" id="delete-coupon-id">
        <input type="text" id="delete-coupon-code" placeholder="Código do Cupom" readonly>
        <button type="submit">Excluir Cupom</button>
    </form>
    <input type="text" id="search-coupon-delete" placeholder="Pesquisar cupom para excluir...">
</div>

<div id="coupon-list-form" class="form" style="display: none;">
    <h3>Lista de Cupons</h3>
    <div id="coupon-list"></div>
    </div>

    <script>
        function showForm(action) {
            const forms = document.querySelectorAll('.form');
            const formContainer = document.getElementById('form-container');
            forms.forEach(form => form.style.display = 'none');
            document.getElementById(action + '-form').style.display = 'block';
            formContainer.style.display = 'block';
            if (action === 'list') {
                fetchProducts();
            }
        }

        // Função para mostrar pré-visualização da imagem
        function setupImagePreview(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            
            input.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                    
                    reader.readAsDataURL(file);
                } else {
                    preview.style.display = 'none';
                }
            });
        }

        // Configurar pré-visualização para todos os formulários
        setupImagePreview('edit-image', 'edit-image-preview');
        setupImagePreview('promo-product-form input[type="file"]', 'promo-image-preview');
        setupImagePreview('add-product-form input[type="file"]', 'add-image-preview');

        async function fetchProducts() {
            try {
                const response = await fetch('products.php?action=list');
                const products = await response.json();
                const productList = document.getElementById('product-list');
                
                if (products.length === 0) {
                    productList.innerHTML = '<p>Nenhum produto cadastrado.</p>';
                    return;
                }
                
                productList.innerHTML = products.map(product => {
                    const isPromo = product.original_price !== null;
                    
                    return `
                        <div class="product-item">
                            <h4>${product.name}</h4>
                            <img src="${product.image}" alt="${product.name}" style="max-width: 200px;">
                            <p><strong>Categoria:</strong> ${product.category}</p>
                            <p><strong>Marca:</strong> ${product.brand || 'N/A'}</p>
                            <p><strong>Estoque:</strong> ${product.stock}</p>
                            ${isPromo ? `
                                <p><strong>Preço Promocional:</strong> R$ ${parseFloat(product.price).toFixed(2)}</p>
                                <p><strong>Preço Original:</strong> R$ ${parseFloat(product.original_price).toFixed(2)}</p>
                            ` : `
                                <p><strong>Preço:</strong> R$ ${parseFloat(product.price).toFixed(2)}</p>
                            `}
                            <p><strong>Tipo:</strong> ${isPromo ? 'Produto em Promoção' : 'Produto Normal'}</p>
                        </div>
                    `;
                }).join('');
            } catch (error) {
                console.error('Erro ao listar produtos:', error);
                alert('Erro ao listar produtos.');
            }
        }

        async function searchProduct(formType, searchTerm) {
            try {
                const response = await fetch(`products.php?action=search&name=${encodeURIComponent(searchTerm)}`);
                const products = await response.json();
                
                if (products.length === 0) {
                    alert('Nenhum produto encontrado.');
                    return;
                }
                
                const product = products[0];
                if (formType === 'edit') {
                    document.getElementById('edit-id').value = product.id;
                    document.getElementById('edit-name').value = product.name;
                    document.getElementById('edit-stock').value = product.stock;
                    document.getElementById('edit-brand').value = product.brand || '';
                    document.getElementById('edit-price').value = product.price;
                    document.getElementById('edit-original-price').value = product.original_price || '';
                    document.getElementById('edit-category').value = product.category;
                    
                    // Mostrar imagem atual no preview
                    const preview = document.getElementById('edit-image-preview');
                    if (product.image) {
                        preview.src = product.image;
                        preview.style.display = 'block';
                    }
                } else if (formType === 'delete') {
                    document.getElementById('delete-id').value = product.id;
                    document.getElementById('delete-name').value = product.name;
                }
            } catch (error) {
                console.error('Erro ao buscar produto:', error);
                alert('Erro ao buscar produto.');
            }
        }

        // Configurar eventos de pesquisa
        document.getElementById('search-product-edit').addEventListener('input', (e) => {
            if (e.target.value.length >= 2) {
                searchProduct('edit', e.target.value);
            }
        });
        
        document.getElementById('search-product-delete').addEventListener('input', (e) => {
            if (e.target.value.length >= 2) {
                searchProduct('delete', e.target.value);
            }
        });

        // Função para enviar formulários com FormData
        async function submitForm(formId, action) {
            const form = document.getElementById(formId);
            const formData = new FormData(form);
            formData.append('action', action);
            
            try {
                const response = await fetch('gerenciarprodutos.php', {
                    method: 'POST',
                    body: formData
                });
                
                if (response.redirected) {
                    window.location.href = response.url;
                }
            } catch (error) {
                console.error(`Erro ao ${action} produto:`, error);
                alert(`Erro ao ${action} produto: ${error.message}`);
            }
        }

        // Configurar envio dos formulários
        document.getElementById('add-product-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            await submitForm('add-product-form', 'add_product');
        });

        document.getElementById('promo-product-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const price = parseFloat(document.querySelector('#promo-product-form input[name="price"]').value);
            const originalPrice = parseFloat(document.querySelector('#promo-product-form input[name="original_price"]').value);
            
            if (price >= originalPrice) {
                alert('O preço promocional deve ser menor que o preço original!');
                return;
            }
            
            await submitForm('promo-product-form', 'add_promo');
        });

        document.getElementById('edit-product-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            await submitForm('edit-product-form', 'edit_product');
        });

        document.getElementById('delete-product-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            if (!confirm('Tem certeza que deseja excluir este produto?')) return;
            await submitForm('delete-product-form', 'delete_product');
        });

        // Carregar lista de produtos ao iniciar
        fetchProducts();

        // Adicionar ao final do <script> em gerenciarprodutos.php

async function fetchCoupons() {
    try {
        const response = await fetch('coupons.php?action=list');
        const coupons = await response.json();
        const couponList = document.getElementById('coupon-list');

        if (coupons.length === 0) {
            couponList.innerHTML = '<p>Nenhum cupom cadastrado.</p>';
            return;
        }

        couponList.innerHTML = coupons.map(coupon => {
            return `
                <div class="coupon-item">
                    <h4>${coupon.code}</h4>
                    <p><strong>Desconto:</strong> ${coupon.type === 'percentage' ? coupon.discount + '%' : 'R$ ' + parseFloat(coupon.discount).toFixed(2)}</p>
                    <p><strong>Tipo:</strong> ${coupon.type === 'percentage' ? 'Porcentagem' : 'Valor Fixo'}</p>
                    <p><strong>Validade:</strong> ${new Date(coupon.valid_until).toLocaleDateString('pt-BR')}</p>
                </div>
            `;
        }).join('');
    } catch (error) {
        console.error('Erro ao listar cupons:', error);
        alert('Erro ao listar cupons.');
    }
}

async function searchCoupon(formType, searchTerm) {
    try {
        const response = await fetch(`coupons.php?action=search&code=${encodeURIComponent(searchTerm)}`);
        const coupons = await response.json();

        if (coupons.length === 0) {
            alert('Nenhum cupom encontrado.');
            return;
        }

        const coupon = coupons[0];
        if (formType === 'edit-coupon') {
            document.getElementById('edit-coupon-id').value = coupon.id;
            document.getElementById('edit-coupon-code').value = coupon.code;
            document.getElementById('edit-coupon-discount').value = coupon.discount;
            document.getElementById('edit-coupon-type').value = coupon.type;
            document.getElementById('edit-coupon-valid-until').value = coupon.valid_until;
        } else if (formType === 'delete-coupon') {
            document.getElementById('delete-coupon-id').value = coupon.id;
            document.getElementById('delete-coupon-code').value = coupon.code;
        }
    } catch (error) {
        console.error('Erro ao buscar cupom:', error);
        alert('Erro ao buscar cupom.');
    }
}

// Configurar eventos de pesquisa para cupons
document.getElementById('search-coupon-edit').addEventListener('input', (e) => {
    if (e.target.value.length >= 2) {
        searchCoupon('edit-coupon', e.target.value);
    }
});

document.getElementById('search-coupon-delete').addEventListener('input', (e) => {
    if (e.target.value.length >= 2) {
        searchCoupon('delete-coupon', e.target.value);
    }
});

// Configurar envio dos formulários de cupons
document.getElementById('add-coupon-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    await submitForm('add-coupon-form', 'add_coupon');
});

document.getElementById('edit-coupon').addEventListener('submit', async (e) => {
    e.preventDefault();
    await submitForm('edit-coupon', 'edit_coupon');
});

document.getElementById('delete-coupon').addEventListener('submit', async (e) => {
    e.preventDefault();
    if (!confirm('Tem certeza que deseja excluir este cupom?')) return;
    await submitForm('delete-coupon', 'delete_coupon');
});

// Modificar a função showForm para carregar cupons quando necessário
function showForm(action) {
    const forms = document.querySelectorAll('.form');
    const formContainer = document.getElementById('form-container');
    forms.forEach(form => form.style.display = 'none');
    document.getElementById(action + '-form').style.display = 'block';
    formContainer.style.display = 'block';
    if (action === 'list') {
        fetchProducts();
    } else if (action === 'coupon-list') {
        fetchCoupons();
    }
}
    </script>
</body>
</html>