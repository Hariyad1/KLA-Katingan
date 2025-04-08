export const getApiHeaders = () => {
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    };

    const apiToken = document.querySelector('meta[name="api-token"]')?.content;
    if (apiToken) {
        headers['Authorization'] = `Bearer ${apiToken}`;
    }

    return headers;
}; 