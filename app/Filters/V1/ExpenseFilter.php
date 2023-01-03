<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

use Illuminate\Http\Request;

class ExpenseFilter extends ApiFilter
{
    protected $safeParms = [
        'id' =>  ['eq'],
        'groupId' =>  ['eq'],
        'amount' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'payer' => ['eq']
    ];

    protected $columnMap = [
        'groupId' => 'group_id',
    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'ne' => '!=',
    ];
}
