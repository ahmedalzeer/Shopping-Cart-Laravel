@extends('layout')

@section('title', 'Products')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/plugins/dataTables/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/colorbox.css') }}">
@endsection

@section('content')

    <div class="breadcrumbs">
        <div class="container">
            <a href="/">Home</a>
            <i class="fa fa-chevron-right breadcrumb-separator"></i>
            <span>Shop</span>
        </div>
    </div> <!-- end breadcrumbs -->

    <div class="products-section container">
        <div class="sidebar">
            <h3>By Category</h3>
            <ul>
                @foreach($categories as $category)
                <li class="{{setActiveCategory($category->slug)}}"><a href="{{route('shop.index',['category'=>$category->slug])}}">{{$category->name}}</a></li>
                @endforeach
            </ul>

            <h3>By Price</h3>
            <ul>
                <li><a href="#">$0 - $700</a></li>
                <li><a href="#">$700 - $2500</a></li>
                <li><a href="#">$2500+</a></li>
            </ul>
        </div> <!-- end sidebar -->

      <div>
        <div class="products-header">
            <h2> {{$categoryName}} ..............</h2>
            <div>
                <strong>Price : </strong>
                <a href="{{route('shop.index',['category'=>request()->category,'sort'=>'low_high'])}}">Low to High</a>\
                <a href="{{route('shop.index',['category'=>request()->category,'sort'=>'high_low'])}}">High to Low </a>
            </div>
        </div>
          <br/>
          <div class="products">
              @forelse($products as $product)
                  <div class="product text-center">
                      <a href="{{route('shop.show',$product->slug)}}"><img src="/img/macbook-pro.png" alt="product"></a>
                      <a href="{{route('shop.show',$product->slug)}}"><div class="product-name">{{$product->name}}</div></a>
                      <div class="product-price">{{$product->presentPrice($product->price)}}</div>
                  </div>
                  @empty
                  <h4>No items found</h4>
              @endforelse
          </div>
      </div> <!-- end products -->

    </div>

    {{$products->appends(request()->input())->links()}}
@endsection
