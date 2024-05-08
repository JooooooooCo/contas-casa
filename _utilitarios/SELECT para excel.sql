-- busca generica por valor
SELECT * FROM `fin_movimento` WHERE (`ds_obs_i` LIKE '%73%' AND `ds_obs_i` LIKE '%90%') OR vl_original LIKE '%73.90%' AND vl_original < 75

-- atualiza para pago (cartao)
UPDATE `fin_movimento` SET dt_pgto = '2022-09-15', cd_tipo_situacao_pgto = 1 WHERE `cd_tipo_pgto` = 2 AND `cd_tipo_situacao_pgto` = 2 AND `dt_vcto` = '2022-09-15'


-- para excel - pivot
-- lista para excel
-- CREATE VIEW contas_view AS
SELECT
    CONCAT(tm.ds_tipo_movimento, ' (', tm.cd_tipo_movimento, ')') AS 'TIPO DE MOVIMENTO',
    CONCAT(tp.ds_tipo_pgto, ' (', tp.cd_tipo_pgto, ')') AS 'MODO PAGAMENTO',
    CONCAT(ts.ds_tipo_situacao_pgto, ' (', ts.cd_tipo_situacao_pgto, ')') AS 'SITUAÇÃO',
    fm.dt_compra AS 'DATA COMPRA',
    fm.dt_vcto AS 'DATA VENCIMENTO',
    fm.dt_pgto AS 'DATA PAGAMENTO',
    fm.vl_original AS 'VALOR',
    fm.nr_parcela_atual AS 'PARCELA ATUAL',
    fm.nr_qtd_parcelas AS 'QTD PARCELAS',
    CONCAT(tg1.ds_tipo_grupo_i, ' (', tg1.cd_tipo_grupo_i, ')') AS 'GRUPO I',
    CONCAT(tg2.ds_tipo_grupo_ii, ' (', tg2.cd_tipo_grupo_ii, ')') AS 'GRUPO II',
    CONCAT(tg3.ds_tipo_grupo_iii, ' (', tg3.cd_tipo_grupo_iii, ')') AS 'GRUPO III',
    fm.ds_movimento AS 'DESCRIÇÃO',
    fm.ds_obs_i AS 'OBS I',
    fm.ds_obs_ii AS 'OBS II',
    fm.ds_media_gastos AS 'MEDIA GASTOS',
    IF(fm.sn_real = 1, 'real', 'adm') as 'MOVIMENTO',
    fm.sn_conciliado AS 'CONCILIADO',
    fm.cd_movimento AS 'cd_movimento'
FROM
    fin_movimento fm
    LEFT JOIN tipo_movimento tm ON (tm.cd_tipo_movimento = fm.cd_tipo_movimento)
    LEFT JOIN tipo_pgto tp ON (tp.cd_tipo_pgto = fm.cd_tipo_pgto)
    LEFT JOIN tipo_situacao_pgto ts ON (
        ts.cd_tipo_situacao_pgto = fm.cd_tipo_situacao_pgto
    )
    LEFT JOIN tipo_grupo_i tg1 ON (tg1.cd_tipo_grupo_i = fm.cd_tipo_grupo_i)
    LEFT JOIN tipo_grupo_ii tg2 ON (tg2.cd_tipo_grupo_ii = fm.cd_tipo_grupo_ii)
    LEFT JOIN tipo_grupo_iii tg3 ON (tg3.cd_tipo_grupo_iii = fm.cd_tipo_grupo_iii)
-- WHERE
--     fm.dt_vcto >= '2022-06-01' AND fm.dt_vcto <= '2022-08-31'
ORDER BY
    fm.dt_vcto ASC,
    tm.ds_tipo_movimento DESC,
    tp.ds_tipo_pgto ASC,
    fm.dt_compra ASC;

