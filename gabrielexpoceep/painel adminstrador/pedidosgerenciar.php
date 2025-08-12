<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido</title>
    <link rel="stylesheet" href="styles/home.css">
</head>
<body>

    <!-- Navegação -->
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

    <!-- Card de Pedido -->
    <div class="card-pedidos">
        <img src="../libs/img/img4.jpg" alt="Imagem do Pedido">
        <h2 id="cliente-nome">João</h2>
        <p id="pedido-numero">Número do pedido: 1821</p>
        <p id="pedido-horario">Data do processamento: 26/03</p>
        <p id="previsao-entrega">Previsão de entrega: 27/03</p>
        <div class="button-container">
            <button onclick="editarPedido()">Editar Pedido</button>
            <button onclick="definirStatusEntrega()">Definir Status de Entrega</button>
            <button onclick="marcarComoEntregue()">Marcar como Entregue</button>
        </div>
    </div>

    <!-- Formulário de Edição (Oculto inicialmente) -->
    <div id="editModal" class="modal" style="display: none;">
        <div class="modal-content">
            <h3>Editar Pedido</h3>
            <form id="editForm">
                <label for="editClienteNome">Nome do Cliente:</label>
                <input type="text" id="editClienteNome" value="João" required>

                <label for="editNumeroPedido">Número do Pedido:</label>
                <input type="text" id="editNumeroPedido" value="1821" required>

                <label for="editDataEntrega">Data de Entrega:</label>
                <input type="date" id="editDataEntrega" value="2025-03-27" required>

                <label for="editCompras">Compras:</label>
                <textarea id="editCompras" rows="4" required>Exemplo de compras...</textarea>

                <button type="submit">Salvar Alterações</button>
                <button type="button" onclick="fecharModal()">Fechar</button>
            </form>
        </div>
    </div>

    <script>
        // Função para editar o pedido
        function editarPedido() {
            document.getElementById('editModal').style.display = 'block';  
        }

        // Função para fechar o modal
        function fecharModal() {
            document.getElementById('editModal').style.display = 'none'; 
        }

        // Função para definir o status de entrega
        function definirStatusEntrega() {
            let status = prompt('Digite o novo status de entrega (Ex: "Em trânsito", "Entregue", etc.)');
            if (status) {
                alert('Status de entrega atualizado para: ' + status);
            } else {
                alert('Status não atualizado');
            }
        }

        // Função para marcar o pedido como entregue
        function marcarComoEntregue() {
            let confirmar = confirm('Você tem certeza que deseja marcar este pedido como entregue?');
            if (confirmar) {
                alert('Pedido 1821 marcado como entregue!');
                document.getElementById('pedido-numero').innerText = 'Número do pedido: 1821 (Entregue)';
                document.getElementById('pedido-horario').innerText = 'Data do processamento: 26/03 (Entregue)';
                document.getElementById('previsao-entrega').innerText = 'Previsão de entrega: 27/03 (Entregue)';
            } else {
                alert('Pedido não marcado como entregue');
            }
        }

        // Lógica para salvar as alterações
        document.getElementById('editForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Impede o envio normal do formulário

            var nomeCliente = document.getElementById('editClienteNome').value;
            var numeroPedido = document.getElementById('editNumeroPedido').value;
            var dataEntrega = document.getElementById('editDataEntrega').value;
            var compras = document.getElementById('editCompras').value;

            // Atualiza as informações na tela
            document.getElementById('cliente-nome').innerText = nomeCliente;
            document.getElementById('pedido-numero').innerText = 'Número do pedido: ' + numeroPedido;
            document.getElementById('previsao-entrega').innerText = 'Previsão de entrega: ' + dataEntrega;

            // Fechar o modal após salvar
            fecharModal();

            alert('Pedido atualizado com sucesso!');
        });
    </script>

</body>
</html>
