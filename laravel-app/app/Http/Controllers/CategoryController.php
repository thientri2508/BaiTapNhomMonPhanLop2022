<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Detail_Category;
use Session;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
{
    public static function getListAll(){
        $list = Category::getListAll();
        return $list;
    }
    public function create_category(Request $request){
        $nameCategory = $this->vn_to_str($request->categoryName);
        $nameCategory = str_replace( ' ', '-', $nameCategory );
        if(Category::checkNameCategory($nameCategory)) {
            Category::addCategory($nameCategory, $request->categoryName);
            $amountDetail = intval($request->amount_detail);
            if($amountDetail>0){
                for($i=1;$i<=$amountDetail;$i++){
                    $s = 'detail-'.$i;
                    if($request->$s!=''){
                        $idDetailCategory = $this->vn_to_str($request->$s);
                        $idDetailCategory = str_replace( ' ', '-', $idDetailCategory);
                        $nameDetailCategory = $request->$s;
                        Detail_Category::addDetailCategory($idDetailCategory, $nameDetailCategory, $nameCategory);
                    }
                }
            }
            Session::put('message','Success');
            return Redirect::to('/admin/create_category');
        }else{
            Session::put('message','This category name already exists');
            return Redirect::to('/admin/create_category');
        }
    }
    public function install_category(Request $request){
        $nameCategory = $this->vn_to_str($request->categoryName);
        $nameCategory = str_replace( ' ', '-', $nameCategory );
        if($nameCategory!=$request->idCategory){
            if(Category::checkNameCategory($nameCategory)) {
                Category::updateCategory($request->idCategory, $request->categoryName, $nameCategory, $request->sort);
                Detail_Category::delDetailCategoryByidCategory($request->idCategory);
                $amountDetail = intval($request->amount_detail);
                if($amountDetail>0){
                    for($i=1;$i<=$amountDetail;$i++){
                        $s = 'detail-'.$i;
                        if($request->$s!=''){
                            $idDetailCategory = $this->vn_to_str($request->$s);
                            $idDetailCategory = str_replace( ' ', '-', $idDetailCategory);
                            $nameDetailCategory = $request->$s;
                            Detail_Category::addDetailCategory($idDetailCategory, $nameDetailCategory, $nameCategory);
                        }
                    }
                }
                return Redirect::to('/admin/list_category');
            }else{
                Session::put('message','This category name already exists');
                return Redirect::to('/admin/install_category/'.$request->idCategory);
            }
        }else{
            Category::updateCategory($request->idCategory, $request->categoryName, $nameCategory, $request->sort);
            Detail_Category::delDetailCategoryByidCategory($request->idCategory);
            $amountDetail = intval($request->amount_detail);
            if($amountDetail>0){
                for($i=1;$i<=$amountDetail;$i++){
                    $s = 'detail-'.$i;
                    if($request->$s!=''){
                        $idDetailCategory = $this->vn_to_str($request->$s);
                        $idDetailCategory = str_replace( ' ', '-', $idDetailCategory);
                        $nameDetailCategory = $request->$s;
                        Detail_Category::addDetailCategory($idDetailCategory, $nameDetailCategory, $nameCategory);
                    }
                }
            }
            return Redirect::to('/admin/list_category');
        }
    }
    public function delete_category($id){
        Category::deleteCategory($id);
        Detail_Category::deleteDetailCategory($id);
        return Redirect::to('/admin/list_category');
    }
    public static function vn_to_str ($str){
 
        $unicode = array(
         
        'a'=>'??|??|???|??|???|??|???|???|???|???|???|??|???|???|???|???|???',
         
        'd'=>'??',
         
        'e'=>'??|??|???|???|???|??|???|???|???|???|???',
         
        'i'=>'??|??|???|??|???',
         
        'o'=>'??|??|???|??|???|??|???|???|???|???|???|??|???|???|???|???|???',
         
        'u'=>'??|??|???|??|???|??|???|???|???|???|???',
         
        'y'=>'??|???|???|???|???',
         
        'A'=>'??|??|???|??|???|??|???|???|???|???|???|??|???|???|???|???|???',
         
        'D'=>'??',
         
        'E'=>'??|??|???|???|???|??|???|???|???|???|???',
         
        'I'=>'??|??|???|??|???',
         
        'O'=>'??|??|???|??|???|??|???|???|???|???|???|??|???|???|???|???|???',
         
        'U'=>'??|??|???|??|???|??|???|???|???|???|???',
         
        'Y'=>'??|???|???|???|???',
         
        );
         
        foreach($unicode as $nonUnicode=>$uni){
         
        $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        $t=strtolower($str);
        }
        return $t;
        }
}
