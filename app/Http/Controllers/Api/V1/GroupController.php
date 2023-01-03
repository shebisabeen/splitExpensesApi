<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Group;
use App\Http\Requests\V1\StoreGroupRequest;
use App\Http\Requests\V1\UpdateGroupRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\GroupResource;
use App\Http\Resources\V1\GroupCollection;
use Illuminate\Http\Request;
use App\Filters\V1\GroupFilter;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = new GroupFilter();
        $filterItems = $filter->transform($request);

        if (count($filterItems) == 0) {
            return new GroupCollection(Group::paginate());
        } else {
            $groups = Group::where($filterItems)->paginate();
            return new GroupCollection($groups->appends($request->query()));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreGroupRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGroupRequest $request)
    {
        return new StoreGroupRequest(Group::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        return new GroupResource($group);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGroupRequest  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGroupRequest $request, Group $group)
    {
        $group->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        //
    }
}
