import React from 'react';
import Widget from './Widget';
import { Line } from 'react-chartjs-2';

const PassRateWidget = ({ results }) => {
    console.log(results);
const today = new Date();

// Get last 7 days
const days = [...Array(7)].map((_, i) => {
  const d = new Date(today);
  d.setDate(today.getDate() - (6 - i));
  return d.toISOString().slice(0, 10); // 'YYYY-MM-DD'
});

// Count passes for each day (started_at date with time removed)
const passCounts = days.map(day =>
  results.filter(
    r => r.started_at && r.started_at.slice(0, 10) === day && r.overall_status === 'passed'
  ).length
);


const data = {
  labels: days,
  datasets: [
    {
      label: 'Passes',
      data: passCounts,
      borderColor: '#4f8cff',
      backgroundColor: 'rgba(79, 140, 255, 0.2)',
    },
  ],
};

// Calculate overall pass rate percentage for the 7 days
const total = results.filter(r => r.started_at && days.includes(r.started_at.slice(0, 10))).length;
const passed = results.filter(r => r.started_at && days.includes(r.started_at.slice(0, 10)) && r.overall_status === 'passed').length;
const passRate = total > 0 ? Math.round((passed / total) * 100) : 0;

  return (
    <Widget title="Pass Rate">
      <div style={{ color: '#4f8cff', textAlign: 'center', fontWeight: 600, fontSize: '2rem', marginBottom: '0.5rem' }}>
        {passRate}%
      </div>
      <Line data={data} options={{ plugins: { legend: { display: false } } }} />
    </Widget>
  );
};

export default PassRateWidget;