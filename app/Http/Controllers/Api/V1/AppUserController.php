<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\AppUser;
use App\Http\Requests\V1\StoreAppUserRequest;
use App\Http\Requests\V1\UpdateAppUserRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\AppUserResource;
use App\Http\Resources\V1\AppUserCollection;
use Illuminate\Http\Request;
use App\Filters\V1\AppUserFilter;

class AppUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = new AppUserFilter();
        $filterItems = $filter->transform($request);

        if (count($filterItems) == 0) {
            return new AppUserCollection(AppUser::paginate());
        } else {
            $appusers = AppUser::where($filterItems)->paginate();
            return new AppUserCollection($appusers->appends($request->query()));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAppUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAppUserRequest $request)
    {
        return new AppUserResource(AppUser::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AppUser  $appuser
     * @return \Illuminate\Http\Response
     */
    public function show(AppUser $appuser)
    {
        return new AppUserResource($appuser);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAppUserRequest  $request
     * @param  \App\Models\AppUser  $appuser
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAppUserRequest $request, AppUser $appuser)
    {
        $appuser->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AppUser  $appuser
     * @return \Illuminate\Http\Response
     */
    public function destroy(AppUser $appuser)
    {
        //
    }
}
