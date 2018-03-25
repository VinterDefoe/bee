<?php


namespace App\Models;


use Core\Container\Container;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\UploadedFile;

class Reviews
{
    /**
     * @var \PDO $db
     */
    private $db;

    public function __construct()
    {
        $this->db = Container::getContainer()->get('db');
    }

    public function create($name, $email, $review, $img, $status = 10)
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

    public function read()
    {
        $sql = "SELECT review_name,review_email,review_text,review_date,review_img FROM reviews;";
        $res = $this->db->query($sql);
        return $res->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function addReview()
    {

//        $file = $this->getImg($file);
//        $file->moveTo('upload/132.jpg');
    }

    public function validate(ServerRequestInterface $request)
    {

        $name = trim($request->getParsedBody()['name']);
        $email = trim($request->getParsedBody()['email']);
        $review = trim($request->getParsedBody()['review']);
        /**
         * @var UploadedFile $file
         */
        $file = $request->getUploadedFiles()['file'];

        var_dump($file);
        $error = [];
        if(empty($name)){
            $error['name'] = 'Enter name';
        }
        if(empty($email)){
            $error['email'] = 'Enter email';
        }elseif (!$this->isValidEmail($email)){
            $error['email'] = 'Enter correct email';
        }
        if(empty($review)){
            $error['review'] = 'Enter review';
        }
        if(!empty($file->getSize())){
            if(!$this->isValidImgMediaType($file)){
                $error['img_error'] = 'Wrong file type';
            }
            if($this->isValidImgSize($file)){
                $error['img_error'] = 'Too big file size';
            }
        }
        return $error;
    }
    public function isValidEmail($email)
    {
        return true;
    }

    public function isValidImgSize(UploadedFile $file,$maxSize = 1024000)
    {
        if ($file->getSize() > $maxSize) {
            return false;
        }
        return true;
    }

    public function isValidImgMediaType(UploadedFile $file, $mediaType = ['gif', 'png', 'jpeg'], $maxSize = 1024000)
    {
        $mediaType = array_map(function ($element) {
            return 'image/' . $element;
        }, $mediaType);
        if (!in_array($file->getClientMediaType(), $mediaType)) {
            return false;
        }
        return true;
    }

    public function getImg(UploadedFile $file, $path = 'upload/', $tmp_path = 'tmp/')
    {
        if ($error = $this->validImg($file)) {
            return false;
        }
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