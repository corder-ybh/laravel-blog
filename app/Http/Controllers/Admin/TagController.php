<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TagCreateRequest;
use App\Http\Requests\TagUpdateRequest;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Url;

class TagController extends Controller
{
    protected $fields = [
        'tag' => '',
        'title' => '',
        'subtitle' => '',
        'meta_description' => '',
        'page_image' => '',
        'layout' => 'blog.layout.index',
        'reverse_direction' => 0,
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::all();
        return view('admin.tag.index')->withTags($tags);
    }

    /** 本方法会在点击 [添加新标签]或者表单被填充但是验证失败时执行
     * 对于后者我们会将传过来的值通过orl方法写回表单中
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $data = [];
        foreach ($this->fields as $field => $default) {
            $data[$field] = old($field, $default);
        }
        return view('admin.tag.create', $data);
    }

    /**
     * 保存Tag
     * @param TagCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TagCreateRequest $request)
    {
        $tag = new Tag();
        foreach (array_keys($this->fields) as $field) {
            $tag->$field = $request->get($field);
        }
        $tag->save();

        return redirect('/admin/tag')
            ->with('success', '标签[' . $tag->tag . ']创建成功.');
    }

    /**
     * 标签编辑页面
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $tag = Tag::findOrFail($id);
        $data = ['id' => $id];
        foreach (array_keys($this->fields) as $field) {
            $data[$field] = old($field, $tag->$field);
        }
        return view('admin.tag.edit', $data);
    }

    /**
     * 更新tag
     * @param TagUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TagUpdateRequest $request, $id)
    {
        $tag = Tag::findOrFail($id);

        foreach (array_keys(array_except($this->fields, ['tag'])) as $field) {
            $tag->$field = $request->get($field);
        }
        $tag->save();

        return redirect("/admin/tag/$id/edit")
            ->with('success', '修改已保存.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();

        return redirect('/admin/tag')->with('success', '标签[' . $tag->tag . ']已删除.');
    }
}
