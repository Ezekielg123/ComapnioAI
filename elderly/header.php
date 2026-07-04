<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'Elderly') {
    header("Location: ../auth/login.php");
    exit();
}
require_once '../config/database.php';
$pdo = getDBConnection();
$elderly_user_id = $_SESSION['user_id'];
$elderly_name = $_SESSION['first_name'];

$stmt = $pdo->prepare("SELECT id FROM patients WHERE user_id = ?");
$stmt->execute([$elderly_user_id]);
$patient = $stmt->fetch();

if (!$patient) {
    $stmt = $pdo->prepare("SELECT id FROM patients WHERE first_name = ? LIMIT 1");
    $stmt->execute([$elderly_name]);
    $patient = $stmt->fetch();
    if ($patient) {
        $pdo->prepare("UPDATE patients SET user_id = ? WHERE id = ?")->execute([$elderly_user_id, $patient['id']]);
    }
}

$patient_id = $patient ? $patient['id'] : null;
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Companio AI | Elderly Dashboard</title>
    <link href="../assets/css/output.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #020617;
            color: #f8fafc;
        }

        .glass-panel {
            background: rgba(30, 41, 59, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Ultra accessible large tap targets */
        a,
        button,
        select,
        input {
            touch-action: manipulation;
        }

        /* HARD-FIX Scroll Constraints */
        html,
        body {
            overflow-x: hidden !important;
            overflow-y: auto !important;
            height: auto !important;
            min-height: 100vh !important;
        }
    </style>
</head>

<body class="antialiased min-h-screen flex flex-col relative overflow-x-hidden text-lg bg-slate-950">
    <!-- Ambient glowing backgrounds specifically crafted for easy contrast -->
    <div
        class="absolute top-[-200px] right-[-200px] w-[800px] h-[800px] bg-indigo-600/10 rounded-full blur-[140px] pointer-events-none -z-10">
    </div>
    <div
        class="absolute bottom-[-200px] left-[-200px] w-[600px] h-[600px] bg-cyan-600/10 rounded-full blur-[120px] pointer-events-none -z-10">
    </div>

    <!-- Top Nav -->
    <nav
        class="h-24 glass-panel border-b-0 border-white/10 shrink-0 flex items-center justify-between px-6 md:px-12 z-20 sticky top-0 shadow-xl">
        <div class="flex items-center gap-5">
            <div
                class="w-16 h-16 rounded-3xl bg-gradient-to-br from-indigo-500 to-cyan-500 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                <i data-lucide="brain-circuit" class="text-white w-9 h-9"></i>
            </div>
            <div>
                <h1 class="font-extrabold text-3xl md:text-4xl tracking-tight text-white mb-1">Hello,
                    <?= htmlspecialchars($elderly_name) ?>!
                </h1>
                <p class="text-slate-300 text-lg md:text-xl font-medium">
                    <?= date('l, F j') ?>
                </p>
            </div>
        </div>
        <div class="flex items-center gap-8">
            <a href="dashboard.php"
                class="hidden md:flex flex-col items-center <?= $current_page == 'dashboard.php' ? 'text-indigo-400' : 'text-slate-300 hover:text-white' ?> transition-colors">
                <div class="p-3 <?= $current_page == 'dashboard.php' ? 'bg-indigo-500/20 rounded-2xl' : '' ?>"><i
                        data-lucide="home" class="w-8 h-8"></i></div>
                <span class="text-sm font-bold mt-1">Home</span>
            </a>
            <a href="../auth/logout.php"
                class="hidden md:flex flex-col items-center text-slate-400 hover:text-rose-400 transition-colors">
                <div class="p-3 hover:bg-rose-500/20 rounded-2xl"><i data-lucide="log-out" class="w-8 h-8"></i></div>
                <span class="text-sm font-bold mt-1">Sign Out</span>
            </a>
        </div>
    </nav>
    <main class="flex-1 p-4 md:p-8 z-10 w-full relative pb-32 md:pb-16">
        <div class="max-w-7xl mx-auto">