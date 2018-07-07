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
