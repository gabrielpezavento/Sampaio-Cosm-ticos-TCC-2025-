SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `cosmetico`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `cpf` char(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha_hash` char(60) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tipo` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `cpf`, `email`, `senha_hash`, `data_criacao`, `data_atualizacao`, `tipo`) VALUES
(1, 'amelia', '77777777777', 'amelia@gmail.com', '$2y$10$/41vTn9mH6x50HSWNWXjt.DtQ0qXgANpVqUDYQgmhErznqoEdfTHC', '2025-06-30 14:57:19', '2025-06-30 15:07:20', 1),
(3, 'admin', '88888888888', 'admin@gmail.com', '$2y$10$25rc7Pcd0VbwdCinwOf7duh8g/3UfHlwTlTSJaQHWNhI27fA0Ba5C', '2025-06-30 15:04:54', '2025-06-30 15:07:36', 2),
(6, 'apare', '999999999', 'clara@gmail.com', '$2y$10$FfYzJthBOVBctEtnf1REPeWLy2NMBtSwIU0jeFjlfqqaHHPv3tMk6', '2025-06-30 16:59:25', '2025-06-30 16:59:25', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RE  SULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- Criação do banco de dados (opcional, descomente se necessário)
-- CREATE DATABASE IF NOT EXISTS cosmetico DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
-- USE cosmetico;

-- Tabela de produtos
-- Tabela de produtos
CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    preco DECIMAL(10, 2) NOT NULL,
    estoque INT NOT NULL,
    marca VARCHAR(100),
    categoria VARCHAR(50) NOT NULL,
    imagem VARCHAR(255),
    preco_original DECIMAL(10, 2),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    discount DECIMAL(10,2) NOT NULL,
    type ENUM('percentage', 'fixed') NOT NULL,
    valid_until DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
);