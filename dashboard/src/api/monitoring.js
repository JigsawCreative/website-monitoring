export async function fetchResults() {

    // Import the API base URL from environment variables
    const apiBase = import.meta.env.VITE_API_BASE_URL;

    // Fetch results from the API endpoint
    const res = await fetch(`${apiBase}/results`);

    // Return the JSON response
    return res.json();

}