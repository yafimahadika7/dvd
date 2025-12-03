<?php
require_once '../config/config.php';

// kalau sudah login, lempar ke index
if (isset($_SESSION['user_id'])) {
    header("Location: " . base_url('/index.php'));
    exit;
}

$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | ArvinoNaufal</title>
    <link rel="icon" href="<?= base_url('/static/assets/imgs/favicon.png') ?>" type="image/png">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            background: url("<?= base_url('/static/assets/imgs/bg.jpg') ?>")
                        no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* ===== NEON GREEN EFFECT ===== */
        .login-container {
            background: #121010;
            width: 360px;
            padding: 40px;
            border-radius: 18px;
            text-align: center;
            animation: fadeIn 0.7s ease;

            border: 2px solid rgba(0, 255, 100, 0.6);
            box-shadow:
                0 0 12px rgba(0, 255, 100, 0.6),
                0 0 25px rgba(0, 255, 100, 0.4),
                inset 0 0 12px rgba(0, 255, 100, 0.5);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px);}
            to { opacity: 1; transform: translateY(0);}
        }

        .logo {
            width: 110px;
            margin-bottom: 20px;
            border-radius: 50%;
            box-shadow: 0 0 12px rgba(0, 255, 100, 0.4);
        }

        input {
            width: 100%;
            padding: 12px 14px;
            margin: 10px 0;
            border-radius: 10px;
            border: 1px solid #ddd;
            outline: none;
            transition: 0.3s;
            font-size: 15px;
        }

        input:focus {
            border-color: #00ff64;
            box-shadow: 0 0 10px rgba(0, 255, 100, 0.6);
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            margin-top: 18px;
            background: #00cc55;
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 0 10px rgba(0, 255, 100, 0.7);
        }

        .btn-login:hover {
            background: #009944;
            box-shadow: 0 0 18px rgba(0, 255, 100, 0.9);
        }

        /* Password – icon mata */
        .password-wrapper {
            position: relative;
            width: 100%;
        }

        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
            color: #999;
        }

        .password-toggle:hover {
            color: #00ff64;
        }

        .footer {
            margin-top: 20px;
            font-size: 13px;
            color: #ccc;
        }

        .alert-error {
            background: #ff4d4f;
            color: #fff;
            padding: 8px 10px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <img src="<?= base_url('/static/assets/imgs/1.jpeg') ?>"
             alt="Logo"
             class="logo">

        <?php if ($error): ?>
            <div class="alert-error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('/auth/proses_login.php') ?>" method="POST">

            <input type="text" name="username" placeholder="Username" required>

            <div class="password-wrapper">
                <input type="password" id="passwordInput" name="password" placeholder="Password" required>
                <span class="password-toggle" id="togglePassword">&#128065;</span>
            </div>

            <button class="btn-login" type="submit">Masuk</button>
        </form>

        <div class="footer">
            © UAS Pemrograman II – Rangga Roris
        </div>
    </div>

    <script>
        const passInput = document.getElementById("passwordInput");
        const toggle = document.getElementById("togglePassword");

        toggle.addEventListener("mousedown", function () {
            passInput.type = "text";
            toggle.style.color = "#00ff64";
        });

        toggle.addEventListener("mouseup", function () {
            passInput.type = "password";
            toggle.style.color = "#999";
        });

        toggle.addEventListener("mouseleave", function () {
            passInput.type = "password";
            toggle.style.color = "#999";
        });
    </script>

</body>
</html>
