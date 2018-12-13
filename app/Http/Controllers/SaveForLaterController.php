<?php

namespace App\Http\Controllers;

use function foo\func;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class SaveForLaterController extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Cart::instance('saveForLater')->remove($id);

        return redirect()->route('cart.index')
            ->with('success','Item has been removed');
    }

    /**
     * switch item form save later to cart.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function switchToSaveForLater($id)
    {
        $item = Cart::instance('saveForLater')->get($id);

        Cart::instance('saveForLater')->remove($id);

        $dublicate = Cart::instance('saveForLater')->search(function($cartItem,$rowId) use ($id){
            return $rowId === $id;
        });

        if($dublicate->isNotEmpty())
        {
            return redirect()->route('cart.index')
                ->with('success','Item already in your cart');
        }

        Cart::instance('default')->add($item->id,$item->name,1,$item->price)
        ->associate('App\Product');

        return redirect()->route('cart.index')
            ->with('success','Item has been moved to your cart');

    }
}
