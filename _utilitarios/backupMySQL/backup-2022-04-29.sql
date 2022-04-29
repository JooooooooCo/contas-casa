/*
 Navicat Premium Data Transfer

 Source Server         : wamp
 Source Server Type    : MySQL
 Source Server Version : 50731
 Source Host           : localhost:3308
 Source Schema         : contas_casa

 Target Server Type    : MySQL
 Target Server Version : 50731
 File Encoding         : 65001

 Date: 29/04/2022 14:57:49
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for centro_custo
-- ----------------------------
DROP TABLE IF EXISTS `centro_custo`;
CREATE TABLE `centro_custo`  (
  `cd_centro_custo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ds_centro_custo` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`cd_centro_custo`) USING BTREE,
  UNIQUE INDEX `IX_cd_centro_custo`(`cd_centro_custo`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of centro_custo
-- ----------------------------
INSERT INTO `centro_custo` VALUES (1, 'Pessoal');
INSERT INTO `centro_custo` VALUES (2, 'Empresa');

-- ----------------------------
-- Table structure for centro_custo_usuario
-- ----------------------------
DROP TABLE IF EXISTS `centro_custo_usuario`;
CREATE TABLE `centro_custo_usuario`  (
  `cd_centro_custo` int(11) UNSIGNED NOT NULL,
  `cd_usuario` int(11) NOT NULL,
  PRIMARY KEY (`cd_centro_custo`, `cd_usuario`) USING BTREE,
  INDEX `fk_cd_usuario_centro_custo_usuario`(`cd_usuario`) USING BTREE,
  CONSTRAINT `fk_cd_centro_custo_centro_custo_usuario` FOREIGN KEY (`cd_centro_custo`) REFERENCES `centro_custo` (`cd_centro_custo`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_cd_usuario_centro_custo_usuario` FOREIGN KEY (`cd_usuario`) REFERENCES `usuario` (`cd_usuario`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of centro_custo_usuario
-- ----------------------------
INSERT INTO `centro_custo_usuario` VALUES (1, 1);
INSERT INTO `centro_custo_usuario` VALUES (2, 1);

-- ----------------------------
-- Table structure for fin_movimento
-- ----------------------------
DROP TABLE IF EXISTS `fin_movimento`;
CREATE TABLE `fin_movimento`  (
  `cd_movimento` int(11) NOT NULL AUTO_INCREMENT,
  `cd_tipo_movimento` int(11) NOT NULL,
  `cd_tipo_pgto` int(11) NULL DEFAULT NULL,
  `cd_tipo_situacao_pgto` int(11) NOT NULL,
  `dt_compra` date NOT NULL,
  `dt_vcto` date NOT NULL,
  `dt_pgto` date NULL DEFAULT NULL,
  `vl_original` decimal(10, 2) UNSIGNED NOT NULL,
  `vl_pago` decimal(10, 2) UNSIGNED NULL DEFAULT NULL,
  `nr_parcela_atual` int(11) NOT NULL,
  `nr_qtd_parcelas` int(11) NOT NULL,
  `cd_tipo_grupo_i` int(11) NOT NULL,
  `cd_tipo_grupo_ii` int(11) NOT NULL,
  `cd_tipo_grupo_iii` int(11) NOT NULL,
  `ds_movimento` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `ds_obs_i` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `ds_obs_ii` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `ds_media_gastos` text CHARACTER SET utf8 COLLATE utf8_bin NULL,
  `sn_real` tinyint(1) NOT NULL DEFAULT 1,
  `dt_inclusao` datetime(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
  `dt_alteracao` datetime(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  `cd_centro_custo` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`cd_movimento`) USING BTREE,
  INDEX `fk_cd_centro_custo_fin_movimento`(`cd_centro_custo`) USING BTREE,
  INDEX `fk_cd_tipo_movimento_fin_movimento`(`cd_tipo_movimento`) USING BTREE,
  INDEX `fk_cd_tipo_pgto_fin_movimento`(`cd_tipo_pgto`) USING BTREE,
  INDEX `fk_cd_tipo_situacao_pgto_fin_movimento`(`cd_tipo_situacao_pgto`) USING BTREE,
  INDEX `fk_cd_tipo_grupo_i_fin_movimento`(`cd_tipo_grupo_i`) USING BTREE,
  INDEX `fk_cd_tipo_grupo_ii_fin_movimento`(`cd_tipo_grupo_ii`) USING BTREE,
  INDEX `fk_cd_tipo_grupo_iii_fin_movimento`(`cd_tipo_grupo_iii`) USING BTREE,
  CONSTRAINT `fk_cd_centro_custo_fin_movimento` FOREIGN KEY (`cd_centro_custo`) REFERENCES `centro_custo` (`cd_centro_custo`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_cd_tipo_grupo_i_fin_movimento` FOREIGN KEY (`cd_tipo_grupo_i`) REFERENCES `tipo_grupo_i` (`cd_tipo_grupo_i`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_cd_tipo_grupo_ii_fin_movimento` FOREIGN KEY (`cd_tipo_grupo_ii`) REFERENCES `tipo_grupo_ii` (`cd_tipo_grupo_ii`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_cd_tipo_grupo_iii_fin_movimento` FOREIGN KEY (`cd_tipo_grupo_iii`) REFERENCES `tipo_grupo_iii` (`cd_tipo_grupo_iii`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_cd_tipo_movimento_fin_movimento` FOREIGN KEY (`cd_tipo_movimento`) REFERENCES `tipo_movimento` (`cd_tipo_movimento`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_cd_tipo_pgto_fin_movimento` FOREIGN KEY (`cd_tipo_pgto`) REFERENCES `tipo_pgto` (`cd_tipo_pgto`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_cd_tipo_situacao_pgto_fin_movimento` FOREIGN KEY (`cd_tipo_situacao_pgto`) REFERENCES `tipo_situacao_pgto` (`cd_tipo_situacao_pgto`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tipo_grupo_i
-- ----------------------------
DROP TABLE IF EXISTS `tipo_grupo_i`;
CREATE TABLE `tipo_grupo_i`  (
  `cd_tipo_grupo_i` int(11) NOT NULL AUTO_INCREMENT,
  `ds_tipo_grupo_i` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `dt_inclusao` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `dt_alteracao` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  `cd_centro_custo` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`cd_tipo_grupo_i`) USING BTREE,
  INDEX `fk_cd_centro_custo_tipo_grupo_i`(`cd_centro_custo`) USING BTREE,
  CONSTRAINT `fk_cd_centro_custo_tipo_grupo_i` FOREIGN KEY (`cd_centro_custo`) REFERENCES `centro_custo` (`cd_centro_custo`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tipo_grupo_i
-- ----------------------------
INSERT INTO `tipo_grupo_i` VALUES (1, 'variaveis', '2022-04-01 08:08:31', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_i` VALUES (2, 'eventuais', '2022-04-01 08:08:31', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_i` VALUES (3, 'fixas', '2022-04-27 21:41:15', '2022-04-27 21:41:15', 1);
INSERT INTO `tipo_grupo_i` VALUES (4, 'antigo', '2022-04-27 21:41:36', '2022-04-27 21:41:36', 1);

-- ----------------------------
-- Table structure for tipo_grupo_ii
-- ----------------------------
DROP TABLE IF EXISTS `tipo_grupo_ii`;
CREATE TABLE `tipo_grupo_ii`  (
  `cd_tipo_grupo_ii` int(11) NOT NULL AUTO_INCREMENT,
  `cd_tipo_movimento` int(11) NOT NULL,
  `ds_tipo_grupo_ii` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `dt_inclusao` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `dt_alteracao` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  `cd_centro_custo` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`cd_tipo_grupo_ii`) USING BTREE,
  INDEX `fk_cd_centro_custo_tipo_grupo_ii`(`cd_centro_custo`) USING BTREE,
  INDEX `fk_cd_tipo_movimento_tipo_grupo_ii`(`cd_tipo_movimento`) USING BTREE,
  CONSTRAINT `fk_cd_centro_custo_tipo_grupo_ii` FOREIGN KEY (`cd_centro_custo`) REFERENCES `centro_custo` (`cd_centro_custo`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_cd_tipo_movimento_tipo_grupo_ii` FOREIGN KEY (`cd_tipo_movimento`) REFERENCES `tipo_movimento` (`cd_tipo_movimento`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tipo_grupo_ii
-- ----------------------------
INSERT INTO `tipo_grupo_ii` VALUES (1, 1, 'alimentacao', '2022-04-01 08:08:59', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_ii` VALUES (2, 1, 'moradia', '2022-04-01 08:08:59', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_ii` VALUES (3, 1, 'saude', '2022-04-01 08:08:59', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_ii` VALUES (4, 1, 'transporte', '2022-04-01 08:08:59', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_ii` VALUES (5, 1, 'vestuario', '2022-04-01 08:08:59', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_ii` VALUES (6, 1, 'lazer/informacao', '2022-04-01 08:08:59', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_ii` VALUES (7, 1, 'educacao', '2022-04-01 08:08:59', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_ii` VALUES (8, 1, 'investimentos', '2022-04-01 08:08:59', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_ii` VALUES (9, 1, 'reserva para gastos futuros', '2022-04-01 08:08:59', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_ii` VALUES (10, 1, 'outros gastos', '2022-04-01 08:08:59', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_ii` VALUES (11, 2, 'receitas', '2022-04-01 08:08:59', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_ii` VALUES (12, 1, 'saldo conta', '2022-04-01 08:08:59', '2022-04-29 14:39:37', 1);
INSERT INTO `tipo_grupo_ii` VALUES (13, 2, 'saldo conta', '2022-04-01 08:08:59', '2022-04-29 14:39:41', 1);

-- ----------------------------
-- Table structure for tipo_grupo_iii
-- ----------------------------
DROP TABLE IF EXISTS `tipo_grupo_iii`;
CREATE TABLE `tipo_grupo_iii`  (
  `cd_tipo_grupo_iii` int(11) NOT NULL AUTO_INCREMENT,
  `cd_tipo_grupo_ii` int(11) NOT NULL,
  `ds_tipo_grupo_iii` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `dt_inclusao` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `dt_alteracao` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  `cd_centro_custo` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`cd_tipo_grupo_iii`) USING BTREE,
  INDEX `fk_cd_centro_custo_tipo_grupo_iii`(`cd_centro_custo`) USING BTREE,
  INDEX `fk_cd_tipo_grupo_ii_tipo_grupo_iii`(`cd_tipo_grupo_ii`) USING BTREE,
  INDEX `ds_tipo_grupo_iii`(`ds_tipo_grupo_iii`) USING BTREE,
  CONSTRAINT `fk_cd_centro_custo_tipo_grupo_iii` FOREIGN KEY (`cd_centro_custo`) REFERENCES `centro_custo` (`cd_centro_custo`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_cd_tipo_grupo_ii_tipo_grupo_iii` FOREIGN KEY (`cd_tipo_grupo_ii`) REFERENCES `tipo_grupo_ii` (`cd_tipo_grupo_ii`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 86 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tipo_grupo_iii
-- ----------------------------
INSERT INTO `tipo_grupo_iii` VALUES (1, 1, 'feira/sacolao', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (2, 1, 'lanches', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (3, 1, 'leite formula bebe', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (4, 1, 'pao', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (5, 1, 'supermercado', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (6, 1, 'previsao feira/sacolao', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (7, 1, 'previsao lanches', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (8, 1, 'previsao leite formula bebe', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (9, 1, 'previsao pao', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (10, 1, 'previsao supermercado', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (11, 2, 'condominio', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (12, 2, 'conta de luz', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (13, 2, 'conta de agua', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (14, 2, 'iptu', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (15, 2, 'manutencao moradia', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (16, 2, 'moveis - eletros', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (17, 2, 'prestacao da casa', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (18, 2, 'taxa lixo', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (19, 2, 'previsao manutencao moradia', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (20, 3, 'farmacia', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (21, 3, 'fraldas', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (22, 3, 'medicos/dentistas', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (23, 3, 'plano de saude', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (24, 3, 'previsao farmacia', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (25, 3, 'previsao fraldas', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (26, 4, 'combustivel', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (27, 4, 'estacionamentos', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (28, 4, 'impostos', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (29, 4, 'manutencao veiculos', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (30, 4, 'onibus/metro/trem', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (31, 4, 'prestacao do carro', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (32, 4, 'seguro', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (33, 4, 'previsao combustivel', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (34, 4, 'previsao manutencao veiculos', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (35, 5, 'acessorios', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (36, 5, 'calcados', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (37, 5, 'roupas', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (38, 5, 'previsao acessorios', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (39, 5, 'previsao calcados', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (40, 5, 'previsao roupas', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (41, 6, 'academia', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (42, 6, 'cinema / teatro / show', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (43, 6, 'internet', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (44, 6, 'jornais / revistas', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (45, 6, 'telefonia celular', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (46, 6, 'tv por assinatura', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (47, 7, 'cursos extras - idiomas / computacao', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (48, 7, 'materiais escolares', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (49, 7, 'mensalidades escolares', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (50, 7, 'uniformes', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (51, 8, 'renda fixa', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (52, 8, 'acoes', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (53, 8, 'empresa rapia', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (54, 8, 'estoque chocolate', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (55, 9, 'escola', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (56, 9, 'impostos', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (57, 9, 'viagem', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (58, 9, 'fundo de reserva', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (59, 10, 'auxilio familia', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (60, 10, 'corte cabelo', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (61, 10, 'despesas bancarias', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (62, 10, 'doacao', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (63, 10, 'emprestimo pessoal', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (64, 10, 'manicure', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (65, 10, 'mesada', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (66, 10, 'oferta', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (67, 10, 'presente', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (68, 10, 'reembolso empresa', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (69, 10, 'sobrancelha', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (70, 10, 'outros', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (71, 10, 'previsao corte cabelo', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (72, 10, 'previsao manicure', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (73, 10, 'previsao mesada', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (74, 10, 'previsao sobrancelha', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (75, 11, 'aluguel', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (76, 11, 'outros', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (77, 11, 'reembolso empresa', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (78, 11, 'salarios', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (79, 11, 'vale ref / alim', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (80, 11, 'retorno fundo de reserva', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (81, 11, 'rendimento renda fixa', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (82, 11, 'rendimento acoes', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (83, 11, 'dividendos', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (84, 12, 'saldo conta', '2022-04-01 08:08:45', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_grupo_iii` VALUES (85, 13, 'saldo conta', '2022-04-01 08:08:45', '2022-04-29 14:38:00', 1);

-- ----------------------------
-- Table structure for tipo_movimento
-- ----------------------------
DROP TABLE IF EXISTS `tipo_movimento`;
CREATE TABLE `tipo_movimento`  (
  `cd_tipo_movimento` int(11) NOT NULL AUTO_INCREMENT,
  `ds_tipo_movimento` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `dt_inclusao` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `dt_alteracao` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  `cd_centro_custo` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`cd_tipo_movimento`) USING BTREE,
  INDEX `fk_cd_centro_custo_tipo_movimento`(`cd_centro_custo`) USING BTREE,
  CONSTRAINT `fk_cd_centro_custo_tipo_movimento` FOREIGN KEY (`cd_centro_custo`) REFERENCES `centro_custo` (`cd_centro_custo`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tipo_movimento
-- ----------------------------
INSERT INTO `tipo_movimento` VALUES (1, 'despesa', '2022-04-01 08:08:17', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_movimento` VALUES (2, 'receita', '2022-04-01 08:08:17', '2022-04-01 13:16:20', 1);

-- ----------------------------
-- Table structure for tipo_pgto
-- ----------------------------
DROP TABLE IF EXISTS `tipo_pgto`;
CREATE TABLE `tipo_pgto`  (
  `cd_tipo_pgto` int(11) NOT NULL AUTO_INCREMENT,
  `ds_tipo_pgto` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `dt_inclusao` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `dt_alteracao` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  `cd_centro_custo` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`cd_tipo_pgto`) USING BTREE,
  INDEX `fk_cd_centro_custo_tipo_pgto`(`cd_centro_custo`) USING BTREE,
  CONSTRAINT `fk_cd_centro_custo_tipo_pgto` FOREIGN KEY (`cd_centro_custo`) REFERENCES `centro_custo` (`cd_centro_custo`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tipo_pgto
-- ----------------------------
INSERT INTO `tipo_pgto` VALUES (1, 'dinheiro', '2022-04-01 08:06:23', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_pgto` VALUES (2, 'credito', '2022-04-01 08:06:23', '2022-04-01 13:16:20', 1);

-- ----------------------------
-- Table structure for tipo_situacao_pgto
-- ----------------------------
DROP TABLE IF EXISTS `tipo_situacao_pgto`;
CREATE TABLE `tipo_situacao_pgto`  (
  `cd_tipo_situacao_pgto` int(11) NOT NULL AUTO_INCREMENT,
  `ds_tipo_situacao_pgto` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `dt_inclusao` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `dt_alteracao` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  `cd_centro_custo` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`cd_tipo_situacao_pgto`) USING BTREE,
  INDEX `fk_cd_centro_custo_tipo_situacao_pgto`(`cd_centro_custo`) USING BTREE,
  CONSTRAINT `fk_cd_centro_custo_tipo_situacao_pgto` FOREIGN KEY (`cd_centro_custo`) REFERENCES `centro_custo` (`cd_centro_custo`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tipo_situacao_pgto
-- ----------------------------
INSERT INTO `tipo_situacao_pgto` VALUES (1, 'pago', '2022-04-01 08:07:52', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_situacao_pgto` VALUES (2, 'pendente', '2022-04-01 08:07:52', '2022-04-01 13:16:20', 1);
INSERT INTO `tipo_situacao_pgto` VALUES (3, 'transferido', '2022-04-01 08:07:52', '2022-04-01 13:16:20', 1);

-- ----------------------------
-- Table structure for usuario
-- ----------------------------
DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario`  (
  `cd_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `ds_login` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ds_email` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ds_senha` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `dt_inclusao` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `dt_alteracao` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`cd_usuario`) USING BTREE,
  UNIQUE INDEX `FX_ds_login`(`ds_login`) USING BTREE,
  UNIQUE INDEX `FX_ds_email`(`ds_email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
