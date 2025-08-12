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
            <div class="chart-placeholder">
                <p>Gráfico de vendas será exibido aqui</p>
            </div>
        </div>
    </div>

</body>
</html>