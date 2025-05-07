import React, { useEffect, useState } from 'react';
import {BarChart, LineChart} from '@mui/x-charts';

function WeeklyReportChart({ data }) {

    if (!data || data.length === 0) {
        return <div>No data</div>;
    }

    const labels = data.map((d) => d.timestamp);     // x‑axis
    const temps  = data.map((d) => d.temperature);   // y data

    console.log(data)

    return (
        <div style={{ height: 400, width: '100%' }}>
                <BarChart
                    height={400}
                    xAxis={[{ data: labels, scaleType: "band", label: "Timestamp"}]}
                    series={[{ data: temps, label: "Temperature(°C)" }]}
                    margin={{ top: 20, bottom: 80, right: 20, left: 60 }}
                />
        </div>
    );
}

export default WeeklyReportChart;