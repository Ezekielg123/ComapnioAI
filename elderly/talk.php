<?php require_once 'header.php'; ?>
<div
    class="max-w-5xl mx-auto flex flex-col h-[75vh] md:h-[80vh] bg-slate-900/80 border border-white/20 rounded-[3rem] overflow-hidden shadow-2xl relative backdrop-blur-2xl">

    <!-- Header -->
    <div
        class="bg-slate-950/90 p-6 md:p-8 border-b-2 border-white/10 flex items-center justify-between z-10 relative shadow-lg">
        <a href="dashboard.php"
            class="bg-slate-800 hover:bg-slate-700 active:scale-95 text-white p-4 rounded-2xl transition-all shadow-md">
            <i data-lucide="arrow-left" class="w-8 h-8"></i>
        </a>
        <div class="flex items-center gap-5 text-center">
            <div
                class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-cyan-500 rounded-[1.5rem] flex items-center justify-center shadow-[0_0_20px_rgba(99,102,241,0.6)]">
                <i data-lucide="mic" class="text-white w-8 h-8"></i>
            </div>
            <div>
                <h2 class="text-3xl font-black text-white tracking-wide">Companio Voice</h2>
                <span class="text-emerald-400 text-sm font-bold flex items-center gap-2 justify-center mt-1">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span> Listening to you
                </span>
            </div>
        </div>
        <!-- Placeholder setting button -->
        <div class="w-[64px]"></div>
    </div>

    <!-- Chat Area -->
    <div class="flex-1 overflow-y-auto p-6 md:p-12 space-y-12 z-10 pb-48 custom-scrollbar">
        <!-- System Alert / AI First Prompt -->
        <div class="flex gap-6 max-w-[90%] md:max-w-[75%]">
            <div
                class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-cyan-500 rounded-3xl flex items-center justify-center shrink-0 shadow-lg border border-indigo-400/50">
                <i data-lucide="brain" class="text-white w-8 h-8"></i>
            </div>
            <div
                class="bg-slate-800 p-8 rounded-[2.5rem] rounded-tl-xl border border-slate-700 shadow-xl relative backdrop-blur-md">
                <p class="text-2xl md:text-3xl text-white font-medium leading-relaxed tracking-wide shadow-black">
                    Hello <span class="font-bold text-indigo-300">
                        <?= htmlspecialchars($elderly_name) ?>
                    </span>! How are you feeling today? Remember, it's almost time for your morning medicine.
                </p>
                <div class="absolute bottom-4 right-6 text-sm text-slate-500 font-bold">10:00 AM</div>
            </div>
        </div>

        <!-- User Prompt Simulation -->
        <div class="flex gap-6 max-w-[90%] md:max-w-[75%] ml-auto justify-end">
            <div
                class="bg-cyan-900/60 p-8 rounded-[2.5rem] rounded-tr-xl border border-cyan-500/50 shadow-xl relative backdrop-blur-md">
                <p class="text-2xl md:text-3xl text-white font-medium leading-relaxed tracking-wide shadow-black">
                    I am feeling good today. Should I take my medicine with food?
                </p>
                <div class="absolute bottom-4 right-6 text-sm text-cyan-300/50 font-bold">10:02 AM</div>
            </div>
            <div
                class="w-16 h-16 bg-slate-700 rounded-3xl flex items-center justify-center shrink-0 border border-slate-600 shadow-lg">
                <i data-lucide="user" class="text-slate-300 w-8 h-8"></i>
            </div>
        </div>

        <!-- AI Response Simulation -->
        <div class="flex gap-6 max-w-[90%] md:max-w-[75%]">
            <div
                class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-cyan-500 rounded-3xl flex items-center justify-center shrink-0 shadow-lg border border-indigo-400/50">
                <i data-lucide="brain" class="text-white w-8 h-8"></i>
            </div>
            <div
                class="bg-slate-800 p-8 rounded-[2.5rem] rounded-tl-xl border border-indigo-500/30 shadow-[0_0_20px_rgba(99,102,241,0.1)] relative backdrop-blur-md">
                <p class="text-2xl md:text-3xl text-white font-medium leading-relaxed tracking-wide shadow-black">
                    Yes, your caregiver noted that you should take Lisinopril <span
                        class="text-emerald-400 font-bold hover:underline decoration-emerald-400 cursor-pointer">after a
                        full meal</span>. Would you like me to tell them you are having breakfast now?
                </p>
                <!-- Voice visualizer simulation -->
                <div class="flex items-center gap-1 mt-6 h-8">
                    <div class="w-2 bg-indigo-400 rounded-full h-4 animate-[bounce_1s_infinite]"></div>
                    <div class="w-2 bg-indigo-400 rounded-full h-8 animate-[bounce_1.2s_infinite]"></div>
                    <div class="w-2 bg-indigo-400 rounded-full h-5 animate-[bounce_0.8s_infinite]"></div>
                    <div class="w-2 bg-indigo-400 rounded-full h-7 animate-[bounce_1.1s_infinite]"></div>
                    <div class="w-2 bg-indigo-400 rounded-full h-3 animate-[bounce_0.9s_infinite]"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Input Trigger Bottom -->
    <div
        class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-slate-950 via-slate-900/90 to-transparent p-12 flex justify-center z-20 pointer-events-none">

        <!-- Giant Mic Button -->
        <button
            class="w-28 h-28 md:w-36 md:h-36 bg-gradient-to-br from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-400 rounded-full flex items-center justify-center text-white shadow-[0_0_50px_rgba(99,102,241,0.6)] transform hover:scale-105 active:scale-95 transition-all text-center border-4 border-indigo-300 relative overflow-visible group cursor-pointer pointer-events-auto">
            <!-- Ripple Effect -->
            <div
                class="absolute inset-0 bg-indigo-400/50 rounded-full animate-ping pointer-events-none duration-1000 scale-150">
            </div>

            <div class="flex flex-col items-center">
                <i data-lucide="mic" class="w-12 h-12 md:w-16 md:h-16 relative z-10 mb-1"></i>
                <span class="text-base md:text-xl font-bold uppercase tracking-widest relative z-10">TAP
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

<?php require_once 'footer.php'; ?>