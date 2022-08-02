-- lista para excel
SELECT
    tm.ds_tipo_movimento,
    tp.ds_tipo_pgto,
    ts.ds_tipo_situacao_pgto,
    fm.dt_compra,
    fm.dt_vcto,
    fm.dt_pgto,
    fm.vl_original,
    fm.ds_movimento,
    fm.ds_obs_i,
    fm.ds_obs_ii,
    fm.ds_media_gastos,
    fm.sn_real
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
WHERE
    fm.dt_vcto >= '2022-07-01' AND fm.dt_vcto <= '2022-07-31'
ORDER BY
    fm.dt_vcto ASC,
    tm.ds_tipo_movimento DESC,
    tp.ds_tipo_pgto ASC,
    fm.dt_compra ASC


-- busca generica por valor
SELECT * FROM `fin_movimento` WHERE (`ds_obs_i` LIKE '%73%' AND `ds_obs_i` LIKE '%90%') OR vl_original LIKE '%73.90%' AND vl_original < 75

-- atualiza para pago (cartao)
UPDATE `fin_movimento` SET dt_pgto = '2022-06-15', cd_tipo_situacao_pgto = 1 WHERE `cd_tipo_situacao_pgto` = 2 AND `dt_vcto` = '2022-06-15'
