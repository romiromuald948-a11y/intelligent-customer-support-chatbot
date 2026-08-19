import nltk
nltk.download('punkt')
nltk.download('punkt_tab')
import sys
import os
import re
import cohere
import mysql.connector
from datetime import datetime
from nltk.tokenize import sent_tokenize
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity


# Obtenir la langue de l'utilisateur
def get_user_language(user_id):
    conn = mysql.connector.connect(
        host="localhost", user="root", password="", database="chatapp_db"
    )
    cursor = conn.cursor()
    cursor.execute("SELECT langue FROM user_form WHERE user_id = %s", (user_id,))
    result = cursor.fetchone()
    return result[0] if result else 'fr'

# API Cohere
co = cohere.Client("JJtTpVSMoYkJzfrIGCJq1s1JEFYAhZRItvB47LjR")

nltk.download('punkt', quiet=True)

# Nettoyage texte
def clean_text(text):
    return re.sub(r'\s+', ' ', text.strip().lower())

# Lecture du document et vectorisation
with open("document_entreprise.txt", "r", encoding="utf-8") as f:
    raw = f.read()
phrases = [clean_text(p) for p in sent_tokenize(raw)]

vectorizer = TfidfVectorizer()
tfidf_matrix = vectorizer.fit_transform(phrases)

# Recherche des extraits les plus similaires
def recherche_similaire(question, top_n=3):
    question_clean = clean_text(question)
    vecteur_question = vectorizer.transform([question_clean])
    similarites = cosine_similarity(vecteur_question, tfidf_matrix).flatten()
    indices_top = similarites.argsort()[-top_n:][::-1]
    return list(set([phrases[i] for i in indices_top]))

# Reformulation multilingue basée sur les extraits
def reformuler_texte(contenu, question, langue="fr"):
    prompt = f"""
Tu es Green Chat, assistant virtuel de Green Engineering SARL. Voici des informations de l'entreprise :
\"{contenu}\"

Réponds à la question ci-dessous de manière claire et professionnelle, uniquement en {langue.upper()} :

Question : {question}
"""
    try:
        response = co.chat(model="command-r", message=prompt, temperature=0.6, max_tokens=200)
        return response.text.strip()
    except Exception as e:
        return f"Erreur IA : {e}"

# Détection de commande
CMD_REGEX = re.compile(r"(?:commande de|je voudrais commander|je veux|je voudrais|j(?:'|e )?aimerais|il me faut)\s+(un|une|\d+)\s+([\w\s'\-]+(?: de [\w\s\d]+)?)", re.I)
def detect_command(msg):
    m = CMD_REGEX.search(msg)
    if not m:
        return None
    quantite_str = m.group(1).lower()
    quantite = 1 if quantite_str in ['un', 'une'] else int(quantite_str)
    produit = m.group(2).strip()
    return {"quantite": quantite, "produit": produit, "unite": "unité"}

# Détection de tentative de négociation
def detecter_negociation(msg):
    msg = msg.lower()
    mots_negociation = [
        "réduction", "remise", "baisser", "négocier", "moins cher", "prix trop élevé", "rabais", "peux-tu faire un effort", 
        "can you lower the price", "discount", "cheaper", "negotiate", "negociar", "rebaja", "más barato"
    ]
    for mot in mots_negociation:
        if mot in msg:
            return True
    return False

# Connexion BDD
def connect_db():
    return mysql.connector.connect(host="localhost", user="root", password="", database="chatapp_db")

# Commande temporaire
def insert_temp_command(user_id, cmd):
    conn = connect_db()
    cur = conn.cursor()
    cur.execute("DELETE FROM commandes_temp WHERE user_id = %s", (user_id,))
    cur.execute("INSERT INTO commandes_temp (user_id, produit, quantite, unite) VALUES (%s, %s, %s, %s)",
                (user_id, cmd["produit"], cmd["quantite"], cmd["unite"]))
    conn.commit()
    cur.close()
    conn.close()

def update_temp_info(user_id, field, value):
    conn = connect_db()
    cur = conn.cursor()
    cur.execute(f"UPDATE commandes_temp SET {field} = %s WHERE user_id = %s", (value, user_id))
    conn.commit()
    cur.close()
    conn.close()

def get_temp_command(user_id):
    conn = connect_db()
    cur = conn.cursor(dictionary=True)
    cur.execute("SELECT * FROM commandes_temp WHERE user_id = %s", (user_id,))
    result = cur.fetchone()
    cur.close()
    conn.close()
    return result

def finalize_command(user_id, data):
    conn = connect_db()
    cur = conn.cursor()
    cur.execute(
        "INSERT INTO commandes (user_id, produit, quantite, unite, date_commande, statut, nom_client, lieu_livraison, telephone, email) "
        "VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
        (
            user_id,
            data["produit"],
            data["quantite"],
            data["unite"],
            datetime.now(),
            "Nouveau",
            data["nom_client"],
            data["lieu_livraison"],
            data["telephone"],
            data["email"]
        )
    )
    cur.execute("DELETE FROM commandes_temp WHERE user_id = %s", (user_id,))
    conn.commit()
    cur.close()
    conn.close()

# ▶️ Lancement du bot
if __name__ == "__main__":
    sys.stdout.reconfigure(encoding='utf-8')

    if len(sys.argv) < 3:
        print("Erreur : usage → python green_bot_vecteur.py <user_id> <message>")
        sys.exit(1)

    user_id = sys.argv[1]
    message = " ".join(sys.argv[2:]).strip()

    langue_utilisateur = get_user_language(user_id)

    # 🔍 Négociation détectée
    if detecter_negociation(message):
        reponse_negociation = {
            "fr": "Merci pour votre intérêt. Les prix sont déjà compétitifs. Vous pourrez discuter de toute négociation avec un agent lorsqu’il vous contactera.",
            "en": "Thank you for your interest. The prices are already competitive. You will be able to discuss any negotiation with an agent when they contact you.",
            "es": "Gracias por su interés. Los precios ya son competitivos. Podrá hablar de cualquier negociación con un agente cuando se comunique con usted."
        }
        print(reponse_negociation.get(langue_utilisateur, reponse_negociation["fr"]))
        sys.exit(0)

    # Suite logique de commande
    data = get_temp_command(user_id)
    if data:
        if not data.get("nom_client"):
            update_temp_info(user_id, "nom_client", message)
            print("📍 Merci. Quel est le lieu de livraison ?")
            sys.exit(0)
        elif not data.get("lieu_livraison"):
            update_temp_info(user_id, "lieu_livraison", message)
            print("📞 Très bien. Quel est votre numéro de téléphone ?")
            sys.exit(0)
        elif not data.get("telephone"):
            update_temp_info(user_id, "telephone", message)
            print("📧 Parfait. Enfin, indiquez-moi votre adresse email :")
            sys.exit(0)
        elif not data.get("email"):
            update_temp_info(user_id, "email", message)
            finalize_command(user_id, get_temp_command(user_id))
            print("✅ Merci ! Votre commande est enregistrée. Un conseiller vous contactera bientôt.")
            sys.exit(0)

    # Détection de commande
    cmd = detect_command(message)
    if cmd:
        insert_temp_command(user_id, cmd)
        print(f"📝 Pour finaliser la commande de {cmd['quantite']} {cmd['produit']}, merci de fournir les informations suivantes.\n🔹 Nom du client :")
        sys.exit(0)

    # Sinon, réponse standard issue du document + traduction
    extraits = recherche_similaire(message)
    contenu = " ".join(extraits)
    reponse = reformuler_texte(contenu, message, langue_utilisateur)
    print(reponse)