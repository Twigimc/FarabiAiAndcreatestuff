<?php
session_start();
require_once 'db.php';

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Generate CSRF token if not exists
    if (empty($_SESSION['login_csrf_token'])) {
        $_SESSION['login_csrf_token'] = bin2hex(random_bytes(32));
    }

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $csrf = $_POST['csrf_token'] ?? '';

    // Validate CSRF
    if (!hash_equals($_SESSION['login_csrf_token'], $csrf)) {
        $error = "انتهت صلاحية الجلسة، يرجى المحاولة مرة أخرى.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, password_hash FROM admins WHERE username = ?");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password_hash'])) {
                // Successful login
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                
                // Regenerate session ID for security
                session_regenerate_id(true);
                
                header('Location: dashboard.php');
                exit;
            } else {
                $error = "اسم المستخدم أو كلمة المرور غير صحيحة.";
            }
        } catch(PDOException $e) {
            $error = "حدث خطأ أثناء الاتصال بقاعدة البيانات.";
        }
    }
}

// Generate new CSRF token for the form
$_SESSION['login_csrf_token'] = bin2hex(random_bytes(32));
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - لوحة التحكم</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #0d1f3c;
            --gold: #b8972a;
            --gold-light: #e8c96a;
            --cream: #faf8f3;
            --border: #d9d0bc;
            --text: #1a1a2e;
            --accent: #1a4a8a;
            --error: #e74c3c;
        }
        body {
            font-family: 'Cairo', sans-serif;
            background: #e8e4da;
            background-image: 
              radial-gradient(circle at 20% 20%, rgba(184,151,42,0.08) 0%, transparent 50%),
              radial-gradient(circle at 80% 80%, rgba(13,31,60,0.06) 0%, transparent 50%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        .login-card {
            background: #fff;
            width: 100%;
            max-width: 400px;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(13,31,60,0.15);
            overflow: hidden;
        }
        .header {
            background: var(--navy);
            color: #fff;
            padding: 30px;
            text-align: center;
            border-top: 6px solid var(--gold);
        }
        .header h1 {
            font-family: 'Amiri', serif;
            margin: 0 0 10px 0;
            font-size: 24px;
        }
        .header p {
            margin: 0;
            font-size: 14px;
            color: rgba(255,255,255,0.7);
        }
        .form-body {
            padding: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text);
            font-size: 14px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            background: var(--cream);
            font-family: 'Cairo', sans-serif;
            font-size: 14px;
            box-sizing: border-box;
            transition: border-color 0.2s;
        }
        input:focus {
            outline: none;
            border-color: var(--accent);
            background: #fff;
        }
        .btn-submit {
            width: 100%;
            padding: 12px;
            background: var(--gold);
            color: var(--navy);
            border: none;
            border-radius: 8px;
            font-family: 'Cairo', sans-serif;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            margin-top: 10px;
        }
        .btn-submit:hover {
            background: var(--gold-light);
            transform: translateY(-1px);
        }
        .error-msg {
            background: #fdf2f2;
            color: var(--error);
            padding: 10px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 20px;
            border: 1px solid #fad5d5;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="header">
        <h1>لوحة تحكم المشاريع الإبداعية</h1>
        <p>تسجيل الدخول للمسؤولين</p>
    </div>
    <div class="form-body">
        <?php if ($error): ?>
            <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['login_csrf_token']) ?>">
            
            <div class="form-group">
                <label for="username">اسم المستخدم</label>
                <input type="text" id="username" name="username" required dir="ltr">
            </div>
            
            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <input type="password" id="password" name="password" required dir="ltr">
            </div>
            
            <button type="submit" class="btn-submit">تسجيل الدخول</button>
        </form>
    </div>
</div>

</body>
</html>
