<?php ob_start(); ?>
<div class="card">
    <h2>编辑学生</h2>
    <div style="margin-bottom: 1rem;">
        <a href="<?= SITE_ROOT ?>/?page=students" class="btn">返回学生列表</a>
    </div>
    
    <form method="POST" action="<?= SITE_ROOT ?>/?page=students&action=update&id=<?= $student->id ?>">
        <div class="form-group">
            <label for="name">姓名 *</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($student->name) ?>" required>
        </div>
        
        <div class="form-group">
            <label for="student_id">学号 *</label>
            <input type="text" id="student_id" name="student_id" value="<?= htmlspecialchars($student->student_id) ?>" required>
        </div>
        
        <div class="form-group">
            <label for="classroom_id">班级 *</label>
            <select id="classroom_id" name="classroom_id" required>
                <option value="">请选择班级</option>
                <?php foreach ($classrooms as $classroom): ?>
                    <option value="<?= $classroom->id ?>" <?= $classroom->id == $student->classroom_id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($classroom->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="gender">性别</label>
            <select id="gender" name="gender">
                <option value="男" <?= $student->gender == '男' ? 'selected' : '' ?>>男</option>
                <option value="女" <?= $student->gender == '女' ? 'selected' : '' ?>>女</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="birth_date">出生日期</label>
            <input type="date" id="birth_date" name="birth_date" value="<?= htmlspecialchars($student->birth_date) ?>">
        </div>
        
        <div class="form-group">
            <label for="phone">电话</label>
            <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($student->phone) ?>">
        </div>
        
        <div class="form-group">
            <label for="address">地址</label>
            <textarea id="address" name="address" rows="3"><?= htmlspecialchars($student->address) ?></textarea>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-success">保存</button>
            <a href="<?= SITE_ROOT ?>/?page=students&action=show&id=<?= $student->id ?>" class="btn">取消</a>
        </div>
    </form>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>