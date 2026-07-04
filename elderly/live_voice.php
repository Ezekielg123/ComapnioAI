<?php require_once 'header.php'; ?>
<div class="max-w-5xl mx-auto flex flex-col h-[75vh] md:h-[80vh] bg-slate-900/80 border border-white/20 rounded-[3rem] overflow-hidden shadow-2xl relative backdrop-blur-2xl"
    id="voice-container">

    <!-- Header -->
    <div
        class="bg-slate-950/90 p-6 md:p-8 border-b-2 border-white/10 flex items-center justify-between z-10 relative shadow-lg">
        <a href="dashboard.php"
            class="bg-slate-800 hover:bg-slate-700 active:scale-95 text-white p-4 rounded-2xl transition-all shadow-md">
            <i data-lucide="arrow-left" class="w-8 h-8"></i>
        </a>
        <div class="flex flex-col items-center justify-center">
            <h2 class="text-3xl font-black text-white tracking-wide flex items-center gap-3">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-[0_0_20px_rgba(99,102,241,0.6)]">
                    <i data-lucide="mic" class="text-white w-6 h-6"></i>
                </div>
                Live Voice
            </h2>
            <div id="status-indicator"
                class="text-slate-400 text-sm font-bold flex items-center gap-2 justify-center mt-2">
                <span class="w-2.5 h-2.5 rounded-full bg-slate-500"></span> Tap Mic below to Start
            </div>
        </div>
        <div class="w-[64px]"></div>
    </div>

    <!-- Scrollable Chat Arena -->
    <div id="chat-history" class="flex-1 overflow-y-auto p-6 md:p-12 z-10 pb-48 custom-scrollbar">
        <!-- Initial Greeting -->
        <div class="flex gap-4 md:gap-6 max-w-[95%] md:max-w-[75%] mb-8">
            <div
                class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-indigo-500 to-cyan-500 rounded-3xl flex items-center justify-center shrink-0 shadow-lg border border-indigo-400/50">
                <i data-lucide="brain" class="text-white w-6 h-6 md:w-8 md:h-8"></i>
            </div>
            <div
                class="bg-slate-800 p-6 md:p-8 rounded-[2.5rem] rounded-tl-xl border border-indigo-500/30 shadow-[0_0_20px_rgba(99,102,241,0.1)] relative backdrop-blur-md">
                <p class="text-2xl md:text-3xl text-white font-medium leading-relaxed tracking-wide shadow-black">
                    Hello <span class="font-bold text-indigo-300">
                        <?= htmlspecialchars($elderly_name) ?>
                    </span>! I am connected and ready. Tap the microphone button at the bottom whenever you want to talk
                    to me.
                </p>
            </div>
        </div>
    </div>

    <!-- Input Trigger Bottom -->
    <div
        class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-slate-950 via-slate-900/90 to-transparent p-12 flex justify-center z-20 pointer-events-none">

        <button id="mic-btn"
            class="w-28 h-28 md:w-36 md:h-36 bg-slate-700 hover:bg-slate-600 rounded-full flex items-center justify-center text-white shadow-xl transform hover:scale-105 active:scale-95 transition-all text-center border-4 border-slate-500 relative overflow-visible group cursor-pointer pointer-events-auto">
            <!-- Ripple Effect shown actively during listening -->
            <div id="mic-ripple"
                class="absolute inset-0 bg-indigo-400/50 rounded-full hidden duration-1000 scale-150 transform transition-all pointer-events-none">
            </div>

            <div class="flex flex-col items-center">
                <i data-lucide="mic" class="w-12 h-12 md:w-16 md:h-16 relative z-10 mb-1"></i>
                <span id="mic-text" class="text-base md:text-xl font-bold uppercase tracking-widest relative z-10">TAP
                    TO<br>TALK</span>
            </div>
        </button>

    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 12px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(15, 23, 42, 0.5);
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(99, 102, 241, 0.5);
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(99, 102, 241, 0.8);
    }
</style>

<!-- Voice Implementation Logic -->
<script src="voice.js?t=<?= time() ?>"></script>

<!-- Keep SOS Modal outside -->
<div id="sosModal"
    class="hidden fixed inset-0 bg-slate-950/95 backdrop-blur-xl z-[100] flex items-center justify-center p-4">
    <div
        class="bg-gradient-to-b from-rose-600 to-rose-800 rounded-[3rem] p-8 md:p-20 max-w-2xl w-full text-center shadow-[0_0_100px_rgba(244,63,94,0.5)] relative border-[8px] border-rose-400 overflow-hidden transform transition-all">
        <div class="absolute inset-0 bg-white/10 animate-pulse pointer-events-none"></div>
        <div
            class="w-32 h-32 bg-white rounded-full flex items-center justify-center mx-auto mb-8 shadow-2xl relative z-10">
            <i data-lucide="alert-triangle" class="w-20 h-20 text-rose-600 animate-[bounce_1s_infinite]"></i>
        </div>
        <h2 class="text-5xl md:text-7xl font-black text-white mb-6 uppercase tracking-wider relative z-10">Help
            is<br />Coming</h2>
        <p class="text-rose-100 text-2xl md:text-3xl mb-12 font-semibold relative z-10">We are alerting your family and
            caregivers right now.</p>

        <div class="flex justify-center relative z-10">
            <button onclick="
                 fetch('../api/trigger_state.php?action=sos_off').then(() => {
                     document.getElementById('sosModal').classList.add('hidden');
                 });
                 "
                class="bg-black/40 hover:bg-black/60 border-4 border-rose-400 text-white px-12 py-6 rounded-full text-2xl font-bold transition-colors w-full shadow-xl">
                I am okay! Cancel Alert
            </button>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>