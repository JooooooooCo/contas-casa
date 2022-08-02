UPDATE centro_custo SET ds_centro_custo = UPPER(ds_centro_custo);
UPDATE fin_movimento SET ds_movimento = UPPER(ds_movimento), ds_obs_i = UPPER(ds_obs_i), ds_obs_ii = UPPER(ds_obs_ii), ds_media_gastos = UPPER(ds_media_gastos);
UPDATE tipo_grupo_i SET ds_tipo_grupo_i = UPPER(ds_tipo_grupo_i);
UPDATE tipo_grupo_ii SET ds_tipo_grupo_ii = UPPER(ds_tipo_grupo_ii);
UPDATE tipo_grupo_iii SET ds_tipo_grupo_iii = UPPER(ds_tipo_grupo_iii);
UPDATE tipo_movimento SET ds_tipo_movimento = UPPER(ds_tipo_movimento);
UPDATE tipo_pgto SET ds_tipo_pgto = UPPER(ds_tipo_pgto);
UPDATE tipo_situacao_pgto SET ds_tipo_situacao_pgto = UPPER(ds_tipo_situacao_pgto);