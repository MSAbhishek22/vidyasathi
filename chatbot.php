<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user info
$user_id = $_SESSION['user_id'];
// Check if name exists in session, otherwise use a default value
$user_name = isset($_SESSION['name']) ? $_SESSION['name'] : "User";
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : "student";

// Set page title and include header
$page_title = 'AI Assistant - VidyaSathi';
include 'header.php';
?>

<div class="py-6"></div>

<div class="container mx-auto px-4 my-8">
    <div class="max-w-5xl mx-auto">
        <div class="bg-card-bg rounded-lg shadow-lg overflow-hidden border border-gray-800">
            <!-- AI Assistant Header -->
            <div class="p-6 border-b border-gray-800 bg-dark">
                <h1 class="text-2xl font-bold flex items-center">
                    <i class="fas fa-robot text-primary mr-3"></i>
                    Veronica AI Assistant
                </h1>
                <p class="text-gray-400 mt-2">
                    Your personal AI learning assistant. Ask questions about your studies, get help with assignments,
                    or discuss educational topics.
                </p>
            </div>

            <!-- Chatbot Interface - Direct implementation -->
            <div class="p-4 md:p-6">
                <div id="chatInterface" class="w-full h-full">
                    <!-- Chatbot UI -->
                    <div class="chat-container" style="max-width: 100%; height: 600px;">
                        <div class="chat-header">
                            🤖 Veronica - Your Assistant
                            <div>
                                <div class="voice-selector">
                                    <button id="voice-select-btn" title="Select Voice"><i
                                            class="fas fa-robot"></i></button>
                                    <div class="voice-options" id="voice-options">
                                        <div class="voice-option" data-voice="female">Female Voice</div>
                                        <div class="voice-option" data-voice="male">Male Voice</div>
                                    </div>
                                </div>
                                <button id="mute-btn" title="Mute/Unmute Bot"><i class="fas fa-volume-up"></i></button>
                                <button class="theme-toggle" onclick="toggleTheme()"><i
                                        class="fas fa-adjust"></i></button>
                            </div>
                        </div>
                        <div class="chat-history" id="chat-history">
                            <div class="message bot-message">Hi, I'm Veronica! How can I assist you today?</div>
                        </div>
                        <div id="thinking"></div>
                        <div class="input-area">
                            <textarea id="user-input" rows="1" placeholder="Ask Veronica..."></textarea>
                            <button id="voice-toggle"><i class="fas fa-volume-up"></i></button>
                            <button id="mic-btn"><i class="fas fa-microphone"></i></button>
                            <button id="send-btn"><i class="fas fa-paper-plane"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Features & Tips Section -->
                <div class="mt-8 pt-6 border-t border-gray-800">
                    <h3 class="text-xl font-semibold mb-4">
                        <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                        Features & Tips
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-dark p-4 rounded-lg">
                            <h4 class="font-semibold text-primary mb-2">
                                <i class="fas fa-microphone mr-2"></i>
                                Voice Input
                            </h4>
                            <p class="text-gray-300">Click the microphone icon to speak with Veronica. Perfect for
                                hands-free interaction.</p>
                        </div>

                        <div class="bg-dark p-4 rounded-lg">
                            <h4 class="font-semibold text-primary mb-2">
                                <i class="fas fa-volume-up mr-2"></i>
                                Text-to-Speech
                            </h4>
                            <p class="text-gray-300">Let Veronica speak responses to you. Toggle voice output on/off as
                                needed.</p>
                        </div>

                        <div class="bg-dark p-4 rounded-lg">
                            <h4 class="font-semibold text-primary mb-2">
                                <i class="fas fa-question-circle mr-2"></i>
                                Study Help
                            </h4>
                            <p class="text-gray-300">Ask about topics you're studying, request explanations, or get
                                study tips.</p>
                        </div>

                        <div class="bg-dark p-4 rounded-lg">
                            <h4 class="font-semibold text-primary mb-2">
                                <i class="fas fa-moon mr-2"></i>
                                Theme Options
                            </h4>
                            <p class="text-gray-300">Toggle between light and dark modes for comfortable day/night
                                studying.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include marked.js for Markdown support -->
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

<!-- Chatbot Styles -->
<style>
    body {
        transition: background-color 0.3s ease;
    }

    .dark-mode {
        background-color: #1e1e1e;
    }

    .chat-container {
        background-color: #fff;
        width: 100%;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: background-color 0.3s ease;
    }

    .dark-mode .chat-container {
        background-color: #2e2e2e;
    }

    .chat-header {
        background-color: #4a90e2;
        color: white;
        padding: 16px;
        text-align: center;
        font-size: 1.2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .theme-toggle {
        background: none;
        border: none;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
    }

    .chat-history {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 12px;
        height: 400px;
    }

    .message {
        max-width: 80%;
        padding: 12px 16px;
        border-radius: 16px;
        line-height: 1.5;
        animation: fadeIn 0.3s ease-in-out;
    }

    .user-message {
        background-color: #d4edda;
        align-self: flex-end;
    }

    .bot-message {
        background-color: #e9ecef;
        align-self: flex-start;
    }

    .dark-mode .user-message {
        background-color: #375a7f;
        color: #fff;
    }

    .dark-mode .bot-message {
        background-color: #444;
        color: #eee;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .input-area {
        display: flex;
        gap: 10px;
        padding: 16px;
        border-top: 1px solid #ddd;
    }

    #user-input {
        flex: 1;
        padding: 12px;
        font-size: 1rem;
        border-radius: 8px;
        border: 1px solid #ccc;
        background-color: #fff;
        color: #333;
    }

    .dark-mode #user-input {
        background-color: #333;
        color: #eee;
        border-color: #555;
    }

    #send-btn,
    #mic-btn,
    #voice-toggle {
        padding: 10px 14px;
        background-color: #4a90e2;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
    }

    #send-btn:hover,
    #mic-btn:hover,
    #voice-toggle:hover {
        background-color: #3b7ccc;
    }

    #thinking {
        text-align: center;
        font-style: italic;
        color: gray;
        padding: 5px;
    }

    #mute-btn {
        padding: 10px 14px;
        background-color: #4a90e2;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
    }

    #mute-btn:hover {
        background-color: #3b7ccc;
    }

    .muted {
        background-color: #dc3545 !important;
    }

    .paused {
        background-color: #ffc107 !important;
    }

    .voice-selector {
        position: relative;
        display: inline-block;
    }

    .voice-options {
        display: none;
        position: absolute;
        right: 0;
        background-color: white;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
        border-radius: 8px;
        overflow: hidden;
    }

    .voice-options.show {
        display: block;
    }

    .voice-option {
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        color: #333;
        cursor: pointer;
    }

    .voice-option:hover {
        background-color: #f1f1f1;
    }

    .dark-mode .voice-options {
        background-color: #2e2e2e;
    }

    .dark-mode .voice-option {
        color: #eee;
    }

    .dark-mode .voice-option:hover {
        background-color: #444;
    }

    #voice-select-btn {
        padding: 10px 14px;
        background-color: #4a90e2;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        margin-right: 10px;
    }

    #voice-select-btn:hover {
        background-color: #3b7ccc;
    }
</style>

<!-- Chatbot Scripts -->
<script>
    const chatHistory = document.getElementById("chat-history");
    const userInput = document.getElementById("user-input");
    const sendBtn = document.getElementById("send-btn");
    const micBtn = document.getElementById("mic-btn");
    const voiceToggleBtn = document.getElementById("voice-toggle");
    const muteBtn = document.getElementById("mute-btn");
    const thinkingDiv = document.getElementById("thinking");
    const voiceSelectBtn = document.getElementById("voice-select-btn");
    const voiceOptions = document.getElementById("voice-options");

    // API endpoint - point to the Flask server
    const apiEndpoint = "http://192.168.137.16:5000/chat";

    let voiceEnabled = false;
    let isMuted = false;
    let isPaused = false;
    let speechQueue = [];
    let currentUtterance = null;
    let selectedVoice = "female"; // Default to female voice

    function toggleTheme() {
        document.body.classList.toggle("dark-mode");
    }

    function appendMessage(text, isUser = false) {
        const msgDiv = document.createElement("div");
        msgDiv.className = `message ${isUser ? 'user-message' : 'bot-message'}`;
        try {
            // Check if text is undefined or null before parsing
            if (text === undefined || text === null) {
                msgDiv.textContent = "Error: Empty response received";
            } else {
                msgDiv.innerHTML = marked.parse(text);
            }
        } catch (error) {
            console.error("Marked parsing error:", error);
            // Fallback to plain text if parsing fails
            msgDiv.textContent = text || "Error displaying message";
        }
        chatHistory.appendChild(msgDiv);
        chatHistory.scrollTop = chatHistory.scrollHeight;
    }

    // Function to get available voices
    function getVoices() {
        return new Promise(resolve => {
            let voices = window.speechSynthesis.getVoices();
            if (voices.length) {
                resolve(voices);
            } else {
                window.speechSynthesis.onvoiceschanged = () => {
                    voices = window.speechSynthesis.getVoices();
                    resolve(voices);
                };
            }
        });
    }

    // Function to set voice based on selection
    async function setVoice(voiceType) {
        const voices = await getVoices();
        let voice;

        if (voiceType === "female") {
            // Try to find a female voice
            voice = voices.find(v => v.name.includes("Female") || v.name.includes("female") || v.name.includes("Samantha") || v.name.includes("Zira"));
            if (!voice) {
                // Fallback to any available voice
                voice = voices[0];
            }
        } else {
            // Try to find a male voice
            voice = voices.find(v => v.name.includes("Male") || v.name.includes("male") || v.name.includes("David") || v.name.includes("Mark"));
            if (!voice) {
                // Fallback to any available voice
                voice = voices[0];
            }
        }

        return voice;
    }

    function updateMuteButton() {
        if (isMuted) {
            muteBtn.classList.add("muted");
            muteBtn.classList.remove("paused");
            muteBtn.innerHTML = '<i class="fas fa-volume-mute"></i>';
        } else if (isPaused) {
            muteBtn.classList.add("paused");
            muteBtn.classList.remove("muted");
            muteBtn.innerHTML = '<i class="fas fa-pause"></i>';
        } else {
            muteBtn.classList.remove("muted");
            muteBtn.classList.remove("paused");
            muteBtn.innerHTML = '<i class="fas fa-volume-up"></i>';
        }
    }

    function updateVoiceToggleButton() {
        voiceToggleBtn.innerHTML = voiceEnabled ?
            '<i class="fas fa-volume-up"></i>' :
            '<i class="fas fa-volume-mute"></i>';
    }

    function sendMessage() {
        const message = userInput.value.trim();
        if (!message) return;
        appendMessage(message, true);
        userInput.value = "";
        thinkingDiv.innerText = "Veronica is thinking...";

        fetch(apiEndpoint, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ message })
        })
            .then(res => {
                if (!res.ok) {
                    throw new Error(`HTTP error! Status: ${res.status}`);
                }
                return res.json();
            })
            .then(data => {
                thinkingDiv.innerText = "";
                if (data.reply) {
                    appendMessage(data.reply);
                    speak(data.reply);
                } else if (data.response) {
                    // Backward compatibility with previous API format
                    appendMessage(data.response);
                    speak(data.response);
                } else {
                    appendMessage("Sorry, I couldn't understand that.");
                }
            })
            .catch(err => {
                thinkingDiv.innerText = "";
                appendMessage("❌ Error: " + err.message);
                console.error("Fetch error:", err);
            });
    }

    sendBtn.addEventListener("click", sendMessage);
    userInput.addEventListener("keypress", e => {
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    micBtn.addEventListener("click", () => {
        try {
            const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
            recognition.lang = 'en-US';
            recognition.start();

            recognition.onresult = event => {
                const transcript = event.results[0][0].transcript;
                userInput.value = transcript;
                sendMessage();
            };

            recognition.onerror = event => {
                alert("🎙️ Voice error: " + event.error);
            };
        } catch (err) {
            alert("Speech recognition is not supported in your browser.");
        }
    });

    voiceToggleBtn.addEventListener("click", () => {
        voiceEnabled = !voiceEnabled;
        voiceToggleBtn.innerHTML = voiceEnabled ? '<i class="fas fa-volume-mute"></i>' : '<i class="fas fa-volume-up"></i>';

        if (!voiceEnabled) {
            window.speechSynthesis.cancel();
            speechQueue = [];
            currentUtterance = null;
            isPaused = false;
            muteBtn.classList.remove("paused");
            muteBtn.classList.remove("muted");
            muteBtn.innerHTML = '<i class="fas fa-volume-up"></i>';
        }
    });

    muteBtn.addEventListener("click", () => {
        if (isPaused) {
            // Resume speech
            isPaused = false;
            muteBtn.classList.remove("paused");
            muteBtn.innerHTML = '<i class="fas fa-volume-up"></i>';

            if (speechQueue.length > 0) {
                currentUtterance = speechQueue.shift();
                window.speechSynthesis.speak(currentUtterance);
            }
        } else if (window.speechSynthesis.speaking) {
            // Pause speech
            isPaused = true;
            muteBtn.classList.add("paused");
            muteBtn.innerHTML = '<i class="fas fa-pause"></i>';
            window.speechSynthesis.pause();
        } else {
            // Toggle mute
            isMuted = !isMuted;
            isPaused = false;
            muteBtn.classList.toggle("muted");
            muteBtn.classList.remove("paused");
            muteBtn.innerHTML = isMuted ? '<i class="fas fa-volume-mute"></i>' : '<i class="fas fa-volume-up"></i>';

            if (isMuted) {
                window.speechSynthesis.cancel();
                speechQueue = [];
                currentUtterance = null;
            }
        }
    });

    // Voice selection event listeners
    voiceSelectBtn.addEventListener("click", () => {
        voiceOptions.classList.toggle("show");
    });

    document.querySelectorAll(".voice-option").forEach(option => {
        option.addEventListener("click", async () => {
            selectedVoice = option.dataset.voice;
            voiceOptions.classList.remove("show");
            voiceSelectBtn.innerHTML = selectedVoice === "female" ?
                '<i class="fas fa-female"></i>' :
                '<i class="fas fa-male"></i>';

            // If there's a current utterance, restart it with new voice
            if (currentUtterance) {
                window.speechSynthesis.cancel();
                await speak(currentUtterance.text);
            }
        });
    });

    // Close voice options when clicking outside
    document.addEventListener("click", (e) => {
        if (!voiceSelectBtn.contains(e.target) && !voiceOptions.contains(e.target)) {
            voiceOptions.classList.remove("show");
        }
    });

    // Function to speak with selected voice
    async function speak(text) {
        if (!voiceEnabled || isMuted) return;

        const cleanText = cleanTextForSpeech(text);
        try {
            const utterance = new SpeechSynthesisUtterance(cleanText);
            const voice = await setVoice(selectedVoice);
            utterance.voice = voice;

            if (isPaused) {
                speechQueue.push(utterance);
                return;
            }

            utterance.onend = () => {
                if (speechQueue.length > 0) {
                    currentUtterance = speechQueue.shift();
                    window.speechSynthesis.speak(currentUtterance);
                } else {
                    currentUtterance = null;
                }
            };

            if (window.speechSynthesis.speaking) {
                speechQueue.push(utterance);
            } else {
                currentUtterance = utterance;
                window.speechSynthesis.speak(utterance);
            }
        } catch (error) {
            console.error("Speech synthesis error:", error);
        }
    }

    // Function to clean markdown formatting
    function cleanTextForSpeech(text) {
        // Remove markdown formatting
        return text
            .replace(/\*\*(.*?)\*\*/g, '$1') // Remove bold
            .replace(/\*(.*?)\*/g, '$1')      // Remove italic
            .replace(/`(.*?)`/g, '$1')        // Remove code
            .replace(/#{1,6}\s/g, '')         // Remove headings
            .replace(/\[(.*?)\]\(.*?\)/g, '$1') // Remove links
            .replace(/\n/g, ' ')              // Replace newlines with spaces
            .replace(/\s+/g, ' ')             // Normalize whitespace
            .trim();
    }
</script>

<?php include 'footer.php'; ?>