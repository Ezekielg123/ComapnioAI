<?php require_once 'header.php'; ?>

<!-- =============================== -->
<!-- 1. NORMAL MODE CONTAINER -->
<!-- =============================== -->
<div id="normal-mode" class="transition-opacity duration-700">
    <div class="mb-10 hidden md:block">
        <button onclick="triggerSOS(true)"
            class="w-full bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 hover:to-rose-400 text-white p-8 rounded-3xl font-black text-4xl transition-transform active:scale-95 shadow-[0_20px_50px_rgba(244,63,94,0.3)] flex items-center justify-center gap-6 border-4 border-rose-400 border-opacity-50 hover:border-opacity-100 cursor-pointer">
            <div class="bg-white/20 p-4 rounded-2xl">
                <i data-lucide="bell-ring" class="w-12 h-12 shadow-sm animate-pulse"></i>
            </div>
            PRESS FOR EMERGENCY HELP
        </button>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 md:gap-12">
        <div class="space-y-8 md:space-y-12">
            <!-- AI Companion Card -->
            <a href="live_voice.php"
                class="block glass-panel p-10 md:p-12 rounded-[2.5rem] border-2 border-indigo-500/50 relative overflow-hidden group hover:border-indigo-400 hover:bg-indigo-900/20 transition-all shadow-[0_15px_40px_rgba(99,102,241,0.2)] hover:shadow-[0_20px_50px_rgba(99,102,241,0.3)] cursor-pointer">
                <div
                    class="absolute -right-12 -top-12 w-64 h-64 bg-indigo-500/30 rounded-full blur-3xl group-hover:bg-indigo-400/40 transition-colors">
                </div>
                <div
                    class="flex flex-col md:flex-row gap-8 items-center md:items-start text-center md:text-left mb-6 relative z-10">
                    <div
                        class="w-28 h-28 bg-indigo-500 rounded-[2rem] flex items-center justify-center shrink-0 shadow-2xl border border-indigo-400 text-white">
                        <i data-lucide="mic" class="w-14 h-14 animate-[bounce_3s_infinite]"></i>
                    </div>
                    <div>
                        <h2 class="text-4xl md:text-5xl font-black text-white mb-4 leading-tight">Talk to<br />Companio
                        </h2>
                        <p class="text-indigo-200 text-xl md:text-2xl font-medium">Tap here to chat, ask questions, or
                            just talk.</p>
                    </div>
                </div>
            </a>
            <!-- History Visual -->
            <div class="glass-panel p-8 md:p-10 rounded-[2.5rem] border border-white/10 bg-slate-900/80">
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center gap-3"><i data-lucide="history"
                        class="w-8 h-8 text-indigo-400"></i> Recent Chats</h3>
                <div class="space-y-4">
                    <?php
                    if ($patient_id) {
                        $chat_stmt = $pdo->prepare("SELECT role, message, created_at FROM conversation_history WHERE patient_id = ? ORDER BY created_at DESC LIMIT 3");
                        $chat_stmt->execute([$patient_id]);
                        $chats = $chat_stmt->fetchAll();

                        if (count($chats) == 0) {
                            echo '<div class="text-center text-slate-400 text-lg">No recent conversations.</div>';
                        } else {
                            foreach ($chats as $c) {
                                $isAI = ($c['role'] === 'model' || $c['role'] === 'assistant');
                                $color = $isAI ? 'text-indigo-300' : 'text-cyan-300';
                                $icon = $isAI ? 'brain' : 'user';
                                echo '
                                <div class="bg-black/20 p-4 rounded-2xl border border-white/5 flex gap-4 items-start shadow-inner">
                                    <i data-lucide="' . $icon . '" class="w-6 h-6 ' . $color . ' shrink-0 mt-1"></i>
                                    <p class="text-slate-300 text-lg font-medium leading-relaxed">' . htmlspecialchars($c['message']) . '</p>
                                </div>';
                            }
                        }
                    } else {
                        echo '<div class="text-center text-slate-400 text-lg">Waiting for conversations...</div>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="space-y-8 md:space-y-12">
            <!-- Today's Medicines (Static View) -->
            <div
                class="glass-panel p-8 md:p-10 rounded-[2.5rem] border-2 border-purple-500/30 bg-gradient-to-b from-purple-900/10 to-transparent">
                <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-8 flex items-center gap-4">
                    <div class="p-4 bg-purple-500/20 rounded-2xl border border-purple-500/50 shadow-inner"><i
                            data-lucide="pill" class="w-10 h-10 text-purple-400"></i></div>
                    Today's Medicines
                </h2>
                <?php
                if ($patient_id) {
                    $med_stmt = $pdo->prepare("SELECT * FROM medicine_schedule WHERE patient_id = ?");
                    $med_stmt->execute([$patient_id]);
                    $meds = $med_stmt->fetchAll();
                } else {
                    $meds = [];
                }
                if (count($meds) == 0):
                    echo "<div class='text-slate-300 text-2xl text-center py-10 bg-black/20 rounded-3xl border border-white/5 font-semibold'>You have no medicines scheduled.</div>";
                else:
                    ?>
                    <div class="space-y-6">
                        <?php foreach ($meds as $m): ?>
                            <div
                                class="bg-black/40 p-6 rounded-2xl border-l-8 border-l-purple-500 flex justify-between items-center shadow-lg border border-white/10">
                                <div>
                                    <h3 class="text-3xl font-bold text-white mb-2"><?= htmlspecialchars($m['medicine_name']) ?>
                                    </h3>
                                    <p class="text-purple-300 text-2xl font-bold flex items-center gap-2"><i data-lucide="clock"
                                            class="w-6 h-6"></i> Take at: <span
                                            class="text-white"><?= htmlspecialchars($m['times_of_day']) ?></span></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Upcoming Appointment -->
            <div
                class="glass-panel p-8 md:p-10 rounded-[2.5rem] border-2 border-emerald-500/30 bg-gradient-to-b from-emerald-900/10 to-transparent">
                <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-8 flex items-center gap-4">
                    <div class="p-4 bg-emerald-500/20 rounded-2xl border border-emerald-500/50 shadow-inner"><i
                            data-lucide="calendar" class="w-10 h-10 text-emerald-400"></i></div>
                    Next Appointment
                </h2>
                <?php
                if ($patient_id) {
                    $appt_stmt = $pdo->prepare("SELECT * FROM appointments WHERE patient_id = ? AND appointment_date >= NOW() ORDER BY appointment_date ASC LIMIT 1");
                    $appt_stmt->execute([$patient_id]);
                    $appt = $appt_stmt->fetch();
                } else {
                    $appt = null;
                }
                if (!$appt):
                    echo "<div class='text-slate-300 text-2xl text-center py-10 bg-black/20 rounded-3xl border border-white/5 font-semibold'>You have no upcoming appointments.</div>";
                else: ?>
                    <div
                        class="bg-black/30 p-8 rounded-3xl border border-emerald-500/30 shadow-xl text-center flex flex-col items-center relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent"></div>
                        <div
                            class="w-32 h-32 rounded-3xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex flex-col items-center justify-center font-bold mb-6 shadow-2xl border border-emerald-400 relative z-10">
                            <span
                                class="text-xl uppercase font-extrabold tracking-widest opacity-90"><?= date('F', strtotime($appt['appointment_date'])) ?></span>
                            <span
                                class="text-6xl leading-none mt-1"><?= date('d', strtotime($appt['appointment_date'])) ?></span>
                        </div>
                        <h3 class="text-3xl font-black text-white mb-3 relative z-10">
                            <?= htmlspecialchars($appt['doctor_name']) ?>
                        </h3>
                        <p class="text-emerald-400 text-3xl font-bold mb-6 relative z-10"><i data-lucide="clock"
                                class="inline w-8 h-8 -mt-1"></i> <?= date('h:i A', strtotime($appt['appointment_date'])) ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    function triggerSOS(active) {
        fetch(`../api/trigger_state.php?action=${active ? 'sos_on' : 'sos_off'}`).then(() => {
            if (active) {
                alert("Emergency Alert Activated: We are contacting your family now.");
            }
        });
    }
</script>

<?php require_once 'footer.php'; ?>