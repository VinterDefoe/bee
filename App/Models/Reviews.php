<?php


namespace App\Models;


use App\Helpers\ValidationHelper;
use Core\Container\Container;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\UploadedFile;

class Reviews
{
	use ValidationHelper;
    /**
     * @var \PDO $db
     */
    private $db;

    private $name;
    private $email;
    private $review;
	/**
	 * @var UploadedFile $file
	 */
    private $file;
    private $status = 10;

    private $uploadImgPath = 'upload/';

    public function __construct()
    {
        $this->db = Container::getContainer()->get('db');
    }

    public function read()
    {
        $sql = "SELECT review_name,review_email,review_text,review_date,review_img FROM reviews;";
        $res = $this->db->query($sql);
        return $res->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return bool
     */
    public function addReview()
    {
        $img = $this->getImgUrl();
       return $this->create($this->name,$this->email,$this->review,$img,$this->status);
    }

	/**
	 * Load Date from $request
	 * @param ServerRequestInterface $request
	 */
	public function loadDate(ServerRequestInterface $request)
	{
		$this->name = trim($request->getParsedBody()['name']);
		$this->email = trim($request->getParsedBody()['email']);
		$this->review = trim($request->getParsedBody()['review']);
		$this->file = $request->getUploadedFiles()['file'];
	}

	/**
     * Validate Data from $request
	 * Return errors messages
	 * @return array
	 */
	public function validate()
	{
		$error = [];
		if(empty($this->name)){
			$error['name'] = 'Enter name';
		}
		if(empty($this->email)){
			$error['email'] = 'Enter email';
		}elseif (!$this->isValidEmail($this->email)){
			$error['email'] = 'Enter correct email';
		}
		if(empty($this->review)){
			$error['review'] = 'Enter review';
		}
		if(!empty($this->file->getSize())){
			if(!$this->isValidImgMediaType($this->file)){
				$error['img_error'] = 'Wrong file type';
			}elseif (!$this->isValidImgSize($this->file)){
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
     * @return string
     */
	private function getImgUrl()
    {
        if(!$this->file->getSize()){
            return $src = '';
        }
        $file = $this->getImg($this->file);
        $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
        $fileName = uniqid();
        $src = $this->uploadImgPath.$fileName.'.'.$extension;
        $file->moveTo($src);
        return $src;
    }
	/**
	 * Return Image with optimizing Width and Height
	 * @param UploadedFile $file
	 * @return bool|UploadedFile
	 */
    private function getImg(UploadedFile $file)
    {
	    if(!$this->isValidImgMediaType($file)) return false;
	    if(!$this->isValidImgSize($file)) return false;

        $tmp = ($file->getStream())->getMetadata()['uri'];

        if ($file->getClientMediaType() === 'image/jpeg') {
            $source = imagecreatefromjpeg($tmp);
        } elseif ($file->getClientMediaType() === 'image/png') {
            $source = imagecreatefrompng($tmp);
        } elseif ($file->getClientMediaType() === 'image/gif') {
            $source = imagecreatefromgif($tmp);
        } else {
            return false;
        }
        $widthImg = imagesx($source);
        $heightImg = imagesy($source);

        $size = $this->resizeImg($widthImg, $heightImg);

        $dest = imagecreatetruecolor($size['width'], $size['height']);

        imagecopyresampled($dest, $source, 0, 0, 0, 0,
            $size['width'], $size['height'], $widthImg, $heightImg);
        imagejpeg($dest, $tmp);
        imagedestroy($dest);
        imagedestroy($source);

        return $file;
    }

	/**
	 * Return optimize Width and Height
	 * @param $srcWidth
	 * @param $srcHeight
	 * @param int $maxWidth
	 * @param int $maxHeight
	 * @return array
	 */
    private function resizeImg($srcWidth, $srcHeight, $maxWidth = 320, $maxHeight = 240)
    {
        if ($srcWidth < $maxWidth && $srcHeight < $maxHeight) {
            return ['width' => $srcWidth, 'height' => $srcHeight];
        }
        $ratio = [$maxWidth / $srcWidth, $maxHeight / $srcHeight];
        $ratio = min($ratio[0], $ratio[1]);
        return ['width' => $srcWidth * $ratio, 'height' => $srcHeight * $ratio];
    }
}