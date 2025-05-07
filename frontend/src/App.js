import React, { useState } from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import { Container, Row, Col, Form, Button, Card } from 'react-bootstrap';
import WeeklyChart from "./WeeklyChart";
import SensorDataCard from "./SensorCard";
import FaultySensors from "./FaultySensors";
import WeeklyFaceChart from "./WeeklyFaceChart";

function App() {
  const [sensorId, setSensorId] = useState('');
  const [sensorInfo, setSensorInfo] = useState([]);
  const [weeklySummary, setWeeklySummary] = useState([]);
  const [weeklyFaceSummary, setWeeklyFaceSummary] = useState([]);

  const handleSearch = async () => {
    try {
      const response = await fetch(`http://localhost/api/sensors/${sensorId}`);
      if (!response.ok) {
        throw new Error('Failed to fetch sensor information');
      }
      const data = await response.json();
      setSensorInfo(data);
    } catch (error) {
      setSensorInfo(`Error: ${error.message}`);
    }
  };

  const loadWeeklySummary = async () => {
    try {
      const response = await fetch(`http://localhost/api/reports/sensor/${sensorId}`);
      if (!response.ok) {
        throw new Error('Failed to fetch weekly summary');
      }
      const data = await response.json();
      setWeeklySummary(data);
    } catch (error) {
      setWeeklySummary([]);
    }
  };

  const triggerAgregate = async () => {
    try {
      const response = await fetch(`http://localhost/api/jobs/aggregate_data`);
      if (!response.ok) {
        throw new Error('Failed to trigger weekly summary');
      }

    } catch (error) {
    }
  }

  const loadFaceWeeklySummary = async () => {
    try {
      const response = await fetch(`http://localhost/api/reports/face`);
      if (!response.ok) {
        throw new Error('Failed to fetch weekly face summary');
      }
      const data = await response.json();
      setWeeklyFaceSummary(data);
    } catch (error) {
      setWeeklyFaceSummary([]);
    }
  };

  return (
    <Container className="mt-5">
      <Row className="mb-4">
        <Col>
          <h1>Welcome Alina,</h1>
          <h2>Sensor Dashboard</h2>
        </Col>
      </Row>
      <Row className="mb-4">
        <Col md={8}>
          <Form>
            <Form.Group controlId="sensorSearch">
              <Form.Label>Search Sensor</Form.Label>
              <Form.Control
                type="text"
                placeholder="Enter Sensor ID"
                value={sensorId}
                onChange={(e) => setSensorId(e.target.value)}
              />
            </Form.Group>
          </Form>
        </Col>
        <Col md={4} className="d-flex align-items-end">
          <Button variant="primary" onClick={handleSearch}>
            Search
          </Button>
        </Col>
      </Row>
      <Row className="mb-4">
        <Col>
          <SensorDataCard sensorData={sensorInfo}>

          </SensorDataCard>
        </Col>
      </Row>
      <Row>
        <Col>
          <Button variant="success" onClick={loadWeeklySummary}>
            Load Weekly Summary
          </Button>


          <Button style={{marginLeft: 4}} variant="warning" onClick={loadFaceWeeklySummary}>
            Load Face Weekly Summary
          </Button>

          <Button style={{marginLeft: 4}} variant="danger" size={'sm'} onClick={triggerAgregate}>
            Trigger Aggregate [DEBUG]
          </Button>
        </Col>
      </Row>
      <Row className="mt-4">
        <Col>
          <Card>
            <Card.Body>
              <Card.Title>Weekly Summary</Card.Title>
              {/*<Card.Text>{weeklySummary || 'No summary loaded.'}</Card.Text>*/}
              <WeeklyChart data={weeklySummary}>

              </WeeklyChart>
            </Card.Body>
          </Card>
          <Card style={{marginTop: 20}}>
            <Card.Body>
              <Card.Title>Weekly Summary</Card.Title>

              <WeeklyFaceChart data={weeklyFaceSummary} >

              </WeeklyFaceChart>
            </Card.Body>
          </Card>
        </Col>
      </Row>
      <FaultySensors/>
    </Container>
  );
}

export default App;