const apiURL = "http://stemwijzer-api.local";

// Constants for answer values
const ANSWER_VALUES = {
  agree: 5,
  neither: 0,
  disagree: -5,
};

let statements = [];
let currentStatementIndex = 0;

// Function to highlight the previously selected answer
function highlightSelectedAnswer(answer) {
  // Remove highlight from all buttons first
  document.getElementById("oneens-button").classList.remove("selected");
  document.getElementById("neutraal-button").classList.remove("selected");
  document.getElementById("eens-button").classList.remove("selected");

  // Add highlight to the selected button
  if (answer === "disagree") {
    document.getElementById("oneens-button").classList.add("selected");
  } else if (answer === "neither") {
    document.getElementById("neutraal-button").classList.add("selected");
  } else if (answer === "agree") {
    document.getElementById("eens-button").classList.add("selected");
  }
}

// Function to get current answers from URL
function getAnswersFromURL() {
  const urlParams = new URLSearchParams(window.location.search);
  const answersParam = urlParams.get("answers");
  return answersParam ? JSON.parse(decodeURIComponent(answersParam)) : {};
}

// Function to load statements
async function loadStatements() {
  try {
    const response = await fetch(`${apiURL}/statements`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    statements = await response.json();
    if (Array.isArray(statements) && statements.length > 0) {
      // Check for existing answers in URI
      const answers = getAnswersFromURL();
      if (Object.keys(answers).length > 0) {
        currentStatementIndex = Object.keys(answers).length - 1;
      }
      displayStatement(currentStatementIndex);
      updateProgress();
      updateBackButtonVisibility();
    } else {
      showError("Geen stellingen gevonden.");
    }
  } catch (error) {
    console.error("Error loading statements:", error);
    showError("Er is een fout opgetreden bij het laden van de stellingen.");
  }
}

// Function to go to previous statement
function goToPreviousStatement() {
  if (currentStatementIndex > 0) {
    currentStatementIndex--;
    displayStatement(currentStatementIndex);
    updateBackButtonVisibility();
  }
}

// Function to update back button visibility
function updateBackButtonVisibility() {
  const backButton = document.getElementById('back-button');
  if (backButton) {
    backButton.style.visibility = currentStatementIndex === 0 ? 'hidden' : 'visible';
  }
}

// Function to display current statement
function displayStatement(index) {
  if (index >= 0 && index < statements.length) {
    const statement = statements[index];
    document.getElementById("statement-name").textContent = statement.name;
    document.getElementById("statement-description").textContent = statement.description;

    // Check if there's a previous answer for this statement
    const answers = getAnswersFromURL();
    const previousAnswer = answers[statement.statementID];
    if (previousAnswer) {
      highlightSelectedAnswer(previousAnswer);
    } else {
      // Remove highlighting if no previous answer
      highlightSelectedAnswer(null);
    }

    updateProgress();
  }
}

// Function to update progress
function updateProgress() {
  const progressPercentage = ((currentStatementIndex + 1) / statements.length) * 100;
  document.getElementById("progress-bar").style.width = `${progressPercentage}%`;
  document.getElementById("progress-bar").setAttribute("aria-valuenow", progressPercentage);
  document.getElementById("progress-text").textContent = `${currentStatementIndex + 1} van de ${statements.length}`;
}

// Function to show error messages
function showError(message) {
  const statementDiv = document.getElementById("statement");
  statementDiv.innerHTML = `<p class="error-message">${message}</p>`;
}

// Handle user answers
async function handleAnswer(answer) {
  const currentStatement = statements[currentStatementIndex];
  const existingAnswers = getAnswersFromURL();

  // Add or update answer
  existingAnswers[currentStatement.statementID] = answer;

  // Update URL with new answers
  const newUrl = new URL(window.location.href);
  newUrl.searchParams.set(
      "answers",
      encodeURIComponent(JSON.stringify(existingAnswers))
  );
  window.history.replaceState({}, "", newUrl);

  // Move to next statement or finish
  if (currentStatementIndex < statements.length - 1) {
    currentStatementIndex++;
    displayStatement(currentStatementIndex);
    updateBackButtonVisibility();
  } else {
    try {
      // Submit answers to API
      const response = await fetch(`${apiURL}/user-answers`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          answers: existingAnswers,
        }),
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      // Redirect to result page with answers in URI
      window.location.href = `result.html?answers=${encodeURIComponent(
          JSON.stringify(existingAnswers)
      )}`;
    } catch (error) {
      console.error("Error submitting answers:", error);
      window.location.href = `result.html?answers=${encodeURIComponent(
          JSON.stringify(existingAnswers)
      )}`;
    }
  }
}

// Add event listeners to buttons
document
    .getElementById("oneens-button")
    .addEventListener("click", () => handleAnswer("disagree"));
document
    .getElementById("neutraal-button")
    .addEventListener("click", () => handleAnswer("neither"));
document
    .getElementById("eens-button")
    .addEventListener("click", () => handleAnswer("agree"));

// Add event listener for back button
document
    .getElementById("back-button")
    .addEventListener("click", goToPreviousStatement);

// Load statements when page loads
document.addEventListener("DOMContentLoaded", loadStatements);