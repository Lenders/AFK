ALTER TABLE EVENT 
ADD COLUMN EVENT_START TIMESTAMP,
ADD COLUMN EVENT_END TIMESTAMP,
MODIFY EVENT_DATE TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

CREATE TABLE EVENT_PROPERTY_CHECK(
    PROPERTY_ID INT PRIMARY KEY AUTO_INCREMENT,
    PROPERTY_NAME VARCHAR(12) UNIQUE,
    PROPERTY_REGEXP VARCHAR(255) DEFAULT '/.*/',
    REQUIRED ENUM('YES', 'NO') DEFAULT 'NO',
    PROPERTY_PRIVACY ENUM('PUBLIC', 'PRIVATE') DEFAULT 'PUBLIC'
);

INSERT INTO EVENT_PROPERTY_CHECK(PROPERTY_NAME, REQUIRED) VALUES('Jeu', 'YES');
INSERT INTO EVENT_PROPERTY_CHECK(PROPERTY_NAME) VALUES('Serveur');
INSERT INTO EVENT_PROPERTY_CHECK(PROPERTY_NAME) VALUES('Lieu');
INSERT INTO EVENT_PROPERTY_CHECK(PROPERTY_NAME, PROPERTY_REGEXP, PROPERTY_PRIVACY) VALUES('Skype', '/^([a-z][a-z0-9]{5,31})?$/i', 'PRIVATE');
INSERT INTO EVENT_PROPERTY_CHECK(PROPERTY_NAME, PROPERTY_REGEXP, PROPERTY_PRIVACY) VALUES('Téléphone', '/^(([0-9]{2}(\\.| |-)?){5})?$/', 'PRIVATE');
INSERT INTO EVENT_PROPERTY_CHECK(PROPERTY_NAME, PROPERTY_REGEXP, PROPERTY_PRIVACY) VALUES('Teamspeak', '/^([0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3})?$/', 'PRIVATE');
INSERT INTO EVENT_PROPERTY_CHECK(PROPERTY_NAME) VALUES('Autre');
INSERT INTO EVENT_PROPERTY_CHECK(PROPERTY_NAME, REQUIRED) VALUES('Description', 'YES');

DROP TABLE INFO;

CREATE TABLE EVENT_PROPERTY(
    EVENT_ID INT,
    PROPERTY_ID INT,
    PROPERTY_VALUE VARCHAR(128),
    PRIMARY KEY (EVENT_ID, PROPERTY_ID),
    FOREIGN KEY (PROPERTY_ID) REFERENCES EVENT_PROPERTY_CHECK (PROPERTY_ID),
    FOREIGN KEY (EVENT_ID) REFERENCES EVENT (EVENT_ID)
);

ALTER TABLE EVENT DROP COLUMN DESCRIPTION;
ALTER TABLE EVENT MODIFY EVENT_STATE ENUM('OPEN', 'CLOSE') DEFAULT 'OPEN';
ALTER TABLE EVENT CHANGE EVENT_TYPE EVENT_PRIVACY ENUM('PUBLIC', 'PRIVATE', 'FRIEND');