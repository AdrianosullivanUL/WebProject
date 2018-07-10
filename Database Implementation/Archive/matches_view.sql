create view matches_view as 
select 
    mt.id as match_id,
    mt.match_user_id_1 ,
    mt.match_user_id_2 ,
    mt.match_date ,
    mt.response_date ,
    mt.user_id_1_interest_level ,
    mt.user_id_2_interest_level ,
    mt.communication_id ,
    mt.user_1_match_status_id,
    ms1.status_description user_profile_1_match_status,
    mt.user_1_match_status_date ,
    mt.user_2_match_status_id ,
    ms1.status_description user_profile2_match_status,
    mt.user_2_match_status_date ,
    mt.system_generated_match ,
  up1.first_name user_profile_1_first_name,
  up1.surname user_profile_1_surname,
  up1.date_of_birth user_profile_1_date_of_birth,
  up1.gender_id user_profile_1_gender_id,
  g1.gender_name user_profile_1_gender,
  up1.gender_preference_id user_profile_1_gender_preference_id,
  gp1.gender_name user_profile_1_gender_preference,
  up1.From_age user_profile_1_from_age,
  up1.to_age user_profile_1_to_age,
  up1.city_id user_profile_1_city_id,
  c1.city as user_profile_1_city,
  up1.Travel_distance user_profile_1_Travel_distance,
  up1.relationship_type_id user_profile_1_relationship_type_id,
  rt1.relationship_type user_profile_1_relationship_type,
  up1.picture user_profile_1_picture,
  up1.my_bio user_profile_1_my_bio,
  up1.user_status_id user_profile_1_user_status_id,
  us1.status_description user_profile_1_status,
    up2.first_name user_profile_2_first_name,
  up2.surname user_profile_2_surname,
  up2.date_of_birth user_profile_2_date_of_birth,
  up2.gender_id user_profile_2_gender_id,
  g2.gender_name user_profile_2_gender,
  up2.gender_preference_id user_profile_2_gender_preference_id,
  gp2.gender_name user_profile_2_gender_preference,
  up2.From_age user_profile_2_from_age,
  up2.to_age user_profile_2_to_age,
  up2.city_id user_profile_2_city_id,
  c2.city as user_profile_2_city,
  up2.Travel_distance user_profile_2_Travel_distance,
  up2.relationship_type_id user_profile_2_relationship_type_id,
  rt2.relationship_type user_profile_2_relationship_type,
  up2.picture user_profile_2_picture,
  up2.my_bio user_profile_2_my_bio,
  up2.user_status_id user_profile_2_user_status_id,
  us2.status_description user_profile_2_status
from match_table mt
 join status_master ms1 on ms1.id = mt.user_1_match_status_id
 join status_master ms2 on ms2.id = mt.user_2_match_status_id
 join user_profile up1 on up1.id = mt.match_user_id_1
 join city c1 on c1.id = up1.city_id
 join status_master us1 on us1.id = up1.user_status_id
 join relationship_type rt1 on rt1.id = up1.relationship_type_id
 join gender g1 on g1.id = up1.gender_id
 join gender gp1 on gp1.id = up1.gender_preference_id
 join user_profile up2 on up2.id = mt.match_user_id_2
 join city c2 on c2.id = up2.city_id
 join status_master us2 on us2.id = up2.user_status_id
 join relationship_type rt2 on rt2.id = up2.relationship_type_id
  join gender g2 on g2.id = up2.gender_id
 join gender gp2 on gp2.id = up2.gender_preference_id
where  up1.is_administrator = false and up1.is_administrator = false
and up1.user_status_id in (select id from status_master where status_description in ('Registered','Active')) and up2.is_administrator = false and up2.is_administrator = false
and up2.user_status_id in (select id from status_master where status_description in ('Registered','Active')) ;



