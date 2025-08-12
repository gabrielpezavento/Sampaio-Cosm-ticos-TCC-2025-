<?php
include_once('config.php');

// INSERIR CLIENTE
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["usuario"])) {
    $usuario = $_POST["usuario"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];
    $cpf = $_POST["cpf"];
    $tipo = $_POST["tipo"];

    if (!empty($usuario) && !empty($email) && !empty($senha) && !empty($cpf)) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $conexao->prepare("INSERT INTO usuarios (usuario, email, senha_hash, cpf, tipo) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $usuario, $email, $senha_hash, $cpf, $tipo);
        $stmt->execute();
        $stmt->close();
    }
}

// EXCLUIR CLIENTE
if (isset($_GET["excluir"])) {
    $id = intval($_GET["excluir"]);
    $stmt = $conexao->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// LISTAR CLIENTES
$clientes = [];
$result = $conexao->query("SELECT id, usuario, email, cpf, tipo FROM usuarios ORDER BY id DESC");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $clientes[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Cliente</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(72deg, #56394f 10%, rgb(94, 34, 63) 90%);
            min-height: 100vh;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            color: #fff;
            margin-bottom: 20px;
            text-align: center;
        }

        #formCadastro {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            color: #333;
        }

        .input-container {
            position: relative;
            margin-bottom: 15px;
            width: 100%;
        }

        .input-container i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 18px;
        }

        .input-container input, 
        .input-container select {
            width: calc(100% - 52px);
            padding: 12px 12px 12px 40px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #56394f;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #5f2c63;
        }

        table {
            width: 100%;
            max-width: 800px;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            color: #333;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #56394f;
            color: white;
            text-align: center;
        }

        table td {
            text-align: left;
        }

        table td:last-child {
            text-align: center;
        }

        .fa-trash {
            color: #e74c3c;
            transition: color 0.3s;
        }

        .fa-trash:hover {
            color: #c0392b;
        }

        @media (max-width: 768px) {
            #formCadastro, table {
                width: 95%;
            }
            
            table {
                font-size: 14px;
            }
            
            table th, table td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>

    <h1>Cadastrar Cliente</h1>

    <!-- Formulário de Cadastro -->
    <form id="formCadastro" method="POST">
        <div class="input-container">
            <i class="fas fa-user"></i>
            <input class="user-input" type="text" name="usuario" placeholder="Nome" required>
        </div>
        <div class="input-container">
            <i class="fas fa-envelope"></i>
            <input class="user-input" type="email" name="email" placeholder="E-mail" required>
        </div>
        <div class="input-container">
            <i class="fas fa-lock"></i>
            <input class="user-input" type="password" name="senha" placeholder="Senha" required>
        </div>
        <div class="input-container">
            <i class="fas fa-id-card"></i>
            <input class="user-input" type="text" name="cpf" placeholder="CPF" required>
        </div>
        <div class="input-container">
            <i class="fas fa-user-tag"></i>
            <select name="tipo" required>
                <option value="3">Cliente</option>
                <option value="2">Administrador</option>
                <option value="1">Gerente</option>
            </select>
        </div>
        <button type="submit">Cadastrar</button>
    </form>
    <!-- Tabela de Clientes -->
    <table border="1">
        <thead>
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Senha</th>
                <th>CPF</th>
                <th>Tipo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?= htmlspecialchars($cliente['usuario']) ?></td>
                    <td><?= htmlspecialchars($cliente['email']) ?></td>
                    <td>••••••••</td>
                    <td><?= htmlspecialchars($cliente['cpf']) ?></td>
                    <td>
                        <?php
                            switch ($cliente['tipo']) {
                                case 1: echo 'Gerente'; break;
                                case 2: echo 'Administrador'; break;
                                default: echo 'Cliente';
                            }
                        ?>
                    </td>
                    <td>
                        <a href="?excluir=<?= $cliente['id'] ?>" onclick="return confirm('Deseja excluir este cliente?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>