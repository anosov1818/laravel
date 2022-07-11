<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Requests\BlogCategoryCreateRequest;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginator = BlogCategory::paginate(5);

        return view('blog.admin.categories.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
       $item = new BlogCategory();
       $categoryList = BlogCategory::all();

       return view('blog.admin.categories.edit',
              compact('item','categoryList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input();
        if (empty($data['slug'])){
            $data['slug'] = Str::of($data['title'])->slug();
        }
        //создаст объект но не добавит в бд                   Variant 1
//        $item = new BlogCategory($data);
//        $item->save();

        //создаст объект и добавит в бд                       variant 2
        $item = (new BlogCategory())->create($data);

        if ($item){
            return redirect()
                ->route('blog.admin.categories.edit', [$item->id])
                ->with(['success'=>'Успешно сохранено']);
        }else{
            return back()
                ->withErrors(['msg'=>'Ошибка сохранения'])
                ->withInput();
        }


    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
       $item = BlogCategory::findOrFail($id);
       $categoryList = BlogCategory::all();

       return view('blog.admin.categories.edit', compact('item', 'categoryList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BlogCategoryUpdateRequest $request, $id)
    {
//        $rules =[
//            'title'  =>'required|min:5|max:200',
//            'slug'   =>'max:200',
//            'description' =>'string|max:500|min:3',
//            'parent_id' => 'required|integer|exists:blog_categories,id',
//        ];

       //$validateData = $this->validate($request, $rules);    //variant 1

        //$validateData = $request->validate($rules);        //variant 2

//        $validator = \Validator::make($request->all(), $rules);            //variant 3
//        $validateData[] = $validator->passes();
//        $validateData[] = $validator->validate();
//        $validateData[] = $validator->valid();
//        $validateData[] = $validator->failed();
//        $validateData[] = $validator->errors();
//        $validateData[] = $validator->fails();

        $item =BlogCategory::find($id);
        if (empty($item)){
            return back()
                ->withErrors(['msg'=>"Запись id=[{$id}] не найдена"])
                ->withInput();
        }

        $data = $request->all();
        if (empty($data['slug'])){
            $data['slug'] = Str::of($data['title'])->slug();
        }

        $result = $item->update($data);

        if ($result){
            return redirect()
                ->route('blog.admin.categories.edit', $item->id)
                ->with(['success'=>'Успешно сохранено']);
        }else{
            return back()
                ->withErrors(['msg'=>'Ошибка сохранения'])
                ->withInput();
        }
    }
}
