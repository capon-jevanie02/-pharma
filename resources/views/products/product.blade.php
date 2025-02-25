@extends('layouts.dashboard')

@section('content')
<div style="max-height: 80vh; overflow-y: auto; padding: 15px; border: 1px solid #ddd; border-radius: 5px;">
    <h1 class="mt-4 mb-4">Product List</h1>

    <!-- Fixed Controls -->
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <i class="fa fa-angle-left"></i> Dashboard
        </a>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createProductModal">
            <i class="fa fa-plus"></i> Create Product
        </button>
        <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
            <i class="fa fa-shopping-cart" aria-hidden="true"></i> Cart 
            <span class="badge badge-pill badge-danger">{{ count((array) session('cart')) }}</span>
        </button>
    </div>

    <!-- Scrollable Product List -->
    <div class="row">
        @if($products->count())
            @foreach($products as $product)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="{{ asset('upload/product_images/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">{{ $product->description }}</p>
                            <p class="card-text text-success">P{{ $product->price }}</p>

                            @php
                                $cart = session('cart', []);
                            @endphp
                            @if(isset($cart[$product->id]))
                                <button class="btn btn-secondary" disabled>Added to Cart</button>
                            @else
                                <a href="{{ route('admin.add.to.cart', $product->id) }}" class="btn btn-primary">Add to Cart</a>
                            @endif

                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-center">No products found.</p>
        @endif
    </div>
</div>

<!-- Create Product Modal -->
<div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createProductModalLabel">Create Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="productName" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="productName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="productDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="productDescription" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="productPrice" class="form-label">Price</label>
                        <input type="number" class="form-control" id="productPrice" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="productImage" class="form-label">Image</label>
                        <input type="file" class="form-control" id="productImage" name="image" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
