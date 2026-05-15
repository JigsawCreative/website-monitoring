import React, { useEffect, useState } from 'react';
import { fetchResults } from '../api/monitoring';

export default function Dashboard() {
  const [results, setResults] = useState([]);

  useEffect(() => {
    fetchResults().then(setResults);
  }, []);

  return (
    <div>
      <h1>Monitoring Results</h1>
      <ul>
        {results.map((result, i) => (
          <li key={i}>
            {result.name} - {result.category} - {result.overall_status}
          </li>
        ))}
      </ul>
    </div>
  );
}