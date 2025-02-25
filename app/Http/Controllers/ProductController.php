<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of products with optional search functionality.
     */
    public function views(Request $request)
{
    $search = $request->input('search');

    // Perform search if a search term is provided, otherwise return all products
    $products = Product::query();

    if ($search) {
        $products = $products->where(function ($query) use ($search) {
            $query->where('name', 'LIKE', "%$search%")
                  ->orWhere('description', 'LIKE', "%$search%");
        });
    }

    // Get the products, paginated if needed
    $products = $products->paginate(10);  // You can adjust the pagination as needed

    return view('products.product', compact('products', 'search'));
}


    /**
     * Show form to create a new product.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'required|integer|min:0',
        ]);

        // Handle Image Upload
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('upload/product_images'), $imageName);

        // Create Product
        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imageName,
            'stock' => $request->stock,
        ]);

        return redirect()->back()->with('success', 'Product created successfully!');
    }

    /**
     * Add a product to the cart.
     */
    public function addToCart($id)
    {
        $product = Product::findOrFail($id);

        // Check if stock is available
        if ($product->stock <= 0) {
            return redirect()->route('admin.views')->with('error', 'Product is out of stock!');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            return redirect()->route('admin.views')->with('success', 'Product is already in the cart!');
        }

        // Add product to cart
        $cart[$id] = [
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => 1,
            'image' => $product->image,
        ];

        session()->put('cart', $cart);

        // Reduce stock
        $product->decrement('stock');

        return redirect()->route('admin.views')->with('success', 'Product added to cart!');
    }

    /**
     * Show the shopping cart.
     */
    public function cart()
    {
        return view('admin.cart');
    }

    /**
     * Checkout and clear the cart.
     */
    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => "Your cart is empty."]);
        }

        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        session()->forget('cart');

        return response()->json([
            'success' => true,
            'message' => "Checkout successful.",
            'totalPrice' => $totalPrice
        ]);
    }

    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart successfully!'
        ]);
    }

    /**
     * Update product quantity in the cart.
     */
    public function updateCart(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);
        if (isset($cart[$request->id])) {
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully!'
        ]);
    }

    /**
     * Show the form for editing a product.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;

        if ($request->hasFile('image')) {
            if ($product->image && file_exists(public_path('upload/product_images/' . $product->image))) {
                unlink(public_path('upload/product_images/' . $product->image));
            }

            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('upload/product_images'), $imageName);
            $product->image = $imageName;
        }

        $product->save();

        return redirect()->route('admin.views')->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product from storage.
     */
    /**
 * Remove the specified product from storage.
 */
public function destroy($id)
{
    $product = Product::find($id);

    if (!$product) {
        return redirect()->route('admin.views')->with('error', 'Product not found.');
    }

    // Delete the product's image if it exists
    if ($product->image && file_exists(public_path('upload/product_images/' . $product->image))) {
        unlink(public_path('upload/product_images/' . $product->image));
    }

    $product->delete();

    return redirect()->route('admin.views')->with('success', 'Product deleted successfully!');
}

}
