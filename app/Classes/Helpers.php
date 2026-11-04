<?php
namespace App\Classes;
use Illuminate\Support\Facades\DB;
use App\Models\Property;
class Helpers{
public function get_all_currencies($currency = NULL){
		$currencies = DB::table('currencies')->whereIn('code',['AED','USD','EUR','PKR'])->orderBy('code','ASC')->get();
		return $currencies;
	}
	public function get_currency_rates($currency = NULL){
		$rates = DB::table('currency_rates')->orderBy('id','DESC')->first();
		$currencies = json_decode($rates->rates);
		if($currency != NULL){
			$currency = strtoupper($currency);
			return $currencies->$currency;
		}else{
			return $currencies;
		} 
	}
	public function convert_to_currency($currency = NULL, $amount = NULL){
		// echo $currency;  echo $amount; 
		$rates = DB::table('currency_rates')->orderBy('id','DESC')->first();
		$currencies = json_decode($rates->rates);
		$currency = "USD";
		if($currency != NULL){
			$currency = strtoupper($currency);
			$rate = $currencies->$currency;
			if($amount != NULL){
				return str_replace(',','',(int)$amount)*$rate;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	public function get_image_path($image = NULL, $path = NULL, $size = NULL, $avatar = NULL){
		$image_path = $_SERVER['DOCUMENT_ROOT'].'/uploads/';
		// echo $image;exit;
		if($image != ''){
			/*if url*/
			$url = $image;
			if (strpos($url, 'http') !== false) {}else{
				$url = 'https:'.$url;
			}
			$url = filter_var($url, FILTER_SANITIZE_URL);
			// echo "<pre>";print_r($url);echo "</pre>";exit;
			if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
				return $url;
				exit;
			}
			/*if url*/
			if($size != ''){
				if(file_exists($image_path.$path.'/'.$size.'_'.addslashes($image))){
					$return_image = asset('uploads/'.$path.'/'.$size.'_'.addslashes($image));
				}elseif(file_exists($image_path.$path.'/'.addslashes($image))){
					$return_image = asset('uploads/'.$path.'/'.addslashes($image));
				}else{
					$return_image = asset('images/default.png');
				}
			}else{
				if(file_exists($image_path.$path.'/small_'.addslashes($image))){
					$return_image = asset('uploads/'.$path.'/small_'.addslashes($image));
				}elseif(file_exists($image_path.$path.'/'.addslashes($image))){
					$return_image = asset('uploads/'.$path.'/'.addslashes($image));
				}else{
					if($avatar == NULL){
						if($avatar == 'Female'){
							$return_image = asset('img/avatargirl.png');
						}else{
							$return_image = asset('img/avatarboy.jpg');
						}
					}else{
						$return_image = asset('images/default.png');
					}
				}
			}
		}else{
			if($avatar == NULL){
				if($avatar == 'Female'){
					$return_image = asset('img/avatargirl.png');
				}else{
					$return_image = asset('img/avatarboy.jpg');
				}
			}else{
				$return_image = asset('images/default.png');
			}
		}
		return $return_image;
	}
	public function slug_generator($string) {
        $string = strtolower(preg_replace('/[^a-zA-Z0-9- ]/', '', $string)); //Removes special characters
        $string = str_replace(" ", "-", trim(preg_replace("/ {2,}/", " ", $string))); //replace multiple spaces with 1 space
        $check_string = preg_replace('/-+/', '-', $string); //replace multiple - with 1
        $i = 1;
        $slug = "";
        while ($i > 0) {
            $data = Property::where('slug', $check_string)->count();
            if ($data > 0) {
                $check_string = $string . "-" . $i;
            } else {
                $slug = $check_string;
                break;
            }
            $i++;
        }
        return $slug;
    }
	
}