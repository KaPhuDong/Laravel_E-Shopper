@extends('master')
@section('content')

<div class="container">
    <div id="content" class="space-top-none">
        <div class="main-content">
            <div class="space60">&nbsp;</div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="beta-products-list">
                        <h4 class="text-center my-4">Kết quả tìm kiếm cho: <span style="color: red">{{ $key }}</span></h4>
                        <br>
                        @if($products->count() > 0)
                        <div class="row">
                            @foreach($products as $product)
                            <div class="col-sm-3">
                                <div class="single-item">
                                    <div class="single-item-header">
                                        <a href="index/detail/{{$product->id}}"><img width="200" height="200"
                                                src="/source/image/product/{{$product->image}}" alt=""></a>
                                    </div>
                                    @if($product->promotion_price==!0)
                                    <div class="ribbon-wrapper">
                                        <div class="ribbon sale">Sale</div>
                                    </div>
                                    @endif
                                    <div class="single-item-body">
                                        <p class="single-item-title">{{$product->name}}</p>
                                        <p class="single-item-price" style="text-align:left;font-size: 15px;">
                                            @if($product->promotion_price==0)

                                            <span class="flash-sale">{{number_format($product->unit_price)}} Đồng</span>
                                            @else
                                            <span class="flash-del">{{number_format($product->unit_price)}} Đồng </span>
                                            <span class="flash-sale">{{number_format($product->promotion_price)}} Đồng</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="single-item-caption">
                                        <a class="add-to-cart pull-left" href="/add-to-cart/{{$product->id}}">
                                            <i class="fa fa-shopping-cart"></i>
                                        </a>
                                            
                                        <a class="add-to-wishlist" href="wishlist/add/{{$product->id}}">
                                            <i class="fa fa-heart"></i>
                                        </a>

                                        <a class="beta-btn primary" href="detail/{{$product->id}}">Details
                                            <i class="fa fa-chevron-right"></i>
                                        </a>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-center text-muted">Không tìm thấy sản phẩm nào.</p>
                        @endif

                        <div class="row">{{$products->links("pagination::bootstrap-4")}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection