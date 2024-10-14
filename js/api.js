function updateAdmin(adminID, partyID, email) {
    return fetch(`http://stemwijzer-api.local/admins/${adminID}`, {
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

function deleteAdmin(adminID) {
    return fetch(`http://stemwijzer-api.local/admins/${adminID}`, {
        method: 'DELETE'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}