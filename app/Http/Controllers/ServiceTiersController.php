<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceTiersController extends Controller
{
  public function filtertiers(Request $request)
  {
    $return_array = [];

    $postData = $request->all();

    // Checkbox data (for both Box 6 & Box 7)
    $filter_checkbox = $this->filters_checkbox($postData);
    $pageData['tiers_checkbox'] = $filter_checkbox['filtered'];

    // Raw input/select data (used for Box 6 only in content view)
    $filter_input = $this->filters_input($postData);
    $filter_select = $this->filters_select($postData);

    $pageData['tiers_input'] = $filter_input;
    $pageData['tiers_select'] = $filter_select;

    // Send full rendered view for .filtered-tiers (Box 6)
    $return_array['status'] = 'true';
    $return_array['content_data'] = view('filter_tiers_view')->with('pageData', $pageData)->render();

    // Send checkbox data separately for .extra_options (Box 7)
    $return_array['tiers_checkbox'] = $filter_checkbox['combined'];

    return response()->json($return_array);
  }

  function filters_checkbox($postData = NULL)
  {
    $cat_get = DB::table('sub_categories')->where('id', $postData['subcategory'])->first();
    $cat_id = $cat_get->category_id ?? 0;

    // Filtered tiers
    $query = DB::table('service_tiers')
      ->where('type', 'checkbox');

    if (!empty($postData['childcategory'])) {
      $query->where('cat_id', $cat_id)
        ->where('subcat_id', $postData['subcategory'])
        ->where('childcat_id', $postData['childcategory']);
    } else {
      $query->where('cat_id', $cat_id)
        ->where('subcat_id', $postData['subcategory']);
    }

    $filteredTiers = $query->select('service_tiers.*')->get();

    // Default tiers (universal)
    $defaultTiers = DB::table('service_tiers')
      ->where('cat_id', 0)
      ->where('subcat_id', 0)
      ->where('type', 'checkbox')
      ->select('service_tiers.*')
      ->get();

    // Return separately
    return [
      'filtered' => $filteredTiers,
      'combined' => $defaultTiers->merge($filteredTiers)
    ];
  }


  function filters_input($postData = NULL)
  {

    $cat_get = DB::table('sub_categories')->where('id', $postData['subcategory'])->first();
    $cat_id = $cat_get->category_id;
    $query = DB::table('service_tiers');


    if (isset($postData['childcategory'])) {

      $query->where('service_tiers.cat_id', $cat_id)->where('service_tiers.subcat_id', $postData['subcategory'])->where('service_tiers.childcat_id', $postData['childcategory']);

    } else {
      $query->where('service_tiers.cat_id', $cat_id)->where('service_tiers.subcat_id', $postData['subcategory']);
    }


    $query->where('service_tiers.type', 'input');



    $query->select('service_tiers.*');
    // $query->take(4);
    $content_data = $query->get();
    // print_r($content_data); die();
    return $content_data;

  }

  function filters_select($postData = NULL)
  {

    $cat_get = DB::table('sub_categories')->where('id', $postData['subcategory'])->first();
    $cat_id = $cat_get->category_id;
    $query = DB::table('service_tiers');


    if (isset($postData['childcategory'])) {

      $query->where('service_tiers.cat_id', $cat_id)->where('service_tiers.subcat_id', $postData['subcategory'])->where('service_tiers.childcat_id', $postData['childcategory']);

    } else {
      $query->where('service_tiers.cat_id', $cat_id)->where('service_tiers.subcat_id', $postData['subcategory']);
    }


    $query->where('service_tiers.type', 'select');



    $query->select('service_tiers.*');
    // $query->take(4);
    $content_data = $query->get();
    // print_r($content_data); die();
    return $content_data;

  }


}
