CREATE DATABASE blog_elf;
USE blog_elf;

CREATE TABLE users(
    userid INT NOT NULL AUTO_INCREMENT , 
    first_name VARCHAR(30) NOT NULL ,
    last_name VARCHAR(30) NOT NULL ,
    profile_ VARCHAR(100) NOT NULL, 
    username VARCHAR(50) NOT NULL ,
    passwd VARCHAR(125) NOT NULL,
    gmail VARCHAR(59) NOT NULL ,
    rol VARCHAR(20) NOT NULL,
    PRIMARY KEY (userid)
);

CREATE TABLE blogs(
    bid INT NOT NULL AUTO_INCREMENT ,
    abstract VARCHAR(254) NOT NULL ,
    long_description MEDIUMTEXT NOT NULL ,
    title VARCHAR(100) NOT NULL ,
    photo VARCHAR(100) NOT NULL,
    date_time  VARCHAR(50) NOT NULL,
    wid INT NOT NULL ,
    cid INT NOT NULL ,
    seen INT NOT NULL,
    PRIMARY KEY (bid)
);

CREATE TABLE category(
    cid INT NOT NULL AUTO_INCREMENT ,
    c_name VARCHAR(50) ,   
    PRIMARY KEY (cid)
);

CREATE TABLE media(
    path_file VARCHAR(255) NOT NULL,
    bid INT NOT NULL , 
    format VARCHAR(10) NOT NULL
);


CREATE TABLE feedback(
    fid INT NOT NULL AUTO_INCREMENT,
    comment VARCHAR(255) ,
    like_ BOOLEAN ,
    report TEXT,
    bid INT  ,
    userid INT, 
    PRIMARY KEY (fid)
);


