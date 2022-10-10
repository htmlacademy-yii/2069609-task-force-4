<?php

namespace app\models\forms;

use app\models\Category;
use app\models\File;
use app\models\Task;
use Yii;
use yii\base\Model;
use yii\web\ServerErrorHttpException;

class TaskCreateForm extends Model
{
    public $description;
    public $details;
    public $category;
    public $location;
    public $budget;
    public $dateOfExecution;
    public $files;

    public function attributeLabels()
    {
        return [
            'description' => 'Мне нужно',
            'details' => 'Подробности задания',
            'category' => 'Категория',
            'location' => 'Локация',
            'budget' => 'Бюджет',
            'dateOfExecution' => 'Срок исполнения',
            'files' => 'Файлы',
        ];
    }

    public function rules()
    {
        return [
            [['description', 'details', 'category'], 'required'],
            ['description', 'string', 'min' => 10, 'max' => 255],
            ['details', 'string', 'min' => 30, 'max' => 255],
            ['category', 'exist', 'targetClass' => Category::class, 'targetAttribute' => ['category' => 'id']],
            ['budget', 'integer', 'min' => 0],
            [['files'], 'file', 'maxFiles' => 4]
        ];
    }

    /**
     * @throws ServerErrorHttpException
     */
    public function createTask(){
        $task = new Task();
        $task->status = Task::STATUS_NEW;
        $task->description = $this->description;
        $task->details = $this->details;
        $task->category_id = $this->category;
        $task->date_of_execution = $this->dateOfExecution;
        $task->budget = $this->budget;
        $task->user_id = Yii::$app->user->id;
        $task->save();
        if ($task->save()) {
            return $task;
        } else {
            throw new ServerErrorHttpException('Loading error');
        }
    }
    public function uploadFiles($taskId)
    {
        if ($this->validate() && !empty($this->files)) {
            foreach ($this->files as $file) {
                $newName = uniqid('upload') . '.' . $file->getExtension();
                $file->saveAs('@webroot/uploads/' . $newName);
                $fileRecord = new File();
                $fileRecord->task_id = $taskId;
                $fileRecord->title = '@webroot/uploads/' . $newName;
                $fileRecord->save();
            }
            return true;
        } else {
            return false;
        }
    }

}