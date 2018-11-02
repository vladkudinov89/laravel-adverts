<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Entity\Adverts\Advert;
use App\Entity\User;
use App\Http\Middleware\FilledProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdvertController extends Controller
{
    public function __construct()
    {
        $this->middleware(FilledProfile::class);
    }

    public function index()
    {
        $adverts = Advert::forUser(Auth::user())->orderByDesc('id')->paginate(20);

        return view('cabinet.adverts.index');
    }

    public function create()
    {
        return view('cabinet.adverts.create');
    }

    public function edit()
    {
        return view('cabinet.adverts.edit');
    }
}
