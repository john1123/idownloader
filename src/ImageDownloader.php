<?php
namespace John1123\ImageDownloader;

class ImageDownloader
{
    protected $filesToDownload;
    protected $filesLocalDirectory;
    protected $isAllowOverwrite = true;
    protected $allowedExtensions = array(
        'gif', 'jpg', 'png'
    );

    public function __construct($filesLocalDirectory = '', $allowOverwrite = true)
    {
        $this->init($filesLocalDirectory, $allowOverwrite);
    }

    /**
     * Инициализация
     *
     * @param string $filesLocalDirectory - имя локальной папки куда будут загружаться файлы
     * @param bool $isOverwrite - разрешена/запрещена перезапись существующих файлов
     * @throws \Exception
     */
    protected function init($filesLocalDirectory = '', $isOverwrite = true)
    {
        if(function_exists('getimagesize') === false) {
            throw new \Exception('GD extension isn`t enabled');
        }

        $this->setFilesLocalDirectory($filesLocalDirectory);
        if (is_writable($this->filesLocalDirectory) === false) {
            throw new \Exception('Directory `' . $this->filesLocalDirectory . '`. is absent or isn`t writeable');
        }

        $this->setOverwriteMode($isOverwrite);
    }

    /**
     * Функция устанавливает список файлов для загрузки
     *
     * @param array $filesToDownload - массив со списком файлов к загрузке
     * @throws \Exception
     */
    public function setFilesToDownload($filesToDownload)
    {
        if (is_array($filesToDownload)) {
            $this->filesToDownload = $filesToDownload;
        } else {
            throw new \Exception('Wrong paraneter type');
        }
    }

    /**
     * Функция устанавливает имя локальной папки куда будут загружаться файлы
     *
     * @param string $filesLocalDirectory - имя локальной папки куда будут загружаться файлы
     * @throws \Exception
     */
    public function setFilesLocalDirectory($filesLocalDirectory)
    {
        if (is_string($filesLocalDirectory)) {
            $this->filesLocalDirectory = $filesLocalDirectory;
        } else {
            throw new \Exception('Wrong paraneter type');
        }
    }

    /**
     * Функция определяет перезаписываль ли файл, если скачиваемый файл уже есть.
     *
     * @param boolean $isOverwrite - разрешена/запрещена перезапись существующих файлов
     * @throws \Exception
     */
    public function setOverwriteMode($isOverwrite)
    {
        if (is_bool($isOverwrite)) {
            $this->isAllowOverwrite = $isOverwrite;
        } else {
            throw new \Exception('Wrong paraneter type');
        }
    }

    /**
     * Функция загружает файлы
     *
     * @throws \Exception
     */
    public function download($filesToDownload = array())
    {
        if (count($filesToDownload) > 0) {
            $this->setFilesToDownload($filesToDownload);
        }
        if (count($this->filesToDownload) < 1) {
            throw new \Exception('Empty download list');
        }

        $successfullCopiedCounter = 0;
        foreach ($this->filesToDownload as $linkToFile) {
            $pathinfo = pathinfo($linkToFile);
            $filename = $pathinfo['basename'];
            if (in_array($pathinfo['extension'], $this->allowedExtensions) === false) {
                continue;
            }
            //$filename = substr(strrchr($linkToFile, '/'), 1);
            if (file_exists($this->filesLocalDirectory . $filename)) {
                if ($this->isAllowOverwrite === false) {
                    continue;
                }
            }
            $localFilename = $this->filesLocalDirectory . $filename;
            if(copy($linkToFile, $localFilename) === false) {
                continue;
            }
            $info = getimagesize($localFilename);
            if ($info === false/* || in_array($info[2], array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG)) == false */) {
                unlink($localFilename);
                continue;
            }
            $successfullCopiedCounter += 1;
        }

        if ($successfullCopiedCounter < count($this->filesToDownload)) {
            $notDownloaded = count($this->filesToDownload) - $successfullCopiedCounter;
            throw new \Exception('Unable to download ' . $notDownloaded . ' file(s)');
        }
        return true;
    }
}

