<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdvertRequest;
use App\Http\Resources\AdverResource;
use App\Http\Resources\AdvertResource;
use App\Models\Advert;
use App\Models\AdvertImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class AdvertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
          return AdvertResource::collection(Advert::with('image')
              ->sortByFields(request('sort', []))
              ->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdvertRequest $request
     * @return JsonResponse
     */
    public function store(AdvertRequest $request)
    {
        $advert = Advert::create($request->validated());

        foreach ($request->imgPaths as $image){
            AdvertImage::create([
               'advert_id' => $advert->id,
               'path' => $image
            ]);
        }
        return response()->json(['data' => ['id' => $advert->id]],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return AdvertResource
     */
    public function show($id)
    {
//        return response()->json(new AdvertResource(Advert::with('image')->findOrFail($id)), 200);
        return new AdvertResource(Advert::with('image')->findOrFail($id),200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
