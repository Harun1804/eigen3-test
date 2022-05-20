<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema()
 */
class BookRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * @OA\Property(format="integer", default="1", description="ID Member", property="member_id"),
     * @OA\Property(format="integer", default="1", description="ID Book", property="book_id"),
     */
    public function rules()
    {
        return [
            'member_id' => 'required|exists:members,id',
            'book_id'   => 'required|exists:books,id',
        ];
    }
}
