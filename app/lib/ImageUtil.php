<?php
use Intervention\Image\Facades\Image as IntImage;

class ImageUtil extends \Eloquent {
    private $file;
    private $image;

    function __construct($file) {
		$this->file = $file;
        $this->image = IntImage::make($file)->orientate();
    }

    public function resize($width = 600, $height = 600) {
        if($this->image->width() > $this->image->height()) {
            $this->image->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        } else {
            $this->image->resize(null, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        return $this;
    }

	public function resize2($type) {
		switch ($type) {
			case 'thumbnail':
				if ($this->getOrientation() == 'landscape')
					$this->image->resize(340, null, function($constraint) { //680
						$constraint->aspectRatio();
						$constraint->upsize();
					});
				else
					$this->image->resize(null, 400, function($c) { // 800
						$c->aspectRatio();
						$c->upsize();
					});
				break;
			case 'gallery':
				if ($this->getOrientation() == 'landscape' && $this->getHeight() >= 960)
					$this->image->resize(null, 960, function($constraint) {
						$constraint->aspectRatio();
						$constraint->upsize();
					});
				elseif ($this->getOrientation() == 'portrait' && $this->getWidth() >= 1024)
					$this->image->resize(1024, null, function($c) {
						$c->aspectRatio();
						$c->upsize();
					});
				break;
			case 'avatar.thumbnail':
				$this->image->fit(80);
				break;
			case 'avatar.profile':
				$this->image->fit(120);
				break;
			case 'avatar.gallery':
				if ($this->getOrientation() == 'landscape')
					$this->image->resize(null, 960, function($constraint) {
						$constraint->aspectRatio();
						$constraint->upsize();
					});
				else
					$this->image->resize(1024, null, function($c) {
						$c->aspectRatio();
						$c->upsize();
					});
				break;
		}
		return $this;
	}

    public function getFile() {
        return $this->file;
    }

    public function getImage() {
        return $this->image;
    }

    public function getWidth() {
        return $this->image->width();
    }

    public function getHeight() {
        return $this->image->height();
    }

    public function getOrientation() {
		if ($this->image->width() >= $this->image->height()) return 'landscape';
		else return 'portrait';
	}
}