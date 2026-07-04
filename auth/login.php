<?php
session_start();
require_once '../config/database.php';

if (isset($_SESSION['user_id'])) {
    $dashboard = ($_SESSION['role_name'] === 'Caregiver') ? '../caregiver/dashboard.php' : '../elderly/dashboard.php';
    header("Location: $dashboard");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("
                SELECT u.id, u.first_name, u.password_hash, r.name as role_name 
                FROM users u
                JOIN roles r ON u.role_id = r.id
                WHERE u.email = ?
            ");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                session_regenerate_id(true); // Prevent Fixation
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['role_name'] = $user['role_name'];

                $dashboard = ($user['role_name'] === 'Caregiver') ? '../caregiver/dashboard.php' : '../elderly/dashboard.php';
                header("Location: $dashboard");
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $error = "Login failed. Please try again later.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Companio AI</title>
    <link href="../assets/css/output.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>

<body
    class="bg-slate-950 text-slate-200 antialiased min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <div
        class="absolute top-0 left-1/2 -translate-x-1/2 w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-[100px] -z-10 pointer-events-none">
    </div>

    <div class="w-full max-w-md">
        <div class="flex flex-col items-center justify-center mb-8">
            <a href="../index.php" class="flex items-center gap-2 mb-2">
                <div
                    class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    <i data-lucide="brain-circuit" class="text-white w-6 h-6"></i>
                </div>
                <span class="font-bold text-2xl tracking-tight text-white">Companio AI</span>
            </a>
            <p class="text-slate-400 text-sm">Welcome back</p>
        </div>

        <div class="bg-slate-900/50 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-2xl relative">
            <?php if ($error): ?>
                <div
                    class="bg-rose-500/10 border border-rose-500/30 text-rose-400 px-4 py-3 rounded-lg mb-6 text-sm flex items-start gap-3">
                    <i data-lucide="alert-circle" class="w-5 h-5 shrink-0"></i>
                    <span>
                        <?php echo htmlspecialchars($error); ?>
                    </span>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5" for="email">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="w-5 h-5 text-slate-500"></i>
                        </div>
                        <input type="email" id="email" name="email" required
                            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                            class="w-full bg-slate-950/50 border border-slate-700 rounded-lg pl-11 pr-4 py-2.5 text-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-sm font-medium text-slate-300" for="password">Password</label>
                        <a href="#" class="text-xs text-indigo-400 hover:text-indigo-300 font-medium">Forgot
                            password?</a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="w-5 h-5 text-slate-500"></i>
                        </div>
                        <input type="password" id="password" name="password" required
                            class="w-full bg-slate-950/50 border border-slate-700 rounded-lg pl-11 pr-4 py-2.5 text-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-2.5 rounded-lg transition-colors shadow-lg shadow-indigo-500/20 flex justify-center items-center gap-2">
                    Sign In <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-slate-400">
                    Don't have an account? <a href="register.php"
                        class="text-indigo-400 hover:text-indigo-300 font-medium transition-colors">Sign up</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>