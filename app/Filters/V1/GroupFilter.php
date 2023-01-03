<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

use Illuminate\Http\Request;

class GroupFilter extends ApiFilter
{
    protected $safeParms = [
        'id' =>  ['eq'],
        'createdBy' => ['eq']
    ];

    protected $columnMap = [
        'createdBy' => 'created_by',
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
