-- Gender
-- ------------------------------------------------

-- City
-- ------------------------------------------------

-- relationship_type
-- ------------------------------------------------

-- interests
-- ------------------------------------------------

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
    UNIQUE KEY(initiating_user_id, reciprocating_user_id)
    );

-- user_interests
-- ------------------------------------------------
