<?php

use SimpleORM\Database\DatabaseManager;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ClassroomTeacher;
use App\Controllers\ClassroomController;
use App\Controllers\StudentController;
use App\Controllers\TeacherController;

// 加载配置
$config = require_once __DIR__ . '/../autoload.php';

// 创建数据库管理器
$dbManager = new DatabaseManager($config);

// 设置模型的数据库管理器
Classroom::setDatabaseManager($dbManager);
Student::setDatabaseManager($dbManager);
Teacher::setDatabaseManager($dbManager);
ClassroomTeacher::setDatabaseManager($dbManager);

// 获取当前页面和操作
$page = $_GET['page'] ?? 'classrooms';
$action = $_GET['action'] ?? 'index';

// 处理POST请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handlePostRequest($page, $action);
    exit;
}

// 根据页面和操作调用相应的控制器方法
switch ($page) {
    case 'classrooms':
        $controller = new ClassroomController();
        switch ($action) {
            case 'show':
                echo $controller->show($_GET['id'] ?? 0);
                break;
            default:
                echo $controller->index();
                break;
        }
        break;
        
    case 'students':
        $controller = new StudentController();
        switch ($action) {
            case 'show':
                echo $controller->show($_GET['id'] ?? 0);
                break;
            case 'create':
                echo $controller->create();
                break;
            case 'edit':
                echo $controller->edit($_GET['id'] ?? 0);
                break;
            case 'delete':
                $result = $controller->delete($_GET['id'] ?? 0);
                // 重定向回学生列表
                header('Location: ' . SITE_ROOT . '/?page=students');
                exit;
            default:
                echo $controller->index();
                break;
        }
        break;
        
    case 'teachers':
        $controller = new TeacherController();
        switch ($action) {
            case 'show':
                echo $controller->show($_GET['id'] ?? 0);
                break;
            default:
                echo $controller->index();
                break;
        }
        break;
        
    default:
        $controller = new ClassroomController();
        echo $controller->index();
        break;
}

function handlePostRequest($page, $action) {
    switch ($page) {
        case 'students':
            $controller = new StudentController();
            switch ($action) {
                case 'store':
                    $result = $controller->store($_POST);
                    if ($result['success']) {
                        // 重定向到学生列表
                        header('Location: ' . SITE_ROOT . '/?page=students');
                    } else {
                        // 显示错误信息
                        echo '<div class="alert alert-error">' . htmlspecialchars($result['message']) . '</div>';
                        echo $controller->create();
                    }
                    break;
                case 'update':
                    $id = $_GET['id'] ?? 0;
                    $result = $controller->update($id, $_POST);
                    if ($result['success']) {
                        // 重定向到学生详情页
                        header('Location: ' . SITE_ROOT . '/?page=students&action=show&id=' . $id);
                    } else {
                        // 显示错误信息
                        echo '<div class="alert alert-error">' . htmlspecialchars($result['message']) . '</div>';
                        echo $controller->edit($id);
                    }
                    break;
            }
            break;
    }
}
?>