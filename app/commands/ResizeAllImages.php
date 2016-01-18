<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ResizeAllImages extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'images:resize';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Resize all images on amazon';

	private $s3;
	private $prefix = 'https://yolanothertest.s3-us-west-2.amazonaws.com/';
	private $quality = 80;
	private $image;
	private $cursor = 0;
	private $count = 0;

	/**
	 * Create a new command instance.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->s3 = AWS::get('s3');
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire() {
		$this->count = Image::count();
		Image::chunk(200, function($images) {
			foreach ($images as $image) {
				$this->line("Added to queue {$image->id}");
				Queue::push('ImagesResizeQueue', $image);
			}
		});
	}
				/*$this->line(memory_get_usage(true));

				$this->line('Init image');
				try {
					$this->line($image->id);
					$this->image = new ImageUtil($image->origin);
				} catch (Exception $e) {
					$this->error($e->getMessage());
					continue;
				}

				if ($this->image) {

					$this->line('Resize image thumb');
					$this->image->resize2('thumbnail');

					$image->thumbnail = $this->uploadImage($this->image->getImage());
					$this->image->getImage()->destroy();
					$this->preventMemoryLeak();

					$this->line('Resize gallery');
					$this->image = new ImageUtil($image->origin);
					$this->image->resize2('gallery');
					$image->regular = $this->uploadImage($this->image->getImage());
					$image->width = $this->image->getWidth();
					$image->height = $this->image->getHeight();
					$this->image->getImage()->destroy();
					$this->preventMemoryLeak();


					if ($image->save()) {
						$this->cursor += 1;
						$this->info("Progress: ".round($this->cursor/$this->count * 100, 2).'%');
						$this->line(memory_get_usage(true));

						$this->preventMemoryLeak();
					}
				}
			}

		});*/
		// consider parralelization
		// http://docs.aws.amazon.com/aws-sdk-php/guide/latest/feature-commands.html
//	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
//			array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
//			['width', null, InputOption::VALUE_REQUIRED, 'The width of image', null],
//			['height', null, InputOption::VALUE_REQUIRED, 'The height of image', null]
		];
	}

	/**
	 * @param $image
	 * @param $s3
	 * @param $name
	 * @param $resultImages
	 * @return array
	 */
	private function uploadImage($image) {
		try {
			$uploadedImage = $this->s3->putObject([
				'ContentType' => $image->mime(),
				'ACL' => 'public-read',
				'Bucket' => 'yolanothertest',
				'Key' => str_random(40).'.jpg',
				'Body' => $image->encode('jpg', $this->quality)
			]);
		} catch (\Aws\S3\Exception\S3Exception $e) {
			$this->error($e->getMessage());
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
