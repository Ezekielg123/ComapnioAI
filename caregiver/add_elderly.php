<?php
require_once 'header.php';
$msg = '';
$msg_type = 'success';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $blood = $_POST['blood_type'];
    $relation = trim($_POST['relation']);
    $notes = trim($_POST['notes']);

    if ($first_name && $last_name) {
        try {
            $stmt = $pdo->prepare("INSERT INTO patients (caregiver_id, first_name, last_name, date_of_birth, gender, blood_type, relation, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$caregiver_id, $first_name, $last_name, $dob, $gender, $blood, $relation, $notes])) {
                header("Location: dashboard.php");
                exit;
            } else {
                $msg_type = 'error';
                $msg = "An error occurred while adding the patient.";
            }
        } catch (PDOException $e) {
            $msg_type = 'error';
            $msg = "Database Error: unable to save profile.";
        }
    } else {
        $msg_type = 'error';
        $msg = "First Name and Last Name are required.";
    }
}
?>

<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <a href="dashboard.php"
            class="inline-flex items-center text-sm text-slate-400 hover:text-indigo-400 mb-4 transition-colors"><i
                data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Back to Dashboard</a>
        <h1 class="text-3xl font-bold text-white mb-2 flex items-center gap-3">
            <div class="bg-indigo-500/20 p-2 rounded-lg border border-indigo-500/30"><i data-lucide="user-plus"
                    class="text-indigo-400 w-6 h-6"></i></div>
            Add Patient Profile
        </h1>
        <p class="text-slate-400 text-sm">Create a master profile to manage their care journey.</p>
    </div>

    <?php if ($msg): ?>
        <div
            class="mb-6 p-4 rounded-xl border flex items-start gap-3 bg-<?= $msg_type == 'error' ? 'rose' : 'emerald' ?>-500/10 border-<?= $msg_type == 'error' ? 'rose' : 'emerald' ?>-500/30 text-<?= $msg_type == 'error' ? 'rose' : 'emerald' ?>-400">
            <i data-lucide="<?= $msg_type == 'error' ? 'alert-circle' : 'check-circle' ?>"
                class="w-5 h-5 shrink-0 mt-0.5"></i>
            <span class="text-sm font-medium">
                <?= htmlspecialchars($msg) ?>
            </span>
        </div>
    <?php endif; ?>

    <div class="glass-panel p-8 rounded-2xl border border-white/5 shadow-2xl relative overflow-hidden">
        <!-- Abstract shape -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/5 rounded-full blur-[80px] -z-10"></div>

        <form method="POST" class="space-y-6">
            <h3 class="text-lg font-semibold text-white border-b border-white/10 pb-2 mb-4">Basic Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">First Name <span
                            class="text-rose-500">*</span></label>
                    <input type="text" name="first_name" required
                        class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-all shadow-inner">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Last Name <span
                            class="text-rose-500">*</span></label>
                    <input type="text" name="last_name" required
                        class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-all shadow-inner">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Date of Birth</label>
                    <input type="date" name="dob"
                        class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-all shadow-inner [color-scheme:dark]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Gender</label>
                    <select name="gender"
                        class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-all shadow-inner appearance-none cursor-pointer">
                        <option value="">Select...</option>
                        <option>Male</option>
                        <option>Female</option>
                        <option>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Blood Type</label>
                    <select name="blood_type"
                        class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-all shadow-inner appearance-none cursor-pointer">
                        <option value="">Select...</option>
                        <option>A+</option>
                        <option>A-</option>
                        <option>B+</option>
                        <option>B-</option>
                        <option>O+</option>
                        <option>O-</option>
                        <option>AB+</option>
                        <option>AB-</option>
                    </select>
                </div>
            </div>

            <h3 class="text-lg font-semibold text-white border-b border-white/10 pb-2 mb-4 mt-8">Relationship & Notes
            </h3>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Relationship to Patient</label>
                <input type="text" name="relation" placeholder="e.g. Son, Daughter, Nurse"
                    class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-all shadow-inner">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Additional Medical Notes or Needs</label>
                <textarea name="notes" rows="4"
                    class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-all shadow-inner resize-none"></textarea>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-500 text-white px-8 py-3 rounded-xl text-sm font-bold transition-all shadow-[0_4px_20px_rgba(99,102,241,0.3)] hover:shadow-[0_4px_25px_rgba(99,102,241,0.5)] hover:-translate-y-0.5 flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i> Create Profile
                </button>
            </div>
        </form>
    </div>
</div>
<?php require_once 'footer.php'; ?>