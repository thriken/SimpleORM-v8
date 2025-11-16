<?php

namespace App\Controllers;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ClassroomTeacher;

class ClassroomController
{
    public function index()
    {
        $classrooms = Classroom::all();
        return $this->render('classrooms/index', ['classrooms' => $classrooms]);
    }

    public function show($id)
    {
        $classroom = Classroom::find($id);
        if (!$classroom) {
            return $this->render('errors/404');
        }

        // 获取班级学生
        $studentResults = Student::query()->where('classroom_id', $id)->get();
        $students = [];
        foreach ($studentResults as $studentData) {
            $student = new Student();
            foreach ($studentData as $key => $value) {
                $student->setAttribute($key, $value);
            }
            $students[] = $student;
        }
        
        // 获取班级老师
        $classroomTeachers = ClassroomTeacher::query()->where('classroom_id', $id)->get();
        $teachers = [];
        foreach ($classroomTeachers as $ct) {
            $teacher = Teacher::find($ct['teacher_id']);
            if ($teacher) {
                $teachers[] = $teacher;
            }
        }

        return $this->render('classrooms/show', [
            'classroom' => $classroom,
            'students' => $students,
            'teachers' => $teachers
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