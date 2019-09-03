<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UploadFileRequest;
use App\Http\Requests\UploadNewFolderRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\UploadsManager;

class UploadController extends Controller
{
    protected $manager;

    public function __construct(UploadsManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * 显示文件列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $folder = $request->get('folder');
        $data = $this->manager->folderInfo($folder);

        return view('admin.upload.index', $data);
    }

    /**
     * 创建新目录
     * @param UploadNewFolderRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createFolder(UploadNewFolderRequest $request)
    {
        $new_folder = $request->get('new_folder');
        $folder = $request->get('folder') . '/' . $new_folder;

        $result = $this->manager->creageDirectory($folder);

        if ($result == true) {
            return redirect()
                ->back()
                ->with('success', '目录[' . $new_folder . ']创建成功.');
        }

        $error = $result ?: "创建目录出错.";
        return redirect()
            ->back()
            ->withErrors([$error]);
    }

    /**
     * 删除文件
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteFile(Request $request)
    {
        $del_file = $request->get('del_file');
        $path = $request->get('folder') . '/' . $del_file;

        $result = $this->manager->deleteFile($path);

        if ($result === true) {
            return redirect()
                ->back()
                ->with('success', '文件[' . $del_file . ']已删除.');
        }

        $error = $result ?: "文件删除出错.";
        return redirect()
            ->back()
            ->withErrors([$error]);
    }

    /**
     * 删除目录
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteFolder(Request $request)
    {
        $del_folder = $request->get('del_folder');
        $folder = $request->get('folder') . '/' . $del_folder;

        $result = $this->manager->deleteDirectory($folder);

        if ($result === true) {
            return redirect()
                ->back()
                ->with('success', '目录[' . $del_folder . ']已删除');
        }

        $error = $result ?: "An error occurred deleting directory";
        return redirect()
            ->back()
            ->withErrors([$error]);
    }

    public function uploadFile(UploadFileRequest $request)
    {
        $file = $_FILES['file'];
        $fileName = $request->get('file_name');
        $fileName = $file ?: $file['name'];
        $path = str_finish($request->get('folder'), '/') . $fileName;
        $content = File::get($file['tmp_name']);

        $result = $this->manager->saveFile($path, $content);
    }
}
