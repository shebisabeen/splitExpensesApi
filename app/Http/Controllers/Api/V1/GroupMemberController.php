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


    public function createGroupMember(Request $request)
    {

        $memberData = GroupMember::create([
            'group_id' => $request->groupId,
            'app_user_id' => $request->appUserId,
        ]);

        $result = [
            'memberData' => $memberData,
        ];
        return $result;
    }

    public function fetchGroupMembers($id)
    {
        $groups = GroupMember::join('app_users', 'group_members.app_user_id', '=', 'app_users.id')
            ->join('groups', 'group_members.group_id', '=', 'groups.id')
            ->where('group_members.group_id', $id)
            ->get(['group_members.id as memberId', 'app_users.name as userName', 'groups.name as groupName', 'group_members.total_paid as totalPaid', 'group_members.total_share as totalShare']);
        return $groups;
    }

    public function fetchGroupsByUserId($id)
    {
        $groups = GroupMember::join('app_users', 'group_members.app_user_id', '=', 'app_users.id')
            ->join('groups', 'group_members.group_id', '=', 'groups.id')
            ->where('group_members.app_user_id', $id)
            ->get(['group_members.id as memberId', 'app_users.name as userName', 'groups.name as groupName', 'group_members.total_paid as totalPaid', 'group_members.total_share as totalShare']);
        return $groups;
    }

    public function getBalances($id)
    {
        $membersArray = GroupMember::join('app_users', 'group_members.app_user_id', '=', 'app_users.id')
            ->where('group_members.group_id', $id)
            ->get(['group_members.id as memberId', 'app_users.name as fullName', 'total_paid as totalPaid', 'total_share as totalShare']);

        $paidByArray = array();
        $needToPay = array();
        $totalToGet = 0;
        foreach ($membersArray as $memberKey => $memberValue) {
            if ($memberValue['totalPaid'] > $memberValue['totalShare']) {
                $singleUser = array();
                $singleUser['memberId'] = $memberValue['memberId'];
                $singleUser['fullName'] = $memberValue['fullName'];
                $singleUser['toGet'] = $memberValue['totalPaid'] - $memberValue['totalShare'];
                $totalToGet += ($memberValue['totalPaid'] - $memberValue['totalShare']);
                array_push($paidByArray, $singleUser);
            } else if ($memberValue['totalPaid'] < $memberValue['totalShare']) {
                $singleUser = array();
                $singleUser['memberId'] = $memberValue['memberId'];
                $singleUser['fullName'] = $memberValue['fullName'];
                $singleUser['toPay'] = $memberValue['totalShare'] - $memberValue['totalPaid'];
                array_push($needToPay, $singleUser);
            }
        }

        foreach ($paidByArray as $paidMemberKey => $paidMemberValue) {
            $paidByArray[$paidMemberKey]['percent'] = ($paidMemberValue['toGet'] * 100) / $totalToGet;
        }

        foreach ($needToPay as $toPayMemberKey => $toPayMemberValue) {
            $payToUsers = array();
            foreach ($paidByArray as $paidKey => $paidValue) {
                $payment = array();
                $payment['memberId'] = $paidValue['memberId'];
                $payment['fullName'] = $paidValue['fullName'];
                $payment['amount'] = ($toPayMemberValue['toPay'] * $paidValue['percent']) / 100;
                array_push($payToUsers, $payment);
            }
            $needToPay[$toPayMemberKey]['payToUsers'] = $payToUsers;
        }

        $result = [
            'hasToGet' => $paidByArray, 'needToPay' => $needToPay
        ];
        return $result;
    }
}
