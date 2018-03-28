<?php


namespace App\Models;


use App\Helpers\ImageHelper;
use App\Helpers\UserHelper;
use Core\Container\Container;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\UploadedFile;

class Reviews
{
    use UserHelper;
    use ImageHelper;
    /**
     * @var \PDO $db
     */
    private $db;

    private $name;
    private $email;
    private $review;
    private $id;
    private $change;
    /**
     * @var UploadedFile $file
     */
    private $file;
    private $status = 10;

    private $uploadImgPath = 'upload/';
    private $defaultImgSrc = '/App/Asserts/img/default.jpg';

    /**
     * Reviews constructor.
     */
    public function __construct()
    {
        $this->db = Container::getContainer()->get('db');
    }

    /**
     * @param $id
     * @return bool|mixed
     */
    public function getReview($id)
    {
        $sql = "SELECT review_id,review_name,review_email,
                       review_text,review_date,review_img,
                       review_status,review_change
                FROM reviews
                WHERE review_id = :id;";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $res = $stmt->execute();
        if (!$res) return false;
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @return array
     */
    public function read()
    {
        $sql = "SELECT review_id,review_name,review_email,
                       review_text,review_date,review_img,
                       review_status,review_change
                FROM reviews;";
        $res = $this->db->query($sql);
        return $res->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return bool
     */
    public function addReview()
    {
        if(!$this->file || $this->hasValidationError()) return false;
        $img = $this->getImgSrc($this->file, $this->uploadImgPath, 320, 240);
        if(!$img){$img = $this->defaultImgSrc;}
        return $this->create($this->name, $this->email, $this->review, $img, $this->status);
    }

    /**
     * @return bool
     */
    public function changeReview()
    {
        if(!$this->id || !$this->file || $this->hasValidationError()) return false;
        $img = $this->getImgSrc($this->file, $this->uploadImgPath, 320, 240);
        if($img){
            $sql = "UPDATE reviews SET review_name = :name,review_email = :email,
                                       review_text = :text,review_img = :img,
                                       review_change = :change
                    WHERE review_id = :id";
        }else{
            $sql = "UPDATE reviews SET review_name = :name,review_email = :email,
                                       review_text = :text,review_change = :change
                    WHERE review_id = :id";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':text', $this->review);
        $stmt->bindParam(':change', $this->change);
        $stmt->bindParam(':id', $this->id);
        if($img){
            $stmt->bindParam(':img', $img);
        }
        return $stmt->execute();
    }

    /**
     * Validate Data from $request
     * Return errors array
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
        if (empty($this->review)) {
            $error['review'] = 'Enter review';
        }
        if (!empty($this->file->getSize())) {
            if (!$this->isValidImgMediaType($this->file)) {
                $error['img_error'] = 'Wrong file type';
            } elseif (!$this->isValidImgSize($this->file)) {
                $error['img_error'] = 'Too big file size';
            }
        }
        return $error;
    }

    /**
     * @param $name
     * @param $email
     * @param $review
     * @param $img
     * @param int $status
     * @return bool
     */
    private function create($name, $email, $review, $img, $status = 10)
    {
        $date = (new \DateTime())->getTimestamp();
        $sql = "INSERT INTO reviews (review_name,
                                     review_email,
                                     review_text,
                                     review_date,
                                     review_img,
                                     review_status)
                VALUES (:name,:email,:review,:date,:img,:status);";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':review', $review);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':img', $img);
        $stmt->bindParam(':status', $status);

        return $stmt->execute();
    }

    /**
     * Load Date from $request
     * @param ServerRequestInterface $request
     */
    public function loadDate(ServerRequestInterface $request)
    {
        $user = $this->getUserAttributes($request);
        $this->change = $user['userName'] ?? false;
        $this->name = $request->getParsedBody()['name'] ?? false;
        $this->email = $request->getParsedBody()['email'] ?? false;
        $this->review = $request->getParsedBody()['review'] ?? false;
        $this->id = $request->getParsedBody()['id'] ?? false;
        $this->file = $request->getUploadedFiles()['file'] ?? false;
        $this->processingDate();
    }

    /**
     * data processing
     */
    private function processingDate()
    {
        $this->name = trim($this->name);
        $this->email = trim($this->email);
        $this->review = trim($this->review);
    }

}