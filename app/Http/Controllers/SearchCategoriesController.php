<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\User;
use App\Models\Job;
use App\Models\Category;
use App\Models\Skill;
use App\Models\SubCategory;
use App\Models\SkillCategory;
use DB;

class SearchCategoriesController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function ajaxJobCategories(Request $request)
    {

        $data = $request->all();
        $catId = isset($data['category_id']) ? $data['category_id'] : null;
        $query = Category::query();
        $query->where('type', 'job');

        if ($catId) {
            $query->where('id', $catId);
        }

        $d = $query->with('subCategory')->get();
        return $d;
    }

    public function ajaxJobSubCategories(Request $request, $categoryId)
    {

        $catId = isset($categoryId) ? $categoryId : null;
        $query = Category::query();
        $query->where('type', 'job');

        if ($catId) {
            $query->where('id', $catId);
        }

        $d = $query->with('subCategory')->get();
        return $d;
    }

    public function ajaxJobSubCategorySkills(Request $request, $subcategoryId)
    {
        $skills = SkillCategory::where('sub_category_id', $subcategoryId)
            ->select(['id', 'name'])
            ->get();

        return response()->json($skills);
    }
    public function ajaxJobSubCategoryGetskills(Request $request, $categoryId)
    {

        $catId = isset($categoryId) && $categoryId != null ? $categoryId : null;

        $query = Skill::query();

        if ($catId) {
            $query->where('skill_category_id', $catId);
        }

        $d = $query->select(['id', 'name', 'skill_category_id'])->get();
        return $d;
    }

    public function ajaxJobSkillsSearch(Request $request)
    {
        $data = $request->all();
        $search = isset($data['search']) ? $data['search'] : '';

        $skills = Skill::where(Skill::raw('lower(name)'), 'LIKE', $search . '%')->select(['id', 'name'])->get();
        return $skills;
    }

    public function getCountryStates(Request $request)
    {
        $countryName = $request->input('name');
        $jsonPath = resource_path('views/partials/countries_states.json');

        // $jsonPath = public_path('countries_states.json');
        if (!file_exists($jsonPath)) {
            return response()->json(['error' => 'JSON file not found at ' . $jsonPath], 404);
        }
        $content = file_get_contents($jsonPath);
        $data = json_decode($content, true);

        $country = collect($data)->firstWhere('name', $countryName);
        $states = $country['states'] ?? [];
        return response()->json([
            'country' => $countryName,
            'states' => $states
        ]);
    }

    public function getCountryCities(Request $request)
    {
        $countryName = $request->input('name');
        // $jsonPath = public_path('countries_cities.json');
        $jsonPath = resource_path('views/partials/countries_cities.json');

        if (!file_exists($jsonPath)) {
            return response()->json(['error' => 'JSON file not found at ' . $jsonPath], 404);
        }

        $content = file_get_contents($jsonPath);
        $data = json_decode($content, true);

        // Find the country by name
        $country = collect($data)->firstWhere('name', $countryName);
        $cities = $country['cities'] ?? [];

        return response()->json([
            'country' => $countryName,
            'cities' => $cities
        ]);
    }
    // public function filtersubs(Request $request)
    // {
    //     $return_array = array();
    //     if ($request->all()) {
    //         $postData = $request->all();
    //         // print_r($postData); die();
    //         $subsData = $this->filters_subs($postData, 'count');
    //         $count =  count($subsData);
    //         $pageData['subsData'] = $subsData;
    //         $return_array['status'] = 'true';
    //         $return_array['content_data'] = view('subspro_filtered')->with('pageData', $pageData)->render();
    //         // $return_array['content_data'] = view('categorywise_product')->with('pageData', $pageData)->render();
    //         // print_r($return_array['content_data']); die();
    //         $return_array['count'] = $count;
    //     } else {
    //         $return_array['status'] = 'false';
    //         $return_array['content_data'] = '';
    //         $return_array['count'] = 0;
    //     }
    //     // echo '<pre>'; print_r($this->db->last_query());exit;
    //     // echo "<pre>";print_r($pageData['strain_data']);exit;
    //     // echo "<pre";print_r($view_array);echo "</pre>";exit;
    //     echo json_encode($return_array);
    // }

    function filters_subs($postData = NULL, $param = NULL)
    {
        $query = DB::table('sub_categories');

        if (isset($postData['category']) && !empty($postData['category'])) {
            $query->where('sub_categories.category_id',  $postData['category']);
        }
        $content_data = $query->get();
        // print_r($content_data); die();
        return $content_data;
    }
    function filters_left_menu(Request $request, $param = NULL)
    {
        $retrunData = $content_data = [];
        if ($postData = $request->all()) {
            $param = $postData['param'];
            $param_id = $postData['param_id'];
            if ($param == 'category') {
                $query = SubCategory::query();
                if ($param_id > 0) {
                    $query->where('category_id', $param_id);
                }
                $content_data = $query->get();
                if ($content_data->count() > 0) {
                    $content_data = view('ajax_view', compact('content_data', 'param'))->render();
                }
            } elseif ($param == 'subcategory') {
                $query = SkillCategory::query();
                if ($param_id > 0) {
                    $query->where('sub_category_id', $param_id);
                }
                $content_data = $query->get();
                if ($content_data->count() > 0) {
                    $content_data = view('ajax_view', compact('content_data', 'param'))->render();
                }
            }
            $retrunData['status'] = 'true';
            $retrunData['message'] = '';
        } else {
            $retrunData['status'] = 'false';
            $retrunData['message'] = 'Post not found';
        }
        $retrunData['content_data'] = $content_data;
        echo json_encode($retrunData);
    }

    public function filterchild(Request $request)
    {
        $return_array = array();
        if ($request->all()) {
            $postData = $request->all();
            $childData = \DB::table('skill_categories')->where('skill_categories.sub_category_id', $postData['subcategory_id'])->get();
            $count =  count($childData);
            $pageData['childData'] = $childData;
            $return_array['status'] = 'true';
            $return_array['content_data'] = view('childpro_filtered')->with('pageData', $pageData)->render();
            $return_array['count'] = $count;
        } else {
            $return_array['status'] = 'false';
            $return_array['content_data'] = '';
            $return_array['count'] = 0;
        }
        return response()->json($return_array);
    }

    public function filterskills(Request $request)
    {
        $return_array = array();

        if ($request->all()) {
            $postData = $request->all();

            // Fetch skills based on sub_category_id
            // $skillsData = \DB::table('skills')->where('skill_category_id', $postData['skillcategory_id'])->get();
            $skillsData = \DB::table('skills')->where('sub_category_id', $postData['subcategory_id'])->get();
            $count = $skillsData->count();
            $pageData['skillsData'] = $skillsData;
            $return_array['status'] = 'true';
            $return_array['content_data'] = view('skillspro_filtered', compact('pageData'))->render();
            $return_array['count'] = $count;
        } else {
            $return_array['status'] = 'false';
            $return_array['content_data'] = '';
            $return_array['count'] = 0;
        }

        return response()->json($return_array);
    }
}
