<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Expense;
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
}
