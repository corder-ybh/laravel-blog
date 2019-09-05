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

/**
 * 用于在视图的复选框和单选框中设置 checked 属性
 * @param $value
 * @return string
 */
function checked($value)
{
    return $value ? 'checked' : "";
}

/**
 * 用于返回上传图片的完整路径
 * @param null $value
 * @return \Illuminate\Config\Repository|mixed|string|null
 */
function page_image($value = null)
{
    if (empty($value)) {
        $value = config('blog.page_image');
    }
    if (!starts_with($value, 'http') && $value[0] !== '/') {
        $value = config('blog.uploads.webpath') . '/' . $value;
    }
    return $value;
}