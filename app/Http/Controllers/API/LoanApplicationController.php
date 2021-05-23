<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\LoanApplicationResource;
use App\Models\LoanApplication;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class LoanApplicationController extends Controller
{
    /**
     * @OA\Get (
     *      path="/api/loan-application",
     *      tags={"Loan Application"},
     *      summary="GET Loan Applications",
     *      operationId="GET",
     *      security={{"bearerAuth": {}}},
     *  @OA\Response(
     *      response=200,
     *      description="Success",
     *     @OA\JsonContent(
     *          @OA\Property(
     *              property="loan_applications",
     *              type="array",
     *            @OA\Items(
     *              type="object",
     *              @OA\Property(
     *                  property="id",
     *                  type="integer",
     *                  example=1
     *              ),
     *              @OA\Property(
     *                  property="loan_term",
     *                  type="integer",
     *                  example=10
     *              ),
     *              @OA\Property(
     *                  property="amount_required",
     *                  type="integer",
     *                  example=10000
     *              ),
     *              @OA\Property(
     *                  property="status",
     *                  type="string",
     *                  example="PENDING"
     *              )
     *           )
     *          )
     *     )
     *  ),
     *  @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Validation Error"),
     *       @OA\Property(
     *          property="errors",
     *          type="object",
     *          @OA\Property(
     *              property="loan_term",
     *              type="array",
     *              @OA\Items(type="string", example="The loan term field is required.")
     *          )
     *       )
     *     )
     *  ),
     * ),
     */
    public function index()
    {
        $loans = LoanApplication::where('user_id', auth()->user()->id)->get();

        return response(['loan_applications' =>  LoanApplicationResource::collection($loans)]);
    }

    /**
     * @OA\Post(
     *      path="/api/loan-application",
     *      tags={"Loan Application"},
     *      summary="Create Loan Application",
     *      operationId="create",
     *      security={{"bearerAuth": {}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"amount_required","loan_term","loan_start_date"},
     *       @OA\Property(property="amount_required", type="integer", example=100000),
     *       @OA\Property(property="loan_term", type="integer", format="password", example=10),
     *       @OA\Property(property="loan_start_date", type="string", format="date", example="2020-06-01")
     *    ),
     * ),
     *  @OA\Response(
     *      response=201,
     *      description="Success",
     *     @OA\JsonContent(
     *          @OA\Property(
     *              property="loan_application",
     *              type="object",
     *              @OA\Property(
     *                  property="id",
     *                  type="integer",
     *                  example=1
     *              ),
     *              @OA\Property(
     *                  property="loan_term",
     *                  type="integer",
     *                  example=10
     *              ),
     *              @OA\Property(
     *                  property="amount_required",
     *                  type="integer",
     *                  example=10000
     *              ),
     *              @OA\Property(
     *                  property="status",
     *                  type="string",
     *                  example="PENDING"
     *              )
     *          )
     *     )
     *  ),
     *  @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Validation Error"),
     *       @OA\Property(
     *          property="errors",
     *          type="object",
     *          @OA\Property(
     *              property="loan_term",
     *              type="array",
     *              @OA\Items(type="string", example="The loan term field is required.")
     *          )
     *       )
     *     )
     *  ),
     * ),
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator =  Validator::make($data, [
           'amount_required' => 'required|integer',
           'loan_term' => 'required|integer',
           'loan_start_date' => 'required|date'
        ]);

        if($validator->fails()) {
            return response(['error' => $validator->errors(), 'message' => 'Validation Error']);
        }

        $loanApplication = LoanApplication::create(
            [
                'amount_required' => $request->amount_required,
                'loan_term' => $request->loan_term,
                'status'    => LoanApplication::LOAN_PENDING_STATUS,
                'loan_start_date' => $request->loan_start_date,
                'user_id'   => auth()->user()->id
            ]
        );

        return response(['loan_application' => new LoanApplicationResource($loanApplication)], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LoanApplication  $loanApplication
     * @return \Illuminate\Http\Response
     */
    public function show(LoanApplication $loanApplication)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LoanApplication  $loanApplication
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LoanApplication $loanApplication)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LoanApplication  $loanApplication
     * @return \Illuminate\Http\Response
     */
    public function destroy(LoanApplication $loanApplication)
    {
        //
    }
}
