-- Generate Maintenace Reserve Billing entries for period
-- -------------------------------------------------------
DROP PROCEDURE if exists `group05`.`generate_matches` ;
 
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
    select id, date_of_birth, gender_id, gender_preference_id, from_age,  to_age, city_id, travel_distance, relationship_type_id, c.geo_x, c.geo_y
    from user_profile up
    left join city c on c.id = up.city_id
    where id >= prm_from_user_id and id <= prm_to_user_id
    and is_administrator = false
    and user_status_id in (select id from status where is_user_status = true and status_description in ('Registered','Active'));
    
    
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
    loc_from_age,  loc_to_age, loc_city_id, loc_travel_distance, loc_relationship_type_id, loc_geo_x, loc_geo_y ;
    

-- All rows processed to exist loop
    IF done THEN
      LEAVE read_loop;
    END IF;                    
               
	-- Insert a list of matching 
    insert into match_table (
        match_user_id_1, match_user_id_2, match_date, match_status_id, match_status_date,system_generated_match)
        select loc_id, up.id, now(), 1, now(),true
        from user_profile up
        where id != loc_id
        and up.id not in (select match_user_id_1 from match_table where match_user_id_1 = loc_id and match_user_id_2 = up.id)
        and up.id not in (select match_user_id_2 from match_table where match_user_id_1 = up.id and match_user_id_2 = loc_id)
        and is_administrator = false
        and user_status_id in (select id from status where is_user_status = true and status_description in ('Registered','Active'))
        and floor(datediff(curdate(),date_of_birth) / 365) >= loc_from_age and floor(datediff(curdate(),date_of_birth) / 365) <= loc_to_age
        and gender_id = loc_gender_preference_id
        and relationship_type_id = loc_relationship_type_id
        and ( 3959 * acos( cos( radians(42.290763) ) * cos( radians( loc_geo_x ) ) 
				* cos( radians(loc_geo_y) - radians(-71.35368)) + sin(radians(42.290763)) 
				* sin( radians(loc_geo_x)))) <= loc_Travel_distance;
        
    -- reset done so that next cursor read controls it again
    set done = false; 

END LOOP;
CLOSE cur1;

-- commit all database changes
COMMIT;     
END; //
delimiter ;
