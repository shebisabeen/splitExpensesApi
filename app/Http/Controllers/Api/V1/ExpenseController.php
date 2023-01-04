<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Expense;
use App\Models\GroupMember;
use App\Models\AppUser;
use App\Http\Requests\V1\StoreExpenseRequest;
use App\Http\Requests\V1\UpdateExpenseRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ExpenseResource;
use App\Http\Resources\V1\ExpenseCollection;
use Illuminate\Http\Request;
use App\Filters\V1\ExpenseFilter;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = new ExpenseFilter();
        $filterItems = $filter->transform($request);

        if (count($filterItems) == 0) {
            return new ExpenseCollection(Expense::paginate());
        } else {
            $expenses = Expense::where($filterItems)->paginate();
            return new ExpenseCollection($expenses->appends($request->query()));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreExpenseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreExpenseRequest $request)
    {
        return new StoreExpenseRequest(Expense::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        return new ExpenseResource($expense);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateExpenseRequest  $request
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        $expense->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        //
    }
    public function createExpense(Request $request)
    {
        $expenseData = Expense::create([
            'group_id' => $request->groupId,
            'description' => $request->description,
            'amount' => $request->amount,
            'payer' => $request->payer,
            'split' => json_encode($request->split),
        ]);
        $payerData = GroupMember::where('id', $request->payer)->increment('total_paid', $request->amount);
        $splitData = $request->split;

        $memberDatas = [];
        foreach ($splitData as $key => $value) {
            $memberData = GroupMember::where('id', $value['memberId'])->increment('total_share', $value['amount']);
            array_push($memberDatas, $memberData);
        }

        $result = [
            'expenseData' => $expenseData,
            'payerData' => $payerData,
            'memberDatas' => $memberDatas,
        ];
        return $result;
    }


    public function expensesDetailsByGroup($id)
    {
        $expenseData = Expense::where('group_id', $id)
            ->get(['expenses.id', 'expenses.description', 'expenses.group_id as groupId', 'expenses.amount', 'expenses.payer', 'expenses.split']);

        foreach ($expenseData as $expenseKey => $expenseValue) {
            $splitData = [];
            $splitData = json_decode($expenseValue['split'], true);
            foreach ($splitData as $splitKey => $splitValue) {
                $appUserName = GroupMember::join('app_users', 'group_members.app_user_id', '=', 'app_users.id')
                    ->where('group_members.id', $splitValue['memberId'])
                    ->get(['app_users.name']);
                $splitData[$splitKey]['userName'] = $appUserName[0]['name'];
            }
            $expenseData[$expenseKey]['split'] = $splitData;
        }
        return $expenseData;
    }
}
