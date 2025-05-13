from flask import Flask, render_template, request, jsonify
import requests
import sqlite3
from datetime import datetime
import os
import sys
from flask_cors import CORS
from dotenv import load_dotenv

# Load environment variables from .env if present
load_dotenv()

# Get API key from environment variable
GROQ_API_KEY = os.environ.get('GROQ_API_KEY')

app = Flask(__name__)
CORS(app)  # Enable CORS for all routes

GROQ_ENDPOINT = "https://api.groq.com/openai/v1/chat/completions"
MODEL = "llama3-8b-8192"

# Create table for chat history
def init_db():
    db_path = os.path.join(os.path.dirname(os.path.abspath(__file__)), "chat_history.db")
    conn = sqlite3.connect(db_path)
    cursor = conn.cursor()
    cursor.execute('''
        CREATE TABLE IF NOT EXISTS messages (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            role TEXT,
            message TEXT,
            timestamp TEXT
        )
    ''')
    conn.commit()
    conn.close()

@app.route("/")
def index():
    return "Veronica Chatbot API is running."

@app.route("/chat", methods=["POST"])
def chat():
    try:
        data = request.get_json()
        if not data:
            return jsonify({"reply": "Invalid JSON data"}), 400
        
        user_input = data.get("message")
        if not user_input:
            return jsonify({"reply": "No message provided"}), 400
        
        # Save the user message to the database
        save_message("user", user_input)
        
        # Use the prompt as the system message if it contains instructions
        if "User:" in user_input:
            system_prompt, user_message = user_input.split("User:", 1)
            messages = [
                {"role": "system", "content": system_prompt.strip()},
                {"role": "user", "content": user_message.strip()}
            ]
        else:
            messages = [
                {"role": "system", "content": "You are Veronica, a helpful educational AI assistant. You provide clear and concise answers to academic questions, helping users understand complex topics."},
                {"role": "user", "content": user_input}
            ]
        
        # Add history from the database
        history = get_chat_history(5)  # Get the last 5 exchanges
        messages[1:1] = history  # Insert history before the latest user message
        
        # Send request to Groq API
        headers = {
            "Authorization": f"Bearer {GROQ_API_KEY}",
            "Content-Type": "application/json"
        }
        
        data = {
            "model": MODEL,
            "messages": messages,
            "temperature": 0.7,
            "max_tokens": 1000
        }
        
        try:
            response = requests.post(GROQ_ENDPOINT, headers=headers, json=data)
            response.raise_for_status()  # Raise an error for bad status codes
            
            result = response.json()
            ai_response = result["choices"][0]["message"]["content"]
            
            # Save the AI response to the database
            save_message("assistant", ai_response)
            
            return jsonify({"reply": ai_response})
        
        except Exception as e:
            print(f"API Error: {str(e)}")
            return jsonify({"reply": f"An error occurred when calling the AI service: {str(e)}"}), 500
    except Exception as e:
        print(f"Request handling error: {str(e)}")
        return jsonify({"reply": f"An error occurred: {str(e)}"}), 500

def save_message(role, message):
    db_path = os.path.join(os.path.dirname(os.path.abspath(__file__)), "chat_history.db")
    conn = sqlite3.connect(db_path)
    cursor = conn.cursor()
    timestamp = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    cursor.execute("INSERT INTO messages (role, message, timestamp) VALUES (?, ?, ?)",
                 (role, message, timestamp))
    conn.commit()
    conn.close()

def get_chat_history(limit=5):
    db_path = os.path.join(os.path.dirname(os.path.abspath(__file__)), "chat_history.db")
    conn = sqlite3.connect(db_path)
    cursor = conn.cursor()
    cursor.execute("SELECT role, message FROM messages ORDER BY id DESC LIMIT ?", (limit * 2,))
    results = cursor.fetchall()
    conn.close()
    
    # Format for API request, reversed to get chronological order
    return [{"role": role, "content": message} for role, message in reversed(results)]

if __name__ == "__main__":
    print(f"Starting Veronica Chatbot server...")
    print(f"API Key exists: {GROQ_API_KEY is not None}")
    print(f"API Key value: {GROQ_API_KEY[:10]}...")
    
    init_db()
    app.run(host='0.0.0.0', port=5000, debug=True) 