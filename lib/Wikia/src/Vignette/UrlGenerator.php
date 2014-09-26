<?php
/**
 * UrlGenerator
 *
 * @author Nelson Monterroso <nelson@wikia-inc.com>
 */

namespace Wikia\Vignette;

use Wikia\Logger\Loggable;
use Wikia\Util\Assert;

class UrlGenerator {
	use Loggable;

	const MODE_ORIGINAL = 'original';
	const MODE_THUMBNAIL = 'thumbnail';
	const MODE_THUMBNAIL_DOWN = 'thumbnail-down';
	const MODE_FIXED_ASPECT_RATIO = 'fixed-aspect-ratio';
	const MODE_FIXED_ASPECT_RATIO_DOWN = 'fixed-aspect-ratio-down';
	const MODE_TOP_CROP = 'top-crop';
	const MODE_TOP_CROP_DOWN = 'top-crop-down';
	const MODE_ZOOM_CROP = 'zoom-crop';
	const MODE_ZOOM_CROP_DOWN = 'zoom-crop-down';

	const FORMAT_WEBP = "webp";

	const REVISION_LATEST = 'latest';

	/** @var string mode of the image we're requesting */
	private $mode = self::MODE_ORIGINAL;

	/** @var string revision of the image we're requesting */
	private $revision = self::REVISION_LATEST;

	/** @var int width of the image, in pixels */
	private $width = 100;

	/** @var int height of the image, in pixels */
	private $height = 100;

	/** @var FileInterface file object we're requesting */
	private $file;

	/** @var array hash of query parameters to send to the thumbnailer */
	private $query = [];

	public function __construct(FileInterface $file) {
		$this->file = $file;
		$this->original();
	}

	public function width($width) {
		$this->width = $width;
		return $this;
	}

	public function height($height) {
		$this->height = $height;
		return $this;
	}

	/**
	 * Request a specific revision of an image. This is a no-op in URL generation
	 * if $this->file->isOld() == true. See url() below.
	 *
	 * @param string $revision
	 * @return $this
	 * @throws AssertionException
	 */
	public function revision($revision) {
		Assert::true(
			(!$this->file->isOld() || $revision == $this->file->getArchiveTimestamp()),
			"Error, unable to set the revision number for an archived file."
		);

		if (!empty($revision)) {
			$this->revision = $revision;
		}

		return $this;
	}

	/**
	 * set an image's language
	 * @param string $lang
	 * @return $this
	 */
	public function lang($lang) {
		if (!empty($lang) && $lang != 'en') {
			$this->query['lang'] = $lang;
		}

		return $this;
	}

	/**
	 * Fill the background with a specific color, instead of white
	 * @param string $color color accepted by ImageMagick (http://www.imagemagick.org/script/color.php), or a hex code.
	 * This only applies when $this->mode = self::MODE_FIXED_ASPECT_RATIO
	 * @return $this
	 */
	public function backgroundFill($color) {
		$this->query['fill'] = $color;
		return $this;
	}

	/**
	 * original image
	 * @return $this
	 */
	public function original() {
		return $this->mode(self::MODE_ORIGINAL);
	}

	/**
	 * create a new thumbnail that is allowed to be bigger than the original
	 * @return $this
	 */
	public function thumbnail() {
		return $this->mode(self::MODE_THUMBNAIL);
	}

	/**
	 * create a new thumbnail that is not allowed to be bigger than the original
	 * @return $this
	 */
	public function thumbnailDown() {
		return $this->mode(self::MODE_THUMBNAIL_DOWN);
	}

	/**
	 * zoom crop, allowed to be bigger than the original
	 * @return $this
	 */
	public function zoomCrop() {
		return $this->mode(self::MODE_ZOOM_CROP);
	}

	/**
	 * zoom crop, not allowed to be bigger than the original
	 * @return $this
	 */
	public function zoomCropDown() {
		return $this->mode(self::MODE_ZOOM_CROP_DOWN);
	}

	/**
	 * return an image that is exactly $this->width x $this->height with the source image centered vertically
	 * or horizontally (depending on which side is longer). The background is filled with the value passed to
	 * $this->backgroundFill(), or white if no background value is specified.
	 *
	 * @return $this
	 */
	public function fixedAspectRatio() {
		return $this->mode(self::MODE_FIXED_ASPECT_RATIO);
	}

	/**
	 * return an image that is exactly $this->width x $this->height with the source image centered in the image window.
	 * This mode will not allow the image to enlarge.
	 * @return $this
	 */
	public function fixedAspectRatioDown() {
		return $this->mode(self::MODE_FIXED_ASPECT_RATIO_DOWN);
	}

	/**
	 * top crop, enlargement allowed
	 * @return $this
	 */
	public function topCrop() {
		return $this->mode(self::MODE_TOP_CROP);
	}

	/**
	 * top crop, not allowed to enlarge
	 * @return $this
	 */
	public function topCropDown() {
		return $this->mode(self::MODE_TOP_CROP_DOWN);
	}

	/**
	 * request an image in webp format
	 * @return $this
	 */
	public function webp() {
		return $this->format(self::FORMAT_WEBP);
	}

	/**
	 * get url for thumbnail/image that's been built up so far
	 * @return string
	 * @throws \Exception
	 */
	public function url() {
		$bucketPath = self::bucketPath();

		if ($this->file->isOld()) {
			$this->revision($this->file->getArchiveTimestamp());
		} else {
			if ($this->revision == self::REVISION_LATEST) {
				$this->query['cb'] = $this->file->getTimestamp();
			}
		}


		$imagePath = "{$bucketPath}/{$this->getRelativeUrl()}/revision/{$this->revision}";

		if (!isset($this->query['lang'])) {
			global $wgLang;
			if (isset($wgLang)) {
				$this->lang($wgLang->getCode());
			}
		}

		if ($this->mode != self::MODE_ORIGINAL) {
			$imagePath .= "/{$this->mode}/width/{$this->width}/height/{$this->height}";
		}

		if (!empty($this->query)) {
			$imagePath .= '?'.implode('&', array_map(function($key, $val) {
					return "{$key}=".urlencode($val);
				}, array_keys($this->query), $this->query));
		}

		return self::domainShard($imagePath);
	}

	/**
	 * Get the relative URL for the image. The MW core code uses /archive/<hash>/<archive name>
	 * in to generate the old image path names which won't work with vignette since the
	 * concept of an archive is a function of the revision. This moves the File::getUrlRel
	 * logic here so we have direct control over it.
	 *
	 * @return string the relative url
	 */
	private function getRelativeUrl() {
		return $this->file->getHashPath() . rawurlencode($this->file->getName());
	}

	public function __toString() {
		return $this->url();
	}

	protected function getLoggerContext() {
		return [
			'file' => $this->file->getName(),
		];
	}

	/**
	 * use the thumbnailer in a specific mode
	 *
	 * @param string $mode one of the MODE_ constants defined above
	 * @return $this
	 */
	private function mode($mode) {
		$this->mode = $mode;
		return $this;
	}

	private function format($format) {
		$this->query['format'] = $format;
		return $this;
	}

	private static function domainShard($imagePath) {
		global $wgVignetteUrl, $wgImagesServers;

		$hash = ord(sha1($imagePath));
		$shard = 1 + ($hash % ($wgImagesServers - 1));

		return str_replace('<SHARD>', $shard, $wgVignetteUrl)."/{$imagePath}";
	}

	/**
	 * get the top level bucket for a given wiki. this may or may not be the same as $wgDBName. This is done via
	 * regular expression because there is no variable that contains the bucket name :(
	 * @return mixed
	 */
	private static function bucketPath() {
		global $wgUploadPath;
		preg_match('/http(s?):\/\/(.*?)\/(.*?)\/(.*)$/', $wgUploadPath, $matches);
		return $matches[3];
	}
}
