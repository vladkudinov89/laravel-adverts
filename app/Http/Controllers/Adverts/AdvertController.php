<?php

namespace App\Http\Controllers\Adverts;

use App\Entity\Adverts\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdvertController extends Controller
{
    public function index(Region $region = null, Category $category = null)
    {
        $query = Advert::with(['category' , 'region'])->orderByDesc('id');

        if($category)
        {
            $query->forCategory($category);
        }

        if($region)
        {
            $query->forRegion($region);
        }

        $adverts = $query->paginate(20);

        return view('adverts.index' , compact('category' , 'region' , 'adverts'));
    }

    public function show(Advert $advert)
    {
        return view('adverts.show' , compact('advert'));
    }
}
