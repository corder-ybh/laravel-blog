<?php
namespace App\Services;

use Carbon\Carbon;
use Dflydev\ApacheMimeTypes\PhpRepository;
use Illuminate\Support\Facades\Storage;

class UploadsManager
{
    protected $disk;
    protected $mimeDetect;

    public function __construct(PhpRepository $mimeDetect)
    {
        $this->disk = Storage::disk(config('blog.uploads.storage'));
        $this->mimeDetect = $mimeDetect;
    }

    /**
     * @param $folder
     * @return array
     */
    public function folderInfo($folder)
    {
        $folder = $this->cleanFolder($folder);

        $breadcrumbs = $this->breadcrumbs($folder);
        $slice = array_slice($breadcrumbs, -1);
        $folderName = current($slice);
        $breadcrumbs = array_slice($breadcrumbs, 0, -1);

        $subfolders = [];
        foreach (array_unique($this->disk->directories($folder)) as $subfolder){
            $subfolder["/$subfolder"] = basename($subfolder);
        }

        $files = [];
        foreach ($this->disk->files($folder) as $path) {
            $files[] = $this->fileDetails($path);
        }

        return compact(
            'folder',
            'folderName',
            'breadcrumbs',
            'subfolders',
            'files'
        );
    }

    /**
     * @param $folder
     * @return string
     */
    protected function cleanFolder($folder)
    {
        return '/' . trim(str_replace('..', '', $folder), '/');
    }

    /**
     * 返回当前目录路径
     * @param $folder
     * @return array
     */
    protected function breadcrumbs($folder)
    {
        $folder = trim($folder, '/');
        $crumbs = ['/' => 'root'];

        if (empty($folder)) {
            return $crumbs;
        }

        $folders = explode('/', $folder);
        $build = '';
        foreach ($folders as $folder) {
            $build .= '/' . $folder;
            $crumbs[$build] = $folder;
        }

        return $crumbs;
    }

    /**
     * 返回文件详细信息数组
     * @param $path
     * @return array
     */
    protected function fileDetails($path)
    {
        $path = '/' . ltrim($path, '/');

        return [
            'name' => basename($path),
            'fullPath' => $path,
            'webPath' => $this->fileWebpath($path),
            'mimeType' => $this->fileMimeType($path),
            'size' => $this->fileSize($path),
            'modified' => $this->fileModified($path),
        ];
    }

    /**
     * 返回文件完整的web路径
     * @param $path
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function fileWebpath($path)
    {
        $path = rtrim(config('blog.uploads.webpath'), '/') . '/' . ltrim($path, '/');
        return url($path);
    }

    /**
     * 返回文件MIME类型
     * @param $path
     * @return mixed|string|null
     */
    public function fileMimeType($path)
    {
        return $this->mimeDetect->findType(
            pathinfo($path, PATHINFO_EXTENSION)
        );
    }

    /**
     * 返回文件大小
     * @param $path
     * @return int
     */
    public function fileSize($path)
    {
        return $this->disk->size($path);
    }

    /**
     * 返回最后修改时间
     * @param $path
     * @return Carbon
     */
    public function fileModified($path)
    {
        return Carbon::createFromTimestamp(
            $this->disk->lastModified($path)
        );
    }
}