-- Select the schema
USE group05;

-- Create the Black Listed Words Table 
drop table if exists black_list_word;
create table black_list_word(
	id int NOT NULL AUTO_INCREMENT,
	word varchar(100) NOT NULL,
    PRIMARY KEY(id),
    UNIQUE KEY(word)
    );

-- Create the Black Listed Words Table 
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



-- Create teh Match table
drop table if exists match_table;
create table match_table(
    id int NOT NULL AUTO_INCREMENT,
    initiating_user_id int,
    initiation_date datetime,
    initiation_interest_level int,
    initiation_communication_id int,
    match_status_id int NOT NULL,
    match_status_date datetime,
    reciprocating_user_id int,
    reciprocating_response_date datetime,
    reciprocating_interest_level int,
    reciprocating_communication_id int,
    PRIMARY KEY(id),
    FOREIGN KEY (initiating_user_id) REFERENCES user_profile(id),
    FOREIGN KEY (reciprocating_user_id) REFERENCES user_profile(id),
    FOREIGN KEY (match_status_id) REFERENCES status_master(id),
    FOREIGN KEY (initiation_communication_id) REFERENCES user_communication(id),
    FOREIGN KEY (reciprocating_communication_id) REFERENCES user_communication(id),
    UNIQUE KEY(initiating_user_id, reciprocating_user_id)
    );
    
    
      
-- Create the user Communication table
drop table if exists user_communication;
create table user_communication(
	id int NOT NULL AUTO_INCREMENT,
    from_user_id int,
    communication_date datetime,
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
   

