<?php
session_start();
include_once('config.php');

// Verifica conexão
if ($conexao->connect_error) {
    die("Conexão falhou: " . $conexao->connect_error);
}

// Processa LOGIN
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['usuario']) && !isset($_POST['cpf'])) {
    $usuario = $_POST["usuario"];
    $senha = $_POST["senha"];

    if (empty($usuario) || empty($senha)) {
        $_SESSION['mensagem'] = "Por favor, preencha todos os campos.";
        $_SESSION['tipo_mensagem'] = 'erro';
    } else {
        // CORREÇÃO AQUI: Removido o parâmetro extra e corrigida a string de tipos
        $sql = "SELECT id, usuario, senha_hash, tipo FROM usuarios WHERE usuario = ?";
        $stmt = $conexao->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("s", $usuario);
            
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    
                    if (password_verify($senha, $row['senha_hash'])) {
                        $_SESSION['usuario_id'] = $row['id'];
                        $_SESSION['usuario_nome'] = $row['usuario'];
                        $_SESSION['tipo_usuario'] = $row['tipo'];
                        
                        if ($row['tipo'] == 1) {
                            header("Location: ./painel_adminstrador/index.php");
                        } elseif ($row['tipo'] == 2) {
                            header("Location: ./painel_adminstrador/index.php");
                        } else {
                            header("Location: carrinho.php");
                        }
                        exit;
                    } else {
                        $_SESSION['mensagem'] = "Credenciais inválidas.";
                        $_SESSION['tipo_mensagem'] = 'erro';
                    }
                } else {
                    $_SESSION['mensagem'] = "Usuário não encontrado.";
                    $_SESSION['tipo_mensagem'] = 'erro';
                }
            } else {
                $_SESSION['mensagem'] = "Erro na execução: " . $stmt->error;
                $_SESSION['tipo_mensagem'] = 'erro';
            }
            $stmt->close();
        } else {
            $_SESSION['mensagem'] = "Erro na preparação: " . $conexao->error;
            $_SESSION['tipo_mensagem'] = 'erro';
        }
    }
}


// Processa CADASTRO
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cpf'])) {
    $usuario = $_POST['usuario'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    
    // Validações
    if (empty($usuario) || empty($cpf) || empty($email) || empty($senha)) {
        $_SESSION['mensagem'] = "Todos os campos são obrigatórios.";
        $_SESSION['tipo_mensagem'] = 'erro';
    } else {
        // Gera hash da senha
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        
        $stmt = $conexao->prepare("INSERT INTO usuarios (usuario, cpf, email, senha_hash) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $usuario, $cpf, $email, $senha_hash);
        
        if ($stmt->execute()) {
            $_SESSION['mensagem'] = "Cadastro realizado com sucesso!";
            $_SESSION['tipo_mensagem'] = 'sucesso';
        } else {
            // Detecta erro de duplicata
            if ($stmt->errno == 1062) {
                $_SESSION['mensagem'] = "Erro: Dados já cadastrados (CPF, e-mail ou usuário)";
            } else {
                $_SESSION['mensagem'] = "Erro no cadastro: " . $stmt->error;
            }
            $_SESSION['tipo_mensagem'] = 'erro';
        }
        $stmt->close();
    }
}

// Processa RECUPERAÇÃO DE SENHA
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['senha_nova'])) {
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $senha_nova = $_POST['senha_nova'];
    
    if (empty($cpf) || empty($email) || empty($senha_nova)) {
        $_SESSION['mensagem'] = "Todos os campos são obrigatórios para recuperação.";
        $_SESSION['tipo_mensagem'] = 'erro';
    } else {
        $senha_hash = password_hash($senha_nova, PASSWORD_DEFAULT);
        
        $stmt = $conexao->prepare("UPDATE usuarios SET senha_hash = ? WHERE cpf = ? AND email = ?");
        $stmt->bind_param("sss", $senha_hash, $cpf, $email);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['mensagem'] = "Senha atualizada com sucesso!";
                $_SESSION['tipo_mensagem'] = 'sucesso';
            } else {
                $_SESSION['mensagem'] = "Nenhum usuário encontrado com esses dados.";
                $_SESSION['tipo_mensagem'] = 'erro';
            }
        } else {
            $_SESSION['mensagem'] = "Erro ao atualizar senha: " . $stmt->error;
            $_SESSION['tipo_mensagem'] = 'erro';
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sampaio Cosméticos - Login</title>
    <link rel="shortcut icon" type="image" href="./libs/img/l2sc.png">
    <link rel="stylesheet" href="./libs/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" charset="utf-8"></script>
    <style>
        .user-input i {
            color: #ccc;
            width: 12px;
            margin-left: 30px;
        }
        a {
            cursor: pointer;
        }
        #painel {
            justify-content: center;
            align-items: center;
            color: rgb(80, 17, 47);
        }
    </style>
</head>
<body>
    <header>
        <a href="home.php" style="cursor: pointer;" class="voltar">⬅️ Voltar</a>
    </header>

    <div class="form-container">
        <!-- FORMULÁRIO DE LOGIN -->
        <form class="form login-form" action="" method="post">
            <img src="libs/img/logo2.jpg" alt="">
            <div class="input-container">
                <i class="fas fa-user"></i>
                <input class="user-input" type="text" name="usuario" placeholder="Usuário" required>
            </div>
            <div class="input-container">
                <i class="fas fa-lock"></i>
                <input class="user-input" type="password" name="senha" placeholder="Senha" required>
            </div>
            <div class="options-01">
                <label class="remember-me"><input type="checkbox" name="">Lembrar-me</label>
                <a href="javascript:void(0)" id="show-recover">Esqueceu sua senha?</a>
            </div>
            <input class="btn" type="submit" value="ENTRAR">
            <div class="options-02">
                <p>Não está registrado? <a href="javascript:void(0)" id="show-signup">Criar uma conta</a></p>
            </div>
        </form>

        <!-- FORMULÁRIO DE CADASTRO -->
        <form class="form signup-form" action="" method="post" style="display: none;">
            <img src="libs/img/logo2.jpg" alt="">
            <div class="input-container">
                <i class="fas fa-user"></i>
                <input class="user-input" type="text" name="usuario" placeholder="Usuário" required>
            </div>
            <div class="input-container">
                <i class="fas fa-id-card"></i>
                <input class="user-input" type="text" name="cpf" placeholder="CPF" maxlength="11" required>
            </div>
            <div class="input-container">
                <i class="fas fa-envelope"></i>
                <input class="user-input" type="email" name="email" placeholder="E-mail" required>
            </div>
            <div class="input-container">
                <i class="fas fa-lock"></i>
                <input class="user-input" type="password" name="senha" placeholder="Senha" required>
            </div>
            <input class="btn" type="submit" value="REGISTRAR">
            <div class="options-02">
                <p>Já está registrado? <a href="javascript:void(0)" id="show-login">Entrar</a></p>
            </div>
        </form>

        <!-- FORMULÁRIO DE RECUPERAÇÃO DE CONTA -->
        <form class="form recover-form" action="" method="post" style="display: none;">
            <img src="libs/img/logo2.jpg" alt="">
            <div class="input-container">
                <i class="fas fa-id-card"></i>
                <input class="user-input" type="text" name="cpf" placeholder="CPF" required>
            </div>
            <div class="input-container">
                <i class="fas fa-envelope"></i>
                <input class="user-input" type="email" name="email" placeholder="E-mail" required>
            </div>
            <div class="input-container">
                <i class="fas fa-lock"></i>
                <input class="user-input" type="password" name="senha_nova" placeholder="Senha nova" required>
            </div>
            <input class="btn" type="submit" value="RECUPERAR CONTA">
            <div class="options-02">
                <p>Já tem uma conta? <a href="javascript:void(0)" id="show-login-recover">Entrar</a></p>
            </div>
        </form>
    </div>

    <script type="text/javascript">
        // Mostrar pop-up de mensagem se existir
        <?php if (isset($_SESSION['mensagem'])): ?>
            alert("<?= $_SESSION['mensagem'] ?>");
            <?php unset($_SESSION['mensagem']); ?>
            <?php unset($_SESSION['tipo_mensagem']); ?>
        <?php endif; ?>

        // Login adm ou cliente
        $('.login-form').submit(function(event) {
            event.preventDefault();
            
            var form = this;
            var usuario = $('input[name="usuario"]').val();
            var senha = $('input[name="senha"]').val();

            // Verificação de admin local
            if (usuario === "admin" && senha === "px-1080w") {
                window.location.href = "./painel adminstrador/index.php";
            } else {
                form.submit();
            }
        });

        // Mostrar o formulário de recuperação de senha
        $('#show-recover').click(function(){
            $('.login-form').animate({
                height: "toggle", 
                opacity: "toggle"
            }, "slow", function() {
                $('.recover-form').slideDown("slow");
            });
        });

        $('#show-signup').click(function(){
            $('.login-form').animate({
                height: "toggle", 
                opacity: "toggle"
            }, "slow", function() {
                $('.signup-form').slideDown("slow");
            });
        });

        $('#show-login').click(function(){
            $('.signup-form').animate({
                height: "toggle", 
                opacity: "toggle"
            }, "slow", function() {
                $('.login-form').slideDown("slow");
            });
        });

        $('#show-login-recover').click(function(){
            $('.recover-form').animate({
                height: "toggle", 
                opacity: "toggle"
            }, "slow", function() {
                $('.login-form').slideDown("slow");
            });
        });
    </script>
</body>
</html>