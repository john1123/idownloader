<?php
namespace John1123\ImageDownloader;

class ImageDownloader
{
    const ALLOW_OWERWRITING = true;
    protected $filesToDownload;
    protected $filesLocalDirectory = './files/';
    protected $allowedExtenstions = array(
        'jpg', 'png', 'gif',
    );

    public function __construct($filesToDownload = array())
    {
        $this->setFilesToDownload($filesToDownload);
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
     * Функция загружает файлы
     *
     * @throws \Exception
     */
    public function download()
    {
        if (count($this->filesToDownload) < 1) {
            throw new \Exception('Empty download list');
        }
        if (is_writable($this->filesLocalDirectory)  == false) {
            throw new \Exception('Directory `' . $this->filesLocalDirectory . '` isn`t writeable');
        }
        //
        $notCopiedCounter = 0;
        foreach ($this->filesToDownload as $linkToFile) {
            $filename = substr(strrchr($linkToFile, '/'), 1);
            if (file_exists($this->filesLocalDirectory . $filename)) {
                if (self::ALLOW_OWERWRITING == false) {
                    $notCopiedCounter += 1;
                    continue;
                }
            }
            if(copy($linkToFile, $this->filesLocalDirectory . $filename) == false) {
                $notCopiedCounter += 1;
            }
        }

        if ($notCopiedCounter > 0) {
            throw new \Exception('Unable to download ' . $notCopiedCounter . 'file(s)');
        }
        return true;
        //
    }
}

