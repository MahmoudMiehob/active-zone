<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;

trait isFavoriteTrait {
    public function isFavorite($user_id, $minisurvice_id) {
        $favorite = DB::table('minisurvices_users')->where('user_id', $user_id)
            ->where('minisurvice_id', $minisurvice_id)
            ->first();

        return $favorite ? true : false;
    }
}
