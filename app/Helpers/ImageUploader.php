<?php
namespace App\Helpers;

use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\ImageUploaderException;
use Intervention\Image\ImageManagerStatic as Image;

class ImageUploader {

    /**
     * Uploads all general images to general folder of specified store
     *
     * @param mixed $request
     *
     * @return string
    */
    public static function uploadGeneral($request = null)
    {
        if (is_null($request)) {
            throw new ImageUploaderException(
                "Request is empty"
            );
        }

        if ($request->hasFile('image') || $request->hasFile('images')) {

            $store = Store::where('id', Auth::user()->store_id)->first();

            if(isset($store)) {
                $destination_path = strtolower($store->id).'/images/general';
            } else {
                $destination_path = 'storage/images/general';
            }
            $file = $request->file('image');

            $path = $file->store($destination_path, ['disk' => 'uploads']);

            $base_url = str_replace("public", "", \URL::to('/'));

            $response = [
                'name' => $file->getClientOriginalName(),
                'path' => $base_url.'/uploads/'.$path,
                'size' => $file->getSize(),
            ];

            return $response;

        }
    }

    public static function uploadUserImage($request = null)
    {

        if (is_null($request)) {
            throw new ImageUploaderException(
                "Request is empty"
            );
        }
        if ($request->hasFile('image') || $request->hasFile('images')) {

            $store = Store::where('id', Auth::user()->store_id)->first();

            if(isset($store)) {
                $destination_path = strtolower($store->id).'/images/user';
            } else {
                $destination_path = public_path('storage/images/user');
            }

            $path = $request->image->store($destination_path, ['disk' => 'uploads']);

            $file = $request->file('image');

            $base_url = str_replace("public", "", \URL::to('/'));

            $response = [
                'name' => $file->getClientOriginalName(),
                'path' => $base_url.'/uploads/'.$path,
                'size' => $file->getSize(),
            ];

            return $response;

        }

    }


     /**
     * Uploads all category images to category folder of specified store
     *
     * @param mixed $request
     *
     * @return string
    */
    public static function uploadCategoryImage($request = null)
    {

        if (is_null($request)) {
            throw new ImageUploaderException(
                "Request is empty"
            );
        }

        if ($request->hasFile('image') || $request->hasFile('images')) {

            $store = Store::where('id', Auth::user()->store_id)->first();

            if(isset($store)) {
                $destination_path = strtolower($store->id).'/images/category';
            } else {
                $destination_path = public_path('storage/images/category');
            }

            $path = $request->image->store($destination_path, ['disk' => 'uploads']);

            $file = $request->file('image');

            $base_url = str_replace("public", "", \URL::to('/'));

            $response = [
                'name' => $file->getClientOriginalName(),
                'path' => $base_url.'/uploads/'.$path,
                'size' => $file->getSize(),
            ];

            return $response;

        }
    }

    /**
     * Uploads all product images to product folder of specified store
     *
     * @param mixed $request
     *
     * @return string
    */
    public static function uploadProductImage($request = null)
    {
        if ($request->hasFile('image') || $request->hasFile('images')) {

            $store = Store::where('id', Auth::user()->store_id)->first();

            if(isset($store)) {
                $destination_path = strtolower($store->id).'/images/product';
            } else {
                $destination_path = public_path('storage/images/product');
            }

            $path = $request->image->store($destination_path, ['disk' => 'uploads']);

            $file = $request->file('image');

            $base_url = str_replace("public", "", \URL::to('/'));

            $response = [
                'name' => $file->getClientOriginalName(),
                'path' => $base_url.'/uploads/'.$path,
                'size' => $file->getSize(),
            ];

            return $response;
        }
    }

    /**
     * Uploads all brand images to brand folder of specified store
     *
     * @param mixed $request
     * @param string $path
     *
     * @return string
    */
    public static function uploadBrandImage($request = null)
    {

        if (is_null($request)) {
            throw new ImageUploaderException(
                "Request is empty"
            );
        }

        if ($request->hasFile('image') || $request->hasFile('images')) {

            $store = Store::where('id', Auth::user()->store_id)->first();

            if(isset($store)) {
                $destination_path = strtolower($store->id).'/images/brand';
            } else {
                $destination_path = public_path('storage/images/brand');
            }

            $path = $request->image->store($destination_path, ['disk' => 'uploads']);
            $file = $request->file('image');
            $base_url = str_replace("public", "", \URL::to('/'));

            $response = [
                'name' => $file->getClientOriginalName(),
                'path' => $base_url.'/uploads/'.$path,
                'size' => $file->getSize(),
            ];

            return $response;
        }
    }


    /**
     * Uploads all banner images to banner folder of specified store
     *
     * @param mixed $request
     * @param string $path
     *
     * @return string
    */
    public static function uploadBannerImage($request = null)
    {

        if (is_null($request)) {
            throw new ImageUploaderException(
                "Request is empty"
            );
        }

        if ($request->hasFile('image') || $request->hasFile('images')) {

            $store = Store::where('id', Auth::user()->store_id)->first();

            if(isset($store)) {
                $destination_path = strtolower($store->id).'/images/banners';
            } else {
                $destination_path = public_path('storage/images/banners');
            }

            $path = $request->image->store($destination_path, ['disk' => 'uploads']);

            $file = $request->file('image');

            $base_url = str_replace("public", "", \URL::to('/'));

            $response = [
                'name' => $file->getClientOriginalName(),
                'path' => $base_url.'/uploads/'.$path,
                'size' => $file->getSize(),
            ];

            return $response;
        }
    }




    public static function uploadOutletImage($request = null)
    {

        if (is_null($request)) {
            throw new ImageUploaderException(
                "Request is empty"
            );
        }

        if ($request->hasFile('image') || $request->hasFile('images')) {

            $store = Store::where('id', Auth::user()->store_id)->first();

            if(isset($store)) {
                $destination_path = strtolower($store->id).'/images/outlet';
            } else {
                $destination_path = public_path('storage/images/outlet');
            }

            $path = $request->image->store($destination_path, ['disk' => 'uploads']);

            $file = $request->file('image');

            $base_url = str_replace("public", "", \URL::to('/'));

            $response = [
                'name' => $file->getClientOriginalName(),
                'path' => $base_url.'/uploads/'.$path,
                'size' => $file->getSize(),
            ];

            return $response;
        }
    }
















    /**
     * Resize image from given path string and store resized version of image to the same path
     *
     * @param string $path
     * @param integer $width
     * @param integer $height
     *
     * @return string
    */
    public static function resize($path = null, $width = null, $height = null) {

        if(isset($path)) {

            $img = Image::make(public_path('storage/'.$path));
            $img->resize($width, $height);

            $mix = explode('.', $path);
            $extension = $mix[1];
            $raw = explode('/', $mix[0]);
            $name = end($raw);

            $file = $name.'- Resized - '."$width".'x'."$height".'.'.$extension;

            unset($raw[(sizeof($raw)-1)]);

		    $file_path = implode('/', $raw).'/'.$file;

            $img->save(public_path('storage/'.$file_path));

            return $file_path;

        } else {
            throw new ImageUploaderException(
                "Path is empty"
            );
        }

    }


    /**
     * Crop image from given path string and store cropped version of image to the same path
     *
     * @param string $path
     * @param integer $width
     * @param integer $height
     * @param integer $x
     * @param integer $y
     *
     * @return string
    */
    public static function crop($path = null, $width = null, $height = null, $x = null, $y = null) {

        if(isset($path)) {

            $img = Image::make(public_path('storage/'.$path));
            $img->crop($width, $height, $x, $y);

            $mix = explode('.', $path);
            $extension = $mix[1];
            $raw = explode('/', $mix[0]);
            $name = end($raw);

            $file = $name.'- Cropped - '."$width".'x'."$height".'.'.$extension;

            unset($raw[(sizeof($raw)-1)]);

		    $file_path = implode('/', $raw).'/'.$file;

            $img->save(public_path('storage/'.$file_path));

            return $file_path;

        } else {
            throw new ImageUploaderException(
                "Path is empty"
            );
        }

    }


    /**
     * Fit image from given path string and store fitted version of image to the same path
     *
     * @param string $path
     * @param integer $width
     * @param integer $height
     *
     * @return string
    */
    public static function fit($path = null, $width = null, $height = null) {

        if(isset($path)) {

            $img = Image::make(public_path('storage/'.$path));
            $img->fit($width, $height);

            $mix = explode('.', $path);
            $extension = $mix[1];
            $raw = explode('/', $mix[0]);
            $name = end($raw);

            $file = $name.'- Fitted - '."$width".'x'."$height".'.'.$extension;

            unset($raw[(sizeof($raw)-1)]);

		    $file_path = implode('/', $raw).'/'.$file;

            $img->save(public_path('storage/'.$file_path));

            return $file_path;

        } else {
            throw new ImageUploaderException(
                "Path is empty"
            );
        }

    }


    /**
     * Optimize image from given path string and store optimized version of image to the same path
     *
     * @param string $path
     * @param integer $quality
     *
     * @return string
    */
    public static function optimize($path = null, $quality = null) {

        if(isset($path)) {

            $img = Image::make(public_path('storage/'.$path));

            $mix = explode('.', $path);
            $extension = $mix[1];
            $raw = explode('/', $mix[0]);
            $name = end($raw);

            $file = $name.'-'.'Optimized'.'.'.$extension;

            unset($raw[(sizeof($raw)-1)]);

		    $file_path = implode('/', $raw).'/'.$file;

            $img->save(public_path('storage/'.$file_path), $quality);

            return $file_path;

        } else {
            throw new ImageUploaderException(
                "Path is empty"
            );
        }

    }


    /**
     * Generate images for all media device from given path string and store generated versions of image to the same path
     *
     * @param string $path
     *
     * @return array
    */
    public static function generate($path = null) {

        if(isset($path)) {

            $img = Image::make(public_path('storage/'.$path));

            return [
                'mobile' => self::generateDeviceSize($path, $img, 'mobile'),
                'tablet' => self::generateDeviceSize($path, $img, 'tablet'),
                'laptop' => self::generateDeviceSize($path, $img, 'laptop'),
                'desktop' => self::generateDeviceSize($path, $img, 'desktop'),
            ];

        } else {
            throw new ImageUploaderException(
                "Path is empty"
            );
        }

    }


    /**
     * Generate device sizes for all images
     *
     * @param string $path
     * @param object $img
     * @param string $device
     *
     * @return string
    */
    protected static function generateDeviceSize($path, $img, $device) {

        $mix = explode('.', $path);
        $extension = $mix[1];
        $raw = explode('/', $mix[0]);
        $name = end($raw);

        unset($raw[(sizeof($raw)-1)]);

        switch ($device) {
            case 'mobile':
                $file = $name.'-'.'Mobile'.'.'.$extension;
                $img->fit(420);
            break;

            case 'tablet':
                $file = $name.'-'.'Tablet'.'.'.$extension;
                $img->fit(640);
            break;

            case 'laptop':
                $file = $name.'-'.'Laptop'.'.'.$extension;
                $img->fit(768);
            break;

            case 'desktop':
                $file = $name.'-'.'Desktop'.'.'.$extension;
                $img->fit(1024);
            break;
        }

        $file_path = implode('/', $raw).'/'.$file;

        $img->save(public_path('storage/'.$file_path));

        return $file_path;

    }


    /**
     * Generate Base64 encoded URL for given image path string
     *
     * @param string $path
     *
     * @return string
    */
    public static function generateBase64($path = null) {

        if(isset($path)) {

            $img = Image::make(public_path('storage/'.$path));

            $data = (string) $img->encode('data-url', 60);

            return $data;

        } else {
            throw new ImageUploaderException(
                "Path is empty"
            );
        }

    }


    


}
