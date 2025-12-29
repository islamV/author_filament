<?php

namespace App\Http\Controllers\v1\Author\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Dashboard\Ad\StoreAdRequest;
use App\Http\Requests\v1\Dashboard\Ad\UpdateAdRequest;
use App\Models\Ad;
use App\Traits\ImageTrait;

class AdController extends Controller
{
    use ImageTrait;
    public function list()
    {
        $ads = Ad::orderBy('created_at','desc')->paginate(10);
        return view('pages.ads.index' , compact('ads'));
    }

    public function create()
    {
        return view('pages.ads.add');
    }

    public function store(StoreAdRequest $request)
    {
        $ad = [
            "title" => $request->title,
        ];
        Ad::create($ad);
        return redirect()->route('ads.list')->with('status' , 'Ad Added Successfully');
    }
    public function edit(Ad $ad)
    {
        return view('pages.ads.edit' , compact('ad'));
    }

    public function update(UpdateAdRequest $request, Ad $ad)
    {
        $ad->update([
            'title' => $request->title ?? $ad->title,
        ]);
        return redirect()->route('ads.list')->with('status' , 'Ad Updated Successfully');
    }

    public function delete(Ad $ad)
    {
        $ad->delete();
        return redirect()->route('ads.list')->with('status' , 'Ad Deleted Successfully');
    }
}
