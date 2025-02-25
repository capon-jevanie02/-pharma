@extends('layouts.user_design')

@section('content')
<div class="container mt-4">
    <div class="cart-container">
        <div class="cart-header">
            <h2 class="text-center text-light">Shopping Cart</h2>
        </div>

        <!-- Scrollable Cart Table -->
        <div class="cart-table-wrapper">
            <div class="table-responsive cart-table">
                <table id="cart" class="table table-hover table-dark">
                    <thead class="table-warning sticky-header">
                        <tr>
                            <th style="width:50%">Product</th>
                            <th style="width:10%">Price</th>
                            <th style="width:8%">Quantity</th>
                            <th style="width:22%" class="text-center">Subtotal</th>
                            <th style="width:10%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0 @endphp
                        @if(session('cart'))
                            @foreach(session('cart') as $id => $details)
                                @php $total += $details['price'] * $details['quantity'] @endphp
                                <tr data-id="{{ $id }}">
                                    <td data-th="Product">
                                        <div class="row">
                                            <div class="col-sm-3 hidden-xs">
                                                <img src="{{ asset('upload/product_images/' . $details['image']) }}" width="100" height="150" class="img-responsive"/>
                                            </div>
                                            <div class="col-sm-9">
                                                <h4 class="nomargin">{{ $details['name'] }}</h4>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-th="Price">P{{ $details['price'] }}</td>
                                    <td data-th="Quantity">
                                        <input type="number" value="{{ $details['quantity'] }}" class="form-control quantity update-cart" />
                                    </td>
                                    <td data-th="Subtotal" class="text-center">P{{ $details['price'] * $details['quantity'] }}</td>
                                    <td class="actions" data-th="">
                                        <button class="btn btn-danger btn-sm remove-from-cart"><i class="fa fa-trash-o"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Cart Footer -->
        <div class="cart-footer">
            <h3 class="text-right text-light"><strong>Total: P{{ $total }}</strong></h3>
            <div class="text-right">
                <a href="{{ route('user.product') }}" class="btn btn-warning"><i class="fa fa-angle-left"></i> Continue Shopping</a>
                <button class="btn btn-success checkout-button"><i class="fa fa-shopping-cart"></i> Checkout</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Dark theme styles */
    body {
        background-color: #121212;
        color: white;
    }

    /* Cart container */
    .cart-container {
        max-width: 900px;
        margin: auto;
        background: #1e1e1e;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0px 5px 15px rgba(255, 215, 0, 0.2);
    }

    /* Cart Header */
    .cart-header {
        padding: 10px;
        text-align: center;
        border-bottom: 2px solid #facc15;
    }

    /* Cart Table Wrapper for Scroll */
    .cart-table-wrapper {
        max-height: 400px; /* Set a fixed height for scrolling */
        overflow-y: auto;
    }

    /* Cart Table */
    .cart-table {
        width: 100%;
    }

    /* Sticky Header */
    .sticky-header {
        position: sticky;
        top: 0;
        background: #facc15;
        color: black;
        z-index: 10;
    }

    /* Cart Footer */
    .cart-footer {
        padding: 10px;
        border-top: 2px solid #facc15;
        margin-top: 10px;
    }
</style>
@endsection
