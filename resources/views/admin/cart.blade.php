@extends('layouts.dashboard')

@section('content')
<div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
    <table id="cart" class="table table-hover table-condensed">
        <thead class="thead-dark" style="position: sticky; top: 0; background: white; z-index: 100;">
            <tr>
                <th style="width:50%">Product</th>
                <th style="width:10%">Price</th>
                <th style="width:8%">Quantity</th>
                <th style="width:22%" class="text-center">Subtotal</th>
                <th style="width:10%"></th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
                $userName = Auth::user()->name;
            @endphp

            <!-- Display User's Name Once at the Top of the Cart -->
            <tr>
                <td colspan="5" class="text-center">
                    <h4><strong>{{ $userName }}'s Order</strong></h4>
                </td>
            </tr>

            @if(session('cart'))
                @foreach(session('cart') as $id => $details)
                    @php
                        $total += $details['price'] * $details['quantity'];
                    @endphp
                    <tr data-id="{{ $id }}">
                        <td data-th="Product">
                            <div class="row">
                                <div class="col-sm-3 hidden-xs">
                                    <img src="{{ asset('upload/product_images/' . $details['image']) }}" width="100" height="150" class="img-responsive"/>
                                </div>
                                <div class="col-sm-9">
                                    <h5>{{ $details['name'] }}</h5>
                                </div>
                            </div>
                        </td>
                        <td data-th="Price">${{ $details['price'] }}</td>
                        <td data-th="Quantity">
                            <input type="number" value="{{ $details['quantity'] }}" class="form-control quantity update-cart" />
                        </td>
                        <td data-th="Subtotal" class="text-center">P {{ $details['price'] * $details['quantity'] }}</td>
                        <td class="actions" data-th="">
                            <button class="btn btn-danger btn-sm remove-from-cart"><i class="fa fa-trash-o"></i></button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    <div class="text-right mt-3">
    <h3><strong>Total: P{{ $total }}</strong></h3>
    <a href="{{ route('admin.views') }}" class="btn btn-warning"><i class="fa fa-angle-left"></i> Continue Shopping</a>
    <button class="btn btn-danger checkout-button">Checkout</button>
</div>
</div>

<!-- Footer Total and Actions -->

@endsection

@section('scripts')
<style>
    .table-responsive {
        border: 1px solid #ddd; /* Optional: Add border for better visualization */
    }
</style>
@endsection
