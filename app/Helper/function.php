<?php

/**
 * 返回可读性更好的文件尺寸
 * @param $bytes
 * @param int $decimals
 */
function human_filesize($bytes, $decimals = 2)
{
    $size = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    $factor = floor((strlen($bytes) - 1) / 3);

    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}

/**
 * 判断文件的MIME类型是否为图片
 * @param $mimeType
 * @return bool
 */
function is_image($mimeType)
{
    return starts_with($mimeType, 'image/');
}