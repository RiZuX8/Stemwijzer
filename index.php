<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/start.css">
    <title>Politieke Stemwijzer</title>
</head>
<body>
<div class="main-container">
    <header>
        <h1 class="stemwijzer-logo">
            <span>Politieke</span> <span>Stemwijzer</span>
        </h1>
        <img src="./assets/vote.svg" alt="participate checklist" />
    </header>

    <section class="content" aria-labelledby="participating-parties">
        <h2 id="participating-parties">Deze partijen doen mee:</h2>
        <ul id="party-grid" class="party-grid">
            <!-- Parties will be loaded here -->
        </ul>
        <div class="ButtonGroup">
            <a href="stellingen.php" class="start-button" aria-description="Begin de Politieke Stemwijzer">Start</a>
        </div>
        <p class="disclaimer" role="note">Let op: het volgende resultaat is slechts een indicatie, het beperkt uw stemvrijheid niet</p>
    </section>
</div>

<!-- Modal for party info -->
<div class="modal-overlay" id="partyModal">
    <div class="modal">
        <button class="modal-close" onclick="closeModal()">&times;</button>
        <div class="modal-header">
            <img class="modal-party-logo" id="modalPartyLogo" alt="Party logo">
            <h2 class="modal-party-name" id="modalPartyName"></h2>
        </div>
        <div class="modal-content">
            <p class="modal-description" id="modalPartyDescription"></p>
        </div>
    </div>
</div>

<script>
    const apiURL = "http://stemwijzer-api.local";
    const modal = document.getElementById('partyModal');

    function showErrorMessage(message) {
        const errorDiv = document.createElement('li');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        document.getElementById('party-grid').appendChild(errorDiv);
    }

    function createPartyElement(party) {
        const li = document.createElement('li');
        li.className = 'party-logo';
        li.setAttribute('data-party-id', party.partyID);
        li.onclick = () => showPartyInfo(party);

        if (party.image) {
            const img = document.createElement('img');
            img.src = party.image;
            img.alt = `${party.name} logo`;

            // Error handling
            img.onerror = () => {
                li.textContent = party.name;
            };

            li.appendChild(img);
        } else {
            li.textContent = party.name;
        }

        return li;
    }

    function showPartyInfo(party) {
        document.getElementById('modalPartyLogo').src = party.image;
        document.getElementById('modalPartyName').textContent = party.name;
        document.getElementById('modalPartyDescription').textContent = party.description;
        modal.classList.add('active');
    }

    function closeModal() {
        modal.classList.remove('active');
    }

    // Close modal when clicking outside
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Close modal with escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

    async function loadParties() {
        const partyGrid = document.getElementById('party-grid');

        try {
            // Loading indicator
            const loadingLi = document.createElement('li');
            loadingLi.className = 'loading';
            loadingLi.textContent = 'Partijen laden...';
            partyGrid.appendChild(loadingLi);

            const response = await fetch(`${apiURL}/parties`);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const parties = await response.json();
            partyGrid.innerHTML = '';

            if (Array.isArray(parties)) {
                parties.forEach(party => {
                    const partyElement = createPartyElement(party);
                    partyGrid.appendChild(partyElement);
                });
            } else {
                throw new Error('Invalid data format received from API');
            }

        } catch (error) {
            console.error('Error loading parties:', error);
            partyGrid.innerHTML = '';
            showErrorMessage('Er kunnen momenteel geen partijen worden geladen.');
        }
    }

    document.addEventListener('DOMContentLoaded', loadParties);
</script>
</body>
</html>