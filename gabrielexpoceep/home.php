<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sampaio Cosméticos - Home</title>
    <link rel="shortcut icon" type="image" href="./libs/img/l2sc.png">
    <link rel="stylesheet" href="./libs/css/header.css">
    <link rel="stylesheet" href="./libs/css/carrossel.css">
    <style>
        * {
            font-family: 'Arial', sans-serif;
            box-sizing: border-box;}
        .content {
            height: 520px;
            width: 100%;
            max-width: 1500px;
            border-radius: 20px;
            overflow: hidden;
            margin: 0 auto;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .navigation {
            position: relative;
            bottom: 40px;
            left: 87%;
            transform: translateX(-50%);
            display: flex;
        }   
        .bar {
            width: 70px;
            height: 5px;
            background-color: #fff;
            margin: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: .4s;
        }
        .bar:hover {
            background-color: #e91e63;
        }
        input[type="radio"] {
            display: none;
        }
        .slides {
            display: flex;
            width: 500%;
            height: 100%;
            transition: transform 0.6s ease;
        }
        .slide {
            width: 20%;
        }
        .slide img {
            width: 100%;
            height: 100%;
            border-radius: 10px;
        }
        #slide1:checked ~ .slides {
            transform: translateX(0);
        }
        #slide2:checked ~ .slides {
            transform: translateX(-20%);
        }
        #slide3:checked ~ .slides {
            transform: translateX(-40%);
        }
        #slide4:checked ~ .slides {
            transform: translateX(-60%);
        }
        #slide5:checked ~ .slides {
            transform: translateX(-80%);
        }
        .frete {
            color: white;
            background-color: #000;
            text-align: center;
            padding: 10px;
            font-weight: bold;
        }
        section.lancamentos {
            padding: 40px 20px;
            background-color: #fff;
            text-align: center;
            margin: 20px 0;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .lancamentos h2 {
            font-size: 2em;
            color: #ab3188;
        }
        .cards {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .card {
            width: 300px;
            background: #f4f4f4;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: 0.3s;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .card-content {
            padding: 15px;
        }
        .card-content h3 {
            margin: 0;
            font-size: 1.5em;
            color: #ab3188;
        }
        .card-content p {
            color: #555;
        }
        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 0.9em;
        }
        hr {
            border: 0;
            height: 2px;
            background: #333;
            margin: 20px 0;
        }
        .depoimentos {
            padding: 40px 20px;
            background-color: #fff;
            text-align: center;
            margin: 20px 0;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .depoimentos h2 {
            font-size: 2em;
            color: #ab3188;
        }
        .depoimentos-list {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .depoimento {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 10px;
            padding: 10px;
            width: 300px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .social-icons {
            margin-top: 10px;
        }
        .social-icons a {
            margin: 0 10px;
            text-decoration: none;
            color: #fff;
        }
    </style>
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
    <br><br>
    <!-- CARROSSEL -->
    <div class="content">
        <div class="slides">
            <input type="radio" name="radio" id="slide1" checked>
            <input type="radio" name="radio" id="slide2">
            <input type="radio" name="radio" id="slide3">
            <input type="radio" name="radio" id="slide4">
            <input type="radio" name="radio" id="slide5">

            <div class="slide s1">
                <img src="./libs/img/img1.jpg" alt="slide1">
            </div>
            <div class="slide">
                <img src="./libs/img/img2.jpg" alt="slide2">
            </div>
            <div class="slide">
                <img src="./libs/img/img3.png" alt="slide3">
            </div>
            <div class="slide">
                <img src="./libs/img/img4.jpg" alt="slide4">
            </div>
        </div>
        <div class="navigation">
            <label class="bar" for="slide1"></label>
            <label class="bar" for="slide2"></label>
            <label class="bar" for="slide3"></label>
            <label class="bar" for="slide4"></label>
        </div>
    </div>
    <br>
    <hr>
    <div class="frete">
        🚚 Frete Grátis em todo o Brasil | 🛍️ Promoções a partir de R$200 | 🎉 Cupons exclusivos para clientes antigos!
    </div>

    <section class="lancamentos">
        <h2>Em Breve</h2>
        <div class="cards">
            <div class="card">
                <img src="./libs/img/produto1.webp" alt="Skin Care">
                <div class="card-content">
                    <h3>Skin Care</h3>
                    <p>Kit skin care clareador antimanchas facial e corporal.</p>
                </div>
            </div>
            <div class="card">
                <img src="./libs/img/produto2.webp" alt="Maquiagem">
                <div class="card-content">
                    <h3>Maquiagem</h3>
                    <p>Kit Maquiagem Melu by Ruby Rose com Base Pó Iluminador Máscara para Cílios.</p>
                </div>
            </div>
            <div class="card">
                <img src="./libs/img/produto3.webp" alt="Perfumes">
                <div class="card-content">
                    <h3>Perfumes</h3>
                    <p>Deo Parfum Ilía Secreto 50 Ml.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="depoimentos">
        <h2>O que nossos clientes dizem</h2>
        <div class="depoimentos-list">
            <div class="depoimento">
                <p>"Adorei os produtos! A qualidade é incrível e o atendimento foi excelente!"</p>
                <p><strong>Maria Silva</strong></p>
            </div>
            <div class="depoimento">
                <p>"Frete rápido e produtos de ótima qualidade. Com certeza voltarei a comprar!"</p>
                <p><strong>João Pereira</strong></p>
            </div>
            <div class="depoimento">
                <p>"Os cupons de desconto são uma ótima vantagem. Recomendo a todos!"</p>
                <p><strong>Ana Costa</strong></p>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 Sampaio Cosméticos - Todos os direitos reservados.</p>
        <div class="social-icons">
            <a href="https://www.facebook.com" target="_blank">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://www.instagram.com" target="_blank">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://www.twitter.com" target="_blank">
                <i class="fab fa-twitter"></i>
            </a>
        </div>
    </footer>

    <script>
        let currentSlide = 1;
        const totalSlides = 5;

        function changeSlide() {
            document.getElementById('slide' + currentSlide).checked = false;
            currentSlide = (currentSlide % totalSlides) + 1;
            document.getElementById('slide' + currentSlide).checked = true;
        }
        setInterval(changeSlide, 5000);
    </script>
</body>
</html>
