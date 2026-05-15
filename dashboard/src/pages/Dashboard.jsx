

import React, { useEffect, useState } from 'react';
import { fetchResults } from '../api/monitoring';
import Sidebar from '../components/Sidebar';
import Widget from '../components/Widget';
import PassRateWidget from '../components/PassRateWidget';
import '../index.css';

export default function Dashboard() {
  const [results, setResults] = useState([]);

  useEffect(() => {
    fetchResults().then(setResults);
  }, []);

  return (
    <div className="dashboard-container">
      <Sidebar />
      <div className="dashboard-main">
        <h1>Monitoring Results</h1>
        <div className="widgets">
          <PassRateWidget results={results} />
          {results.map((result, i) => (
            <Widget key={i} title={result.name} value={result.overall_status}>
              <p>Category: {result.category}</p>
            </Widget>
          ))}
        </div>
      </div>
    </div>
  );
}