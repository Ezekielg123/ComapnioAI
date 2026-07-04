<?php
require_once 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $patient_id = $_POST['patient_id'];
    $name = trim($_POST['contact_name']);
    $relation = trim($_POST['relationship']);
    $phone = trim($_POST['phone_number']);
    $is_primary = isset($_POST['is_primary']) ? 1 : 0;

    if ($patient_id && $name && $phone) {
        // If this is set as primary, unset others for this patient
        if ($is_primary) {
            $pdo->prepare("UPDATE emergency_contacts SET is_primary = 0 WHERE patient_id = ?")->execute([$patient_id]);
        }

        $stmt = $pdo->prepare("INSERT INTO emergency_contacts (patient_id, contact_name, relationship, phone_number, is_primary) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$patient_id, $name, $relation, $phone, $is_primary]);
        header("Location: emergency_contacts.php?patient_id=" . $patient_id);
        exit;
    }
}

$sel_pat = $_GET['patient_id'] ?? (count($my_patients) > 0 ? $my_patients[0]['id'] : null);
?>
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-bold text-white mb-2 flex items-center gap-3">
            <div class="bg-rose-500/20 p-2 rounded-lg border border-rose-500/30"><i data-lucide="phone-call"
                    class="text-rose-400 w-6 h-6"></i></div>
            Emergency Contacts
        </h1>
        <p class="text-slate-400 text-sm">Critical contacts that the AI will alert in case of SOS.</p>
    </div>

    <?php if (count($my_patients) > 0): ?>
        <div class="flex items-center gap-3 glass-panel px-4 py-2 rounded-xl border-rose-500/20">
            <span class="text-slate-400 text-sm font-medium">Viewing Patient:</span>
            <form method="GET" class="inline-block" id="patientForm">
                <select name="patient_id" onchange="document.getElementById('patientForm').submit()"
                    class="bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-white text-sm font-medium focus:outline-none focus:border-rose-500 cursor-pointer">
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
        <p class="text-slate-400 mb-6 font-sm">Please add a patient profile first to manage their contacts.</p>
        <a href="add_elderly.php"
            class="bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-colors">Add
            Patient</a>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 lg:grid-cols-7 gap-8">
        <!-- List -->
        <div class="lg:col-span-4 space-y-4">
            <?php
            $con_stmt = $pdo->prepare("SELECT * FROM emergency_contacts WHERE patient_id = ? ORDER BY is_primary DESC, id ASC");
            $con_stmt->execute([$sel_pat]);
            $contacts = $con_stmt->fetchAll();

            if (count($contacts) == 0):
                ?>
                <div class="glass-panel p-12 rounded-2xl text-center border-dashed border-2 border-slate-700/50 bg-rose-500/5">
                    <div class="w-12 h-12 bg-rose-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i data-lucide="alert-triangle" class="text-rose-400 w-6 h-6"></i>
                    </div>
                    <p class="text-slate-300 font-medium">Critical Warning: No emergency contacts added.</p>
                    <p class="text-slate-400 text-sm mt-1">AI SOS feature requires at least one primary contact.</p>
                </div>
            <?php else:
                foreach ($contacts as $c): ?>
                    <div
                        class="glass-panel p-5 rounded-2xl flex items-center justify-between border-l-4 <?= $c['is_primary'] ? 'border-l-rose-500 bg-rose-500/5' : 'border-l-slate-600' ?>">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-full border border-slate-700 flex items-center justify-center bg-slate-800 text-slate-300 font-bold uppercase relative">
                                <?= substr($c['contact_name'], 0, 1) ?>
                                <?php if ($c['is_primary']): ?>
                                    <div class="absolute -top-1 -right-1 w-4 h-4 bg-rose-500 rounded-full border-2 border-slate-900 flex items-center justify-center"
                                        title="Primary Contact">
                                        <i data-lucide="star" class="w-2.5 h-2.5 text-white fill-white"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h3 class="text-white font-bold text-lg leading-tight flex items-center gap-2">
                                    <?= htmlspecialchars($c['contact_name']) ?>
                                </h3>
                                <p class="text-sm text-slate-400">
                                    <?= htmlspecialchars($c['relationship']) ?>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="tel:<?= htmlspecialchars($c['phone_number']) ?>"
                                class="bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 border border-rose-500/30">
                                <i data-lucide="phone" class="w-4 h-4"></i> <span class="hidden sm:inline">
                                    <?= htmlspecialchars($c['phone_number']) ?>
                                </span>
                            </a>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
        </div>

        <!-- Add Form -->
        <div class="lg:col-span-3">
            <div
                class="glass-panel p-6 rounded-2xl sticky top-8 border border-rose-500/20 shadow-[0_0_30px_rgba(244,63,94,0.1)]">
                <div class="flex items-center gap-2 mb-6 pb-4 border-b border-white/10">
                    <div class="bg-rose-500/20 p-1.5 rounded text-rose-400"><i data-lucide="plus" class="w-4 h-4"></i></div>
                    <h3 class="text-white font-bold">Add Contact</h3>
                </div>

                <form method="POST" class="space-y-4">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="patient_id" value="<?= htmlspecialchars($sel_pat) ?>">

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Contact
                            Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="contact_name" required placeholder="John Doe"
                            class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-rose-500 text-sm">
                    </div>

                    <div>
                        <label
                            class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Relationship
                            <span class="text-rose-500">*</span></label>
                        <input type="text" name="relationship" required placeholder="e.g. Son, Daughter, Neighbor"
                            class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-rose-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Phone
                            Number <span class="text-rose-500">*</span></label>
                        <input type="tel" name="phone_number" required placeholder="+1 (555) 000-0000"
                            class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-rose-500 text-sm">
                    </div>

                    <div class="pt-2">
                        <label
                            class="flex items-center gap-3 cursor-pointer p-3 rounded-lg border border-slate-700 bg-slate-900/50 hover:bg-slate-800 transition-colors">
                            <input type="checkbox" name="is_primary" value="1"
                                class="w-4 h-4 rounded text-rose-600 focus:ring-rose-500 bg-slate-900 border-slate-600">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-white">Set as Primary SOS Contact</span>
                                <span class="text-xs text-slate-400">AI will dial this number first during
                                    emergencies.</span>
                            </div>
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full bg-rose-600 hover:bg-rose-500 text-white px-4 py-3 rounded-lg text-sm font-bold transition-colors mt-4 shadow-[0_4px_15px_rgba(244,63,94,0.25)] flex items-center justify-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> Save Contact
                    </button>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php require_once 'footer.php'; ?>