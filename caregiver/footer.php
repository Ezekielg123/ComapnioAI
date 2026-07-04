</div>
</div>
</main>

<!-- Global Caregiver Siren Alarm UI -->
<div id="caregiver-siren"
    class="fixed inset-0 bg-rose-950/95 z-[999] hidden flex-col items-center justify-center p-6 text-center backdrop-blur-3xl animate-in fade-in">
    <div class="absolute inset-0 bg-rose-600/10 animate-[pulse_0.4s_infinite] pointer-events-none"></div>
    <div
        class="w-48 h-48 bg-rose-600 rounded-full flex items-center justify-center shadow-2xl relative z-10 border-8 border-rose-400 animate-[pulse_0.8s_infinite] shadow-[0_0_80px_rgba(225,29,72,0.8)]">
        <i data-lucide="alert-triangle" class="w-24 h-24 text-white"></i>
    </div>
    <h2 class="text-6xl md:text-8xl font-black text-white mt-10 mb-4 uppercase tracking-widest relative z-10">EMERGENCY SOS</h2>
    <p id="caregiver-siren-text" class="text-rose-200 text-3xl font-bold px-4 relative z-10"></p>
    <button onclick="dismissSiren()" class="mt-12 relative z-10 bg-black/60 hover:bg-black/80 border-4 border-rose-500 text-rose-300 px-12 py-4 rounded-full text-2xl font-bold transition-all shadow-xl active:scale-95">RESOLVE EMERGENCY</button>
</div>

<script>
    lucide.createIcons();

    let sirenMuted = false;
    // Generic non-copyright medical rhythm
    let sirenAudio = new Audio('https://assets.mixkit.co/active_storage/sfx/1007/1007-preview.mp3');
    sirenAudio.loop = true;

    function dismissSiren() {
        sirenMuted = true;
        document.getElementById('caregiver-siren').classList.add('hidden');
        document.getElementById('caregiver-siren').classList.remove('flex');
        sirenAudio.pause();
        
        // Execute master remote resolution across all related databases for Caregiver pipeline
        fetch('../api/caregiver_alert.php?action=resolve', { method: 'POST' });
    }

    function checkCaregiverAlerts() {
        fetch('../api/caregiver_alert.php')
            .then(r => r.json())
            .then(data => {
                if (data.active && data.patients.length > 0 && !sirenMuted) {
                    document.getElementById('caregiver-siren').classList.remove('hidden');
                    document.getElementById('caregiver-siren').classList.add('flex');

                    const names = data.patients.map(p => p.first_name + ' ' + p.last_name).join(' & ');
                    document.getElementById('caregiver-siren-text').innerText = names + " triggered their SOS Button!";

                    sirenAudio.play().catch(e => { console.log('Autoplay blocked'); });
                } else if (!data.active) {
                    sirenMuted = false;
                    document.getElementById('caregiver-siren').classList.add('hidden');
                    document.getElementById('caregiver-siren').classList.remove('flex');
                    sirenAudio.pause();
                }
            }).catch(err => console.log('Polling failure.'));
    }

    // Globally poll DB every 3 seconds for emergencies across all Caregiver pages
    setInterval(checkCaregiverAlerts, 3000);
    checkCaregiverAlerts();
</script>
</body>

</html>