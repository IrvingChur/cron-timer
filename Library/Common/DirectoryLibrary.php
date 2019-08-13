<?php

namespace Library\Common;


class DirectoryLibrary
{
    /**
     * @describe 获取目录下所有文件
     * @param $directoryPath string 文件目录
     * @return array
     */
    public static function foreachDirectory(string $directoryPath)
    {
        $arrFiles = [];

        $sourDirectory = opendir($directoryPath);

        while (false !== $strFile = readdir($sourDirectory)) {
            if ($strFile == '.' || $strFile == '..') {
                continue;
            }

            $strFileDetails = $directoryPath . DIRECTORY_SEPARATOR . $strFile;

            if (is_dir($strFileDetails)) {
                $arrFiles = array_merge($arrFiles, self::foreachDirectory($strFileDetails));
            } else {
                $arrFiles[] = $strFileDetails;
            }
        }

        closedir($sourDirectory);

        return $arrFiles;
    }
}