function updateAdminData(adminId, partyID, email) {
    return fetch(`http://stemwijzer-api.local/admins/${adminId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            partyID: partyID,
            email: email
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}