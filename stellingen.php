<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/start.css">
    <link rel="stylesheet" href="./styles/stellingen.css">
    <title>Stemwijzer</title>
</head>

<body>
    <div class="container">
        <header>
            <h1 class="stemwijzer-logo">
                <span>Politieke</span> <span>Stemwijzer</span>
            </h1>
            <img src="./assets/vote.svg" alt="participate checklist" />
        </header>
        <div class="progress" aria-label="Progress">
            <div class="progress-bar" style="width: 40%;" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
            <span class="progress-text">4 van de 10</span>
        </div>
        <main>
            <div class="statement">
                <h2>Statement Header</h2>
                <p>This is the statement text.</p>
            </div>
            <div class="buttons">
                <button id="oneens-button" class="btn btn-disagree" aria-label="Oneens">Oneens
                    <img class="svg-thumb-down_color" src="./assets/thumb-down.svg" alt="thumb up" />
                </button>
                <button id="neutraal-button" class="btn btn-neutral" aria-label="Neutraal">
                    <img class="svg-tilde_color" src="./assets/tilde.svg" alt="tilde" />
                </button>
                <button id="eens-button" class="btn btn-agree" aria-label="Eens">Eens
                    <img class="svg-thumb-up_color" src="./assets/thumb-up.svg" alt="thumb down" />
                </button>
            </div>
        </main>
    </div>
    <script src="script.js"></script>
</body>

</html>