<?php
require_once 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $patient_id = $_POST['patient_id'];
    $condition = trim($_POST['condition_name']);
    $date = trim($_POST['diagnosis_date']);
    $notes = trim($_POST['notes']);

    if ($patient_id && $condition) {
        $stmt = $pdo->prepare("INSERT INTO medical_history (patient_id, condition_name, diagnosis_date, notes) VALUES (?, ?, ?, ?)");
        $stmt->execute([$patient_id, $condition, $date ?: null, $notes]);
        header("Location: medical_history.php?patient_id=" . $patient_id);
        exit;
    }
}

$sel_pat = $_GET['patient_id'] ?? (count($my_patients) > 0 ? $my_patients[0]['id'] : null);
?>
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-bold text-white mb-2 flex items-center gap-3">
            <div class="bg-indigo-500/20 p-2 rounded-lg border border-indigo-500/30"><i data-lucide="file-text"
                    class="text-indigo-400 w-6 h-6"></i></div>
            Medical History
        </h1>
        <p class="text-slate-400 text-sm">Track chronic conditions, past surgeries, and diagnostic notes.</p>
    </div>

    <?php if (count($my_patients) > 0): ?>
        <div class="flex items-center gap-3 glass-panel px-4 py-2 rounded-xl">
            <span class="text-slate-400 text-sm font-medium">Viewing Patient:</span>
            <form method="GET" class="inline-block" id="patientForm">
                <select name="patient_id" onchange="document.getElementById('patientForm').submit()"
                    class="bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-white text-sm font-medium focus:outline-none focus:border-indigo-500 cursor-pointer">
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
        class="glass-panel p-12 rounded-3xl text-center border-dashed border-2 border-slate-700/50 max-w-2xl mx-auto mt-10">
        <div
            class="w-16 h-16 bg-slate-800/50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-700">
            <i data-lucide="user-x" class="text-slate-500 w-8 h-8"></i>
        </div>
        <h3 class="text-white font-bold text-lg mb-2">No patients available</h3>
        <p class="text-slate-400 mb-6 font-sm">Please add a patient profile first to manage their medical history.</p>
        <a href="add_elderly.php"
            class="bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-colors">Add
            Patient</a>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- List Column -->
        <div class="lg:col-span-2 space-y-4">
            <?php
            $hist_stmt = $pdo->prepare("SELECT * FROM medical_history WHERE patient_id = ? ORDER BY diagnosis_date DESC, created_at DESC");
            $hist_stmt->execute([$sel_pat]);
            $history = $hist_stmt->fetchAll();

            if (count($history) == 0):
                ?>
                <div class="glass-panel p-12 rounded-2xl text-center border-dashed border-2 border-slate-700/50">
                    <div class="w-12 h-12 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i data-lucide="clipboard-list" class="text-slate-500 w-6 h-6"></i>
                    </div>
                    <p class="text-slate-400">No medical history logged for this patient yet.</p>
                </div>
            <?php else:
                foreach ($history as $h): ?>
                    <div
                        class="glass-panel p-6 rounded-2xl border-l-4 border-l-indigo-500 hover:border-l-indigo-400 transition-all hover:shadow-lg relative overflow-hidden group">
                        <div
                            class="absolute right-0 top-0 w-24 h-24 bg-white/5 rounded-full blur-2xl -mr-10 -mt-10 group-hover:bg-indigo-500/10 transition-all">
                        </div>
                        <h3 class="text-white font-bold text-lg mb-1 relative z-10">
                            <?= htmlspecialchars($h['condition_name']) ?>
                        </h3>
                        <div class="flex items-center gap-1.5 text-xs text-indigo-300 font-medium mb-3 relative z-10">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            Diagnosed:
                            <?= $h['diagnosis_date'] ? date('F j, Y', strtotime($h['diagnosis_date'])) : 'Date Unknown' ?>
                        </div>
                        <?php if ($h['notes']): ?>
                            <p
                                class="text-slate-400 text-sm leading-relaxed whitespace-pre-line relative z-10 bg-slate-900/50 p-3 rounded-lg border border-white/5">
                                <?= htmlspecialchars($h['notes']) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; endif; ?>
        </div>

        <!-- Add Form Column -->
        <div class="lg:col-span-1">
            <div class="glass-panel p-6 rounded-2xl sticky top-8 border border-slate-700 shadow-xl">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-white/10">
                    <div class="bg-indigo-500/20 p-1.5 rounded text-indigo-400"><i data-lucide="plus" class="w-4 h-4"></i>
                    </div>
                    <h3 class="text-white font-bold">Log New Condition</h3>
                </div>
                <form method="POST" class="space-y-5">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="patient_id" value="<?= htmlspecialchars($sel_pat) ?>">

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Condition
                            Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="condition_name" required placeholder="e.g. Hypertension, Diabetes Type 2"
                            class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2.5 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all shadow-inner">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Diagnosis
                            Date</label>
                        <input type="date" name="diagnosis_date"
                            class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2.5 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all shadow-inner [color-scheme:dark]">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Physician
                            Notes / History</label>
                        <textarea name="notes" rows="4"
                            placeholder="Add any specific details, previous surgeries, or observations..."
                            class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2.5 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-all shadow-inner resize-none"></textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2.5 rounded-lg text-sm font-bold transition-colors mt-2 shadow-[0_4px_15px_rgba(99,102,241,0.25)] flex items-center justify-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> Save Record
                    </button>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php require_once 'footer.php'; ?>