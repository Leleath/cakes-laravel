<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Item;

class ItemsController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/items",
     *      operationId="getAllItems",
     *      tags={"Items"},
     *      summary="Return a list of all items",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       )
     *     )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Item::all();
        return response()->json([
            "status" => true,
            "message" => $items
        ])->setStatusCode(200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @OA\Post(
     *      path="/api/items/store",
     *      operationId="storeNewItem",
     *      tags={"Items"},
     *      summary="Create a new item in the list",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Request looks like",
     *          @OA\JsonContent(
     *              required={"title","description","weight","price"},
     *              @OA\Property(property="title"),
     *              @OA\Property(property="description"),
     *              @OA\Property(property="weight", type="integer"),
     *              @OA\Property(property="price", type="integer"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request",
     *       ),
     *      @OA\Response(
     *          response=201,
     *          description="Successfuly created",
     *       )
     *     )
     *
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                "title" => ['required'],
                "description" => ['required'],
                "weight" => ['required'],
                "price" => ['required']
            ]
        );
        
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => $validator->messages()
            ])->setStatusCode(400);
        }

        $item = Item::create([
            "title" => $request->title,
            "description" => $request->description,
            "weight" => $request->weight,
            "price" => $request->price
        ]);

        return response()->json([
            "status" => true,
            "message" => $item
        ])->setStatusCode(201);
    }

    /**
     * @OA\Get(
     *      path="/api/items/{id}",
     *      operationId="getItemFromList",
     *      tags={"Items"},
     *      summary="Get an item from the list",
     *      @OA\Parameter(
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Item not found",
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      )
     *    )
     *
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Item::find( $id );

        if ( !$item ) {
            return response()->json([
                "status" => false,
                "message" => "Item not found"
            ])->setStatusCode(404);
        }

        return response()->json([
            "status" => true,
            "message" => $item
        ])->setStatusCode(200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * @OA\Post(
     *      path="/api/items/update/{id}",
     *      operationId="updateItem",
     *      tags={"Items"},
     *      summary="Update an item in the list",
     *      @OA\Parameter(
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Request looks like",
     *          @OA\JsonContent(
     *              required={"title","description","weight","price"},
     *              @OA\Property(property="title"),
     *              @OA\Property(property="description"),
     *              @OA\Property(property="weight", type="integer"),
     *              @OA\Property(property="price", type="integer"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Item not found",
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request",
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      )
     *    )
     *
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $item = Item::find( $id );

        if ( !$item ) {
            return response()->json([
                "status" => false,
                "message" => "Item not found"
            ])->setStatusCode(404);
        };

        $validator = Validator::make(
            $request->all(),
            [
                "title" => ['required'],
                "description" => ['required'],
                "weight" => ['required'],
                "price" => ['required']
            ]
        );
        
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => $validator->messages()
            ])->setStatusCode(400);
        };

        $item->title = $request->title;
        $item->description = $request->description;
        $item->weight = $request->weight;
        $item->price = $request->price;
        $item->save();

        return response()->json([
            "status" => true,
            "error" => "Item success updated"
        ])->setStatusCode(200);
    }

    /**
     * @OA\Post(
     *      path="/api/items/delete/{id}",
     *      operationId="deleteItemFromList",
     *      tags={"Items"},
     *      summary="Delete an item from the list",
     *      @OA\Parameter(
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Item not found",
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      )
     *    )
     *
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::find( $id );

        if ( !$item ) {
            return response()->json([
                "status" => false,
                "message" => "Item not found"
            ])->setStatusCode(404);
        };

        $item->delete();

        return response()->json([
            "status" => true,
            "error" => "Item success deleted"
        ])->setStatusCode(200);
    }
}
