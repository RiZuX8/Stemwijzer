<!DOCTYPE html>
<html lang="nl">

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
        <!-- Added back button -->
        <button id="back-button" class="back-btn" aria-label="Ga terug">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5"/>
                <path d="M12 19l-7-7 7-7"/>
            </svg>
        </button>
        <h1 class="stemwijzer-logo">
            <span>Politieke</span> <span>Stemwijzer</span>
        </h1>
        <img src="./assets/vote.svg" alt="participate checklist" />
    </header>
    <div class="progress" aria-label="Progress">
        <div id="progress-bar" class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
        <span id="progress-text" class="progress-text"></span>
    </div>
    <main>
        <div id="statement" class="statement">
            <h2 id="statement-name"></h2>
            <p id="statement-description"></p>
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
<script src="./js/stellingen.js"></script>
</body>

</html>