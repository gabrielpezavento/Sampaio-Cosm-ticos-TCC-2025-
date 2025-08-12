<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sampaio Cosméticos - Produtos</title>
    <link rel="shortcut icon" type="image" href="./libs/img/l2sc.png" />
    <link rel="stylesheet" href="./libs/css/header.css">
    <link rel="stylesheet" href="./libs/css/produtos.css">
</head>
<body>
    <header class="cabecalho" role="banner">
        <div class="logo" role="img" aria-label="Logo Sampaio Cosméticos">
            <img src="./libs/img/logo2.jpg" alt="Logo Sampaio Cosméticos" class="logo-img" tabindex="0" />
        </div>
        <nav role="navigation" aria-label="Menu Principal">
            <a href="home.php" tabindex="0">Home</a>
            <a href="produtos.php" tabindex="0">Produtos</a>
            <a href="contato.php" tabindex="0">Contato</a>
        </nav>
        <div class="dropdown" tabindex="0" aria-haspopup="true" aria-expanded="false">
            <a class="icone-login" href="login.php" tabindex="-1">
                <img src="./libs/img/login.jpeg" alt="Login" width="30" height="30" />
            </a>
            <span class="arrow">&#9662;</span>
            <div class="dropdown-content" tabindex="-1" aria-label="Menu usuário">
                <a href="profile/cliente.html" tabindex="0">Meu perfil</a>
                <a href="login.php" tabindex="0">Login/Cadastro</a>
            </div>
        </div>
        <a class="icone" href="carrinho.php" aria-label="Carrinho de compras" tabindex="0">
            <img src="./libs/img/carrinho.png" alt="Carrinho" width="30" height="30" />
        </a>
    </header>
    <main>
        <section>
            <div class="secao-container" style="max-width: 700px; margin: 0 auto 3rem; justify-content: center;">
                <select class="filtro-ordem" aria-label="Selecione um filtro de ordenação" id="ordenarFiltro" onchange="filtrarPromocoes()" tabindex="0">
                    <option value="" disabled selected>Selecione um filtro</option>
                    <option value="preco-asc">Preço: Menor para Maior</option>
                    <option value="preco-desc">Preço: Maior para Menor</option>
                    <option value="nome-asc">Nome: A-Z</option>
                    <option value="nome-desc">Nome: Z-A</option>
                </select>
                <div class="barra-pesquisa-container" style="max-width: 480px; width: 100%;">
                    <input type="text" class="barra-pesquisa" placeholder="Pesquisar por nome..." id="searchInput" onkeyup="filtrarPromocoes()" aria-label="Buscar produtos por nome" tabindex="0" />
                </div>
                <button onclick="limparFiltro()" class="limpar-filtro" aria-label="Limpar filtros" tabindex="0" style="margin-left: 20px;">
                    Limpar Filtro
                </button>
            </div>
        </section>

        <div class="cards" id="cardsContainer" role="list">
            <!-- Os produtos serão carregados aqui via JavaScript -->
        </div>

        <section>
    <h2>Cupons Disponíveis</h2>
    <div class="coupons" id="couponsContainer" role="list">
        <!-- Os cupons serão carregados aqui via JavaScript -->
    </div>
</section>
    </main>

    <script>
        // Variável global para armazenar todos os produtos
        let allProducts = [];
        
        async function fetchProducts() {
            try {
                const response = await fetch('products.php?action=list');
                if (!response.ok) throw new Error('Erro na rede');
                
                allProducts = await response.json();
                renderProducts(allProducts);
            } catch (error) {
                console.error('Erro ao carregar produtos:', error);
                document.getElementById('cardsContainer').innerHTML = `
                    <div class="error-message">
                        <p>Não foi possível carregar os produtos.</p>
                        <p>Por favor, recarregue a página ou tente novamente mais tarde.</p>
                    </div>
                `;
            }
        }

        function renderProducts(products) {
            const cardsContainer = document.getElementById('cardsContainer');
            
            if (!products || products.length === 0) {
                cardsContainer.innerHTML = `
                    <div class="no-products">
                        <p>Nenhum produto disponível no momento.</p>
                    </div>
                `;
                return;
            }

            cardsContainer.innerHTML = products.map(product => {
                const isPromo = product.original_price && parseFloat(product.original_price) > parseFloat(product.price);
                
                return `
                    <article class="card-promocao" 
                        data-id="${product.id}"
                        data-categoria="${product.category}" 
                        data-nome="${product.name.toLowerCase()}" 
                        data-preco="${parseFloat(product.price)}">
                        
                        <div class="product-image">
                            <img src="${product.image}" alt="${product.name}" loading="lazy">
                        </div>
                        
                        <div class="card-content">
                            <h3>${product.name}</h3>
                            ${product.brand ? `<p class="brand">${product.brand}</p>` : ''}
                            
                            ${isPromo ? `
                                <div class="price-container">
                                    <span class="original-price">R$ ${parseFloat(product.original_price).toFixed(2)}</span>
                                    <span class="current-price">R$ ${parseFloat(product.price).toFixed(2)}</span>
                                </div>
                            ` : `
                                <div class="price-container">
                                    <span class="current-price">R$ ${parseFloat(product.price).toFixed(2)}</span>
                                </div>
                            `}
                            
                            <button onclick="addToCart(${product.id})" aria-label="Adicionar ${product.name} ao carrinho">
                                Adicionar ao Carrinho
                            </button>
                        </div>
                    </article>
                `;
            }).join('');
        }

        async function addToCart(productId) {
            try {
                const response = await fetch('cart.php?action=add', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `product_id=${productId}&quantity=1`
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert('Produto adicionado ao carrinho com sucesso!');
                    // Redireciona apenas se for bem-sucedido
                    window.location.href = 'carrinho.html';
                } else {
                    alert(result.message || 'Erro ao adicionar ao carrinho');
                }
            } catch (error) {
                console.error('Erro ao adicionar ao carrinho:', error);
                alert('Erro ao adicionar ao carrinho. Por favor, tente novamente.');
            }
        }

        function filtrarPromocoes() {
            const ordenar = document.getElementById("ordenarFiltro").value;
            const searchTerm = document.getElementById("searchInput").value.toLowerCase();
            
            let filtered = [...allProducts];
            
            // Filtro por nome
            if (searchTerm) {
                filtered = filtered.filter(product => 
                    product.name.toLowerCase().includes(searchTerm)
                );
            }
            
            // Ordenação
            if (ordenar) {
                filtered.sort((a, b) => {
                    switch (ordenar) {
                        case 'preco-asc':
                            return parseFloat(a.price) - parseFloat(b.price);
                        case 'preco-desc':
                            return parseFloat(b.price) - parseFloat(a.price);
                        case 'nome-asc':
                            return a.name.localeCompare(b.name);
                        case 'nome-desc':
                            return b.name.localeCompare(a.name);
                        default:
                            return 0;
                    }
                });
            }
            
            renderProducts(filtered);
        }

        function limparFiltro() {
            document.getElementById("ordenarFiltro").value = "";
            document.getElementById("searchInput").value = "";
            renderProducts(allProducts);
        }

        // Carrega os produtos quando a página é carregada
        document.addEventListener('DOMContentLoaded', fetchProducts);
        // Adicionar ao final do <script> em produtos.php

async function fetchCoupons() {
    try {
        const response = await fetch('coupons.php?action=list');
        if (!response.ok) throw new Error('Erro na rede');

        const coupons = await response.json();
        renderCoupons(coupons);
    } catch (error) {
        console.error('Erro ao carregar cupons:', error);
        document.getElementById('couponsContainer').innerHTML = `
            <div class="error-message">
                <p>Não foi possível carregar os cupons.</p>
                <p>Por favor, recarregue a página ou tente novamente mais tarde.</p>
            </div>
        `;
    }
}

function renderCoupons(coupons) {
    const couponsContainer = document.getElementById('couponsContainer');

    if (!coupons || coupons.length === 0) {
        couponsContainer.innerHTML = `
            <div class="no-coupons">
                <p>Nenhum cupom disponível no momento.</p>
            </div>
        `;
        return;
    }

    couponsContainer.innerHTML = coupons.map(coupon => {
        return `
            <article class="coupon-card" data-id="${coupon.id}">
                <h3>${coupon.code}</h3>
                <p><strong>Desconto:</strong> ${coupon.type === 'percentage' ? coupon.discount + '%' : 'R$ ' + parseFloat(coupon.discount).toFixed(2)}</p>
                <p><strong>Validade:</strong> ${new Date(coupon.valid_until).toLocaleDateString('pt-BR')}</p>
                <button onclick="copyCoupon('${coupon.code}')">Copiar Cupom</button>
            </article>
        `;
    }).join('');
}

function copyCoupon(code) {
    navigator.clipboard.writeText(code).then(() => {
        alert(`Cupom ${code} copiado com sucesso!`);
    }).catch(err => {
        console.error('Erro ao copiar cupom:', err);
        alert('Erro ao copiar cupom.');
    });
}

// Modificar o evento DOMContentLoaded para carregar cupons
document.addEventListener('DOMContentLoaded', () => {
    fetchProducts();
    fetchCoupons();
});
    </script>
</body>
</html>