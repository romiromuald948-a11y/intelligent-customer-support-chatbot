const form = document.querySelector(".typing-area");
const inputField = form.querySelector(".input-field");
const sendBtn = form.querySelector(".send_btn");
const chatBox = document.querySelector(".chat-box");
const incoming_id = form.querySelector(".incoming_id").value;

let userMessageCount = 0;

// Empêche le submit natif
form.addEventListener("submit", (e) => e.preventDefault());

// Envoi sur touche Entrée
inputField.addEventListener("keydown", (e) => {
  if (e.key === "Enter" && !e.shiftKey) {
    e.preventDefault();
    sendMessage();
  }
});
sendBtn.addEventListener("click", sendMessage);

// Fonction principale pour envoyer un message
function sendMessage() {
  const message = inputField.value.trim();
  if (message === "") return;

  appendUserMessage(message); // Affiche le message utilisateur immédiatement
  inputField.value = "";

  userMessageCount++;
  if (userMessageCount % 2 === 0) showFeedbackPrompt();

  addTypingIndicator();

  fetch("php/insert_chat.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `message=${encodeURIComponent(message)}&incoming_id=${encodeURIComponent(incoming_id)}`
  })
  .then(res => res.json())
  .then(data => {
    removeTypingIndicator();
    console.log("Réponse du serveur (brute) :", JSON.stringify(data, null, 2)); // Log détaillé
    if (data.success) {
      data.messages.forEach(msg => {
        if (msg.sender === "bot") {
          appendBotMessage(msg.text, msg.avatar);
        }
      });
    } else {
      appendBotMessage("❌ " + (data.error || "Erreur inconnue"));
    }
  })
  .catch(err => {
    removeTypingIndicator();
    appendBotMessage("❌ Erreur réseau : " + err.message);
    console.error("Erreur fetch :", err);
  });
}

// Ajouter un message utilisateur
function appendUserMessage(text) {
  const bubble = document.createElement("div");
  bubble.className = "chat outgoing";
  bubble.innerHTML = `<div class="details"><p>${escapeHtml(text)}</p></div>`;
  chatBox.appendChild(bubble);
  scrollToBottom();
}

// Ajouter un message du bot avec support Markdown
function appendBotMessage(text, avatar = "uploaded_img/Logo Green Engineering OK.png") {
  const bubble = document.createElement("div");
  bubble.className = "chat incoming";

  // Vérification explicite de marked.js et débogage
  if (typeof marked === "undefined") {
    console.error("marked.js n'est pas chargé. Vérifiez l'inclusion dans chat.php.");
    bubble.innerHTML = `<img src="${avatar}" alt="Bot"><div class="details"><p>${escapeHtml(text)}</p></div>`; // Fallback brut
  } else {
    console.log("Texte brut reçu pour le bot :", text); // Log du texte reçu
    const safeText = escapeHtml(text);
    try {
      const htmlContent = marked.parse(safeText);
      bubble.innerHTML = `
        <img src="${avatar}" alt="Bot">
        <div class="details">
          <p>${htmlContent}</p>
        </div>
      `;
    } catch (e) {
      console.error("Erreur dans marked.parse :", e);
      bubble.innerHTML = `<img src="${avatar}" alt="Bot"><div class="details"><p>${safeText}</p></div>`; // Fallback si erreur
    }
  }
  chatBox.appendChild(bubble);
  scrollToBottom();
}

// Indicateur de frappe
function addTypingIndicator() {
  removeTypingIndicator();
  const indicator = document.createElement("div");
  indicator.className = "chat incoming typing-indicator";
  indicator.id = "typing-indicator";
  indicator.innerHTML = `<img src="uploaded_img/Logo Green Engineering OK.png" alt="Bot"><div class="details"><p><em>Green Chat est en train d’écrire<span class="dots">...</span></em></p></div>`;
  chatBox.appendChild(indicator);
  scrollToBottom();
}

// Supprimer l'indicateur
function removeTypingIndicator() {
  const existing = document.getElementById("typing-indicator");
  if (existing) existing.remove();
}

// Échapper le HTML
function escapeHtml(text) {
  const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
  return text.replace(/[&<>"']/g, m => map[m]);
}

// Scroll automatique
function scrollToBottom() {
  chatBox.scrollTop = chatBox.scrollHeight;
}

// Feedback
// ... (reste du JavaScript existant jusqu'à showFeedbackPrompt) ...

// Feedback
function showFeedbackPrompt() {
    if (document.querySelector(".feedback-popup")) return;
    const popup = document.createElement("div");
    popup.className = "feedback-popup";
    popup.innerHTML = `
        <div class="feedback-content">
            <p>📝 Nous aimerions avoir votre avis sur l'expérience. Souhaitez-vous en laisser un ?</p>
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <button class="feedback-btn" onclick="openFeedbackForm()">Laisser un avis</button>
                <button class="feedback-close" onclick="closeFeedbackPopup()">Fermer</button>
            </div>
        </div>
    `;
    document.body.appendChild(popup);
    popup.style.display = "block"; // Afficher explicitement
}

function closeFeedbackPopup() {
    const f = document.querySelector(".feedback-popup");
    if (f) {
        f.style.display = "none"; // Masquer au lieu de supprimer
        setTimeout(() => f.remove(), 300); // Retirer après transition si besoin
    }
}

function openFeedbackForm() {
    closeFeedbackPopup();
    if (document.querySelector(".feedback-form-popup")) return;
    const formDiv = document.createElement("div");
    formDiv.className = "feedback-form-popup";
    formDiv.innerHTML = `
        <div class="feedback-form">
            <p>Votre avis nous aide à nous améliorer :</p>
            <textarea id="feedback-text" rows="4" placeholder="Écrivez votre avis ici..."></textarea>
            <div class="feedback-form-buttons">
                <button onclick="submitFeedback()">Envoyer</button>
                <button onclick="closeFeedbackForm()">Annuler</button>
            </div>
        </div>
    `;
    document.body.appendChild(formDiv);
    formDiv.style.display = "block"; // Afficher explicitement
}

function closeFeedbackForm() {
    const f = document.querySelector(".feedback-form-popup");
    if (f) {
        f.style.display = "none"; // Masquer au lieu de supprimer
        setTimeout(() => f.remove(), 300); // Retirer après transition si besoin
    }
}

function submitFeedback() {
    const textarea = document.getElementById("feedback-text");
    if (!textarea) return;
    const feedback = textarea.value.trim();
    if (feedback === "") {
        alert("Veuillez écrire un avis.");
        return;
    }
    fetch("php/save_feedback.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `feedback=${encodeURIComponent(feedback)}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.ok) alert("Merci pour votre avis !");
        else alert("Erreur : " + (data.error || "Échec"));
        closeFeedbackForm();
    })
    .catch(err => {
        alert("Erreur réseau : " + err.message);
        closeFeedbackForm();
    });
}

// ... (reste du JavaScript existant après submitFeedback) ...
// Charger l’historique des messages au chargement
window.addEventListener("load", () => {
  fetch("php/get_chat.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "incoming_id=" + encodeURIComponent(incoming_id)
  })
  .then(res => res.json())
  .then(data => {
    removeTypingIndicator();
    console.log("Historique (brute) :", JSON.stringify(data, null, 2)); // Log détaillé
    if (data.success) {
      data.messages.forEach(msg => {
        if (msg.sender === "user") appendUserMessage(msg.text);
        else appendBotMessage(msg.text, msg.avatar);
      });
    } else {
      appendBotMessage("❌ " + (data.error || "Erreur inconnue"));
    }
  })
  .catch(err => {
    removeTypingIndicator();
    appendBotMessage("❌ Erreur réseau : " + err.message);
    console.error("Erreur lors du chargement des messages :", err);
  });
});