<?php


namespace App\Models;


use App\Helpers\ImageHelper;
use Core\Container\Container;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;

class Tasks
{
	use ImageHelper;
	/**
	 * @var \PDO $db
	 */
	private $db;
	private $name;
	private $email;
	private $task;
	private $status;
	/**
	 * @var UploadedFileInterface
	 */
	private $image;
	private $uploadImgPath = 'upload/';
	private $defaultImgPath = '/upload/';

	public function __construct()
	{
		$this->db = Container::getContainer()->get('db');
	}

	public function read()
	{
		$sql = "SELECT * FROM tasks;";
		$res = $this->db->query($sql);
		return $res->fetchAll(\PDO::FETCH_ASSOC);
	}

	/**
	 * 1 = Open Task | 2 = Closed Task
	 * @param $id
	 * @param $status
	 */
	public function changeStatus($id, $status)
	{

	}

	/**
	 * @return bool
	 */
	public function addTask()
	{
		if(!$this->image || $this->hasValidationError()) return false;
		$img = $this->getImgSrc($this->image, $this->uploadImgPath, 320, 240);
		if(!$img){$img = $this->defaultImgPath;}
		$date = (new \DateTime())->getTimestamp();
		$sql = "INSERT INTO tasks (task_name,task_email,task_text,task_date,task_src)
                VALUES (:name,:email,:task,:date,:src);";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':name', $this->name);
		$stmt->bindParam(':email', $this->email);
		$stmt->bindParam(':task', $this->task);
		$stmt->bindParam(':date', $date);
		$stmt->bindParam(':src', $img);
		return $stmt->execute();
	}

	public function hasValidationError()
	{
		$error = [];
		if (empty($this->name)) {
			$error['name'] = 'Enter name';
		}
		if (empty($this->email)) {
			$error['email'] = 'Enter email';
		} elseif (!$this->isValidEmail($this->email)) {
			$error['email'] = 'Enter correct email';
		}
		if (empty($this->task)) {
			$error['text'] = 'Enter task';
		}
		if (!empty($this->image->getSize())) {
			if (!$this->isValidImgMediaType($this->image)) {
				$error['img_error'] = 'Wrong file type';
			} elseif (!$this->isValidImgSize($this->image)) {
				$error['img_error'] = 'Too big file size';
			}
		}
		return $error;
	}

	public function loadData(ServerRequestInterface $request)
	{
		$this->name = $request->getParsedBody()['name'] ?? false;
		$this->email = $request->getParsedBody()['email'] ?? false;
		$this->task = $request->getParsedBody()['task'] ?? false;
		$this->image = $request->getUploadedFiles()['file'] ?? false;
		$this->processingData();
	}

	private function processingData()
	{
		$this->name = trim($this->name);
		$this->email = trim($this->email);
		$this->task = trim($this->task);
	}

}