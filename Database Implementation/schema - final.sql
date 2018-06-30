use group05;
-- Gender
-- ------------------------------------------------
drop table if exists gender;
CREATE TABLE `gender` (
  `id` int(11) NOT NULL,
  `Gender_name` varchar(200) NOT NULL
);
-- City
-- ------------------------------------------------
drop table if exists city;
create table city(
user_id  int NOT NULL AUTO_INCREMENT,
city Varchar(100) NOT NULL,
county Varchar(100) NOT NULL,
geo_x float,
geo_y float,
Primary Key(user_id)
);

-- relationship_type
-- ------------------------------------------------
drop table if exists relationship_type;
-- Create the Interests Table
drop table if exists interests;
create table interests(
interests_id int NOT NULL AUTO_INCREMENT,
type Varchar(100) NOT NULL,
description Varchar(200) NOT NULL,
PRIMARY KEY(interests_id)
);    


-- interests
-- ------------------------------------------------
drop table if exists interests;
create table interests(
interests_id int NOT NULL AUTO_INCREMENT,
type Varchar(100) NOT NULL,
description Varchar(200) NOT NULL,
PRIMARY KEY(interests_id)
);  

-- black_list_words
-- ------------------------------------------------
drop table if exists black_list_word;
create table black_list_word(
	id int NOT NULL AUTO_INCREMENT,
	word varchar(100) NOT NULL,
    PRIMARY KEY(id),
    UNIQUE KEY(word)
    );


-- status
-- ------------------------------------------------
drop table if exists status_master;
create table status_master(
	id int NOT NULL AUTO_INCREMENT,
	status_description varchar(100) NOT NULL,
    is_user_status boolean,
    is_match_table_status boolean,
    is_user_communication_status boolean,
    PRIMARY KEY(id),
    UNIQUE KEY(status_description)
    );

-- user_profile
-- ------------------------------------------------
drop table if exists user_profile;
CREATE TABLE `user_profile` (
  `id` int(11) NOT NULL,
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
  `county` varchar(100) DEFAULT NULL,
  `Travel_distance` int(11) DEFAULT NULL,
  `relationship_type_id` int(11) DEFAULT NULL,
  `picture` blob,
  `my_bio` varchar(1000) DEFAULT NULL,
  `black_listed_user` tinyint(1) DEFAULT NULL,
  `black_listed_reason` varchar(100) NOT NULL,
  `black_listed_date` date DEFAULT NULL,
  `user_status` varchar(50) NOT NULL,
  `is_administrator` boolean NOT NULL
);

-- user_communication
-- ------------------------------------------------
drop table if exists user_communication;
create table user_communication(
	id int NOT NULL AUTO_INCREMENT,
    from_user_id int,
    communication_datetime datetime,
    message varchar(140) NOT NULL,
    status_id varchar(20) NOT NULL,
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
drop table if exists match_table;
create table match_table(
    id int NOT NULL AUTO_INCREMENT,
    match_user_id_1 int,
    match_user_id_2 int,
    match_date datetime,
    response_date datetime,
    user_id_1_interest_level int,
    user_id_2_interest_level int,
    communication_id int,
    match_status_id int NOT NULL,
    match_status_date datetime,
    system_generated_match boolean,
    PRIMARY KEY(id),
    FOREIGN KEY (match_user_id_1) REFERENCES user_profile(id),
    FOREIGN KEY (match_user_id_1) REFERENCES user_profile(id),
    FOREIGN KEY (match_status_id) REFERENCES status_master(id),
    FOREIGN KEY (communication_id) REFERENCES user_communication(id),
    UNIQUE KEY(match_user_id_1, match_user_id_2)
    );

-- user_interests
-- ------------------------------------------------
drop table if exists user_interests;
create table user_interests(
user_interests_id int NOT NULL AUTO_INCREMENT,
type Varchar(100) NOT NULL,
PRIMARY KEY(user_interests_id)
);
