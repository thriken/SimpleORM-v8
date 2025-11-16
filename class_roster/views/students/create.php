<?php ob_start(); ?>
<div class="card">
    <h2>添加学生</h2>
    <div style="margin-bottom: 1rem;">
        <a href="<?= SITE_ROOT ?>/?page=students" class="btn">返回学生列表</a>
    </div>
    
    <form method="POST" action="<?= SITE_ROOT ?>/?page=students&action=store">
        <div class="form-group">
            <label for="name">姓名 *</label>
            <input type="text" id="name" name="name" required>
        </div>
        
        <div class="form-group">
            <label for="student_id">学号 *</label>
            <input type="text" id="student_id" name="student_id" required>
        </div>
        
        <div class="form-group">
            <label for="classroom_id">班级 *</label>
            <select id="classroom_id" name="classroom_id" required>
                <option value="">请选择班级</option>
                <?php foreach ($classrooms as $classroom): ?>
                    <option value="<?= $classroom['id'] ?>"><?= htmlspecialchars($classroom['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="gender">性别</label>
            <select id="gender" name="gender">
                <option value="男">男</option>
                <option value="女">女</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="birth_date">出生日期</label>
            <input type="date" id="birth_date" name="birth_date">
        </div>
        
        <div class="form-group">
            <label for="phone">电话</label>
            <input type="text" id="phone" name="phone">
        </div>
        
        <div class="form-group">
            <label for="address">地址</label>
            <textarea id="address" name="address" rows="3"></textarea>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-success">添加学生</button>
            <button type="reset" class="btn">重置</button>
        </div>
    </form>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>