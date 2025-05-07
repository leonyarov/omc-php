import React from 'react';
import {BarChart} from '@mui/x-charts';

function WeeklyFaceReportChart({data}) {
    if (!data || data.length === 0) {
        return <div>No data</div>;
    }

    // Group data by face
    const groupedData = data.reduce((acc, curr) => {
        if (!acc[curr.face]) {
            acc[curr.face] = {labels: [], temps: []};
        }
        acc[curr.face].labels.push(curr.timestamp);
        acc[curr.face].temps.push(curr.temperature);
        return acc;
    }, {});

    // Prepare series for each face
    const series = Object.keys(groupedData).map((face) => ({
        data: groupedData[face].temps,
        label: `${face} face`,
    }));

    const labels = groupedData[Object.keys(groupedData)[0]].labels; // Use labels from one group

    return (
        <div style={{height: 400, width: '100%'}}>
            <BarChart
                height={400}
                xAxis={[{data: labels, scaleType: "band", label: "Timestamp"}]}
                series={series}
                margin={{top: 20, bottom: 80, right: 20, left: 60}}
            />
        </div>
    );
}

export default WeeklyFaceReportChart;