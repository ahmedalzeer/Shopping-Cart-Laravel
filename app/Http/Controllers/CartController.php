<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Cart;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mightAlsoLike = Product::mightAlsolike(4)->get();

        return view('cart')
            ->with('mightAlsoLike',$mightAlsoLike);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dublicate = Cart::search(function($cardItem,$rowId) use ($request){
            return $cardItem->id === $request->id;
        });

        if($dublicate->isNotEmpty())
        {
            return redirect()->route('cart.index')
                ->with('success','Item already in your cart');
        }

       Cart::add($request->id,$request->name,1,$request->price)
       ->associate('App\Product');

       return redirect()->route('cart.index')
           ->with('success','Item was add to your cart');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = $this->validate($request,[
            'quantity' => 'required|numeric|between:1,5'
        ]);

        if($validator->fails())
        {
            session()->flash('errors',collect(['Quantity should be between 1 and 5']));

            return response()->json(['success'=> false],400);
        }

        Cart::update($id,$request->quantity);

        session()->flash('success','Quantity was updated successfully');

        return response()->json(['success'=>true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Cart::remove($id);

        return back()->with('success','Item has been removed');
    }

    /**
     * switch to save for later to cart
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function switchToCart($id)
    {
        $item = Cart::get($id);

        Cart::remove($id);

        $dublicate = Cart::instance('saveForLater')->search(function($cartItem,$rowId) use ($id){
            return $rowId == $id;
        });

        if($dublicate->isNotEmpty())
        {
            return redirect()->route('cart.index')->with('success','Item already saved for later');
        }

        Cart::insatance('saveForLater')->add($item->id,$item->name,1,$item->price)
        ->associate('App\Product');

        return redirect()->route('cart.index')
            ->with('success','Item has been saved for later');
    }
}
