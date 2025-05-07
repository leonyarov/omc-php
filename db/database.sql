-- Drop indexes first (only if they exist)
# DROP INDEX IF EXISTS idx_sensors_face ON sensors;

-- Drop tables
DROP TABLE IF EXISTS sensors;

-- Create sensors table
CREATE TABLE IF NOT EXISTS sensors (
                                       id INT PRIMARY KEY,
                                       face ENUM('north', 'east', 'south', 'west') NOT NULL,
                                       deactivated BOOLEAN DEFAULT FALSE,
                                       removed BOOLEAN DEFAULT FALSE
);

# DROP INDEX idx_sensor_data_time ON sensor_data;

DROP TABLE IF EXISTS sensor_data;
-- Create sensor_data table
CREATE TABLE IF NOT EXISTS sensor_data (
                                           reading_id INT AUTO_INCREMENT PRIMARY KEY,
                                           sensor_id INT NOT NULL,
                                           timestamp datetime NOT NULL,
                                           temperature DOUBLE,
                                           FOREIGN KEY (sensor_id) REFERENCES sensors(id)
);

CREATE TABLE IF NOT EXISTS sensor_hourly_averages (
                                        sensor_id INT,
                                        hour DATETIME,
                                        avg_temperature DOUBLE,
                                        PRIMARY KEY (sensor_id, hour),
                                        FOREIGN KEY (sensor_id) REFERENCES sensors(id)
);

CREATE TABLE IF NOT EXISTS sensor_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message NVARCHAR (1024),
    date DATETIME
);

CREATE TABLE IF NOT EXISTS face_hourly_averages (
                                        face ENUM('north', 'south', 'east', 'west'),
                                        time DATETIME,
                                        avg_temperature DOUBLE,
                                        PRIMARY KEY (time, face)
);

-- Recreate indexes
CREATE INDEX idx_sensors_face ON sensors(face);
CREATE INDEX idx_sensor_data_time ON sensor_data(timestamp);
CREATE INDEX idx_sensors_hour ON sensor_hourly_averages(hour);
