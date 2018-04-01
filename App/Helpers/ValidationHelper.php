<?php

namespace App\Helpers;


use Psr\Http\Message\UploadedFileInterface;

trait ValidationHelper
{
	/**
	 * @param UploadedFileInterface $file
	 * @param array $mediaType
	 * @return bool
	 */
	public function isValidImgMediaType(UploadedFileInterface $file, $mediaType = ['gif', 'png', 'jpeg'])
	{
		$mediaType = array_map(function ($element) {
			return 'image/' . $element;
		}, $mediaType);
		if (!in_array($file->getClientMediaType(), $mediaType)) {
			return false;
		}
		return true;
	}

	/**
	 * @param UploadedFileInterface $file
	 * @param int $maxSize
	 * @return bool
	 */
	public function isValidImgSize(UploadedFileInterface $file, $maxSize = 1024000)
	{
		if ($file->getSize() > $maxSize) {
			return false;
		}
		return true;
	}

	public function isValidEmail($email)
	{
		$pattern = "/@/";
		return preg_match($pattern, $email) ? true : false;
	}
}