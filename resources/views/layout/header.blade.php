<div id="header">
    <div class="header-top">
        <div class="container">
            <div class="pull-left auto-width-left">
                <ul class="top-menu menu-beta l-inline">
                    <li><a href=""><i class="fa fa-home"></i> 90-92 Lê Thị Riêng, Bến Thành, Quận 1</a></li>
                    <li><a href=""><i class="fa fa-phone"></i> 0163 296 7751</a></li>
                </ul>
            </div>
            <div class="pull-right auto-width-right">
                <ul class="top-details menu-beta l-inline">
                    @if(Session::has('user'))
                        <li>
                            <a href="/logout">
                                <i class="fa fa-user"></i> {{ Session::get('user')->name }}
                            </a>
                        </li>
                    @else
                        <li><a href="/register">Đăng kí</a></li>
                        <li><a href="/login">Đăng nhập</a></li>
                    @endif
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="header-body">
        <div class="container beta-relative">
            <div class="pull-left">
                <a href="/index" id="logo"><img src="{{ asset('source/assets/dest/images/logo-cake.png') }}" width="200px" alt=""></a>
            </div>
            <div class="pull-right beta-components space-left ov">
                <div class="space10">&nbsp;</div>
                <div class="beta-comp">
                    <form role="search" method="get" id="searchform" action="/search">
                        <input type="text" name="search" id="s" placeholder="Nhập từ khóa..." />
                        <button class="fa fa-search" type="submit" id="searchsubmit"></button>
                    </form>
                </div>
                <!-- cart -->
                <div class="beta-comp">
                    <div class="cart">
                        <div class="beta-select">
                            <i class="fa fa-shopping-cart"></i> Giỏ hàng
                            ({{ Session::has('cart') ? Session('cart')->totalQty : 0 }})
                            <i class="fa fa-chevron-down"></i>
                        </div>
                        <div class="beta-dropdown cart-body" style="max-width: 352px;">
                            @php
                            $cart = session('cart', null);
                            $product_cart = $cart ? ($cart->items ?? []) : [];
                            @endphp

                            @if (!empty($product_cart) && is_array($product_cart) && count($product_cart) > 0)
                            @foreach($product_cart as $product)
                            <div class="cart-item">
                                <a class="cart-item-delete" href="{{ route('xoagiohang', $product['item']['id']) }}">
                                    <i class="fa fa-times"></i>
                                </a>
                                <div class="media">
                                    <a class="pull-left" href="#">
                                        <img src="source/image/product/{{ $product['item']['image'] }}" alt="">
                                    </a>
                                    <div class="media-body">
                                        <span class="cart-item-title">{{ $product['item']['name'] }}</span>
                                        <span class="cart-item-amount">
                                            {{ $product['qty'] }} X
                                            @if($product['item']['promotion_price'] > 0)
                                            {{ number_format($product['item']['promotion_price'], 0, ',', '.') }} VNĐ
                                            @else
                                            {{ number_format($product['item']['unit_price'], 0, ',', '.') }} VNĐ
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <div class="cart-caption">
                                <div class="cart-total text-right">
                                    Tổng tiền:
                                    <span class="cart-total-value">
                                        {{ number_format($cart->totalPrice ?? 0, 0, ',', '.') }} đồng
                                    </span>
                                </div>
                                <div class="center">
                                    <a href="" class="beta-btn primary">Đặt hàng <i class="fa fa-chevron-right"></i></a>
                                </div>
                            </div>
                            @else
                            <p>Giỏ hàng trống!</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="header-bottom" style="background-color: #0277b8;">
        <div class="container">
            <a class="visible-xs beta-menu-toggle pull-right" href="#"><span class='beta-menu-toggle-text'>Menu</span> <i class="fa fa-bars"></i></a>
            <div class="visible-xs clearfix"></div>
            <nav class="main-menu">
                <ul class="l-inline ov">
                    <li><a href="/index">Trang chủ</a></li>
                    <li><a href="#">Sản phẩm</a>
                        <ul class="sub-menu">
                            @foreach($loai_sp as $type)
                                <li><a href="/index/type_product/{{$type->id}}">{{ $type->name }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li><a href="/about">Giới thiệu</a></li>
                    <li><a href="/contact">Liên hệ</a></li>
                </ul>
                <div class="clearfix"></div>
            </nav>
        </div>
    </div>
</div>