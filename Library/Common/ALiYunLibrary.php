<?php

namespace Library\Common;

use Configure\SystemConfigure;
use OSS\OssClient;

class ALiYunLibrary
{
    /**
     * 获取OSSClient
     * @return OssClient
     */
    protected static function getOSSClient()
    {
        $accessKeyId     = ConfigureLibrary::getConfigure(SystemConfigure::class)['aliyun']['oss_access_key'];
        $accessKeySecret = ConfigureLibrary::getConfigure(SystemConfigure::class)['aliyun']['oss_access_secret'];
        $endpoint        = 'oss-cn-hangzhou.aliyuncs.com';
        $ossClient       = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
        return $ossClient;
    }

    /**
     * 上传文件到OSS
     * @param $bucket   由运维提供
     * @param $object   文件名使用日期格式或目录+日期格式,如 {Ymd}/{His}{r2}.{ext}, {dirname}/{Ymd}/{His}{r2}.{ext} {r2}是两位随机数
     * @param $fileName 上传文件路径
     * @return null
     */
    public static function uploadFile($bucket, $object, $fileName)
    {
        $client = static::getOSSClient();
        $result = $client->uploadFile($bucket, $object, $fileName);
        return $result;
    }

    /**
     * 上传文件内容到OSS
     * @param $bucket  由运维提供
     * @param $object  文件名使用日期格式或目录+日期格式,如 {Ymd}/{His}{r2}.{ext}, {dirname}/{Ymd}/{His}{r2}.{ext} {r2}是两位随机数
     * @param $content 上传文件内容
     * @return null
     */
    public static function uploadContent($bucket, $object, $content)
    {
        $client = static::getOSSClient();
        $result = $client->putObject($bucket, $object, $content);
        return $result;
    }
}