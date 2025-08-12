<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCosmeticos - Dashboard</title>
    <link rel="stylesheet" href="styles/home.css">
</head>
<body>
    <nav>
        <div class="logo">
            <img src="../libs/img/logo2.jpg" alt="Logo Sampaio Cosméticos" class="logo-img">
        </div>
        <h1>SCosmeticos</h1>
        <ul>
            <li><a href="produtosgerenciar.php">Gerenciar Produtos</a></li>
            <li><a href="clientesgerenciar.php">Gerenciar Clientes</a></li>
            <li><a href="pedidosgerenciar.php">Gerenciar Pedidos</a></li>
            <li><a href="vendas.php">Total de Vendas</a></li>
            <li><a href="../login.php">Logout</a></li>
        </ul>
    </nav>

    <div class="main-content">
        <div class="dashboard-cards">
            <div class="card">
                <h3>Produtos Gerenciados</h3>
                <p>150</p>
            </div>
            <div class="card">
                <h3>Clientes Cadastrados</h3>
                <p>320</p>
            </div>
            <div class="card">
                <h3>Pedidos Pendentes</h3>
                <p>5</p>
            </div>
        </div>

        <div class="chart-section">
            <h3>Gráfico de Vendas Mensais</h3>
            <div style="background-color: #ddd; height: 200px; border-radius: 8px;">
                <p style="text-align: center; padding-top: 80px;">Gráfico de vendas será exibido aqui</p>
            </div>
        </div>

        <div class="vendas-section">
            <h3>Total de Vendas por Mês</h3>
            <table id="tabela-vendas">
                <thead>
                    <tr>
                        <th>Mês</th>
                        <th>Total de Vendas (R$)</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Linhas de vendas mensais serão inseridas aqui com JS -->
                </tbody>
            </table>
            <button onclick="adicionarVenda()">Adicionar Venda</button>
        </div>
    </div>

        <script>

const vendasMensais = {
    "Janeiro": 0,
    "Fevereiro": 0,
    "Março": 0,
    "Abril": 0,
    "Maio": 0,
    "Junho": 0,
    "Julho": 0,
    "Agosto": 0,
    "Setembro": 0,
    "Outubro": 0,
    "Novembro": 0,
    "Dezembro": 0
};

// Carrega os dados na tabela
function carregarTabela() {
    const tabela = document.querySelector("#tabela-vendas tbody");
    tabela.innerHTML = "";

    for (const [mes, valor] of Object.entries(vendasMensais)) {
        const linha = tabela.insertRow();
        linha.insertCell(0).textContent = mes;
        linha.insertCell(1).textContent = `R$ ${valor.toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
    }
}

// Adiciona valor de venda ao mês correspondente
function adicionarVenda() {
    let mes = prompt("Digite o mês da venda (ex: Março):");
    if (!mes) return;

    mes = formatarMes(mes.trim());

    if (!vendasMensais.hasOwnProperty(mes)) {
        alert("Mês inválido! Use nomes como: Janeiro, Fevereiro, etc.");
        return;
    }

    const valorStr = prompt(`Digite o valor da venda para ${mes} (em R$):`);
    const valor = parseFloat(valorStr);

    if (isNaN(valor) || valor <= 0) {
        alert("Valor inválido! Digite um número positivo.");
        return;
    }

    vendasMensais[mes] += valor;
    carregarTabela();
}

// Formata o nome do mês
function formatarMes(mes) {
    return mes.charAt(0).toUpperCase() + mes.slice(1).toLowerCase();
}

window.onload = carregarTabela;
</script>

    </body>
    </html>
