<?php
declare(strict_types=1);

/**
 * K010-1: Uploads new image to the media gallery
 */

namespace HanStudio\EventPageBuilder\Model;

use Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\UrlInterface;

class ImageUploader
{

    public const URL_REPLACEMENT = "https://hayward.dev/media";
    public const URL_SEARCH = "/media/.renditions";
    public const BASE_TMP_PATH = "wysiwyg/eventpagebuilder/";
    public const BASE_PATH = "wysiwyg/eventpagebuilder/";
    public const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    /**
     * @var string
     */
    public $baseTmpPath;
    /**
     * @var string
     */
    public $basePath;
    /**
     * @var string[]
     */
    public $allowedExtensions;
    /**
     * @var WriteInterface
     */
    private $mediaDirectory;

    /**
     * ImageUploader constructor.
     * @param \Magento\MediaStorage\Helper\File\Storage\Database $coreFileStorageDatabase
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Psr\Log\LoggerInterface $logger
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        private \Magento\MediaStorage\Helper\File\Storage\Database $coreFileStorageDatabase,
        private \Magento\Framework\Filesystem                      $filesystem,
        private \Magento\MediaStorage\Model\File\UploaderFactory   $uploaderFactory,
        private \Magento\Store\Model\StoreManagerInterface         $storeManager,
        private \Psr\Log\LoggerInterface                           $logger
    ) {
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->baseTmpPath = self::BASE_TMP_PATH;
        $this->basePath = self::BASE_PATH;
        $this->allowedExtensions = self::ALLOWED_EXTENSIONS;
    }

    /**
     * Move file from tmp and create new file
     *
     * @param string $imageName
     * @return string
     * @throws LocalizedException
     */
    public function moveFileFromTmp(string $imageName): string
    {
        $baseTmpPath = $this->getBaseTmpPath();
        $basePath = $this->getBasePath();
        $baseImagePath = $this->getFilePath($basePath, $imageName);
        $baseTmpImagePath = $this->getFilePath($baseTmpPath, $imageName);
        try {
            $this->coreFileStorageDatabase->copyFile(
                $baseTmpImagePath,
                $baseImagePath
            );
            $this->mediaDirectory->renameFile(
                $baseTmpImagePath,
                $baseImagePath
            );
        } catch (Exception $e) {
            throw new LocalizedException(
                __('Something went wrong while saving the file(s).')
            );
        }
        return $imageName;
    }

    /**
     * Get base tmp path
     *
     * @return string
     */
    public function getBaseTmpPath(): string
    {
        return $this->baseTmpPath;
    }

    /**
     * Set base tmp path
     *
     * @param string $baseTmpPath
     * @return void
     */
    public function setBaseTmpPath(string $baseTmpPath): void
    {
        $this->baseTmpPath = $baseTmpPath;
    }

    /**
     * Get base path
     *
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * Set base path
     *
     * @param string $basePath
     * @return void
     */
    public function setBasePath(string $basePath): void
    {
        $this->basePath = $basePath;
    }

    /**
     * Get file path
     *
     * @param string $path
     * @param string $imageName
     * @return string
     */
    public function getFilePath(string $path, string $imageName): string
    {
        return rtrim($path, '/') . '/' . ltrim($imageName, '/');
    }

    /**
     * Save file to temporary directory
     *
     * @param string $fileId
     * @return mixed
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    //    This could receive eventId from the Upload controller
    public function saveFileToTmpDir(string $fileId): array
    {
        $baseTmpPath = $this->getBaseTmpPath();
        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
        $uploader->setAllowedExtensions($this->getAllowedExtensions());
        $uploader->setAllowRenameFiles(true);
        $result = $uploader->save($this->mediaDirectory->getAbsolutePath($baseTmpPath));
        if (!$result) {
            throw new LocalizedException(
                __('File can not be saved to the destination folder.')
            );
        }

        $result['tmp_name'] = str_replace('\\', '/', $result['tmp_name']);
        $result['path'] = str_replace('\\', '/', $result['path']);
        $result['url'] = $this->storeManager
                ->getStore()
                ->getBaseUrl(
                    UrlInterface::URL_TYPE_MEDIA
                ) . $this->getFilePath($baseTmpPath, $result['file']);
        $result['name'] = $result['file'];
        if (isset($result['file'])) {
            try {
                $relativePath = rtrim($baseTmpPath, '/') . '/' . ltrim($result['file'], '/');
                $this->coreFileStorageDatabase->saveFile($relativePath);
            } catch (Exception $e) {
                $this->logger->critical($e);
                throw new LocalizedException(
                    __('Something went wrong while saving the file(s).')
                );
            }
        }
        return $result;
    }

    /**
     * Get allowed extensions
     *
     * @return string[]
     */
    public function getAllowedExtensions(): array
    {
        return $this->allowedExtensions;
    }

    /**
     * Set allowed extensions
     *
     * @param array $allowedExtensions
     */
    public function setAllowedExtensions(array $allowedExtensions): void
    {
        $this->allowedExtensions = $allowedExtensions;
    }

    /**
     * Get formatted URL
     *
     * @param string $url
     * @return string
     */
    public function getFormattedUrl(string $url): string
    {
        if (str_contains($url, self::URL_SEARCH) !== false) {
            return str_replace(self::URL_SEARCH, self::URL_REPLACEMENT, $url);
        } else {
            return $url;
        }
    }

    /**
     * Save media image
     *
     * @param string $imageName
     * @param string $imagePath
     * @return string
     * @throws LocalizedException
     */
    public function saveMediaImage(string $imageName, string $imagePath): string
    {
        $baseTmpPath = $this->getBaseTmpPath();
        $basePath = $this->getBasePath();
        $baseImagePath = $this->getFilePath($basePath, $imageName);
        $mediaPath = substr($imagePath, 0, strpos($imagePath, "media"));
        $baseTmpImagePath = str_replace($mediaPath . "media/", "", $imagePath);
        if ($baseImagePath == $baseTmpImagePath) {
            return $imageName;
        }
        try {
            $this->mediaDirectory->copyFile(
                $baseTmpImagePath,
                $baseImagePath
            );
        } catch (Exception $e) {
            throw new LocalizedException(
                __('Something went wrong while saving the file(s).')
            );
        }
        return $imageName;
    }

    /**
     * Set image data
     *
     * @param Speaker $speaker
     * @param array $data
     * @return Speaker
     * @throws LocalizedException
     */
    public function setImageData(Speaker $speaker, array $data): Speaker
    {
        if (!isset($data['image'][0])) {
            return $speaker;
        }

        if ($speaker->getData('speaker_id')) {
            if (isset($data['image'][0]['name'])) {
                $imageName1 = $speaker->getThumbnail();
                $imageName2 = $data['image'][0]['name'];
                if ($imageName1 != $imageName2) {
                    $imageName = $data['image'][0]['name'];
                    $data['image'][0]['name'] = $this->saveMediaImage($imageName, $data['image'][0]['url']);
//                    All image is saved with full URL
                    $data['image'][0]['url'] = $this->getFormattedUrl($data['image'][0]['url']);
                }
            } else {
                $data['image'] = '';
            }
        } else {
            if (isset($data['image'][0]['name'])) {
                $imageName = $data['image'][0]['name'];
                $data['image'][0]['name'] = $this->saveMediaImage($imageName, $data['image'][0]['url']);
//                    All image is saved with full URL
                $data['image'][0]['url'] = $this->getFormattedUrl($data['image'][0]['url']);
            }
        }
        $data['image'] = json_encode($data['image'][0]);
        $speaker->setData($data);
        return $speaker;
    }
}
