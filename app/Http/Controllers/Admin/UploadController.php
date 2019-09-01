<?php

namespace App\Http\Controllers\Admin;

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
    }
}
