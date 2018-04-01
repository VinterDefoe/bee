<?php


namespace App\Helpers;


use Psr\Http\Message\UploadedFileInterface;

trait ImageHelper
{
	use ValidationHelper;

	/**
	 * @param UploadedFileInterface $file
	 * @param $uploadPath
	 * @param $maxWidth
	 * @param $maxHeight
	 * @return bool|string
	 */
	private function getImgSrc(UploadedFileInterface $file, $uploadPath, $maxWidth, $maxHeight)
	{

		$file = $this->getImg($file, $maxWidth, $maxHeight);
		if (!$file) {
			return false;
		}
		$extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
		$src = $uploadPath . uniqid() . '.' . $extension;
		$file->moveTo($src);
		return '/' . $src;
	}

	/**
	 * Return Image with optimizing Width and Height
	 * @param UploadedFileInterface $file
	 * @param $maxWidth
	 * @param $maxHeight
	 * @return bool|UploadedFileInterface
	 */
	private function getImg(UploadedFileInterface $file, $maxWidth, $maxHeight)
	{
		if (!$file->getSize() || !$this->isValidImgMediaType($file) || !$this->isValidImgSize($file)) {
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

		$size = $this->resizeImg($widthImg, $heightImg, $maxWidth, $maxHeight);

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
	private function resizeImg($srcWidth, $srcHeight, $maxWidth, $maxHeight)
	{
		if ($srcWidth < $maxWidth && $srcHeight < $maxHeight) {
			return ['width' => $srcWidth, 'height' => $srcHeight];
		}
		$ratio = [$maxWidth / $srcWidth, $maxHeight / $srcHeight];
		$ratio = min($ratio[0], $ratio[1]);
		return ['width' => $srcWidth * $ratio, 'height' => $srcHeight * $ratio];
	}
}