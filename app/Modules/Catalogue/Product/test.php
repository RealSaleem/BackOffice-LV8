    public $languages;      
    
    $languages = json_decode(json_encode($request->languages));
    $this->addTranslations($product->id,$languages);

    public function addTranslations($product_id,$variant_id, $translations)
    {
        if(is_array($translations) && sizeof($translations) > 0){

            foreach ($translations as $key => $trans) {
                
                if(isset($trans->attribute_1) && (is_array($trans->attr1_values) && sizeof($trans->attr1_values) > 0)){

                    $attribute = new ProductAttribute;
                    
                    $attribute->language_key = $trans->short_name;
                    $attribute->name = $trans->attribute_1;
                    $attribute->product_id = $product_id;

                    $attribute->save();

                    foreach($trans->attr1_values as $row){
                        $value = new ProductAttributeValue;
                        $value->product_attribute_id = $attribute->id;
                        $value->name = $row->value;
                        $value->save();
                    }
                }

                if(isset($trans->attribute_2) && (is_array($trans->attr2_values) && sizeof($trans->attr2_values) > 0)){

                    $attribute = new ProductAttribute;
                    
                    $attribute->language_key = $trans->short_name;
                    $attribute->name = $trans->attribute_2;
                    $attribute->product_id = $product_id;

                    $attribute->save();

                    foreach($trans->attr2_values as $row){
                        $value = new ProductAttributeValue;
                        $value->product_attribute_id = $attribute->id;
                        $value->name = $row->value;
                        $value->save();
                    }
                }

                if(isset($trans->attribute_3) && (is_array($trans->attr3_values) && sizeof($trans->attr3_values) > 0)){

                    $attribute = new ProductAttribute;
                    
                    $attribute->language_key = $trans->short_name;
                    $attribute->name = $trans->attribute_3;
                    $attribute->product_id = $product_id;

                    $attribute->save();

                    foreach($trans->attr3_values as $row){
                        $value = new ProductAttributeValue;
                        $value->product_attribute_id = $attribute->id;
                        $value->name = $row->value;
                        $value->save();
                    }
                }
            }
        }
    }