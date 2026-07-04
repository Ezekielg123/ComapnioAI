<?php require_once 'header.php'; ?>
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-white mb-2">Dashboard</h1>
        <p class="text-slate-400 text-sm">Welcome back,
            <?= htmlspecialchars($caregiver_name) ?>. Here is the overview of your dependents.
        </p>
    </div>
    <a href="add_elderly.php"
        class="bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-all shadow-lg shadow-indigo-500/25 flex items-center gap-2 hover:-translate-y-0.5">
        <i data-lucide="plus" class="w-4 h-4"></i> Add Patient
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="glass-panel p-6 rounded-2xl relative overflow-hidden group">
        <div
            class="absolute -right-6 -top-6 w-24 h-24 bg-blue-500/10 rounded-full blur-xl group-hover:bg-blue-500/20 transition-all">
        </div>
        <div class="flex justify-between items-start mb-4 relative z-10">
            <div class="p-3 bg-blue-500/10 rounded-xl border border-blue-500/20 shadow-inner"><i data-lucide="users"
                    class="text-blue-400 w-6 h-6"></i></div>
        </div>
        <h3 class="text-slate-400 text-sm font-medium relative z-10">Total Managed Patients</h3>
        <p class="text-4xl font-bold text-white mt-2 relative z-10">
            <?= count($my_patients) ?>
        </p>
    </div>
    <div class="glass-panel p-6 rounded-2xl relative overflow-hidden group">
        <div
            class="absolute -right-6 -top-6 w-24 h-24 bg-purple-500/10 rounded-full blur-xl group-hover:bg-purple-500/20 transition-all">
        </div>
        <div class="flex justify-between items-start mb-4 relative z-10">
            <div class="p-3 bg-purple-500/10 rounded-xl border border-purple-500/20 shadow-inner"><i data-lucide="pill"
                    class="text-purple-400 w-6 h-6"></i></div>
        </div>
        <h3 class="text-slate-400 text-sm font-medium relative z-10">Active Prescriptions</h3>
        <?php
        $meds_count = 0;
        if (count($my_patients) > 0) {
            $ids = implode(',', array_column($my_patients, 'id'));
            $meds = $pdo->query("SELECT COUNT(*) FROM medicine_schedule WHERE patient_id IN ($ids)")->fetchColumn();
            $meds_count = $meds;
        }
        ?>
        <p class="text-4xl font-bold text-white mt-2 relative z-10">
            <?= $meds_count ?>
        </p>
    </div>
    <div class="glass-panel p-6 rounded-2xl relative overflow-hidden group">
        <div
            class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-500/10 rounded-full blur-xl group-hover:bg-emerald-500/20 transition-all">
        </div>
        <div class="flex justify-between items-start mb-4 relative z-10">
            <div class="p-3 bg-emerald-500/10 rounded-xl border border-emerald-500/20 shadow-inner"><i
                    data-lucide="calendar" class="text-emerald-400 w-6 h-6"></i></div>
        </div>
        <h3 class="text-slate-400 text-sm font-medium relative z-10">Upcoming Appointments</h3>
        <?php
        $appts_count = 0;
        if (count($my_patients) > 0) {
            $ids = implode(',', array_column($my_patients, 'id'));
            $appts = $pdo->query("SELECT COUNT(*) FROM appointments WHERE patient_id IN ($ids) AND appointment_date >= NOW()")->fetchColumn();
            $appts_count = $appts;
        }
        ?>
        <p class="text-4xl font-bold text-white mt-2 relative z-10">
            <?= $appts_count ?>
        </p>
    </div>
</div>

<h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2"><i data-lucide="users"
        class="w-5 h-5 text-indigo-400"></i> Your Patients</h2>
<?php if (count($my_patients) > 0): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($my_patients as $p): ?>
            <div
                class="glass-panel p-6 rounded-2xl flex flex-col justify-between group hover:border-indigo-500/30 transition-all hover:shadow-[0_8px_30px_rgb(0,0,0,0.12)]">
                <div class="flex items-center gap-4 mb-6">
                    <div
                        class="w-14 h-14 rounded-full border border-indigo-500/30 shadow-[0_0_15px_rgba(99,102,241,0.2)] flex items-center justify-center bg-slate-900 text-indigo-400 font-bold text-xl uppercase tracking-wider relative">
                        <?= substr($p['first_name'], 0, 1) . substr($p['last_name'], 0, 1) ?>
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 rounded-full border-2 border-slate-900 shadow-sm"
                            title="Active"></div>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold text-lg">
                            <?= htmlspecialchars($p['first_name'] . ' ' . $p['last_name']) ?>
                        </h3>
                        <p class="text-slate-400 text-xs font-medium">Patient ID: #CPAI-
                            <?= str_pad($p['id'], 4, '0', STR_PAD_LEFT) ?>
                        </p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 mt-2 pt-4 border-t border-white/5">
                    <a href="medical_history.php?patient_id=<?= $p['id'] ?>"
                        class="text-xs text-slate-300 hover:text-white bg-slate-800/50 hover:bg-indigo-500/20 border border-slate-700/50 hover:border-indigo-500/30 px-3 py-2 rounded-lg flex items-center justify-center gap-1.5 transition-colors text-center font-medium">
                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i> History
                    </a>
                    <a href="medicine_schedule.php?patient_id=<?= $p['id'] ?>"
                        class="text-xs text-slate-300 hover:text-white bg-slate-800/50 hover:bg-purple-500/20 border border-slate-700/50 hover:border-purple-500/30 px-3 py-2 rounded-lg flex items-center justify-center gap-1.5 transition-colors text-center font-medium">
                        <i data-lucide="pill" class="w-3.5 h-3.5"></i> Meds
                    </a>
                    <a href="appointments.php?patient_id=<?= $p['id'] ?>"
                        class="text-xs text-slate-300 hover:text-white bg-slate-800/50 hover:bg-emerald-500/20 border border-slate-700/50 hover:border-emerald-500/30 px-3 py-2 rounded-lg flex items-center justify-center gap-1.5 transition-colors text-center font-medium">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i> Appts
                    </a>
                    <a href="emergency_contacts.php?patient_id=<?= $p['id'] ?>"
                        class="text-xs text-slate-300 hover:text-white bg-slate-800/50 hover:bg-rose-500/20 border border-slate-700/50 hover:border-rose-500/30 px-3 py-2 rounded-lg flex items-center justify-center gap-1.5 transition-colors text-center font-medium">
                        <i data-lucide="phone" class="w-3.5 h-3.5"></i> Contact
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div
        class="glass-panel p-16 rounded-3xl text-center border-dashed border-2 border-slate-700/50 max-w-2xl mx-auto flex flex-col items-center">
        <div
            class="w-20 h-20 bg-slate-800/50 rounded-2xl flex items-center justify-center mb-6 border border-slate-700 shadow-inner">
            <i data-lucide="user-x" class="text-slate-500 w-10 h-10"></i>
        </div>
        <h3 class="text-white font-bold text-xl mb-3">No profiles added yet</h3>
        <p class="text-slate-400 mb-8 max-w-sm mx-auto leading-relaxed">Start by adding an elderly patient profile to manage
            their medicines, appointments, and emergency contacts.</p>
        <a href="add_elderly.php"
            class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-3 rounded-xl text-sm font-medium transition-colors inline-flex items-center gap-2 shadow-lg shadow-indigo-500/20">
            <i data-lucide="plus-circle" class="w-5 h-5"></i> Create Patient Profile
        </a>
    </div>
<?php endif; ?>
<?php require_once 'footer.php'; ?>