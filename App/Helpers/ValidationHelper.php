<?php

namespace App\Helpers;


use Zend\Diactoros\UploadedFile;

trait ValidationHelper
{
	/**
	 * @param UploadedFile $file
	 * @param array $mediaType
	 * @return bool
	 */
	public function isValidImgMediaType(UploadedFile $file, $mediaType = ['gif', 'png', 'jpeg'])
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
	 * @param UploadedFile $file
	 * @param int $maxSize
	 * @return bool
	 */
	public function isValidImgSize(UploadedFile $file,$maxSize = 1024000)
	{
		if ($file->getSize() > $maxSize) {
			return false;
		}
		return true;
	}

	public function isValidEmail($email)
	{
		return true;
	}
}