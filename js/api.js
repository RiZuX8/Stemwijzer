apiURL = "http://stemwijzer-api.local";

function getAdmins() {
    return fetch(apiURL + "/admins").then((response) => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function getAdminByID(adminID) {
    return fetch(apiURL + `/admins/id/${adminID}`).then((response) => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function getAdminByEmail(email) {
    return fetch(apiURL + `/admins/email/${email}`).then((response) => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function addAdmin(partyID, email, password) {
    return fetch(apiURL + "/admins", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            partyID: partyID,
            email: email,
            password: password,
        }),
    }).then((response) => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function updateAdmin(adminID, partyID, email) {
    return fetch(apiURL + `/admins/${adminID}`, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            partyID: partyID,
            email: email,
        }),
    }).then((response) => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function deleteAdmin(adminID) {
    return fetch(apiURL + `/admins/${adminID}`, {
        method: "DELETE",
    }).then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        if (response.status === 204) {
            return null;
        }
        return response.json();
    });
}

function loginAdmin(email, password) {
    return fetch(apiURL + "/admins/login", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            email: email,
            password: password,
        }),
    }).then((response) => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function getSuperAdmins() {
    return fetch(apiURL + "/superadmins").then((response) => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function getSuperAdminByID(superAdminID) {
    return fetch(apiURL + `/superadmins/id/${superAdminID}`).then((response) => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function getSuperAdminByEmail(email) {
    return fetch(apiURL + `/superadmins/email/${email}`).then((response) => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function addSuperAdmin(email, password) {
    return fetch(apiURL + "/superadmins", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            email: email,
            password: password,
        }),
    }).then((response) => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function updateSuperAdmin(superAdminID, email, password) {
    return fetch(apiURL + `/superadmins/${superAdminID}`, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            email: email,
            password: password,
        }),
    }).then((response) => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function deleteSuperAdmin(superAdminID) {
    return fetch(apiURL + `/superadmins/${superAdminID}`, {
        method: "DELETE",
    }).then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        if (response.status === 204) {
            return null;
        }
        return response.json();
    });
}

function loginSuperAdmin(email, password) {
    return fetch(apiURL + "/superadmins/login", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            email: email,
            password: password,
        }),
    }).then((response) => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function getParties() {
    return fetch(apiURL + "/parties")
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (Array.isArray(data)) {
                return data.map(party => ({
                    ...party,
                    image: apiURL + '/uploads/' + party.image
                }));
            }
            return data;
        });
}

function getParty(partyID) {
    return fetch(apiURL + `/parties/${partyID}`)
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(party => {
            if (party) {
                return {
                    ...party,
                    image: apiURL + '/uploads/' + party.image
                };
            }
            return party;
        });
}

function addParty(name, description, image) {
    return fetch(apiURL + "/parties", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            name: name,
            description: description,
            image: image,
        }),
    }).then((response) => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function updateParty(partyID, name, description, image) {
    return fetch(apiURL + `/parties/${partyID}`, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            name: name,
            description: description,
            image: image,
        }),
    }).then((response) => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function deleteParty(partyID) {
    return fetch(apiURL + `/parties/${partyID}`, {
        method: "DELETE",
    }).then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        if (response.status === 204) {
            return null;
        }
        return response.json();
    });
}

function addPartyWithImage(formData) {
    return fetch(`${apiURL}/parties`, {
        method: "POST",
        body: formData  // FormData object met name, description en image file
    }).then(handleResponse);
}

function updatePartyWithImage(partyID, formData) {
    // Voeg de _method parameter toe voor de method override
    formData.append('_method', 'PUT');

    return fetch(`${apiURL}/parties/${partyID}`, {
        method: "POST",  // We gebruiken POST met _method override
        body: formData
    }).then(async response => {
        const data = await response.json();
        if (!response.ok) {
            throw new Error(data.message || 'Er is een fout opgetreden bij het bijwerken van de partij.');
        }
        return data;
    });
}

function getStatements() {
    return fetch(apiURL + "/statements").then((response) => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function getStatement(statementID) {
    return fetch(apiURL + `/statements/${statementID}`).then((response) => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function addStatement(name, description, xValue, yValue, priority) {
    return fetch(apiURL + "/statements", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            name: name,
            description: description,
            xValue: xValue,
            yValue: yValue,
            priority: priority,
        }),
    }).then((response) => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function updateStatement(
    statementID,
    name,
    description,
    xValue,
    yValue,
    priority
) {
    return fetch(apiURL + `/statements/${statementID}`, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            name: name,
            description: description,
            xValue: xValue,
            yValue: yValue,
            priority: priority,
        }),
    }).then((response) => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function deleteStatement(statementID) {
    return fetch(apiURL + `/statements/${statementID}`, {
        method: "DELETE",
    }).then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        if (response.status === 204) {
            return null;
        }
        return response.json();
    });
}

function getPartyStatements() {
    return fetch(apiURL + "/parties-statements").then((response) => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function getPartyStatement(partyID) {
    return fetch(apiURL + `/parties-statements/party/${partyID}`).then(
        (response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        }
    );
}

function getStatementParty(statementID) {
    return fetch(apiURL + `/parties-statements/statement/${statementID}`).then(
        (response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        }
    );
}

function addPartyStatement(partyID, statementID, answerValue) {
    return fetch(apiURL + "/parties-statements", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            partyID: partyID,
            statementID: statementID,
            answerValue: answerValue,
        }),
    }).then((response) => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function updatePartyStatement(partyID, statementID, answerValue) {
    return fetch(apiURL + `/parties-statements`, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            partyID: partyID,
            statementID: statementID,
            answerValue: answerValue,
        }),
    }).then((response) => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    });
}

function deletePartyStatement(partyID, statementID) {
    return fetch(apiURL + `/parties-statements`, {
        method: "DELETE",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            partyID: partyID,
            statementID: statementID,
        }),
    }).then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        if (response.status === 204) {
            return null;
        }
        return response.json();
    });
}

function handleResponse(response) {
    if (!response.ok) {
        if (response.status === 422) {
            return response.json().then(err => Promise.reject(new Error('Ongeldige invoer. Controleer alle velden.')));
        }
        return response.json().then(err => Promise.reject(new Error(err.message || 'Er is een fout opgetreden.')));
    }
    return response.json();
}