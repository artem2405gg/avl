<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AVL Logistic — платформа грузоперевозок</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f6f9; color: #333; }
        .navbar { background: #1a1a2e; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .navbar a { color: white; text-decoration: none; margin-left: 20px; }
        .navbar a:hover { color: #4cc9f0; }
        .logo { font-size: 22px; font-weight: bold; color: #4cc9f0; }
        .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; }
        .btn { display: inline-block; padding: 10px 20px; border-radius: 6px; text-decoration: none; border: none; cursor: pointer; font-size: 14px; }
        .btn-primary { background: #007bff; color: white; }
        .btn-primary:hover { background: #0056b3; }
        .btn-success { background: #28a745; color: white; }
        .btn-success:hover { background: #1e7e34; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-sm { padding: 6px 14px; font-size: 13px; }
        .card { background: white; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 15px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        @media (max-width: 768px) { .grid-2 { grid-template-columns: 1fr; } .navbar { flex-direction: column; gap: 10px; } }
    </style>
</head>
<body>
    <nav class="navbar">
        <div>
            <a href="/" class="logo">🚛 AVL Logistic</a>
        </div>
        <div>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/dashboard">Главная</a>
                <a href="/orders">Заказы</a>
                <?php if ($_SESSION['user_role'] === 'owner'): ?>
                    <a href="/orders/create">Создать заказ</a>
                <?php endif; ?>
                <a href="/orders/my">Мои сделки</a>
                <span style="color:#4cc9f0;"><?= htmlspecialchars($_SESSION['user_name']) ?></span>
                <a href="/logout" style="color:#dc3545;">Выйти</a>
            <?php else: ?>
                <a href="/login">Вход</a>
                <a href="/register">Регистрация</a>
            <?php endif; ?>
        </div>
    </nav>
    <div class="container">