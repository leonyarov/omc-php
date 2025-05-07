import React from 'react';
import { Card } from 'react-bootstrap';

function SensorDataCard({ sensorData }) {
    return (
        <Card style={{ width: '18rem' }} className="mb-3">
            <Card.Body>
                <Card.Title>Sensor ID: {sensorData.id}</Card.Title>
                <Card.Text>
                    <strong>Face:</strong> {sensorData.face} <br />
                    <strong>Deactivated:</strong> {sensorData.deactivated ? 'Yes' : 'No'} <br />
                    <strong>Removed:</strong> {sensorData.removed ? 'Yes' : 'No'}
                </Card.Text>
            </Card.Body>
        </Card>
    );
}

export default SensorDataCard;