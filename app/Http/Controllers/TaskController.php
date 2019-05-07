<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(){
        return view('welcome');
    }

    public function showTask(){
        $tasks = Task::all();
        return view('show', compact('tasks'));
    }

    public function create(){
        return view('add');
    }

    public function store(Request $request){
        $task = new Task();
        $task->title = $request->inputTitle;
        $task->content = $request->inputContent;
        $task->date = $request->inputDueDate;

        if (!$request->hasFile('inputFile')) {
            $task->image = $request->inputFile;
        } else {
            $file = $request->file('inputFile');

            //Lấy ra định dạng và tên mới của file từ request
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = $request->inputFileName;

            // Gán tên mới cho file trước khi lưu lên server
            $newFileName = "$fileName.$fileExtension";

            //Lưu file vào thư mục storage/app/public/image với tên mới
            $request->file('inputFile')->storeAs('public/images', $newFileName);

            // Gán trường image của đối tượng task với tên mới
            $task->image = $newFileName;
        }
        $task->save();

        return redirect()->route('tasks.index', compact('message'));
    }
}
