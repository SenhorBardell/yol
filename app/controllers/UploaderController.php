<?php

use \Aws\S3\Exception\S3Exception as S3Exception;
use Intervention\Image\Facades\Image as IntImage;
use Helpers\Transformers\CollectionTransformer;

class UploaderController extends ApiController {

	/**
	 * @var Helpers\Transformers\CollectionTransformer
	 */
	protected $collectionTransformer;
	protected $file;
	public $s3;

	function __construct(CollectionTransformer $collectionTransformer) {
		$this->collectionTransformer = $collectionTransformer;
		$this->s3 = AWS::get('s3');
//		ini_set('memory_limit', '256M');
	}

	public function upload() {
		if (!Input::hasFile('image'))
			return $this->respondNotFound('Image not found');

		$this->log('Started');
		$this->log(memory_get_usage(true));
		$attachment = new Image();
		$this->file = Input::file('image');

		$this->log('Init origin image');
		$image = new ImageUtil($this->file);
		$this->log(memory_get_usage(true));

		$this->log('Uploading origin image');
		$this->log(memory_get_usage(true));

		$attachment->origin = $this->uploadImage2($image->getImage());

		$this->log(memory_get_usage(true));
//		preventMemoryLeak();
		$this->log('Garbage collector');
		$this->log(memory_get_usage(true));

		$this->log('Gallery image upload');
		$attachment->regular = $this->uploadImage2($image->resize2('gallery')->getImage());
		$this->log(memory_get_usage(true));

		$this->log('Destroying gallery image');
		$image->getImage()->destroy();
		$image = null;

		$this->log(memory_get_usage(true));
//		preventMemoryLeak();
		$this->log('Garbage collector');
		$this->log(memory_get_usage(true));

		$this->log('Init thumbnail image');
		$thumb = new ImageUtil($this->file);
		$thumb->resize2('thumbnail');
		$this->log(memory_get_usage(true));

		$this->log('uploading thumbnail image');

		$attachment->thumbnail = $this->uploadImage2($thumb->getImage());

		$this->log(memory_get_usage(true));
//		preventMemoryLeak();
		$this->log('Garbage collector');
		$this->log(memory_get_usage(true));

		$attachment->width = $thumb->getWidth();
		$attachment->height = $thumb->getHeight();
		$this->log('Destroying the thumb image');
		$thumb->getImage()->destroy();
		$thumb = null;
		$this->log(memory_get_usage(true));
		$attachment->save();

		return $this->respond($this->collectionTransformer->transformImage($attachment));
	}

	/*
	 * This function is leaking memory like a bitch leaking sweat
	 */
	private function uploadImage2($image) {
		$name = str_random(40).'.jpg';
		try {
			$uploadedImage = $this->s3->putObject([
				'ContentType' => $image->mime(),
				'ACL' => 'public-read',
				'Bucket' => S3_PUBLIC_BUCKET_NAME,
				'Key' => $name,
				'Body' => $image->encode('jpg', IMAGE_COMPRESSING_QUALITY)
			]);
		} catch (S3Exception $e) {
//			dd($e->getMessage());
			return null;
		}

//		return $uploadedImage->get('ObjectURL');
		return $name;
	}

	private function log($msg) {
		Log::debug($msg, ['upld']);
	}

}