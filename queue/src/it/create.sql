CREATE SCHEMA queue_test
	DEFAULT CHARACTER SET utf8
	DEFAULT COLLATE utf8_general_ci;


CREATE TABLE queue_test.queue (
	 id VARCHAR(128) NOT NULL
	,CONSTRAINT pk_queue PRIMARY KEY (id)
) ENGINE = InnoDB;


CREATE TABLE queue_test.consumer (
	 id VARCHAR(128) NOT NULL
	,CONSTRAINT pk_consumer PRIMARY KEY (id)
) ENGINE = InnoDB;


CREATE TABLE queue_test.queue_consumer (
	 queue_id VARCHAR(128) NOT NULL
	,consumer_id VARCHAR(128) NOT NULL
) ENGINE = InnoDB;

ALTER TABLE queue_test.queue_consumer
		ADD FOREIGN KEY fk_queue_consumer_queue_id(queue_id)
		REFERENCES queue_test.queue(id)
		ON DELETE CASCADE
	;

CREATE INDEX idx_queue_consumer_queue_id ON queue_test.queue_consumer(queue_id);

ALTER TABLE queue_test.queue_consumer
		ADD FOREIGN KEY fk_queue_consumer_consumer_id(consumer_id)
		REFERENCES queue_test.consumer(id)
		ON DELETE CASCADE
	;

CREATE INDEX idx_queue_consumer_consumer_id ON queue_test.queue_consumer(consumer_id);


CREATE TABLE queue_test.event (
	 id BIGINT UNSIGNED AUTO_INCREMENT
	,consumer_id VARCHAR(128) NOT NULL
    ,created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ,errors INT NOT NULL DEFAULT 0
    ,in_process DATETIME
	,last_error TEXT
	,payload TEXT
    ,processed DATETIME
	,queue_id VARCHAR(128) NOT NULL
	,type VARCHAR(128) NOT NULL
	,CONSTRAINT pk_event PRIMARY KEY (id)
) ENGINE = InnoDB;

ALTER TABLE queue_test.event
		ADD FOREIGN KEY fk_event_queue_id(queue_id)
		REFERENCES queue_test.queue(id)
		ON DELETE CASCADE
	;

CREATE INDEX idx_event_queue_id ON queue_test.event(queue_id);

ALTER TABLE queue_test.event
		ADD FOREIGN KEY fk_event_consumer_id(consumer_id)
		REFERENCES queue_test.queue_consumer(consumer_id)
		ON DELETE CASCADE
	;

CREATE INDEX idx_event_consumer_id ON queue_test.event(consumer_id);


insert into queue_test.queue (id) values ('test');
insert into queue_test.consumer (id) values ('test');
insert into queue_test.queue_consumer (queue_id, consumer_id) values ('test', 'test');


CREATE USER 'queue_test'@'%';
SET PASSWORD FOR 'queue_test'@'%' = PASSWORD('foo');
GRANT SELECT ON queue_test.queue_consumer TO 'queue_test'@'%';
GRANT DELETE, INSERT, SELECT, UPDATE ON queue_test.event TO 'queue_test'@'%';
