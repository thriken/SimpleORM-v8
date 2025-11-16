<?php

namespace App\Controllers;

use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\ClassroomTeacher;

class TeacherController
{
    public function index()
    {
        $teachers = Teacher::all();
        return $this->render('teachers/index', ['teachers' => $teachers]);
    }

    public function show($id)
    {
        $teacher = Teacher::find($id);
        if (!$teacher) {
            return $this->render('errors/404');
        }

        // 获取老师任教的班级
        $classroomTeachers = ClassroomTeacher::query()->where('teacher_id', $id)->get();
        $classrooms = [];
        foreach ($classroomTeachers as $ct) {
            $classroom = Classroom::find($ct['classroom_id']);
            if ($classroom) {
                $classrooms[] = $classroom;
            }
        }

        return $this->render('teachers/show', [
            'teacher' => $teacher,
            'classrooms' => $classrooms
        ]);
    }

    protected function render($view, $data = [])
    {
        extract($data);
        ob_start();
        include __DIR__ . "/../views/{$view}.php";
        return ob_get_clean();
    }
}