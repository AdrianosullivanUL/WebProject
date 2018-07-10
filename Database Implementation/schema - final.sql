use group05;

-- Drop Tables (in reverse order of creation 
drop table if exists user_interests;
drop table if exists match_table;
drop table if exists user_communication;
drop table if exists user_profile;
drop table if exists status_master;
drop table if exists black_list_word;
drop table if exists interests;
drop table if exists relationship_type;
drop table if exists city;
drop table if exists gender;
DROP view if exists `group05`.`matches_view` ;
DROP PROCEDURE if exists `group05`.`generate_matches` ;

-- Gender
-- ------------------------------------------------

CREATE TABLE `gender` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gender_name` varchar(200) NOT NULL,
  Primary Key(id)
);
-- City
-- ------------------------------------------------

create table city(
id  int NOT NULL AUTO_INCREMENT,
city Varchar(100) NOT NULL,
county Varchar(100) NOT NULL,
geo_x float,
geo_y float,
Primary Key(id),
unique key (city)
);

-- relationship_type
-- ------------------------------------------------

create table relationship_type(
id int NOT NULL AUTO_INCREMENT,
relationship_type Varchar(200) NOT NULL,
PRIMARY KEY(id),
unique key(relationship_type)
);    


-- interests
-- ------------------------------------------------

create table interests(
id int NOT NULL AUTO_INCREMENT,
description Varchar(200) NOT NULL,
PRIMARY KEY(id)
);  

-- black_list_words
-- ------------------------------------------------

create table black_list_word(
	id int NOT NULL AUTO_INCREMENT,
	word varchar(100) NOT NULL,
    PRIMARY KEY(id),
    UNIQUE KEY(word)
    );


-- status
-- ------------------------------------------------

create table status_master(
	id int NOT NULL AUTO_INCREMENT,
	status_description varchar(100) NOT NULL,
    is_user_status boolean,
    is_match_table_status boolean,
    is_user_communication_status boolean,
    PRIMARY KEY(id)
    );

-- user_profile
-- ------------------------------------------------

CREATE TABLE `user_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `password_hash` varchar(200) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender_id` int(11) DEFAULT NULL,
  `gender_preference_id` int(11) DEFAULT NULL,
  `From_age` int(11) DEFAULT NULL,
  `to_age` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `Travel_distance` int(11) DEFAULT NULL,
  `relationship_type_id` int(11) DEFAULT NULL,
  `picture` mediumblob,
  `my_bio` varchar(1000) DEFAULT NULL,
  `black_listed_user` tinyint(1) DEFAULT NULL,
  `black_listed_reason` varchar(100) NOT NULL,
  `black_listed_date` date DEFAULT NULL,
  `user_status_id` int NOT NULL,
  `is_administrator` boolean NOT NULL,
   PRIMARY KEY(id),
   FOREIGN KEY (gender_id) REFERENCES gender(id),
   FOREIGN KEY (gender_preference_id) REFERENCES gender(id),
   FOREIGN KEY (city_id) REFERENCES city(id),
   FOREIGN KEY (relationship_type_id) REFERENCES relationship_type(id),
   FOREIGN KEY (user_status_id) REFERENCES status_master(id),
   UNIQUE KEY(email)
);

-- user_communication
-- ------------------------------------------------

create table user_communication(
	id int NOT NULL AUTO_INCREMENT,
    from_user_id int,
    communication_datetime datetime,
    message varchar(140) NOT NULL,
    status_id int NOT NULL,
    to_user_id int,
    replying_to_communication_id int,
    black_listed boolean,
    black_listed_date datetime,
	black_listed_word_id int,
    PRIMARY KEY(id),
    FOREIGN KEY (from_user_id) REFERENCES user_profile(id),
    FOREIGN KEY (to_user_id) REFERENCES user_profile(id),
    FOREIGN KEY (status_id) REFERENCES status_master(id),
    FOREIGN KEY (black_listed_word_id) REFERENCES black_list_word(id)
    );

-- match_table
-- ------------------------------------------------

create table match_table(
    id int NOT NULL AUTO_INCREMENT,
    match_user_id_1 int,
    match_user_id_2 int,
    match_date datetime,
    response_date datetime,
    user_id_1_interest_level int,
    user_id_2_interest_level int,
    communication_id int,
    user_1_match_status_id int NOT NULL,
    user_1_match_status_date datetime,
    user_2_match_status_id int NOT NULL,
    user_2_match_status_date datetime,
    system_generated_match boolean,
    PRIMARY KEY(id),
    FOREIGN KEY (match_user_id_1) REFERENCES user_profile(id),
    FOREIGN KEY (match_user_id_2) REFERENCES user_profile(id),
    FOREIGN KEY (user_1_match_status_id) REFERENCES status_master(id),
    FOREIGN KEY (user_2_match_status_id) REFERENCES status_master(id),
    FOREIGN KEY (communication_id) REFERENCES user_communication(id),
    UNIQUE KEY(match_user_id_1, match_user_id_2)
    );

-- user_interests
-- ------------------------------------------------

create table user_interests(
id int NOT NULL AUTO_INCREMENT,
interest_id int not null,
user_id int NOT NULL,
PRIMARY KEY(id),
FOREIGN KEY (user_id) REFERENCES user_profile(id),
FOREIGN KEY (interest_id) REFERENCES interests(id),
UNIQUE KEY(interest_id, user_id)
);


-- match view
-- ------------------------------------------------

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

-- stored procedure
-- ------------------------------------------------
use group05;

DELIMITER //
 create procedure  generate_matches(prm_from_user_id int, prm_to_user_id int)
BEGIN
	DECLARE loc_invalidParam CONDITION FOR SQLSTATE '48000';
    DECLARE errno INT;
    declare msg varchar(250);
    declare loc_message varchar(250);
   
   declare loc_id int(11);
  declare loc_date_of_birth date ;
  declare loc_gender_id int(11) ;
  declare loc_gender_preference_id int(11) ;
  declare loc_From_age int(11) ;
  declare loc_to_age int(11) ;
  declare loc_city_id int(11) ;
  declare loc_Travel_distance int(11) ;
  declare loc_relationship_type_id int(11) ;
  declare loc_geo_x float;
  declare loc_geo_y float;
    declare done bit(1);

-- Create a cursor to get all un-processed engine usage entries for period 
	DECLARE cur1 CURSOR FOR  
    select  up.id, up.date_of_birth, up.gender_id, up.gender_preference_id, up.from_age,  up.to_age, 
			up.city_id, up.travel_distance, up.relationship_type_id, c.geo_x, c.geo_y
    from user_profile up
    left join city c on c.id = up.city_id
    where up.id >= prm_from_user_id and up.id <= prm_to_user_id
		and up.is_administrator = false
		and up.user_status_id in (select id from status_master where is_user_status = true and status_description in ('Registered','Active'));
    
    
-- Declare the continue handler     
   DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;  
-- Declare the exception handler and roll back if issues found
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		GET DIAGNOSTICS CONDITION 1
        errno = RETURNED_SQLSTATE, msg = MESSAGE_TEXT;
		-- GET CURRENT DIAGNOSTICS CONDITION 1 errno = MYSQL_ERRNO;
SELECT errno, msg;
		ROLLBACK;
    END;    
-- disable auto commit and begin transaction control
	START TRANSACTION;

-- Open the cursor and read each row into local variables for processing
OPEN cur1;

read_loop: LOOP
    FETCH cur1 INTO loc_id,  loc_date_of_birth, loc_gender_id, loc_gender_preference_id, 
					loc_from_age,  loc_to_age, loc_city_id, loc_travel_distance, loc_relationship_type_id, 
                    loc_geo_x, loc_geo_y ;

-- All rows processed to exist loop
    IF done THEN
      LEAVE read_loop;
    END IF;                    
               
	-- Insert a list of matching profiles
    -- TODO - Fix problem with matching not functioning correctly
    insert into match_table (match_user_id_1, match_user_id_2, match_date, 
                             user_1_match_status_id, user_1_match_status_date,
                             user_2_match_status_id, user_2_match_status_date,
                             system_generated_match)
        select loc_id, up.id, now(), 1, now(),1, now(),true
        from user_profile up
        join city c on c.id = up.city_id
        where up.id != loc_id
        and loc_id not in (select match_user_id_1 
								from match_table 
                                where (match_user_id_1 = loc_id and match_user_id_2 = up.id)
                                or (match_user_id_1 = up.id and match_user_id_2 = loc_id))
        and loc_id not in (select match_user_id_2 
								from match_table 
                                where (match_user_id_1 = up.id and match_user_id_2 = loc_id)
                                or (match_user_id_1 = loc_id and match_user_id_2 = up.id))
        and is_administrator = false
        and user_status_id in (select id from status_master where is_user_status = true and status_description in ('Registered','Active'))
        and floor(datediff(curdate(),date_of_birth) / 365) >= loc_from_age and floor(datediff(curdate(),date_of_birth) / 365) <= loc_to_age
        and gender_id = loc_gender_preference_id
        and relationship_type_id = loc_relationship_type_id
        and ( 6372.795 * acos( cos( radians(c.geo_x) ) * cos( radians( loc_geo_x ) ) 
				* cos( radians(loc_geo_y) - radians(c.geo_y)) + sin(radians(c.geo_x)) 
				* sin( radians(loc_geo_x)))) <= loc_Travel_distance;
        
    -- reset done so that next cursor read controls it again
    set done = false; 

END LOOP;
CLOSE cur1;

-- commit all database changes
COMMIT;     
END; //
delimiter ;



