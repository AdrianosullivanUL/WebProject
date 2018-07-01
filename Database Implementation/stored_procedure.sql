-- Generate Maintenace Reserve Billing entries for period
-- -------------------------------------------------------
DROP PROCEDURE if exists `engine_management`.`generate_maintenance_reserve_billing` ;
 
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
  declare loc_county varchar(100) ;
  declare loc_Travel_distance int(11) ;
  declare loc_relationship_type_id int(11) ;
  declare loc_my_bio varchar(1000) ;
  declare loc_black_listed_user tinyint(1) ;
  declare loc_black_listed_reason varchar(100);
  declare loc_black_listed_date date ;
  declare loc_user_status_id varchar(50) ;
  declare loc_is_administrator boolean ;
    declare done bit(1);

-- Create a cursor to get all un-processed engine usage entries for period 
	DECLARE cur1 CURSOR FOR  
    select *
    from user_profile up
    where id >= prm_from_user_id and id <= prm_to_user_id
    and is_administrator = false
    and user_status_id in (select id from status where is_user_status - true and status_description in ('Registered','Active'));
    
    
-- Declare the continue handler     
   DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;  
-- Declare the exception handler and roll back if issues found
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		GET DIAGNOSTICS CONDITION 1
        errno = RETURNED_SQLSTATE, msg = MESSAGE_TEXT;
		-- GET CURRENT DIAGNOSTICS CONDITION 1 errno = MYSQL_ERRNO;
        -- Note: see https://bugs.mysql.com/bug.php?id=79975 regarding issue with GET SELECT errno AS MYSQL_ERROR;
        select errno, msg;
		ROLLBACK;
    END;    
-- disable auto commit and begin transaction control
	START TRANSACTION;

-- Open the cursor and read each row into local variables for processing
OPEN cur1;

read_loop: LOOP
    FETCH cur1 INTO loc_engine_usage_id,
					loc_year,
					loc_month,
					loc_cycles,
					loc_usage_rate_id,
					loc_rate,
					loc_harsh_environment,
					loc_harsh_environment_loading;
-- All rows processed to exist loop
    IF done THEN
      LEAVE read_loop;
    END IF;                    
                   
		-- get the matching rate id and rate for this engine usage entry
	select usage_rate_id, rate into loc_usage_rate_id, loc_rate 
			from usage_rate 
			where loc_cycles >= from_cycle and loc_cycles <= to_cycle;
           
	if (loc_usage_rate_id is null) then
			set loc_usage_rate_id = 0;
	end if;

	if (loc_usage_rate_id > 0) then 
		-- Do the calculations for charges
		set loc_usage_charge = loc_rate * loc_cycles;
        if (loc_harsh_environment) then
			set loc_harsh_environment_charge = loc_usage_charge * loc_harsh_environment_loading;
			
        else
			set loc_harsh_environment_charge = 0;
        end if;
    
        -- Mark the engine usage entry as processed
		update engine_usage set billing_generated = true where engine_usage_id = loc_engine_usage_id;
     
        -- Create a new billing entry
        insert into maintenance_reserve_billing (engine_usage_id, usage_rate_id, rate, 
											     harsh_environment, harsh_environment_loading, usage_charge, 
                                                 harsh_environment_charge)
					values (loc_engine_usage_id, loc_usage_rate_id, loc_rate, 
							loc_harsh_environment, loc_harsh_environment_loading, loc_usage_charge, 
                            loc_harsh_environment_charge);
    else
		-- rate not found so report it as an error and continue processing
		select loc_engine_usage_id as 'EngineUsageId', 'No rate found for engine usage entry id' as 'Message'; 
            
    end if;
    -- reset done so that next cursor read controls it again
    set done = false; 

END LOOP;
CLOSE cur1;

-- commit all database changes
COMMIT;     
END; //
delimiter ;
