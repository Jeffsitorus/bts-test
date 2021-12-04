<?php

namespace App\Http\Controllers;

use App\Models\Shopping;
use Illuminate\Http\Request;
use App\Http\Requests\StoreShoppingRequest;

class ShoppingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shoppings = Shopping::query()->get();

        return response()->json(['data' => $shoppings, 'message' => 'Success', 'status' => 'success'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreShoppingRequest $request)
    {
        $shopping = new Shopping();

        $shopping->CreatedDate = $request->createddate;
        $shopping->name = $request->name;

        $shopping->save();

        return response()->json(['data' => $shopping, 'message' => 'Success', 'status' => 'success'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $shopping = Shopping::query()->findOrFail($id);

        return response()->json(['data' => $shopping, 'message' => 'Success', 'status' => 'success'], 201);
    }

    //update shopping
    public function update(Request $request, $id)
    {
        $shopping = Shopping::query()->findOrFail($id);

        if ($shopping) {
            ($request->createddate) ? $shopping->CreatedDate  = $request->createddate : '';
            ($request->name) ? $shopping->name   = $request->name : '';

            $shopping->update();

            return response()->json(['data' => $shopping, 'message' => 'Shopping updated successfully', 'status' => 'success'], 200);
        }
    }

    //delete shopping
    public function destroy($id)
    {
        $shopping = Shopping::findOrFail($id);
        $shopping->delete();

        return response()->json(['data' => $shopping, 'message' => 'Shopping deleted successfully', 'status' => 'success'], 200);
    }
}
