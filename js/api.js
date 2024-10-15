function getAdmins() {
    return fetch('http://stemwijzer-api.local/admins')
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function getAdminByID(adminID) {
    return fetch(`http://stemwijzer-api.local/admins/id/${adminID}`)
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function getAdminByEmail(email) {
    return fetch(`http://stemwijzer-api.local/admins/email/${email}`)
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function addAdmin(partyID, email) {
    return fetch('http://stemwijzer-api.local/admins', {
        method: 'POST',
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

function loginAdmin(email, password) {
    return fetch('http://stemwijzer-api.local/admins/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            email: email,
            password: password
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function getSuperAdmins() {
    return fetch('http://stemwijzer-api.local/superadmins')
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function getSuperAdminByID(superAdminID) {
    return fetch(`http://stemwijzer-api.local/superadmins/id/${superAdminID}`)
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function getSuperAdminByEmail(email) {
    return fetch(`http://stemwijzer-api.local/superadmins/email/${email}`)
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function addSuperAdmin(email, password) {
    return fetch('http://stemwijzer-api.local/superadmins', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            email: email,
            password: password
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function updateSuperAdmin(superAdminID, email, password) {
    return fetch(`http://stemwijzer-api.local/superadmins/${superAdminID}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            email: email,
            password: password
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function deleteSuperAdmin(superAdminID) {
    return fetch(`http://stemwijzer-api.local/superadmins/${superAdminID}`, {
        method: 'DELETE'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function loginSuperAdmin(email, password) {
    return fetch('http://stemwijzer-api.local/superadmins/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            email: email,
            password: password
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function getParties() {
    return fetch('http://stemwijzer-api.local/parties')
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function getParty(partyID) {
    return fetch(`http://stemwijzer-api.local/parties/${partyID}`)
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function addParty(name, description) {
    return fetch('http://stemwijzer-api.local/parties', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            name: name,
            description: description
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function updateParty(partyID, name, description) {
    return fetch(`http://stemwijzer-api.local/parties/${partyID}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            name: name,
            description: description
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function deleteParty(partyID) {
    return fetch(`http://stemwijzer-api.local/parties/${partyID}`, {
        method: 'DELETE'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function getStatements() {
    return fetch('http://stemwijzer-api.local/statements')
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function getStatement(statementID) {
    return fetch(`http://stemwijzer-api.local/statements/${statementID}`)
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function addStatement(name, description, xValue, yValue, priority) {
    return fetch('http://stemwijzer-api.local/statements', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            name: name,
            description: description,
            xValue: xValue,
            yValue: yValue,
            priority: priority
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function updateStatement(statementID, name, description, xValue, yValue, priority) {
    return fetch(`http://stemwijzer-api.local/statements/${statementID}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            name: name,
            description: description,
            xValue: xValue,
            yValue: yValue,
            priority: priority
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function deleteStatement(statementID) {
    return fetch(`http://stemwijzer-api.local/statements/${statementID}`, {
        method: 'DELETE'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function getPartyStatements() {
    return fetch('http://stemwijzer-api.local/party-statements')
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function getPartyStatement(partyID) {
    return fetch(`http://stemwijzer-api.local/party-statements/party/${partyID}`)
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function getStatementParty(statementID) {
    return fetch(`http://stemwijzer-api.local/party-statements/statement/${statementID}`)
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function addPartyStatement(partyID, statementID, answerValue) {
    return fetch('http://stemwijzer-api.local/party-statements', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            partyID: partyID,
            statementID: statementID,
            answerValue: answerValue
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function updatePartyStatement(partyID, statementID, answerValue) {
    return fetch(`http://stemwijzer-api.local/party-statements`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            partyID: partyID,
            statementID: statementID,
            answerValue: answerValue
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function deletePartyStatement(partyID, statementID) {
    return fetch(`http://stemwijzer-api.local/party-statements`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            partyID: partyID,
            statementID: statementID
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}