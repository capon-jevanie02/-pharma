<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Added Auth import
use Illuminate\Http\RedirectResponse; // Added RedirectResponse import
use App\Models\Product;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function userDashboard()
    {
        return view("user.user_dashboard");
    }

    /**
     * Log out the user.
     */
    public function UserLogout(Request $request): RedirectResponse
    {
        Auth::logout(); // Logs out the user
        $request->session()->invalidate(); // Clears session
        $request->session()->regenerateToken(); // Prevents CSRF issues

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Display a listing of products with optional search functionality.
     */
    public function views(Request $request)
    {
        $search = $request->input('search');

        // Fetch products based on search query if provided
        $products = $search
            ? Product::where('name', 'LIKE', "%$search%")
                ->orWhere('description', 'LIKE', "%$search%")
                ->get()
            : Product::all(); // Get all products if no search

        return view('user.product', compact('products', 'search'));
    }

    /**
     * Add a product to the cart.
     */
    public function addToCart($id)
    {
        $product = Product::find($id);
    
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }
    
        $cart = session()->get('cart', []);
    
        if (!isset($cart[$id])) {
            $cart[$id] = [
                "name" => $product->name,
                "price" => $product->price,
                "quantity" => 1,
                "image" => $product->image
            ];
        } else {
            $cart[$id]['quantity']++;
        }
    
        session()->put('cart', $cart);
    
        return redirect()->route('user.product')->with('success', 'Product added to cart successfully!');
    }
    
    


    /**
     * Show the shopping cart.
     */
    public function cart()
    {
        return view('user.cart'); // Changed from 'admin.cart' to 'user.cart'
    }

    /**
     * Checkout and clear the cart.
     */
    public function checkout()
{
    $cart = session()->get('cart', []);

    if (empty($cart)) {
        return response()->json([
            'message' => 'Your cart is empty. Please add items before checkout.'
        ]);
    }

    $totalPrice = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

    session()->forget('cart');

    return response()->json([
        'message' => 'Checkout successful!',
        'totalPrice' => $totalPrice
    ]);
}


    /**
     * Remove a product from the cart.
     */
    public function remove(Request $request)
    {
        $request->validate([
            'id' => 'required|integer' // Ensure the ID is valid
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$request->id])) {
            unset($cart[$request->id]); // Remove the product from the cart
            session()->put('cart', $cart); // Save the updated cart to session
            return redirect()->back()->with('success', 'Product removed successfully'); // Fixed missing return statement
        }

        return redirect()->back()->with('error', 'Product not found in cart');
    }

    /**
     * Show user names.
     */
    public function showUserNames()
    {
        // Retrieve users excluding those with the role 'admin'
        $users = User::where('role', 'user')->get();

        // Pass the users to the view
        return view('admin.names', compact('users'));
    }

    /**
     * Update user name.
     */
    public function update(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
        ]);

        // Find the user by ID and update the name
        $user = User::find($request->user_id);
        $user->name = $request->name;
        $user->save();

        // Redirect back with success message
        return redirect()->route('admin.names')->with('success', 'User name updated successfully.');
    }

    /**
     * Delete a user.
     */
    public function destroy($id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        // Delete the user
        $user->delete();

        // Redirect or return a response
        return redirect()->route('admin.names')->with('success', 'User deleted successfully'); // Fixed incorrect route
    }
}
