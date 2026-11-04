<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use App\Models\ChildCategories;
use App\Models\FavoriteItem;
use App\Models\Service;
use App\Models\Software;
use App\Models\SkillCategory;
use App\Models\Skill;
use Illuminate\Support\Facades\Auth;

class ServiceUtilityController extends Controller
{
    public function category(Request $request)
    {
        $sub_category = SubCategory::where('category_id', $request->category)->get();
        if ($sub_category->isEmpty()) {
            return response()->json(['error' => "Sub category not available under this category"]);
        } else {
            return response()->json($sub_category);
        }
    }
    public function subcategory(Request $request)
    {
        $child_category = ChildCategories::where('subcat_id', $request->subcategory)->get();
        if ($child_category->isEmpty()) {
            return response()->json(['error' => "Child category not available under this category"]);
        } else {
            return response()->json($child_category);
        }
    }

    public function skillcategory(Request $request)
    {
        $skill_category = SkillCategory::where('sub_category_id', $request->subcategory)->get()->pluck('id', 'name')->toArray();
        if (count($skill_category) == 0) {
            return response()->json(['error' => "Sub category not available under this category"]);
        } else {
            // $data = [];
            // foreach($skill_category as $key => $s)
            // {
            //     $skill = Skill::where('skill_category_id',$s)->get();
            //         if(count($skill) > 0)
            //         {
            //             foreach($skill_category as $key => $s)
            //             {
            //             $data[$s] = ["id" => $s->id,"name" => $skill->name];
            //             }
            //         }
            // }
            //return $skill_category;
            return response()->json($skill_category);
        }
    }
    public function getskills(Request $request)
    {
        $skill = Skill::where('skill_category_id', $request->c_id)->get()->pluck('id', 'name', 'skill_category_id')->toArray();
        $skills = Skill::where('skill_category_id', $request->c_id)->get()->pluck('category_id')->toArray();
        if (count($skill) == 0) {
            return response()->json(['error' => "0"]);
        } else {
            $a = 'aaa';
            return response()->json($skill);
        }
    }
    public function getskillsbyname(Request $request)
    {
        $skill = Skill::where(Skill::raw('lower(name)'), 'LIKE', $request->search . '%')->get()->pluck('id', 'name')->toArray();
        if (count($skill) == 0) {
            return response()->json(['error' => "0"]);
        } else {

            return response()->json($skill);
        }
    }

    public function serviceFavorite(Request $request)
    {
        echo "$request->id";
        exit;
        $user = Auth::user();
        $service = Service::find($request->id);
        if (!$service) {
            return response()->json(['error' => 'Invalid service']);
        }
        if ($service->user_id == $user->id) {
            return response()->json(['error' => 'You can not be added your self service']);
        }
        $favorite = FavoriteItem::where('service_id', $service->id)->where('user_id', $user->id)->first();
        if ($favorite) {
            $service->favorite -= 1;
            $service->save();
            $favorite->delete();
            return response()->json(['success' => 'Service remove to your favorites item']);
        } else {
            $favoriteItem = new FavoriteItem();
            $favoriteItem->user_id = $user->id;
            $favoriteItem->service_id = $service->id;
            $favoriteItem->save();

            $service->favorite += 1;
            $service->save();
            return response()->json(['success' => 'Service added to your favorites item']);
        }
    }

    public function softwareFavorite(Request $request)
    {
        $user = Auth::user();
        $software = Software::find($request->id);
        if (!$software) {
            return response()->json(['error' => 'Invalid software']);
        }
        if ($software->user_id == $user->id) {
            return response()->json(['error' => 'You can not be added your self software']);
        }
        $favorite = FavoriteItem::where('software_id', $software->id)->where('user_id', $user->id)->first();
        if ($favorite) {
            $software->favorite -= 1;
            $software->save();
            $favorite->delete();
            return response()->json(['success' => 'Software remove to your favorites item']);
        } else {
            $favoriteItem = new FavoriteItem();
            $favoriteItem->user_id = $user->id;
            $favoriteItem->software_id = $software->id;
            $favoriteItem->save();

            $software->favorite += 1;
            $software->save();
            return response()->json(['success' => 'Software added to your favorites item']);
        }
    }
}
