<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\GroupMember;
use App\Http\Requests\V1\StoreGroupMemberRequest;
use App\Http\Requests\V1\UpdateGroupMemberRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\GroupMemberResource;
use App\Http\Resources\V1\GroupMemberCollection;
use Illuminate\Http\Request;
use App\Filters\V1\GroupMemberFilter;

class GroupMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = new GroupMemberFilter();
        $filterItems = $filter->transform($request);

        if (count($filterItems) == 0) {
            return new GroupMemberCollection(GroupMember::paginate());
        } else {
            $groupmembers = GroupMember::where($filterItems)->paginate();
            return new GroupMemberCollection($groupmembers->appends($request->query()));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreGroupMemberRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGroupMemberRequest $request)
    {
        return new StoreGroupMemberRequest(GroupMember::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GroupMember  $groupmember
     * @return \Illuminate\Http\Response
     */
    public function show(GroupMember $groupmember)
    {
        return new GroupMemberResource($groupmember);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGroupMemberRequest  $request
     * @param  \App\Models\GroupMember  $groupmember
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGroupMemberRequest $request, GroupMember $groupmember)
    {
        $groupmember->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GroupMember  $groupmember
     * @return \Illuminate\Http\Response
     */
    public function destroy(GroupMember $groupmember)
    {
        //
    }
}
