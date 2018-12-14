<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pagination = 9;
        $categories = Category::all();

        if(\request()->category)
        {
            $products = Product::with('categories')->wherehas('categories',function ($query){
                return $query->where('slug',\request()->category);
            });
            $categoryName = optional(Category::where('slug',\request()->category)->first())->name;
        }else
        {
            $products   = Product::where('featured',true);
            $categoryName = 'Featured';
        }

        if(\request()->sort == 'low_high')
        {
            $products = $products->orderBy('price','ASC')->paginate($pagination);
        }
        elseif (\request()->sort == 'high_low')
        {
            $products = $products->orderBy('price','DSEC')->paginate($pagination);
        }
        else
        {
            $products = $products->paginate($pagination);
        }

        return view('shop')->with([
            'products'    =>$products,
            'categories'  =>$categories,
            'categoryName'=>$categoryName
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $product       = Product::where('slug',$slug)->firstOrFail();
        $mightAlsoLike = Product::where('slug','!=',$slug)->mightAlsoLike(4)->get();

        return view('product')
            ->with([
                'mightAlsoLike'=>$mightAlsoLike,
                'product'      =>$product,
                ]);
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
