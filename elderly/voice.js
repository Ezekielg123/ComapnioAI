document.addEventListener('DOMContentLoaded', () => {
    const micBtn = document.getElementById('mic-btn');
    const micText = document.getElementById('mic-text');
    const micRipple = document.getElementById('mic-ripple');
    const statusIndicator = document.getElementById('status-indicator');
    const chatHistory = document.getElementById('chat-history');

    // Initialize Web Speech APIs
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    const synth = window.speechSynthesis;

    if (!SpeechRecognition) {
        alert("Audio services are not supported in this browser. Please use Google Chrome.");
        return;
    }

    const recognition = new SpeechRecognition();
    recognition.continuous = false;
    recognition.interimResults = false;
    recognition.lang = 'en-US';

    let isListening = false;
    let isProcessing = false;

    // UI Helper: Render Elderly User Message
    const appendUserMessage = (text) => {
        const div = document.createElement('div');
        div.className = "flex gap-4 md:gap-6 max-w-[95%] md:max-w-[75%] ml-auto justify-end mb-8";
        div.innerHTML = `
            <div class="bg-cyan-900/60 p-6 md:p-8 rounded-[2.5rem] rounded-tr-xl border border-cyan-500/50 shadow-xl relative backdrop-blur-md text-right">
                <p class="text-2xl md:text-3xl text-white font-medium leading-relaxed tracking-wide shadow-black">${text}</p>
            </div>
            <div class="w-14 h-14 md:w-16 md:h-16 bg-slate-700 rounded-3xl flex items-center justify-center shrink-0 border border-slate-600 shadow-lg">
                <i data-lucide="user" class="text-slate-300 w-6 h-6 md:w-8 md:h-8"></i>
            </div>
        `;
        chatHistory.appendChild(div);
        lucide.createIcons({ root: div });
        chatHistory.scrollTo({ top: chatHistory.scrollHeight, behavior: 'smooth' });
    };

    // UI Helper: Render AI Companion Message
    const appendAIMessage = (text) => {
        const div = document.createElement('div');
        div.className = "flex gap-4 md:gap-6 max-w-[95%] md:max-w-[75%] mb-8";
        div.innerHTML = `
            <div class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-indigo-500 to-cyan-500 rounded-3xl flex items-center justify-center shrink-0 shadow-lg border border-indigo-400/50">
                <i data-lucide="brain" class="text-white w-6 h-6 md:w-8 md:h-8"></i>
            </div>
            <div class="bg-slate-800 p-6 md:p-8 rounded-[2.5rem] rounded-tl-xl border border-indigo-500/30 shadow-[0_0_20px_rgba(99,102,241,0.1)] relative backdrop-blur-md">
                <p class="text-2xl md:text-3xl text-white font-medium leading-relaxed tracking-wide shadow-black">${text}</p>
            </div>
        `;
        chatHistory.appendChild(div);
        lucide.createIcons({ root: div });
        chatHistory.scrollTo({ top: chatHistory.scrollHeight, behavior: 'smooth' });
    };

    // UI Controls
    const setListeningState = (listening) => {
        isListening = listening;
        if (listening) {
            micBtn.className = "w-28 h-28 md:w-36 md:h-36 bg-gradient-to-br from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-400 rounded-full flex items-center justify-center text-white shadow-[0_0_50px_rgba(99,102,241,0.6)] transform hover:scale-105 transition-all text-center border-4 border-indigo-300 relative overflow-visible group cursor-pointer pointer-events-auto";
            micRipple.classList.remove('hidden');
            micRipple.classList.add('animate-ping');
            micText.innerHTML = "LISTENING";
            statusIndicator.innerHTML = `<span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span> Listening to you`;
            statusIndicator.className = "text-emerald-400 text-sm font-bold flex items-center gap-2 justify-center mt-2";
            try { recognition.start(); } catch (e) { }
        } else {
            micBtn.className = "w-28 h-28 md:w-36 md:h-36 bg-slate-700 hover:bg-slate-600 rounded-full flex items-center justify-center text-white shadow-xl transform hover:scale-105 active:scale-95 transition-all text-center border-4 border-slate-500 relative overflow-visible group cursor-pointer pointer-events-auto";
            micRipple.classList.add('hidden');
            micRipple.classList.remove('animate-ping');
            micText.innerHTML = "TAP TO<br>TALK";
            statusIndicator.innerHTML = `<span class="w-2.5 h-2.5 rounded-full bg-slate-500"></span> Tap Mic to Start`;
            statusIndicator.className = "text-slate-400 text-sm font-bold flex items-center gap-2 justify-center mt-2";
            try { recognition.stop(); } catch (e) { }
        }
    };

    const setProcessingState = (processing) => {
        isProcessing = processing;
        if (processing) {
            statusIndicator.innerHTML = `<span class="w-2.5 h-2.5 rounded-full bg-cyan-500 animate-bounce"></span> Thinking...`;
            statusIndicator.className = "text-cyan-400 text-sm font-bold flex items-center gap-2 justify-center mt-2";
            micText.innerHTML = "WAIT";
            micBtn.style.pointerEvents = "none";
            micBtn.classList.replace("bg-slate-700", "bg-slate-800");
        } else {
            micBtn.style.pointerEvents = "auto";
            micBtn.classList.replace("bg-slate-800", "bg-slate-700");
        }
    };

    // Toggle Mic
    micBtn.addEventListener('click', () => {
        synth.cancel(); // Stop talking if currently outputting speech
        if (isProcessing) return;
        setListeningState(!isListening);
    });

    // Recognition Logic
    recognition.onresult = async (event) => {
        const transcript = event.results[0][0].transcript;
        setListeningState(false);

        if (transcript.trim().length > 0) {
            appendUserMessage(transcript);
            setProcessingState(true);

            try {
                // Submit to backend AI Pipeline
                const res = await fetch('../api/chat.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message: transcript })
                });

                const data = await res.json();
                setProcessingState(false);

                if (data.error) {
                    const errMsg = "System Error: " + data.error;
                    appendAIMessage(errMsg);
                    speakText("I am having a system error right now.");
                } else {
                    appendAIMessage(data.response);
                    speakText(data.response);

                    // Trigger UI Screen Overrides Automatically
                    if (data.action === 'sos_on') {
                        const modal = document.getElementById('sosModal');
                        if (modal) modal.classList.remove('hidden');
                    }
                }
            } catch (err) {
                setProcessingState(false);
                const netErr = "I'm having trouble reaching the network right now.";
                appendAIMessage(netErr);
                speakText(netErr);
            }
        }
    };

    recognition.onerror = (event) => {
        console.error("Speech Recognition Error:", event.error);
        setListeningState(false);
        setProcessingState(false);
    };

    // Synthesis Logic (TTS)
    const speakText = (text) => {
        // Strip out asterisks and markdown hash symbols for clean TTS reading
        const cleanText = text.replace(/[*#]/g, '');
        const utterance = new SpeechSynthesisUtterance(cleanText);

        const voices = synth.getVoices();
        // Fallback to warm sounding default english female voice if available
        let selectedVoice = voices.find(v => v.name.includes("Female") || v.name.includes("Google US English") || v.name.includes("Samantha"));
        if (selectedVoice) {
            utterance.voice = selectedVoice;
        }

        // Elderly considerations: Speak slightly slower and clearer
        utterance.rate = 0.95;
        utterance.pitch = 1.0;

        utterance.onstart = () => {
            statusIndicator.innerHTML = `<span class="w-2.5 h-2.5 rounded-full bg-purple-500 animate-pulse"></span> Speaking...`;
            statusIndicator.className = "text-purple-400 text-sm font-bold flex items-center gap-2 justify-center mt-2";
            micBtn.style.pointerEvents = "none";
        };

        utterance.onend = () => {
            statusIndicator.innerHTML = `<span class="w-2.5 h-2.5 rounded-full bg-slate-500"></span> Tap Mic to Reply`;
            statusIndicator.className = "text-slate-400 text-sm font-bold flex items-center gap-2 justify-center mt-2";
            micBtn.style.pointerEvents = "auto";
        };

        synth.speak(utterance);
    };

    if (speechSynthesis.onvoiceschanged !== undefined) {
        speechSynthesis.onvoiceschanged = () => synth.getVoices();
    }
});
