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
	private $defaultImgPath = '/App/Asserts/img/default.jpg';

	public function __construct()
	{
		$this->db = Container::getContainer()->get('db');
	}

	/**
	 * @param $id
	 * @return bool|mixed
	 */
	public function getTaskText($id)
	{
		$sql = "SELECT task_text,task_id
                FROM tasks
                WHERE task_id = :id;";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':id', $id);
		$res = $stmt->execute();
		if (!$res) return false;
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	/**
	 * @param $id
	 * @param $text
	 * @return bool
	 */
	public function changeTaskText($id,$text)
	{
		$sql = "UPDATE tasks SET task_text = :text
                WHERE task_id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':text', $text);
		$stmt->bindParam(':id', $id);
		return $stmt->execute();
	}
	/**
	 * @return array
	 */
	public function dataForPagination()
	{
		$sql = "SELECT task_id FROM tasks;";
		$res = $this->db->query($sql);
		return $res->fetchAll(\PDO::FETCH_ASSOC);
	}

	/**
	 * @param $currentPage
	 * @param $maxPerPage
	 * @param $sortBy
	 * @return array|bool
	 */
	public function getTask($currentPage, $maxPerPage, $sortBy)
	{
		switch ($sortBy){
			case 'name':
				$sort = 'task_name';
				$type = 'ASC';
				break;
			case 'email':
				$sort = 'task_email';
				$type = 'ASC';
				break;
			case 'status':
				$sort = 'task_status';
				$type = 'ASC';
				break;
			default:
				$sort = 'task_date';
				$type = 'DESC';
		}
		$startLimit = ($currentPage-1)*$maxPerPage;
		$sql = "SELECT * FROM tasks ORDER BY $sort $type LIMIT :start, :end;";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':start', $startLimit);
		$stmt->bindParam(':end', $maxPerPage);
		$res = $stmt->execute();
		if (!$res) return false;
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	/**
	 * 1 = Open Task | 2 = Closed Task
	 * @param $id
	 * @param $status
	 * @return bool
	 */
	public function changeStatus($id, $status)
	{
		$sql = "UPDATE tasks SET task_status = :status WHERE task_id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':status', $status);
		$stmt->bindParam(':id', $id);
		return $stmt->execute();
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

	/**
	 * @return array
	 */
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

	/**
	 * @param ServerRequestInterface $request
	 */
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