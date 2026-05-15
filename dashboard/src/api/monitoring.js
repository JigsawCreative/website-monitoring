export async function fetchResults() {

    // Import the API base URL from environment variables
    //const apiBase = import.meta.env.VITE_API_BASE_URL;

    const res = await fetch(`https://testenv.local/wp-json/website-monitoring/v1/results`);

    return res.json();

}