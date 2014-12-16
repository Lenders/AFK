CREATE TABLE ACCOUNT
(
    USER_ID INT PRIMARY KEY AUTO_INCREMENT,
    PSEUDO VARCHAR(32) UNIQUE,
    PASS VARCHAR(128),
    SALT VARCHAR(32),
    FIRST_NAME VARCHAR(32),
    LAST_NAME VARCHAR(32),
    GENDER ENUM ('MALE', 'FEMALE'),
    MAIL VARCHAR(64),
    AVATAR VARCHAR(128)
)
ENGINE = INNODB;

CREATE TABLE FRIEND
(
    USER_ID INT,
    FOREIGN KEY (USER_ID) REFERENCES ACCOUNT(USER_ID),
    FRIEND_ID INT,
    FOREIGN KEY (FREIND_ID)
)
ENGINE = INNODB;

CREATE TABLE EVENT
(
    EVENT_ID INT PRIMARY KEY AUTO_INCREMENT,
    EVENT_NAME VARCHAR(32),
    ORGANIZER VARCHAR(32),
    FOREIGN KEY (ORGANIZER) REFERENCES USER(USER_ID),
    DESCRIPTION VARCHAR(128),
    EVENT_DATE DATE,
    EVENT_STATE ENUM ('FINISHED', 'ONGOING', 'SOON'),
    EVENT_TYPE ENUM ('PRIVATE', 'PUBLIC', 'FRIEND')
)
ENGINE = INNODB;

CREATE TABLE INFO
(
    EVENT_ID INT,
    FOREIGN KEY (EVENT_ID) REFERENCES EVENT(EVENT_ID),
    PROPERTY VARCHAR(32),
    INFO_VALUE VARCHAR(32),
    PRIMARY KEY(EVENT_ID, PROPERTY)
)
ENGINE = INNODB;


CREATE TABLE COMPETITOR
(
    EVENT_ID INT,
    FOREIGN KEY (EVENT_ID) REFERENCES EVENT(EVENT_ID),
    COMPETITOR_STATE ENUM ('ACCEPT', 'PENDING'),
    USER_ID INT,
    FOREIGN KEY (USER_ID) REFERENCES ACCOUNT(USER_ID)
)
ENGINE = INNODB;