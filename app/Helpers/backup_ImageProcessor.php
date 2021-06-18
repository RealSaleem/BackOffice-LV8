<?php

namespace App\Helpers;

use App\Models\Store;
use App\Models\WebSettings;
use App\Models\Themes;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\ImageUploaderException;
use Intervention\Image\ImageManagerStatic as Image;

class ImageProcessor
{

    /**
     * Process images of given store id
     *
     * @param int $store_id
     * A
     * @return bool
     */
    public static function process($store_id = null)
    {
        // Check if store id is not null and proceed to function
        if (isset($store_id) && $store_id != null && is_int($store_id)) {
            self::processImages($store_id);
        }
    }

    /**
     * Process product images of given parameters
     *
     * @param int $store_id
     * B
     * @return bool
     */
    protected static function processImages($store_id)
    {
        // Get web settings of a given store id
        $store_id = $store_id;
        $store = WebSettings::where('store_id', $store_id)->first();
        $theme_name = $store->ecom_theme;

        // Get images aspect ratio from the store object
        $params = explode(':', $store->aspect_ration);
        $missing_ratio = 1 / 1;

        // Check if aspect ratio exists and assign to missing_ratio variable
        if (sizeof($params) == 2) {
            $num1 = isset($params[0]) && $params[0] != null  ? $params[0] : 1;
            $num2 = isset($params[1]) && $params[1] != null  ? $params[1] : 1;
            $missing_ratio = $num2 / $num1;
        }

        // $missing_ratio = 4 / 3;

        // Get Current Theme
        $theme = Themes::where('name', $theme_name)->first();

        if(!isset($theme)) {
            $theme = new Themes();
            $theme->sizes = json_encode(['tablet' => 550]);
        }

        // Check if theme image sizes exist or not
        if (isset($theme->sizes) && $theme->sizes != null) {

            // Create Array object of product image sizes
            $product_image_sizes = json_decode($theme->sizes, true);

            // Check if given arguement is Array or not
            if (is_array($product_image_sizes)) {

                // Get all product images and product variants of a given store id
                $products = Product::with(['product_images', 'product_variants'])
                                ->where(['store_id' => $store_id])
                                ->whereHas('product_images')
                                ->whereNull('deleted_at')->get();

                // dd($products->toArray());

                // Check if products Array is not empty
                if (sizeof($products) > 0) {

                    // Iterate the product image sizes array to get size type and its size value
                    foreach ($product_image_sizes as $size_type => $width_from_theme_setting) {

                        // Iterate the product object to get the product images and variants
                        foreach ($products as $product) {

                            // Variables to check the logic for products that have multiple images and variants
                            $last_sku = 0;
                            $sku_count = 0;

                            // Check if product images object is not empty
                            if (sizeof($product->product_images) > 0) {

                                // Iterate the product images to get image path
                                foreach ($product->product_images as $image) {


                                    $parts = explode('public/',$image->url);


                                    $file_parts = explode('.',$parts[1]);
                                    // print_r($file_parts);

                                    $url = public_path($parts[1]);//exit;
                                    //dd($url);
                                    if(file_exists($url) && strtolower($file_parts[1]) != 'webp'){
                                        // dd($url);
                                        // Get SKU from product variants relation by giving product id of an image
                                        $product_sku = $product->product_variants()->select('sku')->where('product_id', $image->product_id)->first();
                                        $sku = $product_sku->sku;

                                        // Check if product SKU is equal to last SKU iterated and create logic for renaming of an image
                                        if ($product_sku->sku == $last_sku) {
                                            $sku_count++;
                                            $sku = $sku . '-' . $sku_count;
                                        }

                                        // Check if theme product images size type is equal to tablet and return base64 string for storing in database, else process images and save in storage accordingly
                                        if ($size_type == 'tablet') {
                                            $base64 = self::generateImageSize($url, $width_from_theme_setting, $missing_ratio, 20, $store_id, $sku, true);
                                        } else {
                                            self::generateImageSize($url, $width_from_theme_setting, $missing_ratio, 20, $store_id, $sku);
                                        }

                                        // Update last sku variable for above logic
                                        $last_sku = $product_sku->sku;

                                        // Create filename according to SKU
                                        $name = $sku . '.' . $file_parts[1];

                                        // Check if theme product images size type is equal to tablet and save base64 string in database, else save filename in database
                                        if ($size_type == 'tablet') {
                                            ProductImage::where('url', $image->url)->update(['url_encoded' => $base64]);
                                        } else {
                                            ProductImage::where('url', $image->url)->update(['name' => $name]);
                                        }
                                    }else{
                                        echo 'File does not exist';
                                        echo '<br />';
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Generate image with the given parameters
     *
     * @param string $path
     * @param int $width
     * @param int $ratio
     * @param int $quality
     * @param int $store_id
     * @param mixed $sku
     * @param bool $is_base64
     * C
     * @return bool
     * @return base64
     */
    protected static function generateImageSize($path = null, $width = null, $ratio = null, $quality = null, $store_id = null, $sku = null, $is_base64 = false)
    {
        // Check if $path is not empty otherwise throw exception
        if (isset($path)) {

            // Checks if path has folder reference and remove it
            if (strrchr($path, '/')) {
                $filename = substr(strrchr($path, '/'), 1); // remove folder references
            } else {
                $filename = $path;
            }

            // Create filename with extension
            $mix = explode('.', $filename);
            $extension = $mix[1];
            $file = $sku . '.' . $extension;

            // Ratio Calculation
            $height_calc = $width / $ratio;
            $height_calc = round($height_calc);

            // Create Image Object
            $img = Image::make($path);

            // Perform Operation
            $img->fit($width, $height_calc);

            // Check if $is_base64 flag true
            if ($is_base64) {
                // Return Base64 String
                return $data = (string) $img->encode('data-url', $quality);
            } else {
                // Create directory and save processed image in created directory
                Storage::makeDirectory($store_id . '/images/product/' . $width . 'x' . $height_calc);
                $img->save(public_path('storage/' . $store_id . '/images/product/' . $width . 'x' . $height_calc . '/') . $file, $quality);
            }
        } else {
            // Throw an exception if image path is empty
            throw new ImageUploaderException(
                "Path is empty"
            );
        }
    }
}
