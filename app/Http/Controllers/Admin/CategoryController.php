<?php

namespace App\Http\Controllers\Admin;

use App\Models\Skill;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ChildCategories;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\SkillCategory;
use App\Rules\FileTypeValidate;
use App\Http\Controllers\Controller;
use File;

class CategoryController extends Controller
{

    public function index()
    {
        $pageTitle = "Category list";
        $emptyMessage = "No data found";

        $search = request('search');

        $categorys = Category::with('subCategory')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(getPaginate())
            ->withQueryString();

        return view('admin.category.index', compact(
            'categorys',
            'pageTitle',
            'emptyMessage',
            'search'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50'
        ]);
       // print_r($request->all());exit;
        $category = new Category;
        $category->name = $request->name;
        $category->type = $request->type;
        $category->status = $request->status ? 1 : 2;
        $path = imagePath()['subcategory']['path'];
        $size = imagePath()['subcategory']['size'];
      if ($request->file('image')) {
        $file= $request->file('image');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file-> move(public_path('assets/images/category'), $filename);

        $category->image = $filename;
    }
        $category->save();
        $notify[] = ['success', 'Category has been created'];
        return back()->withNotify($notify);
    }

    public function update(Request $request)
    {
        $request->validate([
            // 'name' => 'required|max:50|unique:categories,name,' . $request->id,
            'id' => 'required|exists:categories,id'
        ]);
        $category = Category::find($request->id);
        $category->name = $request->name;
        $category->type = $request->type;
        $category->status = $request->status ? 1 : 2;
        $path = imagePath()['subcategory']['path'];
        $size = imagePath()['subcategory']['size'];
      if ($request->file('image')) {
        $file= $request->file('image');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file-> move(public_path('assets/images/category'), $filename);

        $category->image = $filename;
    }
        $category->save();
        $notify[] = ['success', 'Category has been updated'];
        return back()->withNotify($notify);
    }


    public function subCategoryIndex()
    {
        $pageTitle = "Sub Category list";
        $emptyMessage = "No data found";

        $search = request('search');
        $categoryId = request('category_id');

        $categorys = Category::where('status', 1)->get();

        $subCategorys = SubCategory::with('category')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->latest()
            ->paginate(getPaginate())
            ->withQueryString();

        return view(
            'admin.category.sub_index',
            compact(
                'subCategorys',
                'pageTitle',
                'emptyMessage',
                'categorys'
            )
        );
    }

    public function childCategoryIndex()
    {
        $pageTitle = "Child Category list";
        $emptyMessage = "No data found";

        $search = request('search');
        $categoryId = request('category_id');
        $subCategoryId = request('subcategory_id');

        $categorys = Category::where('status', 1)->get();

        $subCategorys = SubCategory::with('category')
            ->get();

        $childCategorys = ChildCategories::with([
                'category',
                'subCategory'
            ])
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('cat_id', $categoryId);
            })
            ->when($subCategoryId, function ($query) use ($subCategoryId) {
                $query->where('subcat_id', $subCategoryId);
            })
            ->latest()
            ->paginate(getPaginate())
            ->withQueryString();

        return view(
            'admin.category.child_index',
            compact(
                'subCategorys',
                'childCategorys',
                'pageTitle',
                'emptyMessage',
                'categorys'
            )
        );
    }

    public function subCategoryStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            //'image' => ['required', 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'category_id' => 'required|exists:categories,id'
        ]);
        $subcategory = new SubCategory;
        $subcategory->name = $request->name;
        $subcategory->category_id = $request->category_id;
        // $path = imagePath()['subcategory']['path'];
        // $size = imagePath()['subcategory']['size'];
        // if ($request->hasFile('image')) {
        //     $file = $request->image;
        //     $this->fileValidate($file);
        //     try {
        //         $filename = uploadImage($file, $path, $size);
        //     } catch (\Exception $exp) {
        //         $notify[] = ['error', 'Image could not be uploaded.'];
        //         return back()->withNotify($notify);
        //     }
        //     $subcategory->image = $filename;
        // }
        $path = imagePath()['subcategory']['path'];
        $size = imagePath()['subcategory']['size'];
        if ($request->file('image')) {
            $file= $request->file('image');
                $filename= date('YmdHi').$file->getClientOriginalName();
                $file-> move(public_path('assets/images/subcategory'), $filename);

            $subcategory->image = $filename;
        }
        $subcategory->save();
        $notify[] = ['success', 'Sub category has been created'];
        return back()->withNotify($notify);
    }

    public function childCategoryStore(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:sub_categories|max:50',
            //'image' => ['required', 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'category_id' => 'required|exists:categories,id'
        ]);
        $childcategory = new ChildCategories;
        $childcategory->name = $request->name;
        $childcategory->cat_id = $request->category_id;
        $childcategory->subcat_id = $request->subcategory_id;
        $path = imagePath()['subcategory']['path'];
        $size = imagePath()['subcategory']['size'];
        if ($request->file('image')) {
            $file= $request->file('image');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file-> move(public_path('assets/images/subcategory'), $filename);

            $childcategory->image = $filename;
        }
        $childcategory->save();
        $notify[] = ['success', 'Child category has been created'];
        return back()->withNotify($notify);
    }


    public function subCategoryUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:sub_categories,id',
            'name' => 'required|max:50|unique:sub_categories,name,' . $request->id,
            //'image' => ['image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'category_id' => 'required|exists:categories,id'
        ]);
        $subcategory = SubCategory::find($request->id);
        $subcategory->name = $request->name;
        $subcategory->category_id = $request->category_id;
        // $path = imagePath()['subcategory']['path'];
        // $size = imagePath()['subcategory']['size'];
        // if ($request->hasFile('image')) {
        //     $file = $request->image;
        //     $this->fileValidate($file);
        //     try {
        //         $filename = uploadImage($file, $path, $size);
        //     } catch (\Exception $exp) {
        //         $notify[] = ['error', 'Image could not be uploaded.'];
        //         return back()->withNotify($notify);
        //     }
        //     $subcategory->image = $filename;
        // }
        $path = imagePath()['subcategory']['path'];
        $size = imagePath()['subcategory']['size'];
        if ($request->file('image')) {
            $file= $request->file('image');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file-> move(public_path('assets/images/subcategory'), $filename);
            $subcategory->image = $filename;
        }
        $subcategory->save();

        $notify[] = ['success', 'Sub category has been updated'];
        return back()->withNotify($notify);
    }
     public function childCategoryUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:sub_categories,id',
            'name' => 'required|max:50|unique:sub_categories,name,' . $request->id,
            //'image' => ['image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'category_id' => 'required|exists:categories,id'
        ]);
        $childcategory = ChildCategories::find($request->id);
        $childcategory->name = $request->name;
        $childcategory->cat_id = $request->category_id;
        $childcategory->subcat_id = $request->subcategory_id;
        $path = imagePath()['subcategory']['path'];
        $size = imagePath()['subcategory']['size'];
        // if ($request->hasFile('image')) {
        //     $file = $request->image;
        //     $this->fileValidate($file);
        //     try {
        //         $filename = uploadImage($file, $path, $size);
        //     } catch (\Exception $exp) {
        //         $notify[] = ['error', 'Image could not be uploaded.'];
        //         return back()->withNotify($notify);
        //     }
        //     $childcategory->image = $filename;
        // }
        if ($request->file('image')) {
            $file= $request->file('image');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file-> move(public_path('assets/images/subcategory'), $filename);

            $childcategory->image = $filename;
        }
        $childcategory->save();
        $notify[] = ['success', 'Child category has been updated'];
        return back()->withNotify($notify);
    }

    public function subCategoryData(Request $request)
    {
        $sub = SubCategory::where('category_id', $request->category_id)->latest()->get()->pluck('id', 'name')->toArray();
        if (count($sub)) {
            return $sub;
        } else {
            return ['No Sub categores available' => ''];
        }
    }

    public function skillCategoryIndex()
    {
        $pageTitle = "Skill Category list";
        $emptyMessage = "No data found";

        $search = request('search');
        $categoryId = request('category_id');
        $subCategoryId = request('sub_category_id');

        $categorys = Category::where('status', 1)->get();

        $skillCategorys = SkillCategory::with(['category', 'subCategory'])
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($subCategoryId, function ($query) use ($subCategoryId) {
                $query->where('sub_category_id', $subCategoryId);
            })
            ->latest()
            ->paginate(getPaginate())
            ->withQueryString();

        return view(
            'admin.category.skill_category_index',
            compact(
                'skillCategorys',
                'pageTitle',
                'emptyMessage',
                'categorys'
            )
        );
    }


    public function skillCategoryStore(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:skill_categories|max:50',
            // 'image' => ['required', 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id'
        ]);
        $skillCategory = new SkillCategory;
        $skillCategory->name = $request->name;
        $skillCategory->category_id = $request->category_id;
        $skillCategory->sub_category_id = $request->sub_category_id;
        // $path = imagePath()['subcategory']['path'];
        // $size = imagePath()['subcategory']['size'];
        // if ($request->hasFile('image')) {
        //     $file = $request->image;
        //     $this->fileValidate($file);
        //     try {
        //         $filename = uploadImage($file, $path, $size);
        //     } catch (\Exception $exp) {
        //         $notify[] = ['error', 'Image could not be uploaded.'];
        //         return back()->withNotify($notify);
        //     }
        //     $skillCategory->image = $filename;
        // }
        $skillCategory->save();
        $notify[] = ['success', 'Skill category has been created'];
        return back()->withNotify($notify);
    }


    public function skillCategoryUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:skill_categories,id',
            'name' => 'required|max:50|unique:skill_categories,name,' . $request->id,
            // 'image' => ['image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id'
        ]);
        $skillCategory = SkillCategory::find($request->id);
        $skillCategory->name = $request->name;
        $skillCategory->category_id = $request->category_id;
        $skillCategory->sub_category_id = $request->sub_category_id;
        // $path = imagePath()['subcategory']['path'];
        // $size = imagePath()['subcategory']['size'];
        // if ($request->hasFile('image')) {
        //     $file = $request->image;
        //     $this->fileValidate($file);
        //     try {
        //         $filename = uploadImage($file, $path, $size);
        //     } catch (\Exception $exp) {
        //         $notify[] = ['error', 'Image could not be uploaded.'];
        //         return back()->withNotify($notify);
        //     }
        //     $subcategory->image = $filename;
        // }
        $skillCategory->save();
        $notify[] = ['success', 'Skill category has been updated'];
        return back()->withNotify($notify);
    }

    public function skillCategoryData(Request $request)
    {
        $sub = SkillCategory::where('sub_category_id', $request->sub_category_id)->latest()->get()->pluck('id', 'name')->toArray();
        if (count($sub)) {
            return $sub;
        } else {
            return ['No Skill Categories available' => ''];
        }
    }


    public function skillIndex()
    {
        $pageTitle = "Skills list";
        $emptyMessage = "No data found";

        $search = request('search');
        $categoryId = request('category_id');
        $subCategoryId = request('sub_category_id');
        $skillCategoryId = request('skill_category_id');

        $categorys = Category::where('status', 1)->get();

        $skills = Skill::with([
                'category',
                'subCategory',
                'skillCategory'
            ])
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($subCategoryId, function ($query) use ($subCategoryId) {
                $query->where('sub_category_id', $subCategoryId);
            })
            ->when($skillCategoryId, function ($query) use ($skillCategoryId) {
                $query->where('skill_category_id', $skillCategoryId);
            })
            ->latest()
            ->paginate(getPaginate())
            ->withQueryString();

        return view(
            'admin.category.skill_index',
            compact(
                'skills',
                'pageTitle',
                'emptyMessage',
                'categorys'
            )
        );
    }


    public function skillStore(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50',
            // 'image' => ['required', 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'skill_category_id' => 'required|exists:skill_categories,id',
        ]);
        $skills = new Skill();
        $skills->name = $request->name;
        $skills->category_id = $request->category_id;
        $skills->sub_category_id = $request->sub_category_id;
        $skills->skill_category_id = $request->skill_category_id;
        // $path = imagePath()['subcategory']['path'];
        // $size = imagePath()['subcategory']['size'];
        // if ($request->hasFile('image')) {
        //     $file = $request->image;
        //     $this->fileValidate($file);
        //     try {
        //         $filename = uploadImage($file, $path, $size);
        //     } catch (\Exception $exp) {
        //         $notify[] = ['error', 'Image could not be uploaded.'];
        //         return back()->withNotify($notify);
        //     }
        //     $subcategory->image = $filename;
        // }
        $skills->save();
        $notify[] = ['success', 'Skill has been created'];
        return back()->withNotify($notify);
    }


    public function skillUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:skills,id',
            'name' => [
                'required',
                'max:50',
            ],
            // 'image' => ['image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'skill_category_id' => 'required|exists:skill_categories,id',
        ]);
        $skill = Skill::find($request->id);
        $skill->name = $request->name;
        $skill->category_id = $request->category_id;
        $skill->sub_category_id = $request->sub_category_id;
        $skill->skill_category_id = $request->skill_category_id;
        // $path = imagePath()['subcategory']['path'];
        // $size = imagePath()['subcategory']['size'];
        // if ($request->hasFile('image')) {
        //     $file = $request->image;
        //     $this->fileValidate($file);
        //     try {
        //         $filename = uploadImage($file, $path, $size);
        //     } catch (\Exception $exp) {
        //         $notify[] = ['error', 'Image could not be uploaded.'];
        //         return back()->withNotify($notify);
        //     }
        //     $subcategory->image = $filename;
        // }
        $skill->save();
        $notify[] = ['success', 'Skill has been updated'];
        return back()->withNotify($notify);
    }


    private function fileValidate($file)
    {
        $allowedExts = array('jpeg', 'jpg', 'png');
        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, $allowedExts)) {
            $notify = 'Only jpeg, jpg, png files are allowed';
            return back()->withNotify($notify);
        }
    }
}
