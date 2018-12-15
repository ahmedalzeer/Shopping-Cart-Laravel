<?php

namespace App\Http\Controllers;

use Cartalyst\Stripe\Exception\CardErrorException;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Couchbase\Exception;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    private function getNumbers()
    {
        $tax          = config('cart.tax') / 100 ;
        $discount     = session()->get('coupon')['discount'] ?? 0 ;
        $newSubtotal  = (Cart::subtotal() - $discount);
        $newTotal     = $newSubtotal * (1 + $tax) ;
        $newTax       = $newSubtotal * $tax ;
        return collect([
            'tax'         => $tax,
            'discount'    => $discount,
            'newSubtotal' => $newSubtotal,
            'newTax'      => $newTax,
            'newTotal'    => $newTotal
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('checkout')->with([
            'discount'    => $this->getNumbers()->get('discount'),
            'newSubtotal' => $this->getNumbers()->get('newSubtotal'),
            'newTax'      => $this->getNumbers()->get('newTax'),
            'newTotal'    => $this->getNumbers()->get('newTotal')
        ]);
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
        $this->validate($request,[
            'email'          => 'required | email',
            'name'           => 'required',
            'address'        => 'required',
            'city'           => 'required',
            'province'       => 'required',
            'postalcode'     => 'required',
            'phone'          => 'required',
        ]);

        $contents = Cart::content()->map(function ($item){
            return $item->modal->slug.' , '.$item->qty;
        })->value()->toJson();
       try{
           $charge = Stripe::charges()->create([
               'amount'        => $this->getNumbers()->get('newTotal'),
               'currancy'      => 'USD',
               'source'        => $request->stripeToken,
               'description'   =>'order',
               'receipt_email' => $request->email,
               'metadata'      => [
                   'contents'  => $contents,
                   'quantify'  => Cart::instance('default')->count(),
                   'discount'  => collect(session()->get('coupon'))->toJson()
               ]
           ]);

           Cart::instance('default')->destroy();
           session()->forget('coupon');

           return redirect()->route('confirmation.index')
               ->with('success','Your payment has been accepted successfully');
       }catch (CardErrorException $e)
       {
            return back()->with('error',$e->getMessage());
       }
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
