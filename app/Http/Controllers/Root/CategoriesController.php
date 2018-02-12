<?php

namespace App\Http\Controllers\Root;

use Toastr as Notify;
use App\Category;
use ImageUploader;
use File, Str, URL, Carbon, Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        return view('root.categories.index', ['categories' => $categories]);
    }

    public function create()
    {
        return view('root.categories.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'type'          => 'required',
            'name'          => 'required|max:100|unique:categories,name,NULL,id,deleted_at,NULL',
            'description'   => 'max:500'
        ]);

        try {
            $category = new Category();

            $category->type         = $request->input('type');
            $category->name         = Str::lower($request->input('name'));
            $category->description  = $request->input('description');

            if ($category->save()) {
                Notify::success('Category created.', 'Success!');

                return redirect()->route('root.categories.image', $category->id);
            }

            Notify::warning('Cannot create a category', 'Ooops?');

        } catch (Exception $e) {
            Notify::error($e->getMessage(), 'Ooops!');
        }

        return redirect()->back();
    }

    public function edit($id)
    {
        $category = Category::find($id);

        return view('root.categories.edit', ['category' => $category]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'type'          => 'required',
            'name'          => "required|max:100|unique:categories,name,{$id},id,deleted_at,NULL",
            'description'   => 'max:500'
        ]);

        try {
            $category = Category::find($id);

            $category->type         = $request->input('type');
            $category->name         = Str::lower($request->input('name'));
            $category->description  = $request->input('description');

            if ($category->save()) {
                Notify::success('Category updated.', 'Success!');

                return redirect()->route('root.categories.index');
            }

            Notify::warning('Cannot update category', 'Ooops?');

        } catch (Exception $e) {
            Notify::error($e->getMessage(), 'Ooops!');
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        try {
            $category = Category::find($id);

            if ($category->delete()) {
                Notify::success('Category deleted.', 'Success!');

                return redirect()->back();
            }

            Notify::warning('Cannot delete category', 'Ooops?');

        } catch (Exception $e) {
            Notify::error($e->getMessage(), 'Ooops!');
        }

        return redirect()->back();
    }

    public function restore($id)
    {
        //
    }

    public function selectImage($id)
    {
        $category = Category::find($id);

        return view('root.categories.image', ['category' => $category]);
    }

    public function uploadedImage(Request $request, $id)
    {
        $category = Category::find($id);

        if (File::exists($category->file_directory.'/'.$category->file_name)) {
            $file_path = $category->file_directory.'/'.$category->file_name;

            $images = [
                [ 
                    'directory' => URL::to($category->file_directory),
                    'name'      => File::name($file_path).'.'.File::extension($file_path), 
                    'size'      => File::size($file_path) 
                ]
            ];

            return response()->json(['images' => $images]);
        }

        return response()->json('No image.');
    }

    public function uploadImage(Request $request, $id)
    {
        try {
            $category = Category::find($id);

            $upload = ImageUploader::upload($request->file('image'), "categories/{$category->id}");

            if ($upload) {
                $category->file_path = $upload['file_path'];
                $category->file_directory = $upload['file_directory'];
                $category->file_name = $upload['file_name'];
            }

            if ($category->save()) {
                 return response()->json($upload);
            }

        } catch(Exception $e) {
            return response()->json($e, 400);
        }
    }

    public function destroyImage(Request $request, $id)
    {
       try {
            $category = Category::find($id);

            if (File::exists($category->file_directory.'/'.$category->file_name)) {
                $deleted = File::delete($category->file_directory.'/'.$category->file_name);
            }

            if (File::exists($category->file_directory.'/resized/'.$category->file_name)) {
                $deleted = File::delete($category->file_directory.'/resized/'.$category->file_name);
            }

            if (File::exists($category->file_directory.'/thumbnails/'.$category->file_name)) {
                $deleted = File::delete($category->file_directory.'/thumbnails/'.$category->file_name);
            }

            $category->file_path = null;
            $category->file_directory = null;
            $category->file_name = null;
            $category->save();

            if ($deleted) {
                return response()->json('File deleted.');
            }

            return response()->json('File not deleted.');
        } catch(Exception $e) {
            return response()->json($e, 400);
        }
    }
}
