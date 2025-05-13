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

<body>
    <div style="height: 4px;"></div>
    <div class="container mx-auto px-4 my-0" style="background: #181a20;">
        <div class="max-w-5xl mx-auto" style="padding: 0;">
            <div class="bg-card-bg rounded-lg shadow-lg overflow-hidden border border-gray-800">
                <!-- AI Assistant Header -->
                <div class="p-6 border-b border-gray-800 bg-dark">
                    <h1 class="text-2xl font-bold flex items-center">
                        <i class="fas fa-robot text-primary mr-3"></i>
                        Veronica AI Assistant
                    </h1>
                    <p class="text-gray-400 mt-2">
                        I'm Veronica, your personal student wellness and productivity companion. You can talk to me
                        about stress, motivation, time management, mental well-being, or anything else on your mind.
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
                                </div>
                            </div>
                            <div class="chat-history" id="chat-history">
                                <div class="message bot-message">
                                    Hey there! I'm Veronica — your well-being and productivity buddy. Feeling stressed?
                                    Need motivation or just want to talk it out? I'm here for you.
                                </div>
                            </div>
                            <div id="thinking"></div>
                            <div class="input-area">
                                <select id="modeSelector"
                                    class="ml-2 p-1 rounded border bg-[#23272f] text-white text-sm"
                                    style="width: 160px; min-width: 120px;">
                                    <option value="wellness">🧠 Mental Wellness</option>
                                    <option value="study">📘 Study Help</option>
                                    <option value="motivation">�� Motivation</option>
                                </select>
                                <textarea id="user-input" rows="2" style="min-height:44px;"
                                    placeholder="Ask Veronica..."></textarea>
                                <button id="voice-toggle"><i class="fas fa-volume-up"></i></button>
                                <button id="mic-btn"><i class="fas fa-microphone"></i></button>
                                <button id="send-btn"><i class="fas fa-paper-plane"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="max-w-5xl mx-auto mt-0 mb-2" style="color: #fff;">
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px; justify-items: center;">
            <div
                style="background:#23272f; border-radius:12px; padding:16px 20px; min-width:180px; display:flex; align-items:center; gap:12px; box-shadow:0 2px 8px 0 rgba(0,0,0,0.10); margin-bottom: 0;">
                <i class="fas fa-brain" style="font-size:2rem; color:#d4af37;"></i>
                <div>
                    <div style="font-weight:600;">Mental Wellness</div>
                    <div style="font-size:0.97rem; color:#ccc;">Talk about stress, anxiety, or motivation. Get self-care
                        tips and support.</div>
                </div>
            </div>
            <div
                style="background:#23272f; border-radius:12px; padding:16px 20px; min-width:180px; display:flex; align-items:center; gap:12px; box-shadow:0 2px 8px 0 rgba(0,0,0,0.10); margin-bottom: 0;">
                <i class="fas fa-book-open" style="font-size:2rem; color:#d4af37;"></i>
                <div>
                    <div style="font-weight:600;">Study Help</div>
                    <div style="font-size:0.97rem; color:#ccc;">Ask for study tips, explanations, or help with
                        assignments and concepts.</div>
                </div>
            </div>
            <div
                style="background:#23272f; border-radius:12px; padding:16px 20px; min-width:180px; display:flex; align-items:center; gap:12px; box-shadow:0 2px 8px 0 rgba(0,0,0,0.10); margin-bottom: 0;">
                <i class="fas fa-bullseye" style="font-size:2rem; color:#d4af37;"></i>
                <div>
                    <div style="font-weight:600;">Goal Motivation</div>
                    <div style="font-size:0.97rem; color:#ccc;">Get encouragement, productivity hacks, and help with
                        time management.</div>
                </div>
            </div>
            <div
                style="background:#23272f; border-radius:12px; padding:16px 20px; min-width:180px; display:flex; align-items:center; gap:12px; box-shadow:0 2px 8px 0 rgba(0,0,0,0.10); margin-bottom: 0;">
                <i class="fas fa-user-friends" style="font-size:2rem; color:#d4af37;"></i>
                <div>
                    <div style="font-weight:600;">Personal Support</div>
                    <div style="font-size:0.97rem; color:#ccc;">Share your thoughts or worries. Veronica listens without
                        judgment.</div>
                </div>
            </div>
            <div
                style="background:#23272f; border-radius:12px; padding:16px 20px; min-width:180px; display:flex; align-items:center; gap:12px; box-shadow:0 2px 8px 0 rgba(0,0,0,0.10); margin-bottom: 0;">
                <i class="fas fa-heartbeat" style="font-size:2rem; color:#d4af37;"></i>
                <div>
                    <div style="font-weight:600;">Self-Care Tips</div>
                    <div style="font-size:0.97rem; color:#ccc;">Get actionable advice for breaks, mindfulness, and
                        healthy habits.</div>
                </div>
            </div>
            <div
                style="background:#23272f; border-radius:12px; padding:16px 20px; min-width:180px; display:flex; align-items:center; gap:12px; box-shadow:0 2px 8px 0 rgba(0,0,0,0.10); margin-bottom: 0;">
                <i class="fas fa-lightbulb" style="font-size:2rem; color:#d4af37;"></i>
                <div>
                    <div style="font-weight:600;">Productivity Hacks</div>
                    <div style="font-size:0.97rem; color:#ccc;">Learn Pomodoro, time management, and focus techniques.
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<!-- Include marked.js for Markdown support -->
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

<!-- Chatbot Styles -->
<style>
    body,
    .container,
    .max-w-5xl,
    .mx-auto,
    .bg-card-bg,
    .p-4,
    .md\:p-6,
    .p-6,
    .border,
    .border-gray-800,
    .bg-dark {
        background: #181a20 !important;
        color: #fff !important;
        box-shadow: none !important;
        border: none !important;
    }

    .chat-container {
        background: #23272f !important;
        border-radius: 18px;
        box-shadow: 0 4px 32px 0 rgba(0, 0, 0, 0.10);
        color: #fff !important;
        width: 100%;
        max-width: 700px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        min-height: 500px;
    }

    .chat-header {
        background: #d4af37 !important;
        color: #23272f !important;
        border-top-left-radius: 18px;
        border-top-right-radius: 18px;
        padding: 18px 24px;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        border-bottom: 1px solid #23272f;
    }

    .chat-header i,
    .chat-header svg {
        color: #23272f !important;
    }

    .chat-history {
        background: #23272f !important;
        color: #fff !important;
        flex: 1;
        padding: 32px 24px 16px 24px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 18px;
        min-height: 300px;
    }

    .message {
        display: flex;
        align-items: flex-end;
        gap: 12px;
        margin-bottom: 0;
    }

    .user-message,
    .bot-message {
        padding: 14px 20px;
        border-radius: 16px;
        font-size: 1.08rem;
        max-width: 70%;
        word-break: break-word;
        box-shadow: 0 2px 8px 0 rgba(0, 0, 0, 0.04);
    }

    .user-message {
        background: #375a7f !important;
        color: #fff !important;
        margin-left: auto;
        border-bottom-right-radius: 4px;
    }

    .bot-message {
        background: #313543 !important;
        color: #fff !important;
        margin-right: auto;
        border-bottom-left-radius: 4px;
    }

    .avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #313543;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        font-weight: bold;
        color: #d4af37;
    }

    .input-area {
        background: #23272f !important;
        display: flex;
        gap: 8px;
        padding: 14px 18px;
        border-top: 1px solid #313543;
        align-items: flex-end;
    }

    #modeSelector {
        background: #23272f !important;
        color: #fff !important;
        border: 1px solid #313543;
        font-size: 0.95rem;
        min-width: 120px;
        max-width: 160px;
        height: 38px;
        margin-right: 4px;
    }

    #user-input {
        flex: 1;
        padding: 12px 14px;
        font-size: 1rem;
        border-radius: 10px;
        border: 1px solid #313543;
        background: #181a20 !important;
        color: #fff !important;
        outline: none;
        min-height: 44px;
        max-height: 80px;
        resize: vertical;
        transition: border 0.2s;
    }

    #user-input:focus {
        border: 1.5px solid #d4af37;
    }

    #send-btn,
    #mic-btn,
    #voice-toggle {
        padding: 0 14px;
        background: #d4af37 !important;
        color: #23272f !important;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        height: 44px;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }

    #send-btn:hover,
    #mic-btn:hover,
    #voice-toggle:hover {
        background: #bfa133 !important;
    }

    #thinking {
        text-align: left;
        font-style: italic;
        color: #d4af37;
        padding: 0 0 0 60px;
        font-size: 1rem;
        margin-bottom: 8px;
    }

    .theme-toggle {
        background: none;
        border: none;
        color: #fff;
        font-size: 1.2rem;
        cursor: pointer;
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

    .voice-selector {
        position: relative;
        display: inline-block;
    }

    .voice-options {
        display: none;
        position: absolute;
        right: 0;
        background-color: #23272f;
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
        color: #fff;
        cursor: pointer;
    }

    .voice-option:hover {
        background-color: #313543;
    }

    #voice-select-btn {
        padding: 10px 14px;
        background-color: #d4af37;
        color: #23272f;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        margin-right: 10px;
    }

    #voice-select-btn:hover {
        background-color: #bfa133;
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
    const modeSelector = document.getElementById("modeSelector");

    // API endpoint - point to the Flask server
    const apiEndpoint = "http://localhost:5000/chat";

    let voiceEnabled = false;
    let isMuted = false;
    let isPaused = false;
    let speechQueue = [];
    let currentUtterance = null;
    let selectedVoice = "female"; // Default to female voice

    function appendMessage(text, isUser = false) {
        const msgDiv = document.createElement("div");
        msgDiv.className = `message ${isUser ? 'user-message' : 'bot-message'}`;
        // Add avatar
        const avatar = document.createElement('div');
        avatar.className = 'avatar';
        avatar.innerHTML = isUser ? '<i class="fas fa-user"></i>' : '<i class="fas fa-robot"></i>';
        if (isUser) {
            msgDiv.appendChild(avatar);
        }
        const content = document.createElement('div');
        try {
            if (text === undefined || text === null) {
                content.textContent = "Error: Empty response received";
            } else {
                // Highlight important words and improve spacing
                let formatted = text
                    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>') // bold
                    .replace(/\*(.*?)\*/g, '<em>$1</em>') // italic
                    .replace(/\n/g, '<br>') // newlines
                    .replace(/(stress(ed|ful)?|motivat(e|ed|ion|ing)?|well-being|support(s|ed|ing)?|stud(y|ies|ied|ying)?|goal(s)?|productivit(y|ies)?|listen(s|ed|ing)?|help(s|ed|ing)?|assign(ment|ments)?|explain(s|ed|ing|ation)?|encourag(e|ed|ing|ement)?|self-care|time management|memorize(s|d|ing)?|achiev(e|ed|ing|ement)?|celebrat(e|ed|ing|ion)?|break(s|ing)?|reward(s|ed|ing)?|progress|focus(ed|ing)?|burnout|group(s)?|victor(y|ies)?|success|believe(s|d|ing)?)/gi, '<span style="color:#d4af37;font-weight:600;">$1</span>');
                content.innerHTML = formatted;
                content.style.lineHeight = '1.7';
                content.style.letterSpacing = '0.01em';
                content.style.margin = '4px 0';
            }
        } catch (error) {
            content.textContent = text || "Error displaying message";
        }
        msgDiv.appendChild(content);
        if (!isUser) {
            msgDiv.insertBefore(avatar, msgDiv.firstChild);
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
            voice = voices.find(v => v.name.includes("Female") || v.name.includes("female") || v.name.includes("Samantha") || v.name.includes("Zira"));
            if (!voice) {
                voice = voices[0];
            }
        } else {
            voice = voices.find(v => v.name.includes("Male") || v.name.includes("male") || v.name.includes("David") || v.name.includes("Mark"));
            if (!voice) {
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

    // Remove duplicate volume icon from chat header (if present)
    document.addEventListener("DOMContentLoaded", function () {
        const chatHeader = document.querySelector('.chat-header');
        if (chatHeader) {
            const icons = chatHeader.querySelectorAll('button i.fas.fa-volume-up, button i.fas.fa-volume-mute');
            if (icons.length > 1) {
                icons[1].parentElement.remove();
            }
        }
    });

    function getModePromptPrefix(mode) {
        switch (mode) {
            case "wellness":
                return "You're a friendly mental health assistant. Focus on empathy, self-care tips, mindfulness, and motivation.";
            case "study":
                return "You're an academic assistant. Help with study tips, concepts, and assignments.";
            case "motivation":
                return "You're a motivational coach. Give encouragement, productivity hacks, and support.";
            default:
                return "You're a helpful assistant.";
        }
    }

    function sendMessage() {
        const message = userInput.value.trim();
        const selectedMode = modeSelector.value;
        const promptPrefix = getModePromptPrefix(selectedMode);
        if (!message) return;
        appendMessage(message, true);
        userInput.value = "";
        thinkingDiv.innerText = "Veronica is thinking...";

        // Send the prompt prefix and user message to the backend
        fetch(apiEndpoint, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ message: `${promptPrefix}\nUser: ${message}` })
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

    // Only speak visible text (strip HTML tags and hidden formatting)
    async function speak(text) {
        if (!voiceEnabled || isMuted) return;
        // Remove all HTML tags and decode entities
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = text;
        const cleanText = tempDiv.textContent || tempDiv.innerText || "";
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
</script>

<?php include 'footer.php'; ?>