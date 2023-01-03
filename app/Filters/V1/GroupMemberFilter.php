<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

use Illuminate\Http\Request;

class GroupMemberFilter extends ApiFilter
{
    protected $safeParms = [
        'id' => ['eq'],
        'appUserId' => ['eq'],
        'groupId' => ['eq'],
        'totalPaid' => ['eq', 'lt', 'gt'],
        'totalShare' => ['eq', 'lt', 'gt']
    ];

    protected $columnMap = [
        'appUserId' => 'app_user_id',
        'groupId' => 'group_id',
        'totalPaid' => 'total_paid',
        'totalShare' => 'total_share'
    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
    ];
}
