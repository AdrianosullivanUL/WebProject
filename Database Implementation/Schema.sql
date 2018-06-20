-- -------------------------------------------------------
-- Create Schema
-- -------------------------------------------------------
 
-- Create a new Schema for the database
CREATE SCHEMA if not exists `first_chance_saloon` ;

-- Select the schema
USE first_chance_saloon;

-- -------------------------------------------------------
-- Create Tables
-- -------------------------------------------------------

-- Create the Dating site Tables
drop table if exists user_profile;
create table user_profile(
	id int NOT NULL AUTO_INCREMENT,
    password_hash varchar(200) NOT NULL,
    first_name varchar(50) NOT NULL,
    sur_name varchar(100) NOT NULL,
    email varchar(100) NOT NULL,
    date_of_birth date,
    sex bool,
    gender_preference varchar(20) NOT NULL,
    Black_listed_date date,
    black_listed_user bool,
    black_listed_reason varchar(100) NOT NULL,
    black_listed_date date,
    user_status varchar(50) NOT NULL,
    PRIMARY KEY (id)
    );

-- Create teh Match table
drop table if exists match_table;
create table match_table(
    initiating_user_id int NOT NULL AUTO_INCREMENT,
    initiation_date datetime,
    initiation_interest_level int,
    initiation_prefered_meeting_location varchar(150) NOT NULL,
    initiating_preferred_meet_datetime datetime,
    i_status varchar(150) NOT NULL,
    i_status_date datetime,
    reciprocating_user_id int,
    reciprocating_response_date datetime,
    reciprocating_response varchar(2000) NOT NULL,
    reciprocating_interest_level int,
    PRIMARY KEY(initiating_user_id),
    PRIMARY KEY(reciprocating_user_id)
    );
    
    
      
-- Create the user Communication table
drop table if exists user_communication;
create table user_communication(
	communication_id int NOT NULL AUTO_INCREMENT,
    from_user_id int,
    communication_date datetime,
    message varchar(150) NOT NULL,
    com_status varchar(20) NOT NULL,
    to_user_id int,
    replying_to_communication_id int,
    black_listed bool,
    black_listed_date datetime,
	black_listed_word_id int,
    PRIMARY KEY(from_user_id)
    );
   
-- Create the Preferred Age range table
drop table if exists perferred_age_ranges;
create table perferred_age_ranges(
	range_id int NOT NULL AUTO_INCREMENT,
    From_age int,
    to_age	int,
    PRIMARY KEY(range_id)
    );
    


-- Create the Black Listed Words Table 
drop table if exists black_list_words;
create table black_list_words(
	black_id int NOT NULL AUTO_INCREMENT,
	word varchar(100) NOT NULL,
    PRIMARY KEY(black_id)
    );

-- Create the User Seek Table 
drop table if exists user_seek_code;
create table user_seek_code(
	user_id	int NOT NULL AUTO_INCREMENT,
	Seek_code_id int,
    PRIMARY KEY(user_id)
    );

-- Create the Seek Code Table
drop table if exists seek_code_master;
create table seek_code_master(
	seek_id	int NOT NULL AUTO_INCREMENT,
	seek_description varchar(100) NOT NULL,
    PRIMARY KEY(seek_id)
    );

-- Create the User Interests tables
drop table if exists user_interests;
create table user_interests(
	user_id	int NOT NULL AUTO_INCREMENT,
	interest_id int,
    PRIMARY KEY(user_id),
    PRIMARY KEY(interest_id)
    );
    
-- Create the Interests Table
drop table if exists interests;
create table user_interests(
	interest_id	int NOT NULL AUTO_INCREMENT,
    type	Varchar(100) NOT NULL,
	description	Varchar(200) NOT NULL,
    PRIMARY KEY(interest_id)
    );
    
    

