<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    /**
     * Display the password reset link request view.
     *
     * @return \Illuminate\Database\Eloquent\Collection|News[]
     */
    public function view(): \Illuminate\Database\Eloquent\Collection|array
    {
        $user = Auth::user();
        dd($user->api_token ?? null);
        return News::all();
    }
}
