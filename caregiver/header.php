<?php
ob_start();
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'Caregiver') {
    header("Location: ../auth/login.php");
    exit();
}
require_once '../config/database.php';
$pdo = getDBConnection();
$caregiver_id = $_SESSION['user_id'];
$caregiver_name = $_SESSION['first_name'];

$patients_stmt = $pdo->prepare("SELECT id, first_name, last_name FROM patients WHERE caregiver_id = ?");
$patients_stmt->execute([$caregiver_id]);
$my_patients = $patients_stmt->fetchAll();

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caregiver Dashboard | Companio AI</title>
    <link href="../assets/css/output.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #020617;
            color: #f1f5f9;
        }

        .sidebar {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        .glass-panel {
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>

<body class="antialiased flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="sidebar w-64 flex-shrink-0 flex flex-col hidden md:flex z-20 relative">
        <div class="h-20 flex items-center px-6 border-b border-white/5">
            <div
                class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center mr-3 shadow-lg shadow-indigo-500/20">
                <i data-lucide="brain-circuit" class="text-white w-5 h-5"></i>
            </div>
            <span class="font-bold text-xl tracking-tight text-white">Companio AI</span>
        </div>

        <div class="p-4 flex-1 overflow-y-auto">
            <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 mt-2">Caregiver Menu</p>
            <nav class="space-y-1.5">
                <a href="dashboard.php"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors <?= $current_page == 'dashboard.php' ? 'bg-indigo-500/20 text-indigo-400 border border-indigo-500/20 font-medium' : 'text-slate-400 hover:text-slate-200 hover:bg-white/5' ?>">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dashboard
                </a>
                <a href="add_elderly.php"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors <?= $current_page == 'add_elderly.php' ? 'bg-indigo-500/20 text-indigo-400 border border-indigo-500/20 font-medium' : 'text-slate-400 hover:text-slate-200 hover:bg-white/5' ?>">
                    <i data-lucide="user-plus" class="w-5 h-5"></i> Add Patient
                </a>
                <a href="medical_history.php"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors <?= $current_page == 'medical_history.php' ? 'bg-indigo-500/20 text-indigo-400 border border-indigo-500/20 font-medium' : 'text-slate-400 hover:text-slate-200 hover:bg-white/5' ?>">
                    <i data-lucide="file-text" class="w-5 h-5"></i> Medical History
                </a>
                <a href="medicine_schedule.php"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors <?= $current_page == 'medicine_schedule.php' ? 'bg-indigo-500/20 text-indigo-400 border border-indigo-500/20 font-medium' : 'text-slate-400 hover:text-slate-200 hover:bg-white/5' ?>">
                    <i data-lucide="pill" class="w-5 h-5"></i> Medicine Schedule
                </a>
                <a href="appointments.php"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors <?= $current_page == 'appointments.php' ? 'bg-indigo-500/20 text-indigo-400 border border-indigo-500/20 font-medium' : 'text-slate-400 hover:text-slate-200 hover:bg-white/5' ?>">
                    <i data-lucide="calendar" class="w-5 h-5"></i> Appointments
                </a>
                <a href="summary.php"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors <?= $current_page == 'summary.php' ? 'bg-indigo-500/20 text-indigo-400 border border-indigo-500/20 font-medium' : 'text-slate-400 hover:text-slate-200 hover:bg-white/5' ?>">
                    <i data-lucide="clipboard-check" class="w-5 h-5"></i> Daily Summary
                </a>
                <a href="emergency_contacts.php"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors <?= $current_page == 'emergency_contacts.php' ? 'bg-indigo-500/20 text-indigo-400 border border-indigo-500/20 font-medium' : 'text-slate-400 hover:text-slate-200 hover:bg-white/5' ?>">
                    <i data-lucide="phone-call" class="w-5 h-5"></i> Emergency Contacts
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-white/5">
            <div class="flex items-center gap-3 px-2 py-2">
                <div class="w-9 h-9 rounded-full bg-slate-800 flex items-center justify-center border border-slate-700">
                    <i data-lucide="user" class="text-slate-400 w-5 h-5"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">
                        <?= htmlspecialchars($caregiver_name) ?>
                    </p>
                    <p class="text-xs text-slate-500 truncate">Caregiver</p>
                </div>
                <a href="../auth/logout.php"
                    class="text-slate-500 hover:text-rose-400 transition-colors tooltip flex-shrink-0" title="Logout">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden relative">
        <div
            class="absolute top-0 right-0 w-[600px] h-[600px] bg-indigo-600/5 rounded-full blur-[120px] pointer-events-none -z-10">
        </div>
        <div
            class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-purple-600/5 rounded-full blur-[100px] pointer-events-none -z-10">
        </div>

        <!-- Top header for mobile -->
        <header
            class="h-16 border-b border-white/5 flex items-center justify-between px-4 md:hidden glass-panel shrink-0 z-10">
            <div class="flex items-center gap-2">
                <div
                    class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                    <i data-lucide="brain-circuit" class="text-white w-4 h-4"></i>
                </div>
                <span class="font-bold text-lg text-white">Companio AI</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="../auth/logout.php" class="text-slate-400 hover:text-rose-400"><i data-lucide="log-out"
                        class="w-5 h-5"></i></a>
            </div>
        </header>

        <!-- Content Area -->
        <div class="flex-1 overflow-auto p-4 md:p-8 z-10 w-full relative">
            <div class="max-w-6xl mx-auto">