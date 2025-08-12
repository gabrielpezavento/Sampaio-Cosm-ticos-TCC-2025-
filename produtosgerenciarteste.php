<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gerenciamento de Produtos</title>
    <link rel="stylesheet" href="styles/home.css">
    <style>
        
        .corpoproduto {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background:  linear-gradient(72deg, #56394f 10%, rgb(94, 34, 63) 90%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        
        .card svg {
            width: 40px;
            height: 40px;
            margin-bottom: 10px;
            fill: #d4098a;
        }
        
        .card h4 {
            margin: 0;
            color: #333;
        }
        
        .form-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
            display: none;
        }
        
        .form-container.show {
            display: block;
        }
        
        .form {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .form h3 {
            margin-top: 0;
            color: #d4098a;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .form input, .form select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            box-sizing: border-box;
        }
        
        .form button {
            background-color: #d4098a;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        
        .form button:hover {
            background-color: #b00772;
        }
        
        #product-list {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        #product-list .product-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        #product-list img {
            max-width: 100px;
            height: auto;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        
        #search-product-edit, #search-product-delete {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        /* Novos estilos para upload de imagem */
        .form input[type="file"] {
            padding: 8px;
        }
        
        .image-preview {
            max-width: 200px;
            max-height: 200px;
            margin: 10px 0;
            display: none;
        }
        
        @media (max-width: 768px) {
            .dashboard-cards {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
            
            .form-container {
                padding: 0 15px;
            }
        }
        
        @media (max-width: 480px) {
            .dashboard-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body class="corpoproduto">
    <header>
        <a href="index.php" style="cursor: pointer;" class="voltar">⬅️ Voltar</a>
    </header>

    <div class="dashboard-cards">
        <div class="card" onclick="showForm('edit')">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M14.206 2.794a1 1 0 0 1 1.414 0l5.586 5.586a1 1 0 0 1 0 1.414l-8.793 8.792a1 1 0 0 1-.528.247l-2.32.775a1 1 0 0 1-1.276-1.277l.775-2.32a1 1 0 0 1 .246-.527L14.206 2.794zM3 12a1 1 0 0 1 1-1h5a1 1 0 1 1 0 2H4a1 1 0 0 1-1-1z"/></svg>
            <h4>Edição de Produto</h4>
        </div> 
        <div class="card" onclick="showForm('promo')">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2a1 1 0 0 1 1 1v8h8a1 1 0 0 1 0 2h-8v8a1 1 0 0 1-2 0v-8H2a1 1 0 0 1 0-2h8V3a1 1 0 0 1 1-1z"/></svg>
            <h4>Cadastro de Promoção</h4>
        </div>  
        <div class="card" onclick="showForm('add')">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2a1 1 0 0 1 1 1v8h8a1 1 0 0 1 0 2h-8v8a1 1 0 0 1-2 0v-8H2a1 1 0 0 1 0-2h8V3a1 1 0 0 1 1-1z"/></svg>
            <h4>Cadastro de Produto</h4>
        </div>  
        <div class="card" onclick="showForm('delete')">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3 6a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v1h-18V6zm6 0h6V5a2 2 0 0 1 4 0v1h3a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h3V5a2 2 0 0 1 4 0v1zm2-1v1h4V5a2 2 0 0 0-4 0z"/></svg>
            <h4>Exclusão de Produto</h4>
        </div>
        <div class="card" onclick="showForm('list')">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3 4h18v2H3V4zm0 7h18v2H3v-2zm0 7h18v2H3v-2z"/></svg>
            <h4>Listar Produtos</h4>
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
            <input type="text" id="search-product-edit" placeholder="Pesquisar produto para editar..." onkeyup="searchProduct('edit')">
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
            <input type="text" id="search-product-delete" placeholder="Pesquisar produto para excluir..." onkeyup="searchProduct('delete')">
        </div>

        <div id="list-form" class="form" style="display: none;">
            <h3>Lista de Produtos</h3>
            <div id="product-list"></div>
        </div>
    </div>

    <script>
        function showForm(action) {
            const forms = document.querySelectorAll('.form');
            const formContainer = document.getElementById('form-container');
            forms.forEach(form => form.style.display = 'none');
            document.getElementById(action + '-form').style.display = 'block';
            formContainer.classList.add('show');
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
        setupImagePreview('promo-image', 'promo-image-preview');
        setupImagePreview('add-image', 'add-image-preview');

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

        async function searchProduct(formType) {
            const searchInput = document.getElementById(`search-product-${formType}`).value.trim();
            if (searchInput.length < 2) return;
            
            try {
                const response = await fetch(`products.php?action=search&name=${encodeURIComponent(searchInput)}`);
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

        // Função para enviar formulários com FormData
        async function submitForm(formId, action) {
            const form = document.getElementById(formId);
            const formData = new FormData(form);
            
            try {
                const response = await fetch(`products.php?action=${action}`, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                
                if (result.error) {
                    throw new Error(result.error);
                }
                
                alert(result.message);
                form.reset();
                
                // Limpar pré-visualização
                if (formId === 'add-product-form') {
                    document.getElementById('add-image-preview').style.display = 'none';
                } else if (formId === 'promo-product-form') {
                    document.getElementById('promo-image-preview').style.display = 'none';
                }
                
                fetchProducts();
            } catch (error) {
                console.error(`Erro ao ${action} produto:`, error);
                alert(`Erro ao ${action} produto: ${error.message}`);
            }
        }

        // Configurar envio dos formulários
        document.getElementById('add-product-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            await submitForm('add-product-form', 'add');
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
            await submitForm('edit-product-form', 'edit');
        });

        document.getElementById('delete-product-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            if (!confirm('Tem certeza que deseja excluir este produto?')) return;
            
            const formData = new FormData(e.target);
            try {
                const response = await fetch('products.php?action=delete', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                alert(result.message);
                e.target.reset();
                fetchProducts();
            } catch (error) {
                console.error('Erro ao excluir produto:', error);
                alert('Erro ao excluir produto.');
            }
        });

        // Carregar lista de produtos ao iniciar
        fetchProducts();
    </script>
</body>
</html>