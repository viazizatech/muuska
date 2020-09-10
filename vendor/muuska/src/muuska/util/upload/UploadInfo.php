<?php
namespace muuska\util\upload;

interface UploadInfo{
	public function getId();
	public function getUniqueFileName();
	public function getFileName();
	public function getObjectType();
	public function getOriginalFileName();
	public function isUsed();
	public function getCreationDate();
	public function getLastModifiedDate();
}