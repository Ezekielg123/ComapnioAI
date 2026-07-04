<?php
require_once 'header.php';
$sel_pat = $_GET['patient_id'] ?? (count($my_patients) > 0 ? $my_patients[0]['id'] : null);
?>
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-bold text-white mb-2 flex items-center gap-3">
            <div class="bg-cyan-500/20 p-2 rounded-lg border border-cyan-500/30"><i data-lucide="clipboard-check"
                    class="text-cyan-400 w-8 h-8"></i></div>
            Daily Summary
        </h1>
        <p class="text-slate-400 text-sm md:text-base">Monitor patient compliance, real-time AI conversation streams,
            and overall activity derived securely from the system backend.</p>
    </div>

    <?php if (count($my_patients) > 0): ?>
        <div class="flex items-center gap-3 glass-panel px-4 py-2 rounded-xl">
            <span class="text-slate-400 text-sm font-medium">Viewing Patient:</span>
            <form method="GET" class="inline-block" id="patientForm">
                <select name="patient_id" onchange="document.getElementById('patientForm').submit()"
                    class="bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-white text-sm font-medium focus:outline-none focus:border-cyan-500 cursor-pointer">
                    <?php foreach ($my_patients as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= $p['id'] == $sel_pat ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['first_name'] . ' ' . $p['last_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php if (count($my_patients) == 0): ?>
    <div
        class="glass-panel p-12 rounded-3xl text-center border-dashed border-2 border-slate-700/50 max-w-2xl mx-auto mt-10 shadow-[0_10px_40px_rgba(0,0,0,0.2)]">
        <div
            class="w-20 h-20 bg-slate-800/80 rounded-full flex items-center justify-center mx-auto mb-6 border border-slate-700">
            <i data-lucide="user-x" class="text-slate-500 w-10 h-10"></i>
        </div>
        <h3 class="text-white font-bold text-2xl mb-3">No patients available right now</h3>
        <p class="text-slate-400 mb-8 font-medium">Please add an elderly patient profile first to view their daily
            behavioral and interactive summary.</p>
        <a href="add_elderly.php"
            class="bg-indigo-600 hover:bg-indigo-500 text-white px-8 py-3.5 rounded-xl font-bold transition-all shadow-lg hover:shadow-indigo-500/25">Add
            Patient</a>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
        <!-- Medicine Compliance Panel (Fetched from DB Log) -->
        <div
            class="glass-panel p-8 md:p-10 rounded-[2.5rem] border-2 border-purple-500/20 shadow-2xl relative overflow-hidden group">
            <div class="absolute -right-12 -top-12 w-48 h-48 bg-purple-500/10 rounded-full blur-3xl pointer-events-none">
            </div>
            <h3 class="text-2xl font-bold text-white mb-8 flex items-center gap-3">
                <div class="p-2.5 rounded-xl bg-purple-500/20 border border-purple-500/30">
                    <i data-lucide="pill" class="text-purple-400 w-6 h-6"></i>
                </div>
                Medicines Tracked Today
            </h3>
            <?php
            $logs_stmt = $pdo->prepare("SELECT medicine_name, DATE_FORMAT(taken_at, '%h:%i %p') as time FROM medicine_logs WHERE patient_id = ? AND DATE(taken_at) = CURDATE() ORDER BY taken_at DESC");
            $logs_stmt->execute([$sel_pat]);
            $logs = $logs_stmt->fetchAll();

            if (count($logs) == 0) {
                echo "<div class='text-slate-400 italic text-center p-10 border-2 border-slate-700/50 border-dashed rounded-2xl bg-black/20'>No medications have been acknowledged by the patient today.</div>";
            } else {
                echo '<div class="space-y-4">';
                foreach ($logs as $log) {
                    echo '<div class="flex justify-between items-center bg-black/40 p-5 rounded-2xl border-l-4 border-l-purple-500 shadow-inner group-hover:bg-black/60 transition-colors cursor-default">';
                    echo '  <span class="text-white font-bold text-lg flex items-center gap-3"><i data-lucide="check-circle-2" class="text-emerald-400 w-6 h-6"></i> ' . htmlspecialchars($log['medicine_name']) . '</span>';
                    echo '  <span class="text-slate-300 font-medium bg-white/5 px-3 py-1 rounded-md text-sm border border-white/10">' . $log['time'] . '</span>';
                    echo '</div>';
                }
                echo '</div>';
            }
            ?>
        </div>

        <!-- Companion AI Chat History Stream -->
        <div
            class="glass-panel p-8 md:p-10 rounded-[2.5rem] border-2 border-indigo-500/20 shadow-2xl flex flex-col h-[700px] relative overflow-hidden">
            <div class="absolute -left-12 -bottom-12 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none">
            </div>
            <h3 class="text-2xl font-bold text-white mb-6 flex items-center gap-3 relative z-10">
                <div class="p-2.5 rounded-xl bg-indigo-500/20 border border-indigo-500/30">
                    <i data-lucide="message-square" class="text-indigo-400 w-6 h-6"></i>
                </div>
                Today's AI Transcripts
            </h3>

            <!-- Live Chat Container -->
            <div class="flex-1 overflow-y-auto pr-4 space-y-6 relative z-10 custom-scrollbar">
                <?php
                $chat_stmt = $pdo->prepare("SELECT role, message, DATE_FORMAT(created_at, '%h:%i %p') as time FROM conversation_history WHERE patient_id = ? AND DATE(created_at) = CURDATE() ORDER BY created_at ASC");
                $chat_stmt->execute([$sel_pat]);
                $chats = $chat_stmt->fetchAll();

                if (count($chats) == 0) {
                    echo "<div class='text-slate-400 italic text-center p-10 border-2 border-slate-700/50 border-dashed rounded-2xl bg-black/20'>Patient hasn't conversed with Companio AI yet today.</div>";
                } else {
                    foreach ($chats as $c) {
                        $isAI = $c['role'] === 'model';
                        echo '<div class="p-5 md:p-6 rounded-[2rem] shadow-lg ' . ($isAI ? 'bg-indigo-950/40 border-2 border-indigo-500/20 ml-12 rounded-tr-xl' : 'bg-slate-900 border-2 border-slate-700/50 mr-12 rounded-tl-xl') . '">';
                        echo '  <div class="flex justify-between items-center mb-3">';
                        echo '    <span class="text-xs font-black uppercase tracking-widest flex items-center gap-1 ' . ($isAI ? 'text-indigo-400' : 'text-slate-400') . '">';
                        if ($isAI)
                            echo '<i data-lucide="brain" class="w-4 h-4"></i> Companio AI';
                        else
                            echo '<i data-lucide="user" class="w-4 h-4"></i> Patient';
                        echo '    </span>';
                        echo '    <span class="text-xs font-medium text-slate-500">' . $c['time'] . '</span>';
                        echo '  </div>';
                        echo '  <p class="text-slate-200 leading-relaxed font-medium">' . htmlspecialchars($c['message']) . '</p>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 8px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.1);
        border-radius: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(99, 102, 241, 0.3);
        border-radius: 4px;
    }
</style>

<?php require_once 'footer.php'; ?>