-- Select the schema
USE group05;

-- -------------------------------------------------------
-- Create Tables
-- -------------------------------------------------------



-- Create the User Interests Table
drop table if exists user_interests;
create table user_interests(
user_interests_id int NOT NULL AUTO_INCREMENT,
type Varchar(100) NOT NULL,
PRIMARY KEY(user_interests_id)
);



-- Create the Interests Table
drop table if exists interests;
create table interests(
interests_id int NOT NULL AUTO_INCREMENT,
description Varchar(200) NOT NULL,
PRIMARY KEY(interests_id)
);    

-- Create the Relationship_Type Table 
drop table if exists relationship_type;
create table relationship_type(
id int NOT NULL AUTO_INCREMENT,
relationship_type Varchar(200) NOT NULL,
PRIMARY KEY(id)
);  

-- Create the CITY Table
drop table if exists city;
create table city(
user_id  int NOT NULL AUTO_INCREMENT,
city Varchar(100) NOT NULL,
county Varchar(100) NOT NULL,
geo_x float(4.2),
geo_y float(4.2),
Primary Key(user_id)
);
    



     
