// Utility functions for dashboard statistics
export function getPassRate(results) {

  const total = results.length;
  const passed = results.filter(r => r.overall_status === 'passed').length;

  return total > 0 ? Math.round((passed / total) * 100) : 0;
}
