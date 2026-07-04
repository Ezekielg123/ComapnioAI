<?php
session_start();
require_once '../config/database.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    $dashboard = ($_SESSION['role_name'] === 'Caregiver') ? '../caregiver/dashboard.php' : '../elderly/dashboard.php';
    header("Location: $dashboard");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role_id = trim($_POST['role'] ?? '');

    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($role_id)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } else {
        try {
            $pdo = getDBConnection();

            // Check if email already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = "Email is already registered.";
            } else {
                // Securely hash the password
                $hashed_password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

                $insert = $pdo->prepare("INSERT INTO users (role_id, first_name, last_name, email, password_hash) VALUES (?, ?, ?, ?, ?)");
                $insert->execute([$role_id, $first_name, $last_name, $email, $hashed_password]);

                $success = "Registration successful! You can now login.";
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $error = "Registration Error: " . $e->getMessage() . " Please show this to the AI!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Companio AI</title>
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
        class="absolute top-0 left-1/2 -translate-x-1/2 w-[500px] h-[500px] bg-purple-600/10 rounded-full blur-[100px] -z-10 pointer-events-none">
    </div>

    <div class="w-full max-w-md py-8">
        <div class="flex flex-col items-center justify-center mb-8">
            <a href="../index.php" class="flex items-center gap-2 mb-2">
                <div
                    class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    <i data-lucide="brain-circuit" class="text-white w-6 h-6"></i>
                </div>
                <span class="font-bold text-2xl tracking-tight text-white">Companio AI</span>
            </a>
            <p class="text-slate-400 text-sm">Create your account</p>
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

            <?php if ($success): ?>
                <div
                    class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-lg mb-6 text-sm flex items-start gap-3">
                    <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
                    <span>
                        <?php echo htmlspecialchars($success); ?>
                    </span>
                </div>
                <!-- Show login link nicely if successful, instead of form -->
                <a href="login.php"
                    class="w-full block text-center bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-2.5 rounded-lg transition-colors shadow-lg shadow-indigo-500/20">
                    Sign in to your account
                </a>
            <?php else: ?>

                <form method="POST" action="register.php" class="space-y-5">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1.5" for="first_name">First
                                Name</label>
                            <input type="text" id="first_name" name="first_name" required
                                value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>"
                                class="w-full bg-slate-950/50 border border-slate-700 rounded-lg px-4 py-2.5 text-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1.5" for="last_name">Last Name</label>
                            <input type="text" id="last_name" name="last_name" required
                                value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>"
                                class="w-full bg-slate-950/50 border border-slate-700 rounded-lg px-4 py-2.5 text-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                        </div>
                    </div>

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
                        <label class="block text-sm font-medium text-slate-300 mb-1.5" for="role">I am a...</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="users" class="w-5 h-5 text-slate-500"></i>
                            </div>
                            <select id="role" name="role" required
                                class="w-full bg-slate-950/50 border border-slate-700 rounded-lg pl-11 pr-4 py-2.5 text-slate-200 appearance-none focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors cursor-pointer">
                                <option value="" disabled <?php echo empty($_POST['role']) ? 'selected' : ''; ?>>Select
                                    your role</option>
                                <?php
                                try {
                                    $pdo = getDBConnection();
                                    // Critical fallback: Ensure roles exist in case phpMyAdmin import missed them
                                    $pdo->exec("INSERT IGNORE INTO roles (id, name) VALUES (1, 'Caregiver'), (2, 'Elderly')");

                                    $roles = $pdo->query("SELECT id, name FROM roles")->fetchAll();
                                    foreach ($roles as $r) {
                                        $selected = (isset($_POST['role']) && $_POST['role'] == $r['id']) ? 'selected' : '';
                                        echo "<option value='{$r['id']}' {$selected}>{$r['name']}</option>";
                                    }
                                } catch (Exception $e) {
                                    echo "<option value='1'>Caregiver</option>";
                                    echo "<option value='2'>Elderly</option>";
                                }
                                ?>
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5" for="password">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="lock" class="w-5 h-5 text-slate-500"></i>
                            </div>
                            <input type="password" id="password" name="password" required
                                class="w-full bg-slate-950/50 border border-slate-700 rounded-lg pl-11 pr-4 py-2.5 text-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5" for="confirm_password">Confirm
                            Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="lock" class="w-5 h-5 text-slate-500"></i>
                            </div>
                            <input type="password" id="confirm_password" name="confirm_password" required
                                class="w-full bg-slate-950/50 border border-slate-700 rounded-lg pl-11 pr-4 py-2.5 text-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-2.5 rounded-lg transition-colors shadow-lg shadow-indigo-500/20">
                        Create Account
                    </button>
                </form>
            <?php endif; ?>

            <div class="mt-6 text-center">
                <p class="text-sm text-slate-400">
                    Already have an account? <a href="login.php"
                        class="text-indigo-400 hover:text-indigo-300 font-medium transition-colors">Sign in here</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>