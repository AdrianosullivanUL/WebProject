-- Select the schema
USE group05;

-- -------------------------------------------------------
-- Create Tables
-- -------------------------------------------------------
-- Create the User Interests tables
drop table if exists user_interests;
create table user_interests(
	user_id	int NOT NULL AUTO_INCREMENT,
	interest_id int,
    PRIMARY KEY(user_id, interest_id)
    );
    

----Create the Interests Table
drop table if exists interests;
create table interests(
interest_id int NOT NULL AUTO_INCREMENT,
type Varchar(100) NOT NULL,
description Varchar(200) NOT NULL,
PRIMARY KEY(interest_id)
);
----Create the Relationship_Type Table 
drop table if exists relationship_type; 
create table relationship_type( 
user_id int NOT NULL AUTO_INCREMENT, 
relationship_type Varchar(100) NOT NULL, 
relationship_interest_id    int
PRIMARY KEY(user_id),
FOREIGN KEY(relationship_interest_id ) REFERENCES interests(interest_id)
);

-----Create the CITY Table
drop table if exits city table;
create table city(
user_id  int NOT NULL AUTO_INCREMENT,
city Varchar(100) NOT NULL,
county Varchar(100) NOT NULL,
geo_x float(10),
geo_y float(10),
Primary Key(user_id);



     
