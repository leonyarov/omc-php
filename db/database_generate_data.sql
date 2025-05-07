-- Populate 10,000 sensors evenly across 4 faces
INSERT INTO sensors (id, face, deactivated, removed)
SELECT
    seq.id,
    CASE
        WHEN seq.id <= 2500 THEN 'north'
        WHEN seq.id <= 5000 THEN 'east'
        WHEN seq.id <= 7500 THEN 'south'
        ELSE 'west'
        END as face,
    FALSE as deactivated,
    FALSE as removed
FROM (
         SELECT @rownum := @rownum + 1 AS id
         FROM information_schema.columns AS a
                  CROSS JOIN information_schema.columns AS b
                  CROSS JOIN information_schema.columns AS c
         LIMIT 10000
     ) AS seq, (SELECT @rownum := 0) AS init;

DELIMITER $$

DROP PROCEDURE IF EXISTS seed_sensor_data$$
CREATE PROCEDURE seed_sensor_data()
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE sid INT;
    DECLARE base_time DATETIME DEFAULT NOW();
    DECLARE i INT;

    DECLARE sensor_cursor CURSOR FOR SELECT id FROM sensors;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN sensor_cursor;

    sensor_loop: LOOP
        FETCH sensor_cursor INTO sid;
        IF done THEN
            LEAVE sensor_loop;
        END IF;

        SET i = 0;
        WHILE i < 2 DO
                INSERT INTO sensor_data (sensor_id, timestamp, temperature)
                VALUES (
                           sid,
                           DATE_ADD(base_time, INTERVAL (i + (sid * 10)) SECOND),
                           ROUND(15 + (RAND() * 20), 2)
                       );
                SET i = i + 1;
            END WHILE;
    END LOOP;

    CLOSE sensor_cursor;
END$$

DELIMITER ;

-- Call the procedure
CALL seed_sensor_data();