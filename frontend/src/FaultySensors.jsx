import React, { useEffect, useState } from 'react';
import { Table, Container, Row, Col, Button } from 'react-bootstrap';

function FaultySensors() {
  const [faultySensors, setFaultySensors] = useState([]);
  const [trigger, setTrigger] = useState(false);

    useEffect(() => {
        const fetchFaultySensors = async () => {
            try {
                const response = await fetch('http://localhost/api/reports/faulty');
                if (!response.ok) {
                    throw new Error('Failed to fetch faulty sensors');
                }
                const data = await response.json();
                setFaultySensors(data);
            } catch (err) {
                console.error(err);
            }
        };

        fetchFaultySensors();
    }, [trigger]);

  return (
    <Container className="mt-5">
        <Button onClick={() => setTrigger(!trigger) } variant="primary" className="mb-3">
            Report Faulty Sensors
        </Button>
      <Row>
        <Col>
          <h2>Faulty Sensors</h2>

            <Table striped bordered hover>
              <thead>
                <tr>
                  <th>Sensor ID</th>
                  <th>Hour</th>
                  <th>Hourly Avg Temperature</th>
                  <th>Weekly Avg Temperature</th>
                </tr>
              </thead>
              <tbody>
                {faultySensors.length > 0 ? (
                  faultySensors.map((sensor) => (
                    <tr key={`${sensor.sensor_id}-${sensor.hour}`}>
                      <td>{sensor.sensor_id}</td>
                      <td>{sensor.hour}</td>
                      <td>{sensor.hourly_avg}</td>
                      <td>{sensor.weekly_avg}</td>
                    </tr>
                  ))
                ) : (
                  <tr>
                    <td colSpan="4" className="text-center">
                      No faulty sensors found.
                    </td>
                  </tr>
                )}
              </tbody>
            </Table>
        </Col>
      </Row>
    </Container>
  );
}

export default FaultySensors;