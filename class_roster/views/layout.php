<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>班级花名册管理系统</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        h1 {
            margin: 0;
            font-size: 2.5rem;
        }
        nav {
            background: white;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        nav a {
            margin-right: 1rem;
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
            padding: 0.5rem 1rem;
            border-radius: 5px;
        }
        nav a:hover, nav a.active {
            background: #667eea;
            color: white;
        }
        .card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .card h2 {
            color: #667eea;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 0.5rem;
            margin-top: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 0.75rem;
            text-align: left;
        }
        th {
            background: #f8f9fa;
        }
        tr:nth-child(even) {
            background: #f8f9fa;
        }
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin: 0.25rem;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background: #5a6fd8;
            text-decoration: none;
        }
        .btn-danger {
            background: #e53e3e;
        }
        .btn-danger:hover {
            background: #c53030;
        }
        .btn-success {
            background: #38a169;
        }
        .btn-success:hover {
            background: #2f855a;
        }
        .btn-warning {
            background: #d69e2e;
        }
        .btn-warning:hover {
            background: #b7791f;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .alert {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        .alert-success {
            background: #e6fffa;
            border-left: 4px solid #00b894;
        }
        .alert-error {
            background: #fff5f5;
            border-left: 4px solid #e53e3e;
        }
        .alert-info {
            background: #ebf8ff;
            border-left: 4px solid #3182ce;
        }
    </style>
</head>
<body>
    <header>
        <h1>班级花名册管理系统</h1>
    </header>

    <nav>
        <a href="<?= SITE_ROOT ?>/?page=classrooms" class="<?= ($_GET['page'] ?? '') === 'classrooms' ? 'active' : '' ?>">班级管理</a>
        <a href="<?= SITE_ROOT ?>/?page=students" class="<?= ($_GET['page'] ?? '') === 'students' ? 'active' : '' ?>">学生管理</a>
        <a href="<?= SITE_ROOT ?>/?page=teachers" class="<?= ($_GET['page'] ?? '') === 'teachers' ? 'active' : '' ?>">老师管理</a>
        <a href="<?= SITE_ROOT ?>/migrate.php">迁移工具</a>
    </nav>

    <main>
        <?= $content ?? '' ?>
    </main>

    <footer style="text-align: center; padding: 2rem; margin-top: 2rem; background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <p>班级花名册管理系统 &copy; 2025</p>
    </footer>
</body>
</html>