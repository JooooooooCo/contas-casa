-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           5.7.31 - MySQL Community Server (GPL)
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para contas_casa
CREATE DATABASE IF NOT EXISTS `contas_casa` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_bin */;
USE `contas_casa`;

-- Copiando estrutura para tabela contas_casa.contas_movimentos
CREATE TABLE IF NOT EXISTS `contas_movimentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_movimento` int(11) DEFAULT NULL,
  `tipo_pgto` int(11) DEFAULT NULL,
  `tipo_situacao_pgto` int(11) DEFAULT NULL,
  `dt_compra` date DEFAULT NULL,
  `dt_vcto` date DEFAULT NULL,
  `dt_pgto` date DEFAULT NULL,
  `vl_original` decimal(10,0) DEFAULT NULL,
  `vl_pago` decimal(10,0) DEFAULT NULL,
  `dif_pgto` decimal(10,0) DEFAULT NULL,
  `parcela_atual` int(11) DEFAULT NULL,
  `qtd_parcelas` int(11) DEFAULT NULL,
  `grupo_um` int(11) DEFAULT NULL,
  `grupo_dois` int(11) DEFAULT NULL,
  `grupo_tres` int(11) DEFAULT NULL,
  `descricao_pessoal` text CHARACTER SET latin1,
  `obs_um` text CHARACTER SET latin1,
  `obs_dois` text CHARACTER SET latin1,
  `media_gastos` text CHARACTER SET latin1,
  `sn_real` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `FK_tipo_movimento` (`tipo_movimento`),
  KEY `FK_tipo_pgto` (`tipo_pgto`),
  KEY `FK_grupo_um` (`grupo_um`),
  KEY `FK_grupo_dois` (`grupo_dois`),
  KEY `FK_grupo_tres` (`grupo_tres`),
  KEY `FK_tipo_situacao_pgto` (`tipo_situacao_pgto`),
  CONSTRAINT `FK_grupo_dois` FOREIGN KEY (`grupo_dois`) REFERENCES `tipo_grupo_dois` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_grupo_tres` FOREIGN KEY (`grupo_tres`) REFERENCES `tipo_grupo_tres` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_grupo_um` FOREIGN KEY (`grupo_um`) REFERENCES `tipo_grupo_um` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_tipo_movimento` FOREIGN KEY (`tipo_movimento`) REFERENCES `tipo_movimento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_tipo_pgto` FOREIGN KEY (`tipo_pgto`) REFERENCES `tipo_pgto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_tipo_situacao_pgto` FOREIGN KEY (`tipo_situacao_pgto`) REFERENCES `tipo_situacao_pgto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Copiando dados para a tabela contas_casa.contas_movimentos: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `contas_movimentos` DISABLE KEYS */;
INSERT INTO `contas_movimentos` (`id`, `tipo_movimento`, `tipo_pgto`, `tipo_situacao_pgto`, `dt_compra`, `dt_vcto`, `dt_pgto`, `vl_original`, `vl_pago`, `dif_pgto`, `parcela_atual`, `qtd_parcelas`, `grupo_um`, `grupo_dois`, `grupo_tres`, `descricao_pessoal`, `obs_um`, `obs_dois`, `media_gastos`, `sn_real`) VALUES
	(24, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'teste', NULL, NULL, NULL, 1);
/*!40000 ALTER TABLE `contas_movimentos` ENABLE KEYS */;

-- Copiando estrutura para tabela contas_casa.tipo_grupo_dois
CREATE TABLE IF NOT EXISTS `tipo_grupo_dois` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_movimento` int(11) NOT NULL,
  `descricao` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tipo_movimento_grupo_dois` (`tipo_movimento`),
  CONSTRAINT `FK_tipo_movimento_grupo_dois` FOREIGN KEY (`tipo_movimento`) REFERENCES `tipo_movimento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Copiando dados para a tabela contas_casa.tipo_grupo_dois: ~12 rows (aproximadamente)
/*!40000 ALTER TABLE `tipo_grupo_dois` DISABLE KEYS */;
INSERT INTO `tipo_grupo_dois` (`id`, `tipo_movimento`, `descricao`) VALUES
	(1, 1, 'alimentacao'),
	(2, 1, 'moradia'),
	(3, 1, 'saude'),
	(4, 1, 'transporte'),
	(5, 1, 'vestuario'),
	(6, 1, 'lazer/informacao'),
	(7, 1, 'educacao'),
	(8, 1, 'investimentos'),
	(9, 1, 'reserva para gastos futuros'),
	(10, 1, 'outros gastos'),
	(11, 2, 'receitas'),
	(12, 3, 'saldo conta');
/*!40000 ALTER TABLE `tipo_grupo_dois` ENABLE KEYS */;

-- Copiando estrutura para tabela contas_casa.tipo_grupo_tres
CREATE TABLE IF NOT EXISTS `tipo_grupo_tres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_grupo_dois` int(11) NOT NULL,
  `descricao` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tipo_grupo_dois` (`tipo_grupo_dois`),
  CONSTRAINT `FK_tipo_grupo_dois` FOREIGN KEY (`tipo_grupo_dois`) REFERENCES `tipo_grupo_dois` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Copiando dados para a tabela contas_casa.tipo_grupo_tres: ~84 rows (aproximadamente)
/*!40000 ALTER TABLE `tipo_grupo_tres` DISABLE KEYS */;
INSERT INTO `tipo_grupo_tres` (`id`, `tipo_grupo_dois`, `descricao`) VALUES
	(1, 1, 'feira/sacolao'),
	(2, 1, 'lanches'),
	(3, 1, 'leite formula bebe'),
	(4, 1, 'pao'),
	(5, 1, 'supermercado'),
	(6, 1, 'previsao feira/sacolao'),
	(7, 1, 'previsao lanches'),
	(8, 1, 'previsao leite formula bebe'),
	(9, 1, 'previsao pao'),
	(10, 1, 'previsao supermercado'),
	(11, 2, 'condominio'),
	(12, 2, 'conta de luz'),
	(13, 2, 'conta de agua'),
	(14, 2, 'iptu'),
	(15, 2, 'manutencao moradia'),
	(16, 2, 'moveis - eletros'),
	(17, 2, 'prestacao da casa'),
	(18, 2, 'taxa lixo'),
	(19, 2, 'previsao manutencao moradia'),
	(20, 3, 'farmacia'),
	(21, 3, 'fraldas'),
	(22, 3, 'medicos/dentistas'),
	(23, 3, 'plano de saude'),
	(24, 3, 'previsao farmacia'),
	(25, 3, 'previsao fraldas'),
	(26, 4, 'combustivel'),
	(27, 4, 'estacionamentos'),
	(28, 4, 'impostos'),
	(29, 4, 'manutencao veiculos'),
	(30, 4, 'onibus/metro/trem'),
	(31, 4, 'prestacao do carro'),
	(32, 4, 'seguro'),
	(33, 4, 'previsao combustivel'),
	(34, 4, 'previsao manutencao veiculos'),
	(35, 5, 'acessorios'),
	(36, 5, 'calcados'),
	(37, 5, 'roupas'),
	(38, 5, 'previsao acessorios'),
	(39, 5, 'previsao calcados'),
	(40, 5, 'previsao roupas'),
	(41, 6, 'academia'),
	(42, 6, 'cinema / teatro / show'),
	(43, 6, 'internet'),
	(44, 6, 'jornais / revistas'),
	(45, 6, 'telefonia celular'),
	(46, 6, 'tv por assinatura'),
	(47, 7, 'cursos extras - idiomas / computacao'),
	(48, 7, 'materiais escolares'),
	(49, 7, 'mensalidades escolares'),
	(50, 7, 'uniformes'),
	(51, 8, 'renda fixa'),
	(52, 8, 'acoes'),
	(53, 8, 'empresa rapia'),
	(54, 8, 'estoque chocolate'),
	(55, 9, 'escola'),
	(56, 9, 'impostos'),
	(57, 9, 'viagem'),
	(58, 9, 'fundo de reserva'),
	(59, 10, 'auxilio familia'),
	(60, 10, 'corte cabelo'),
	(61, 10, 'despesas bancarias'),
	(62, 10, 'doacao'),
	(63, 10, 'emprestimo pessoal'),
	(64, 10, 'manicure'),
	(65, 10, 'mesada'),
	(66, 10, 'oferta'),
	(67, 10, 'presente'),
	(68, 10, 'reembolso empresa'),
	(69, 10, 'sobrancelha'),
	(70, 10, 'outros'),
	(71, 10, 'previsao corte cabelo'),
	(72, 10, 'previsao manicure'),
	(73, 10, 'previsao mesada'),
	(74, 10, 'previsao sobrancelha'),
	(75, 11, 'aluguel'),
	(76, 11, 'outros'),
	(77, 11, 'reembolso empresa'),
	(78, 11, 'salarios'),
	(79, 11, 'vale ref / alim'),
	(80, 11, 'retorno fundo de reserva'),
	(81, 11, 'rendimento renda fixa'),
	(82, 11, 'rendimento acoes'),
	(83, 11, 'dividendos'),
	(84, 12, 'saldo conta');
/*!40000 ALTER TABLE `tipo_grupo_tres` ENABLE KEYS */;

-- Copiando estrutura para tabela contas_casa.tipo_grupo_um
CREATE TABLE IF NOT EXISTS `tipo_grupo_um` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Copiando dados para a tabela contas_casa.tipo_grupo_um: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `tipo_grupo_um` DISABLE KEYS */;
INSERT INTO `tipo_grupo_um` (`id`, `descricao`) VALUES
	(1, 'variaveis'),
	(2, 'eventuais');
/*!40000 ALTER TABLE `tipo_grupo_um` ENABLE KEYS */;

-- Copiando estrutura para tabela contas_casa.tipo_movimento
CREATE TABLE IF NOT EXISTS `tipo_movimento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Copiando dados para a tabela contas_casa.tipo_movimento: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `tipo_movimento` DISABLE KEYS */;
INSERT INTO `tipo_movimento` (`id`, `descricao`) VALUES
	(1, 'despesa'),
	(2, 'receita'),
	(3, 'saldo');
/*!40000 ALTER TABLE `tipo_movimento` ENABLE KEYS */;

-- Copiando estrutura para tabela contas_casa.tipo_pgto
CREATE TABLE IF NOT EXISTS `tipo_pgto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Copiando dados para a tabela contas_casa.tipo_pgto: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `tipo_pgto` DISABLE KEYS */;
INSERT INTO `tipo_pgto` (`id`, `descricao`) VALUES
	(1, 'dinheiro'),
	(2, 'credito');
/*!40000 ALTER TABLE `tipo_pgto` ENABLE KEYS */;

-- Copiando estrutura para tabela contas_casa.tipo_situacao_pgto
CREATE TABLE IF NOT EXISTS `tipo_situacao_pgto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Copiando dados para a tabela contas_casa.tipo_situacao_pgto: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `tipo_situacao_pgto` DISABLE KEYS */;
INSERT INTO `tipo_situacao_pgto` (`id`, `descricao`) VALUES
	(1, 'pago'),
	(2, 'pendente'),
	(3, 'transferido');
/*!40000 ALTER TABLE `tipo_situacao_pgto` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
