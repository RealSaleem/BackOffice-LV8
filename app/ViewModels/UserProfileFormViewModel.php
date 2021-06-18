<?php


namespace App\ViewModels;


use App\Models\User;
use Auth;


class UserProfileFormViewModel
{
    public $users;

    public static function load($id = 0)
    {
        $model = new UserProfileFormViewModel();

        $user = Auth::user();
        $outlets = $user->store->outlets;

            $user = [
                'outlets' => $outlets,
                'image' => [['name' => $user->name, 'url' => $user->user_image, 'size' => 0]],
                'user' => $user,
                'role' => $user->roles->first(),
                'outlet_ids' => array_column($user->outlets->toArray(), 'id')
            ];

        return $user;
    }

}
