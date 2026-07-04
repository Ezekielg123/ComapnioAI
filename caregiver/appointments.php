<?php
require_once 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $patient_id = $_POST['patient_id'];
    $doctor = trim($_POST['doctor_name']);
    $specialty = trim($_POST['specialty']);
    $date = $_POST['appointment_date'];
    $location = trim($_POST['location']);
    $notes = trim($_POST['notes']);
    
    if ($patient_id && $doctor && $date) {
        $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_name, specialty, appointment_date, location, notes) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$patient_id, $doctor, $specialty, $date, $location, $notes]);
        header("Location: appointments.php?patient_id=" . $patient_id);
        exit;
    }
}

$sel_pat = $_GET['patient_id'] ?? (count($my_patients) > 0 ? $my_patients[0]['id'] : null);
?>
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-bold text-white mb-2 flex items-center gap-3">
            <div class="bg-emerald-500/20 p-2 rounded-lg border border-emerald-500/30"><i data-lucide="calendar" class="text-emerald-400 w-6 h-6"></i></div>
            Appointments
        </h1>
        <p class="text-slate-400 text-sm">Schedule and track upcoming medical checkups.</p>
    </div>
    
    <?php if (count($my_patients) > 0): ?>
    <div class="flex items-center gap-3 glass-panel px-4 py-2 rounded-xl border-emerald-500/20">
        <span class="text-slate-400 text-sm font-medium">Viewing Patient:</span>
        <form method="GET" class="inline-block" id="patientForm">
            <select name="patient_id" onchange="document.getElementById('patientForm').submit()" class="bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-white text-sm font-medium focus:outline-none focus:border-emerald-500 cursor-pointer">
                <?php foreach($my_patients as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= $p['id']==$sel_pat?'selected':'' ?>><?= htmlspecialchars($p['first_name'].' '.$p['last_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
    <?php endif; ?>
</div>

<?php if (count($my_patients) == 0): ?>
<div class="glass-panel p-12 rounded-3xl text-center border-dashed border-2 border-slate-700/50 max-w-2xl mx-auto mt-10">
    <div class="w-16 h-16 bg-slate-800/50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-700">
        <i data-lucide="user-x" class="text-slate-500 w-8 h-8"></i>
    </div>
    <h3 class="text-white font-bold text-lg mb-2">No patients available</h3>
    <p class="text-slate-400 mb-6 font-sm">Please add a patient profile first to manage their appointments.</p>
    <a href="add_elderly.php" class="bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-colors">Add Patient</a>
</div>
<?php else: ?>
<div class="grid grid-cols-1 lg:grid-cols-7 gap-8">
    <!-- List -->
    <div class="lg:col-span-4 space-y-4">
        <h3 class="text-lg font-bold text-white mb-4 border-b border-white/10 pb-2">Upcoming & Past Appointments</h3>
        <?php
        $appt_stmt = $pdo->prepare("SELECT * FROM appointments WHERE patient_id = ? ORDER BY appointment_date ASC");
        $appt_stmt->execute([$sel_pat]);
        $appts = $appt_stmt->fetchAll();
        
        if (count($appts) == 0):
        ?>
        <div class="glass-panel p-12 rounded-2xl text-center border-dashed border-2 border-slate-700/50">
            <div class="w-12 h-12 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-3">
                <i data-lucide="calendar-x" class="text-slate-500 w-6 h-6"></i>
            </div>
            <p class="text-slate-400">No appointments scheduled.</p>
        </div>
        <?php else: foreach($appts as $a): 
            $is_past = strtotime($a['appointment_date']) < time();
        ?>
        <div class="glass-panel p-5 rounded-2xl flex gap-5 border-l-4 <?= $is_past ? 'border-l-slate-700 opacity-70' : 'border-l-emerald-500 hover:shadow-lg' ?> transition-all relative">
            <div class="flex flex-col items-center justify-center shrink-0 w-16 h-16 rounded-xl <?= $is_past ? 'bg-slate-800' : 'bg-emerald-500/20 text-emerald-400' ?> border border-white/5">
                <span class="text-xs font-bold uppercase"><?= date('M', strtotime($a['appointment_date'])) ?></span>
                <span class="text-2xl font-black leading-none"><?= date('d', strtotime($a['appointment_date'])) ?></span>
            </div>
            
            <div class="flex-1">
                <div class="flex justify-between items-start">
                    <h3 class="<?= $is_past ? 'text-slate-300' : 'text-white' ?> font-bold text-lg flex items-center gap-2">
                        <?= htmlspecialchars($a['doctor_name']) ?>
                        <?php if($a['specialty']): ?>
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-slate-800 text-slate-300 border border-slate-700"><?= htmlspecialchars($a['specialty']) ?></span>
                        <?php endif; ?>
                    </h3>
                    <span class="text-sm font-semibold <?= $is_past ? 'text-slate-500' : 'text-emerald-400' ?>">
                        <?= date('h:i A', strtotime($a['appointment_date'])) ?>
                    </span>
                </div>
                
                <?php if($a['location']): ?>
                    <p class="text-sm text-slate-400 mt-2 flex items-center gap-1.5"><i data-lucide="map-pin" class="w-3.5 h-3.5"></i> <?= htmlspecialchars($a['location']) ?></p>
                <?php endif; ?>
                
                <?php if($a['notes']): ?>
                    <p class="text-sm text-slate-400 mt-1 flex items-start gap-1.5"><i data-lucide="info" class="w-3.5 h-3.5 shrink-0 mt-0.5"></i> <?= htmlspecialchars($a['notes']) ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; endif; ?>
    </div>
    
    <!-- Add Form -->
    <div class="lg:col-span-3">
        <div class="glass-panel p-6 rounded-2xl sticky top-8 border border-emerald-500/20 shadow-[0_0_30px_rgba(16,185,129,0.1)]">
            <div class="flex items-center gap-2 mb-6 pb-4 border-b border-white/10">
                <div class="bg-emerald-500/20 p-1.5 rounded text-emerald-400"><i data-lucide="plus" class="w-4 h-4"></i></div>
                <h3 class="text-white font-bold">New Appointment</h3>
            </div>
            
            <form method="POST" class="space-y-4">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="patient_id" value="<?= htmlspecialchars($sel_pat) ?>">
                
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Doctor / Clinic Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="doctor_name" required placeholder="Dr. Smith or City Hospital" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-emerald-500 text-sm">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Specialty</label>
                        <input type="text" name="specialty" placeholder="Cardiologist" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-emerald-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Date & Time <span class="text-rose-500">*</span></label>
                        <input type="datetime-local" name="appointment_date" required class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-emerald-500 text-sm [color-scheme:dark]">
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Location</label>
                    <input type="text" name="location" placeholder="Full address or Clinic Room" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-emerald-500 text-sm">
                </div>
                
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Notes / Reminders</label>
                    <textarea name="notes" rows="2" placeholder="Bring ID and insurance card..." class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-emerald-500 text-sm resize-none"></textarea>
                </div>
                
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2.5 rounded-lg text-sm font-bold transition-colors mt-2 shadow-[0_4px_15px_rgba(16,185,129,0.25)] flex items-center justify-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i> Schedule Appointment
                </button>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php require_once 'footer.php'; ?>
