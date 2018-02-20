<?php
Class Xml
{
	/**
	 * Get subrek 1 & 4 from V_CLIENT_SUBREK14
	 */
	public static function getSubrek($client_cd)
	{
		$sql = "SELECT * FROM V_CLIENT_SUBREK14 WHERE CLIENT_CD = '$client_cd'";
		$res = DAO::queryRowSql($sql);
		return $res;
	}
	
	/**
	 * Parse date from datepicker
	 * dd/mm/yyyy to Ymd
	 */
	protected static function changeDateFormat($save_dt)
	{
		$temp = explode('/',$save_dt);
		return $temp[2].$temp[1].$temp[0];
	}
	
	/**
	 * Get Column name in mst_sys_param
	 */
	protected static function getColName($colx,$sdi_type)
	{
		$type = '';
		if($sdi_type==AConstant::CLIENT_TYPE_INDIVIDUAL)$type = 'SDII';
		else if($sdi_type==AConstant::CLIENT_TYPE_INSTITUTIONAL)$type = 'SDIC';
		
		$colx = substr($colx,3);
		$query = "select prm_desc from mst_parameter where prm_cd_1 = '$type' and trim(prm_cd_2) = '$colx'";
		$res = DAO::queryRowSql($query);
		return $res['prm_desc'];
	}
	
	/**
	 * Get Column name in mst_sys_param
	 */
	protected static function getColDesc($colx,$sdi_type)
	{
		$type = '';
		if($sdi_type==AConstant::CLIENT_TYPE_INDIVIDUAL)$type = 'SDII';
		else if($sdi_type==AConstant::CLIENT_TYPE_INSTITUTIONAL)$type = 'SDIC';
		
		$colx = substr($colx,3);
		$query = "select prm_desc,prm_desc2 from mst_parameter where prm_cd_1 = '$type' and trim(prm_cd_2) = '$colx'";
		$res = DAO::queryRowSql($query);
		return $res;
	}
	
	/**
	 * Get column for generating xml
	 * @client_cd is the client code
	 * @col_type is the client_type_1
	 */
	protected static function getXMLCol($client_cd,$col_type)
	{
		if($col_type==AConstant::CLIENT_TYPE_INDIVIDUAL):
			return Xml::getXMLColIndi($client_cd);
		elseif($col_type==AConstant::CLIENT_TYPE_INSTITUTIONAL):
			return Xml::getXMLColCom($client_cd);
		endif;
	}
	
	/**
	 * Get column for generating xml
	 * Client type 1 is Individual
	 * @client_cd
	 */
	protected static function getXMLColIndi($client_cd)
	{
		$sql = "select f.tax_id as col6,
				f.cif_name as col7,
				null as col8,
				null as col9,
		       i.nationality  as col10,
			   decode(f.ic_type,'0',  f_clean(f.client_ic_num),null) as col11,
			   decode(f.ic_type,'0',
		      decode(to_char(f.IC_EXPIRY_DT,'yyyy'),'5000','99999999',to_char(f.IC_EXPIRY_DT,'yyyymmdd')),null) as col12,
			   f.npwp_no as col13,
			   to_char(f.npwp_date,'yyyymmdd') as col14,
			   decode(f.ic_type,'2',  f.client_ic_num,null) as col15,
			   decode(f.ic_type,'2', to_char(f.IC_EXPIRY_DT,'yyyymmdd'),null) as col16,
			   nvl(i.kitas_num,f.skd_no) as col17,
			   to_char(nvl(i.kitas_EXPIRY_DT, f.skd_expiry),'yyyymmdd') as col18,
			   i.BIRTH_PLACE as col19,
			   to_char(f.client_birth_dt,'yyyymmdd') as col20,
			   i.id_addr as col21,
			   substr(i.id_rtrw|| decode(f.client_type_2,'L',' Kel.','')||i.id_klurahn,1,60) as col22,
			   substr(decode(f.client_type_2,'L',' Kec.','')||i.id_kcamatn,1,60)  as col23,
			   i.city_cd as col24,
			   i.province_cd as col25,
				substr(i.id_post_cd,1,5) as col26,
			   f.country  as col27,
			   substr(nvl(f.phone_num,f.hp_num),1,20) col28,
			   decode(f.phone_num,null,'',f.hp_num) col29,
			   f.e_mail1 as col30,
			   f.fax_num as col31,
			   decode(m.def_addr_1,i.id_addr,'',m.def_addr_1) as col32,
			   decode(m.def_addr_1,i.id_addr,'',m.def_addr_2) as col33,
			   decode(m.def_addr_1,i.id_addr,'',m.def_addr_3) as col34,
		      decode(m.def_addr_1,i.id_addr,'',decode(m.rebate_basis,'DM',f.def_city_cd,'')) as col35,
		      decode(m.def_addr_1,i.id_addr,'',decode(m.rebate_basis,'DM',f.def_province_cd,'')) as col36,
				decode(m.def_addr_1,i.id_addr,'',m.post_cd) as col37,
				null as col38,
				decode(m.rebate_basis,'KT',i.empr_phone,'') as col39,
				null as col40,
				decode(m.rebate_basis,'KT',i.empr_email,'') as col41,
				decode(m.rebate_basis,'KT',i.empr_fax,'') as col42,
			   i.sex_code as col43,
			   i.marital_status as col44,
			   i.spouse_name as col45,
				i.heir as col46,
				i.heir_relation as col47,
			   educ_code as col48,
			   occup_code as col49,
			   occupation as col50,
			   i.biz_type as col51,
			   f.income_code as col52,
			   f.fund_code as col53,
				f.source_of_funds as col54,
				null as col55,"./*
		 		b1.bank_name as col56,
			   b1.bank_acct_num as col57,
			   b1.bic as col58,
			   b1.acct_name as col59,
			   b1.currency as col60,
			   b2.bank_name as col61,
			   b2.bank_acct_num as col62,
			   b2.bic as col63,
			   b2.acct_name as col64,
			   b2.currency as col65,*/"
			   null as col55,
			   null as col56,
			   null as col57,
			   null as col58,
			   null as col59,
			   null as col60,
			   null as col61,
			   null as col62,
			   null as col63,
			   null as col64,
			   null as col65,
			   null as col66,
			   null as col67,
			   null as col68,
			   null as col69,
			   null as col70,
			   f.purpose as col71,
			   f.mother_name as col72,
		      f.direct_sid as col73,
		      f.asset_owner as col74
		from(  select cifs,nationality,
					 decode(trim(i.birth_place),'JAKARTA',trim(i.birth_place),nvl(z.city, 'OTHERS')) birth_place,
					 p1.sex_code,
			 		 id_addr,id_rtrw, i.id_klurahn,
					 i.id_kcamatn,	y.city_cd, y.province_cd, i.id_post_cd,
					 decode(p4.marital_status,'3',decode(i.sex_code,'F','4','3'),p4.marital_status ) as marital_status,
					 spouse_name, 
					 nvl(p2.educ_code,'1') educ_code,
					 p3.occup_code,
					 decode(p3.occup_code,'1',i.occupation,'') occupation,
					 decode(p3.occup_code,'5',i.empr_biz_type,'') biz_type,
		          heir, heir_relation,
				  empr_phone, empr_fax, empr_email,
					 kitas_num, kitas_expiry_dt
			 from mst_client_indi i, mst_city y, mst_city z,
				 ( select prm_cd_2, prm_desc2 sex_code
				   from mst_parameter
				   where prm_cd_1 = 'GENDER') p1,
			   ( select prm_cd_2, prm_desc2 educ_code
			   from mst_parameter
			   where prm_cd_1 = 'EDUC') p2,
			   ( select prm_cd_2, prm_desc2 occup_code
			   from mst_parameter
			   where prm_cd_1 = 'WORK') p3,
			   ( select prm_cd_2, prm_desc2 marital_status
			   from mst_parameter
			   where prm_cd_1 = 'MARITL') p4
			  where i.id_kota = y.city(+)
				 and i.birth_place = z.city(+)
			    and i.sex_code = p1.prm_cd_2 
				and i.educ_code  = p2.prm_cd_2(+)
				and i.OCCUP_CODE = p3.prm_cd_2(+)
				and i.marital_status = p4.prm_cd_2(+)
			 )	  i,
			 ( select mst_cif.cifs, cif_name,tax_id, ic_type,  	  	client_ic_num,
			          ic_expiry_dt,    npwp_no,  		npwp_date,
					  client_birth_dt, 	client_type_2, 	country,
					  phone_num,	   	hp_num,			e_mail1,
					  fax_num,		   	y.city_cd as def_city_cd,	y.province_cd as def_province_cd,
						r1.income_code,	r2.fund_code,	 
						decode(funds_code,'90',source_of_funds,'') source_of_funds,
						mother_name,
					   pu.purpose, direct_sid, asset_owner,
						skd_no, skd_expiry    
			   from mst_cif, v_sdi_purpose pu,
			       mst_city y, 
				   ( select prm_cd_2, prm_desc2 income_code
				   from mst_parameter
				   where prm_cd_1 = 'INCOME') r1,
				   ( select prm_cd_2, prm_desc2 fund_code
				   from mst_parameter
				   where prm_cd_1 = 'FUND') r2
			   where mst_cif.annual_income_cd = r1.prm_cd_2(+)
				and mst_cif.funds_code = r2.prm_cd_2(+)
				and mst_cif.cifs = pu.cifs(+)	
				and mst_cif.def_city = y.city(+))	  f,
			( SELECT m.cifs,NVL(bi.bank_name, p.bank_name) bank_name, b.bank_acct_num, bi.rtgs_code AS bic, 
					b.acct_name, 'IDR' currency
				  FROM MST_CLIENT m, MST_CLIENT_BANK b, (SELECT * FROM MST_BANK_BI WHERE APPROVED_STAT='A') bi,
				   ( SELECT BANK_CD,  bank_name,bi_code
                      FROM MST_IP_BANK
                      WHERE APPROVED_STAT = 'A') p
				  WHERE m.cifs = b.cifs
				  AND m.client_Cd = '$client_cd' 
				  AND m.bank_acct_num = b.bank_acct_num
				  --AND b.bank_cd = bi.ip_bank_cd(+)--[IN] 27/07/2017 ip_bank_cd gk dipake lagi
                  AND  p.bi_code = bi.bi_code(+)
				  AND b.bank_cd = p.bank_cd(+)) b1,
		   (
		    SELECT m.cifs, NVL(bi.bank_name, p.bank_name) bank_name, b.bank_acct_num, bi.rtgs_code AS bic, 
					b.acct_name, 'IDR' currency
				  FROM MST_CLIENT m, MST_CLIENT_BANK b, (SELECT * FROM MST_BANK_BI WHERE APPROVED_STAT='A') bi,
				      ( SELECT BANK_CD,  bank_name, bi_code
                      FROM MST_IP_BANK
                      WHERE APPROVED_STAT = 'A') p
				  WHERE m.cifs = b.cifs
				  AND m.client_Cd = '$client_cd' 
				  AND m.bank_acct_num <> b.bank_acct_num
				 -- AND b.bank_cd = bi.ip_bank_cd(+)--[IN] 27/07/2017 ip_bank_cd gk dipake lagi
				 and p.bi_code=bi.bi_code(+)
				  AND b.bank_cd = p.bank_cd(+)
		        AND ROWNUM = 1
				 ) b2,				
			  mst_client m 
		where client_Cd = '$client_cd' 
		and m.cifs = f.cifs
		and m.cifs = i.cifs
		and m.cifs = b1.cifs(+)
		and m.cifs = b2.cifs(+)
		";
		$res = DAO::queryRowSql($sql);
		return $res;
	}
	
	/**
	 * Get column for generating xml
	 * Client type 1 is Company
	 */
	protected static function getXMLColCom($client_cd)
	{
		$sql = "select f.cif_name as col6,
			   null as col7,
			   f.tax_id   as col8,
			   f.country  as col9,
			   f.npwp_no  as col10,
		      to_char(f.npwp_date,'yyyymmdd') as col11,
			   f.skd_no   as col12,
			   to_char(f.SKD_EXPIRY,'yyyymmdd') as col13,
			   decode(f.country,'INDONESIA',decode(substr(f.tempat_pendirian,1,7),'JAKARTA','JAKARTA',f.tempat_pendirian),'OTHERS') as col14,
			   to_char(f.client_birth_dt,'yyyymmdd') as col15,
			   f.def_Addr_1 as col16,
			   f.def_Addr_2 as col17,
			   f.def_Addr_3 as col18,
		      f.def_city as col19,
		      y.province as col20,
			  f.post_cd as col21,
			   f.country  as col22,
			   f.phone_num as col23,
			   f.hp_num    as col24,
			   f.e_mail1   as col25,
			   f.fax_num   as col26,
			   decode(m.rebate_basis,'SU',m.def_addr_1,'') as col27,
			   decode(m.rebate_basis,'SU',m.def_addr_2,'') as col28,
		   	   decode(m.rebate_basis,'SU',m.def_addr_3,'') as col29,
		   	   null as col30,
		   	   null as col31,
		   	   decode(m.rebate_basis,'SU',m.post_cd,'') as col32,
		   	   null as col33,
		   	   null as col34,
		   	   null as col35,
		   	   null as col36,
		   	   null as col37,
			   r1.biz_code  as col38,
			   r2.char_cd   as col39,
			   r3.fund_cd   as col40,
			   f.act_first  as col41,
			   f.siup_no    as col42,
			   substr(f.autho_person_name,1,40) as col43,
			   null as col44,
			   null as col45,
			   f.autho_person_position as col46,
			   decode(f.autho_person_ic_type,'0',f_clean(f.autho_person_ic_num),'') as col47,
			   decode(f.autho_person_ic_type,'0',to_char(f.autho_person_ic_expiry,'yyyymmdd'),'') as col48,
			   decode(f.autho_person_ic_type,'4',f_clean(f.autho_person_ic_num),'') as col49,
			   decode(f.autho_person_ic_type,'4',to_char(f.autho_person_ic_expiry,'yyyymmdd'),'') as col50,
			   decode(f.autho_person_ic_type,'2',f_clean(f.autho_person_ic_num),'') as col51,
			   decode(f.autho_person_ic_type,'2',to_char(f.autho_person_ic_expiry,'yyyymmdd'),'') as col52,
			   decode(f.autho_person_ic_type,'5',f_clean(f.autho_person_ic_num),'') as col53,
			   decode(f.autho_person_ic_type,'5',to_char(f.autho_person_ic_expiry,'yyyymmdd'),'') as col54,
			   a2.first_name as col55,
			   a2.middle_name as col56,
		   	a2.last_name as col57,
				a2.POSITION    as col58,
				f_clean(a2.KTP_NO)      as col59,
				to_char(a2.KTP_EXPIRY,'yyyymmdd') as col60,
				f_clean(a2.NPWP_NO)     as col61,
				to_char(a2.NPWP_DATE,'yyyymmdd') as col62,
				f_clean(a2.PASSPORT_NO) as col63,
				to_char(a2.PASSPORT_EXPIRY,'yyyymmdd') as col64,
				f_clean(a2.KITAS_NO)    as col65,
				to_char(a2.KITAS_EXPIRY,'yyyymmdd') as col66,
			   a3.first_name as col67,
			   a3.middle_name as col68,
		   	a3.last_name as col69,
				a3.POSITION    as col70,
				f_clean(a3.KTP_NO)      as col71,
				to_char(a3.KTP_EXPIRY,'yyyymmdd') as col72,
				f_clean(a3.NPWP_NO)     as col73,
				to_char(a3.NPWP_DATE,'yyyymmdd') as col74,
				f_clean(a3.PASSPORT_NO) as col75,
				to_char(a3.PASSPORT_EXPIRY,'yyyymmdd') as col76,
				f_clean(a3.KITAS_NO)    as col77,
				to_char(a3.KITAS_EXPIRY,'yyyymmdd') as col78,
			   a4.first_name as col79,
			   a4.middle_name as col80,
		   	a4.last_name as col81,
				a4.POSITION    as col82,
				f_clean(a4.KTP_NO)      as col83,
				to_char(a4.KTP_EXPIRY,'yyyymmdd') as col84,
				f_clean(a4.NPWP_NO)     as col85,
				to_char(a4.NPWP_DATE,'yyyymmdd') as col86,
				f_clean(a4.PASSPORT_NO) as col87,
				to_char(a4.PASSPORT_EXPIRY,'yyyymmdd') as col88,
				f_clean(a4.KITAS_NO)    as col89,
				to_char(a4.KITAS_EXPIRY,'yyyymmdd') as col90,
			   r4.asset_cd   as col91,
			   null as col92,
			   null as col93,
			   r5.profit_cd  as col94,
			   null as col95,
			   null as col96,
			   null as col97,
			   null as col98,
			   null as col99,
			   null as col100,
			   null as col101,
			   null as col102,".
			   /*b1.bank_name  as col103,
			   b1.bank_acct_num as col104,
			   b1.bic		 as col105,
			   b1.acct_name  as col106,
			   b1.currency   as col107,
			   b2.bank_name  as col108,
			   b2.bank_acct_num as col109,
			   b2.bic		 as col110,
			   b2.acct_name  as col111,
			   b2.currency   as col112,
			   */"
			   null as col103,
			   null as col104,
			   null as col105,
			   null as col106,
			   null as col107,
			   null as col108,
			   null as col109,
			   null as col110,
			   null as col111,
			   null as col112,
			   pu.purpose    as col113,
			   f.direct_sid   as col114,
			   f.asset_owner  as col115
		from  mst_client m, mst_cif f, v_sdi_purpose pu, 
		      mst_city y,
		   ( select prm_cd_2, prm_desc2 biz_code
		   from mst_parameter
		   where prm_cd_1 = 'BIZTYP') r1,
		   ( select prm_cd_2, prm_desc2 char_cd
		   from mst_parameter
		   where prm_cd_1 = 'KARAK') r2,
		   ( select prm_cd_2, DECODE(prm_desc2,'3','2', PRM_DESC2) fund_cd
		   from mst_parameter
		   where prm_cd_1 = 'FUNDC') r3,
		   ( select prm_cd_2, prm_desc2 asset_cd
		   from mst_parameter
		   where (prm_cd_1 = 'NASSET' or prm_cd_1 = 'TASSET' )) r4,
		   ( select prm_cd_2, prm_desc2 profit_cd
		   from mst_parameter
		   where prm_cd_1 = 'PROFIT') r5,
		  ( SELECT *
		  	FROM( SELECT 
			  	CIFS, row_number() over (partition by cifs order by cifs,seqno) norut,
				 	FIRST_NAME, 
			   MIDDLE_NAME, LAST_NAME, POSITION, 
			   NPWP_NO, NPWP_DATE, KTP_NO, 
			   KTP_EXPIRY, PASSPORT_NO, PASSPORT_EXPIRY, 
			   KITAS_NO, KITAS_EXPIRY
			   FROM MST_CLIENT_AUTHO)
			   WHERE NORUT = 1  ) a2,
		  ( SELECT *
		  	FROM( SELECT 
			  	CIFS, row_number() over (partition by cifs order by cifs,seqno) norut,
				 	FIRST_NAME, 
			   MIDDLE_NAME, LAST_NAME, POSITION, 
			   NPWP_NO, NPWP_DATE, KTP_NO, 
			   KTP_EXPIRY, PASSPORT_NO, PASSPORT_EXPIRY, 
			   KITAS_NO, KITAS_EXPIRY
			   FROM MST_CLIENT_AUTHO)
			   WHERE NORUT = 2  ) a3,
			( SELECT *
		  	FROM( SELECT 
			  	CIFS, row_number() over (partition by cifs order by cifs,seqno) norut,
				 	FIRST_NAME, 
			   MIDDLE_NAME, LAST_NAME, POSITION, 
			   NPWP_NO, NPWP_DATE, KTP_NO, 
			   KTP_EXPIRY, PASSPORT_NO, PASSPORT_EXPIRY, 
			   KITAS_NO, KITAS_EXPIRY
			   FROM MST_CLIENT_AUTHO)
			   WHERE NORUT = 3  ) a4,
			( SELECT m.cifs,NVL(bi.bank_name, p.bank_name) bank_name, b.bank_acct_num, bi.rtgs_code AS bic, 
					b.acct_name, 'IDR' currency
				  FROM MST_CLIENT m, MST_CLIENT_BANK b, (SELECT * FROM MST_BANK_BI WHERE APPROVED_STAT='A') bi,
				   (SELECT BANK_CD,  bank_name, bi_code
                      FROM MST_IP_BANK
                      WHERE APPROVED_STAT = 'A') p
				  WHERE m.cifs = b.cifs
				  AND m.client_Cd = '$client_cd' 
				  AND m.bank_acct_num = b.bank_acct_num
				  --AND b.bank_cd = bi.ip_bank_cd(+) --[IN] 27/07/2017 ip_bank_cd gk dipake lagi
				  AND p.bi_code = bi.bi_code(+)
				  AND b.bank_cd = p.bank_cd(+)) b1,
		   (
		    SELECT m.cifs, NVL(bi.bank_name, p.bank_name) bank_name, b.bank_acct_num, bi.rtgs_code AS bic, 
					b.acct_name, 'IDR' currency
				  FROM MST_CLIENT m, MST_CLIENT_BANK b, (SELECT * FROM MST_BANK_BI WHERE APPROVED_STAT='A') bi,
				      (SELECT BANK_CD,  bank_name, bi_code
                      FROM MST_IP_BANK
                      WHERE APPROVED_STAT = 'A') p
				  WHERE m.cifs = b.cifs
				  AND m.client_Cd = '$client_cd' 
				  AND m.bank_acct_num <> b.bank_acct_num
				  --AND b.bank_cd = bi.ip_bank_cd(+)--[IN] 27/07/2017 ip_bank_cd gk dipake lagi
				  AND p.bi_code = bi.bi_code(+)
				  AND b.bank_cd = p.bank_cd(+)
		        AND ROWNUM = 1
				 ) b2	      
		where client_Cd = '$client_cd' 
		and m.cifs = f.cifs
		and f.biz_type = r1.prm_cd_2(+)
		and f.inst_type = r2.prm_cd_2(+)
		and f.funds_code = r3.prm_cd_2(+)
		and f.net_asset_cd = r4.prm_cd_2(+)
		and f.profit_cd = r5.prm_cd_2(+)
		and m.cifs = pu.cifs(+)
		and f.def_city = y.city(+)
		and m.cifs = a2.cifs(+)
		and m.cifs = a3.cifs(+)
		and m.cifs = a4.cifs(+)
		and m.cifs = b1.cifs(+)
		and m.cifs = b2.cifs(+)";
		$res = DAO::queryRowSql($sql);
		return $res;
	}
}
