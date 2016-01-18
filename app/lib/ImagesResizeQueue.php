<?php

class ImagesResizeQueue {

	/**
	 * Function that gets called on a job
	 * Push::queue('smsSender', $emergency);
	 *
	 * @param $job
	 * @param Emergency $emergency
	 */
	public function fire($job, $image) {
		$s3 = AWS::get('s3');

		$image = Image::find($image['id']);

		if (!$image) return $job->delete();

		try {
			$imageUtil = new ImageUtil($image->origin);
			$galleryImageUtil = new ImageUtil($image->origin);
		} catch(Exception $e) {
			return $job->delete();
		}

		$image->thumbnail = $this->uploadImage($s3, $imageUtil->resize2('thumbnail')->getImage());
		Log::debug("From queue: Thumbnail: width - {$imageUtil->getWidth()}, height - {$imageUtil->getHeight()}");
		Log::debug("Thumbnail URL: {$image->thumbnail}");
		$this->preventMemoryLeak();

		$image->regular = $this->uploadImage($s3, $galleryImageUtil->resize2('gallery')->getImage());
		Log::debug("From queue: Gallery: width - {$galleryImageUtil->getWidth()}, height - {$galleryImageUtil->getHeight()}");
		Log::debug("Gallery URL: {$image->regular}");
		$this->preventMemoryLeak();

		$image->width = $galleryImageUtil->getWidth();
		$image->height = $galleryImageUtil->getHeight();

		$image->save();

		return $job->delete();
	}


	/**
	 * @param $image
	 * @param $s3
	 * @param $name
	 * @param $resultImages
	 * @return array
	 */
	private function uploadImage($s3, $image) {
		try {
			$uploadedImage = $s3->putObject([
				'ContentType' => $image->mime(),
				'ACL' => 'public-read',
				'Bucket' => 'yolanothertest',
				'Key' => str_random(40).'.jpg',
				'Body' => $image->encode('jpg', 80)
			]);
		} catch (\Aws\S3\Exception\S3Exception $e) {
//			$this->error($e->getMessage());
			return false;
		}

		return $uploadedImage->get('ObjectURL');
	}

	private function preventMemoryLeak() {
		gc_enable();
		gc_collect_cycles();
		gc_disable();
	}

}

