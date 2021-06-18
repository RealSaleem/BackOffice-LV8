<?php

namespace App\Imports;

// use App\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ItemImport implements ToCollection,WithHeadingRow
{
    private $lang;
    private $stock;

    public function __construct($lang,$stock)
    {
        $this->lang = $lang;
        $this->stock = $stock;

    }

    public function collection(Collection $rows)
    {
        $items = [];
        foreach ($rows as $index => $row)
        {
            $data = [];
            foreach ($row as $key => $value) {
                if(strlen($key) > 0){
                    $data[$key] = $value;
                }
            }
            array_push($items, $data);
        }
        $res = $this->Serve($items);
        // season use for pasing the response to the import controller
        Session::put('Item_Response',$res);
        return $res;
    }
    public function Serve($request){

        try {
            dd($request);
                DB::beginTransaction();

                if (!empty($request)) {
                    $skus = [];
                    $errors = [];

                    $composite_sheet_errors = $this->validateCompositeSheet($request);
                    if(sizeof($composite_sheet_errors)){
                        $errors['Composite Sheet'] = $composite_sheet_errors;
                    }
                    if(sizeof($errors) > 0){
                        return new Response(false,null,null,$errors);
                    }

                    $combo_groups = $this->groupArray($request, 'compositename' );
                    $cskus = [];

                    foreach ($combo_groups as $c_pro) {
                        $c_skus = [];
                        foreach ($c_pro as $cvalue) {
                            array_push($c_skus, $cvalue['sku']);
                        }
                        array_push($cskus, $c_skus);
                    }

                    foreach ($combo_groups as $key => $combo_group) {
                        if (sizeof($combo_group) > 1) {
                            $this->insertCompositeSheet($key, $combo_group, $this->stock);
                        }
                    }
                }

                DB::commit();

                $Message = 'Composite sheet has been Uploaded successfully';
                return new Response(true,null,null,null, $Message);

            } catch (Exception $ex) {
            DB::rollBack();
            return new Response(false,null,null,$ex->getMessage());
        }
    }

    private function validateCompositeSheet($sheet)
    {
        if(!is_null($sheet) && sizeof($sheet) > 0)
        {
            $error_rows = [];
            $sku_groups = [];
            $index = 0;

            foreach($sheet as $row){

                $rows = array_slice($sheet,0,$index);

                $sku_groups = $this->getGroupedSkus($rows);

                if(is_array($sku_groups)){
                    $sss = array_filter($sku_groups,function($item) use($row){
                        if($row['compositename'] != $item['name']){
                            return $item;
                        }
                    });
                }

                $skus = array_column($sss, 'sku');
                $var_sku = ProductVariant::where([['sku',$row['variantsku']],['store_id', Auth::user()->store_id]])->first();
                $sku_check = [];
                if(isset($var_sku->sku)){
                    array_push($sku_check, $var_sku->sku);
                }
                $validator = Validator::make($row, [
                    'compositename'         => 'required|min:3|max:490',
                    'sku'                   => ['required','min:3','max:250', Rule::notIn($skus)],
                    'variantsku'            => ['required','min:3','max:250', Rule::in($sku_check)],
                    'category'              => 'required|string|nullable',
                    'quantityofvariants'    => 'required|integer|min:1',
                    'retailprice'           => 'required|min:0',
                    'compareprice'          => 'min:0|nullable',
                    'supplyprice'           => 'min:0|nullable',
                    'stock'                 => 'integer|nullable',
                    'reorderpoint'          => 'integer|min:0|nullable',
                    'reorderquantity'       => 'integer|min:0|nullable',
                ],[
                    'sku.not_in'    => ':attribute '.$row['sku'].' already exist in composite group',
                    'variantsku.in' => ':attribute '.$row['variantsku'].' must exist in standard sheet or system'
                ]);

                if ($validator->fails()) {
                    $errors = $validator->errors();

                    $error_row = [];

                    foreach ($errors->all() as $message) {
                        array_push($error_row,$message);
                    }

                    $key = 'Line '. ($index + 2);

                    $error_rows[$key] = $error_row;
                }

                $index++;
            }

            return $error_rows;
        }

        return [];
    }

}
