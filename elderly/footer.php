</div>
</main>

<!-- Mobile Nav Bar (Bottom) for easy large thumb pressing -->
<nav
    class="md:hidden h-28 bg-slate-950/90 backdrop-blur-xl border-t border-white/10 flex justify-around items-center px-2 pb-safe z-30 fixed bottom-0 w-full shadow-[0_-10px_40px_rgba(0,0,0,0.5)]">
    <a href="dashboard.php" class="flex flex-col items-center gap-1 text-slate-300">
        <div
            class="p-3 <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'bg-indigo-500 text-white rounded-2xl shadow-lg' : 'hover:bg-white/10 rounded-2xl text-slate-400' ?> transition-colors">
            <i data-lucide="home" class="w-8 h-8"></i>
        </div>
        <span class="text-sm font-bold">Home</span>
    </a>

    <button onclick="document.getElementById('sosModal').classList.remove('hidden')"
        class="flex flex-col items-center gap-1 text-rose-500 relative -top-8 hover:scale-105 transition-transform active:scale-95 cursor-pointer">
        <div
            class="p-6 bg-gradient-to-r from-rose-600 to-rose-500 rounded-full shadow-[0_0_30px_rgba(244,63,94,0.6)] border-[6px] border-slate-950 text-white flex items-center justify-center pulse">
            <i data-lucide="bell-ring" class="w-10 h-10 animate-[wiggle_1s_ease-in-out_infinite]"></i>
        </div>
        <span class="text-[14px] font-black tracking-widest text-rose-500 mt-1 uppercase">SOS</span>
    </button>

    <a href="talk.php" class="flex flex-col items-center gap-1 text-slate-300">
        <div
            class="p-3 <?= basename($_SERVER['PHP_SELF']) == 'talk.php' ? 'bg-indigo-500 text-white rounded-2xl shadow-lg' : 'hover:bg-white/10 rounded-2xl text-slate-400' ?> transition-colors">
            <i data-lucide="mic" class="w-8 h-8"></i>
        </div>
        <span class="text-sm font-bold">Talk</span>
    </a>
</nav>

<style>
    @keyframes wiggle {

        0%,
        100% {
            transform: rotate(-5deg);
        }

        50% {
            transform: rotate(5deg);
        }
    }
</style>
<script>
    lucide.createIcons();
</script>
</body>

</html>