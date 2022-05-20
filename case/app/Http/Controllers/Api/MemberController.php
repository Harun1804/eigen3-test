<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;

class MemberController extends Controller
{
    /**
     * @OA\Get(
     *     path="/members/all",
     *     tags={"Members"},
     *     summary="Returns all members",
     *     description="A all members",
     *     operationId="getAllMembers",
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="string",example="Success"),
     *             @OA\Property(property="message", type="string",example="Members retrieved successfully"),
     *             @OA\Property(property="data", type="array",
     *                @OA\Items(type="object",
     *                   @OA\Property(property="id", type="integer",example=1),
     *                   @OA\Property(property="code", type="string",example="M001"),
     *                   @OA\Property(property="name", type="string",example="Angga"),
     *                   @OA\Property(property="created_at", type="string",example="2022-05-20T08:17:32.000000Z"),
     *                   @OA\Property(property="updated_at", type="string",example="2022-05-20T08:17:32.000000Z"),
     *                   @OA\Property(property="books_count", type="integer",example=0),
     *              )
     *              ),
     *          )
     *      ),
     *  )
     */
    public function getAll()
    {
        $query = Member::withCount(['books' => function($q){
            $q->where('transaction_status', 'borrowed');
        }])->get();

        return Response::success($query, 'Members retrieved successfully');
    }
}
