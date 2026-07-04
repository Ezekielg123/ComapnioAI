<?php
require_once 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $patient_id = $_POST['patient_id'];
    $med_name = trim($_POST['medicine_name']);
    $dosage = trim($_POST['dosage']);
    $frequency = trim($_POST['frequency']);
    $times = implode(', ', $_POST['times_of_day'] ?? []);
    $start = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
    $end = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
    $notes = trim($_POST['notes']);

    if ($patient_id && $med_name && $dosage && $frequency && $times) {
        $stmt = $pdo->prepare("INSERT INTO medicine_schedule (patient_id, medicine_name, dosage, frequency, times_of_day, start_date, end_date, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$patient_id, $med_name, $dosage, $frequency, $times, $start, $end, $notes]);
        header("Location: medicine_schedule.php?patient_id=" . $patient_id);
        exit;
    }
}

$sel_pat = $_GET['patient_id'] ?? (count($my_patients) > 0 ? $my_patients[0]['id'] : null);
?>
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-bold text-white mb-2 flex items-center gap-3">
            <div class="bg-purple-500/20 p-2 rounded-lg border border-purple-500/30"><i data-lucide="pill"
                    class="text-purple-400 w-6 h-6"></i></div>
            Medicine Schedule
        </h1>
        <p class="text-slate-400 text-sm">Manage active prescriptions and timing for AI voice alerts.</p>
    </div>

    <?php if (count($my_patients) > 0): ?>
        <div class="flex items-center gap-3 glass-panel px-4 py-2 rounded-xl border-purple-500/20">
            <span class="text-slate-400 text-sm font-medium">Viewing Patient:</span>
            <form method="GET" class="inline-block" id="patientForm">
                <select name="patient_id" onchange="document.getElementById('patientForm').submit()"
                    class="bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-white text-sm font-medium focus:outline-none focus:border-purple-500 cursor-pointer">
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
        <p class="text-slate-400 mb-6 font-sm">Please add a patient profile first to manage medicines.</p>
        <a href="add_elderly.php"
            class="bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-colors">Add
            Patient</a>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 lg:grid-cols-7 gap-8">
        <!-- List -->
        <div class="lg:col-span-4 space-y-4">
            <?php
            $med_stmt = $pdo->prepare("SELECT * FROM medicine_schedule WHERE patient_id = ? ORDER BY id DESC");
            $med_stmt->execute([$sel_pat]);
            $meds = $med_stmt->fetchAll();

            if (count($meds) == 0):
                ?>
                <div class="glass-panel p-12 rounded-2xl text-center border-dashed border-2 border-slate-700/50">
                    <div class="w-12 h-12 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i data-lucide="pill" class="text-slate-500 w-6 h-6"></i>
                    </div>
                    <p class="text-slate-400">No active medicines logged for this patient.</p>
                </div>
            <?php else:
                foreach ($meds as $m): ?>
                    <div class="glass-panel p-6 rounded-2xl flex flex-col sm:flex-row gap-6 relative overflow-hidden group">
                        <!-- decorative -->
                        <div
                            class="absolute -right-6 -bottom-6 w-32 h-32 bg-purple-500/10 rounded-full blur-2xl group-hover:bg-purple-500/20 transition-all">
                        </div>

                        <div
                            class="w-16 h-16 rounded-2xl bg-purple-500/20 border border-purple-500/30 flex items-center justify-center shrink-0">
                            <i data-lucide="pill" class="text-purple-400 w-8 h-8"></i>
                        </div>

                        <div class="flex-1 relative z-10">
                            <h3 class="text-white font-bold text-xl mb-1 flex items-center gap-3">
                                <?= htmlspecialchars($m['medicine_name']) ?>
                                <span class="text-xs bg-white/10 px-2 py-0.5 rounded-md font-medium text-slate-300">
                                    <?= htmlspecialchars($m['dosage']) ?>
                                </span>
                            </h3>
                            <div class="flex items-center gap-4 text-xs font-medium text-slate-400 mb-3 uppercase tracking-wider">
                                <span class="flex items-center gap-1"><i data-lucide="clock"
                                        class="w-3.5 h-3.5 text-indigo-400"></i>
                                    <?= htmlspecialchars($m['frequency']) ?>
                                </span>
                            </div>

                            <div class="bg-slate-900/50 p-3 rounded-lg border border-white/5 mb-3 inline-block">
                                <span class="text-xs font-semibold text-slate-500 uppercase block mb-1">Timing Alerts</span>
                                <div class="flex flex-wrap gap-2">
                                    <?php
                                    $times = explode(', ', $m['times_of_day']);
                                    foreach ($times as $t):
                                        ?>
                                        <span
                                            class="bg-indigo-500/20 text-indigo-300 px-2.5 py-1 rounded-md text-xs font-medium border border-indigo-500/30">
                                            <?= htmlspecialchars($t) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <?php if ($m['notes']): ?>
                                <p class="text-sm text-slate-400 mt-2"><span class="font-medium text-slate-300">Instructions:</span>
                                    <?= htmlspecialchars($m['notes']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
        </div>

        <!-- Add Form -->
        <div class="lg:col-span-3">
            <div
                class="glass-panel p-6 rounded-2xl sticky top-8 border border-purple-500/20 shadow-[0_0_30px_rgba(168,85,247,0.1)]">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-white/10">
                    <div class="bg-purple-500/20 p-1.5 rounded text-purple-400"><i data-lucide="plus" class="w-4 h-4"></i>
                    </div>
                    <h3 class="text-white font-bold">Add Prescription</h3>
                </div>

                <form method="POST" class="space-y-5">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="patient_id" value="<?= htmlspecialchars($sel_pat) ?>">

                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Medicine
                                Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="medicine_name" required placeholder="e.g. Lisinopril"
                                class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2.5 text-white focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Dosage
                                <span class="text-rose-500">*</span></label>
                            <input type="text" name="dosage" required placeholder="e.g. 10mg"
                                class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2.5 text-white focus:outline-none focus:border-purple-500 text-sm">
                        </div>
                        <div>
                            <label
                                class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Frequency
                                <span class="text-rose-500">*</span></label>
                            <select name="frequency" required
                                class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2.5 text-white focus:outline-none focus:border-purple-500 text-sm">
                                <option>Daily</option>
                                <option>Twice a day</option>
                                <option>Weekly</option>
                                <option>As needed</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Timing (AI
                            Alerts) <span class="text-rose-500">*</span></label>
                        <div class="grid grid-cols-2 gap-2 bg-slate-900/50 p-3 rounded-lg border border-slate-700">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="times_of_day[]" value="Morning (8:00 AM)"
                                    class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500 bg-slate-800 border-slate-600">
                                <span class="text-sm text-slate-300">Morning</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="times_of_day[]" value="Afternoon (1:00 PM)"
                                    class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500 bg-slate-800 border-slate-600">
                                <span class="text-sm text-slate-300">Afternoon</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="times_of_day[]" value="Evening (6:00 PM)"
                                    class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500 bg-slate-800 border-slate-600">
                                <span class="text-sm text-slate-300">Evening</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="times_of_day[]" value="Night (9:00 PM)"
                                    class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500 bg-slate-800 border-slate-600">
                                <span class="text-sm text-slate-300">Night</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Special
                            Instructions</label>
                        <textarea name="notes" rows="2" placeholder="e.g. Take with food..."
                            class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2.5 text-white focus:outline-none focus:border-purple-500 text-sm resize-none"></textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-purple-600 hover:bg-purple-500 text-white px-4 py-3 rounded-lg text-sm font-bold transition-colors mt-2 shadow-[0_4px_15px_rgba(168,85,247,0.25)] flex items-center justify-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> Add Prescription
                    </button>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php require_once 'footer.php'; ?>