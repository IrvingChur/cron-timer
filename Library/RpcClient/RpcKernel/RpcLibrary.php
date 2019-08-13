<?php

namespace Library\RpcClient\RpcKernel;


class RpcLibrary
{
    const HEADER_SIZE           = 16;
    const HEADER_STRUCT         = "Nlength/Ntype/Nuid/Nserid";
    const HEADER_PACK           = "NNNN";

    const DECODE_PHP            = 1;   // 使用PHP的serialize打包
    const DECODE_JSON           = 2;   // 使用json_encode打包
    const DECODE_MSGPACK        = 3;   // 使用msgpack打包
    const DECODE_GZIP           = 128; // 启用GZIP压缩

    /**
     * @describe 打包数据
     * @param array $data 数据
     * @param int $type 打包类型
     * @param int $uid uid
     * @param int $serid serid
     * @return string
     */
    public static function encode($data, $type = self::DECODE_PHP, $uid = 0, $serid = 0)
    {
        // 启用压缩
        if ($type & self::DECODE_GZIP) {
            $intType = $type & ~self::DECODE_GZIP;
            $isGzipCompress = true;
        }
        else {
            $isGzipCompress = false;
            $intType = $type;
        }

        switch($intType) {
            case self::DECODE_JSON:
                $strBody = json_encode($data);
                break;
            case self::DECODE_PHP:
            default:
                $strBody = serialize($data);
                break;
        }
        if ($isGzipCompress) {
            $strBody = gzencode($strBody);
        }
        return pack(self::HEADER_PACK, strlen($strBody), $type, $uid, $serid) . $strBody;
    }

    /**
     * @describe 解析数据
     * @param string $content 数据内容
     * @return string
     */
    public static function decode(string $content)
    {
        $strResponseLength = reset(unpack(RpcLibrary::HEADER_PACK, $content));
        $strResponseBody   = substr($content, - $strResponseLength);

        return $strResponseBody;
    }
}