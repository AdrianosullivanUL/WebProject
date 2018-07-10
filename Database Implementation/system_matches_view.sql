create view system_matches_view as 
select mt.id as match_id* from match_table mt
left join user_profile up1 on up1.id = mt.match_user_id_1
left join user_profile up2 on up1.id = mt.match_user_id_2
where  mt.system_generated_match = true
and up1.is_administrator = false and up1.is_administrator = false
and up1.user_status_id in (select id from status_master where status_description in ('Registered','Active')) 
and up2.is_administrator = false and up2.is_administrator = false
and up2.user_status_id in (select id from status_master where status_description in ('Registered','Active')) 

;

