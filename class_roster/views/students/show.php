<?php ob_start(); ?>
<div class="card">
    <h2>学生详情</h2>
    <div style="margin-bottom: 1rem;">
        <a href="<?= SITE_ROOT ?>/?page=students" class="btn">返回学生列表</a>
        <a href="<?= SITE_ROOT ?>/?page=students&action=edit&id=<?= $student->id ?>" class="btn btn-warning">编辑</a>
        <a href="<?= SITE_ROOT ?>/?page=students&action=delete&id=<?= $student->id ?>" class="btn btn-danger" 
           onclick="return confirm('确定要删除这个学生吗？')">删除</a>
    </div>
    
    <div class="card">
        <h3>基本信息</h3>
        <table>
            <tr>
                <th>ID</th>
                <td><?= $student->id ?></td>
            </tr>
            <tr>
                <th>姓名</th>
                <td><?= htmlspecialchars($student->name) ?></td>
            </tr>
            <tr>
                <th>学号</th>
                <td><?= htmlspecialchars($student->student_id) ?></td>
            </tr>
            <tr>
                <th>性别</th>
                <td><?= htmlspecialchars($student->gender) ?></td>
            </tr>
            <tr>
                <th>出生日期</th>
                <td><?= htmlspecialchars($student->birth_date) ?></td>
            </tr>
            <tr>
                <th>电话</th>
                <td><?= htmlspecialchars($student->phone) ?></td>
            </tr>
            <tr>
                <th>地址</th>
                <td><?= htmlspecialchars($student->address) ?></td>
            </tr>
            <tr>
                <th>班级</th>
                <td>
                    <?php if ($classroom): ?>
                        <a href="<?= SITE_ROOT ?>/?page=classrooms&action=show&id=<?= $classroom->id ?>">
                            <?= htmlspecialchars($classroom->name) ?>
                        </a>
                    <?php else: ?>
                        未知班级
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>