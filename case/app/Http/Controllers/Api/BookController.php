<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Models\Member;
use App\Models\Penalty;
use Carbon\Carbon;

class BookController extends Controller
{
    /**
     * @OA\Get(
     *     path="/books/all",
     *     tags={"Books"},
     *     summary="Returns all books",
     *     description="Returns all books",
     *     operationId="getAllBooks",
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="string",example="Success"),
     *             @OA\Property(property="message", type="string",example="Books retrieved successfully"),
     *             @OA\Property(property="data", type="array",
     *                @OA\Items(type="object",
     *                   @OA\Property(property="title", type="string",example="Twilight"),
     *                   @OA\Property(property="stock", type="integer",example=1),
     *              )
     *              ),
     *          )
     *      ),
     *  )
     */
    public function getAll()
    {
        $query = Book::select(['title','stock'])->where('stock','!=',0)->get();

        return Response::success($query, 'Books retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/books/borrowed",
     *     tags={"Books"},
     *     summary="Return Borrowed Book By User",
     *     description="Return Borrowed Book By User",
     *     operationId="postBorrowedBook",
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/BookRequest",
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="string",example="Success"),
     *             @OA\Property(property="message", type="string",example="Books retrieved successfully"),
     *             @OA\Property(property="data", type="array",
     *                @OA\Items(type="object",
     *                   @OA\Property(property="id", type="integer",example=1),
     *                   @OA\Property(property="code", type="string",example="JK-45"),
     *                   @OA\Property(property="title", type="string",example="Twilight"),
     *                   @OA\Property(property="author", type="string",example="J.K Rowling"),
     *                   @OA\Property(property="stock", type="integer",example=1),
     *              )
     *              ),
     *          )
     *      ),
     *
     *      @OA\Response(
     *         response=400,
     *         description="Penalty",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="string",example="error"),
     *             @OA\Property(property="message", type="string",example="Sorry you can`t borrow book, cause you have penalty"),
     *          )
     *      ),
     *
     *      @OA\Response(
     *         response=405,
     *         description="Already Borrowed",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="string",example="error"),
     *             @OA\Property(property="message", type="string",example="Sorry Book Already Borrowed"),
     *          )
     *      ),
     *
     *      @OA\Response(
     *         response=401,
     *         description="too many borrow book",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="string",example="error"),
     *             @OA\Property(property="message", type="string",example="Sorry you borrow too many book"),
     *          )
     *      ),
     *  )
     */
    public function postBorrowedBook(BookRequest $request)
    {
        $data = $request->validated();

        $member = Member::findOrFail($data['member_id']);
        $book   = Book::findOrFail($data['book_id']);

        if($member->penalty != null){
            return Response::error('Sorry you can`t borrow book, cause you have penalty');
        }elseif($member->books()->where('member_id',$data['member_id'])->where('transaction_status','borrowed')->count() == 2){
            return Response::error('Sorry you borrow too many book');
        }elseif($book->members()->where('book_id',$data['book_id'])->where('transaction_status','borrowed')->exists()){
            return Response::error('Sorry Book Already Borrowed');
        }else{
            $member->books()->attach($book,[
                'transaction_status' => 'borrowed',
                'borrowed_at'        => now(),
                'due_date'           => now()->addDays(7)
            ]);

            $book->update([
                'stock' => $book->stock - 1
            ]);

            return Response::success($member->books()->where('transaction_status','borrowed')->get(), 'Book Borrowed Successfully');
        }
    }

        /**
     * @OA\Post(
     *     path="/books/returned",
     *     tags={"Books"},
     *     summary="Return Returned Book By User",
     *     description="Return Returned Book By User",
     *     operationId="postReturnedBook",
     *
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/BookRequest",
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="string",example="Success"),
     *             @OA\Property(property="message", type="string",example="Book Returned Successfully"),
     *             @OA\Property(property="data", type="array",
     *                @OA\Items(type="object",
     *                   @OA\Property(property="id", type="integer",example=1),
     *                   @OA\Property(property="code", type="string",example="JK-45"),
     *                   @OA\Property(property="title", type="string",example="Twilight"),
     *                   @OA\Property(property="author", type="string",example="J.K Rowling"),
     *                   @OA\Property(property="stock", type="integer",example=1),
     *              )
     *              ),
     *          )
     *      ),
     *
     *      @OA\Response(
     *         response=400,
     *         description="Penalty",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="string",example="error"),
     *             @OA\Property(property="message", type="string",example="Thanks you for returning book, but you got a penalty"),
     *          )
     *      ),
     *
     *      @OA\Response(
     *         response=405,
     *         description="Already Returned",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="string",example="error"),
     *             @OA\Property(property="message", type="string",example="Book Already Returned"),
     *          )
     *      ),
     *
     *  )
     */
    public function postReturnedBook(BookRequest $request)
    {
        $data = $request->validated();

        $member = Member::findOrFail($data['member_id']);
        $book   = Book::findOrFail($data['book_id']);

        if($member->books()->where('due_date') <= Carbon::now()->format('Y-m-d')){
            Penalty::create([
                'member_id'     => $data['member_id'],
                'start_date'    => now(),
                'end_date'      => now()->addDays(3)
            ]);

            $member->books()->detach($book);

            $book->update([
                'stock' => $book->stock + 1
            ]);

            return Response::success($book,'Thanks you for returning book, but you got a penalty');
        }elseif($member->books()->where('book_id',$data['book_id'])->where('transaction_status','borrowed')->exists()){
            $member->books()->detach($book);

            $book->update([
                'stock' => $book->stock + 1
            ]);

            return Response::success($book, 'Book Returned Successfully');
        }else{
            return Response::error('Book Already Returned');
        }
    }
}
