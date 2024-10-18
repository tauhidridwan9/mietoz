<?php

namespace App\Http\Controllers;

use App\Events\OrderUpdated;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;




class ProductController extends Controller
{
    public function detail($id)
    {
        // Mencari produk berdasarkan ID
        $product = Product::findOrFail($id);

        // Return view 'products.show' dan mengirimkan data produk ke view
        return view('products.detail', compact('product'));
    }
    public function index(Request $request)
    {
        Log::info('Product Filter Request:', [
            'search' => $request->input('search'),
            'price' => $request->input('price'),
            'category' => $request->input('category'),
        ]);

        // Fetch all categories for the filter
        $categories = Category::all();

        // Build the query for products
        $query = Product::query();

        // Apply search filter
        if (!empty($request->search)) {
            Log::info('Search query applied: ' . $request->search);
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        // Apply price filter
        if (!empty($request->price)) {
            Log::info('Price filter applied: ' . $request->price);
            if ($request->price === 'low_to_high') {
                $query->orderBy('price', 'asc');
            } elseif ($request->price === 'high_to_low') {
                $query->orderBy('price', 'desc');
            }
        }

        // Apply category filter
        if (!empty($request->category)) {
            Log::info('Category filter applied: ' . $request->category);
            $query->where('category_id', $request->category);
        }

        // Debugging: Check final SQL query with bindings
        Log::info('Final Product Query: ' . $query->toSql(), $query->getBindings());

        // Fetch products with pagination
        $products = $query->paginate(9);

        // Return the appropriate view with data
        $viewData = [
            'products' => $products,
            'categories' => $categories,
            'search' => $request->search,
            'selectedCategory' => $request->category,
            'selectedPrice' => $request->price,
            'message' => $products->isEmpty() ? 'Tidak ada produk yang ditemukan sesuai pencarian Anda.' : null,
        ];

        if (Auth::check()) {
    if (Auth::user()->hasVerifiedEmail()) {
        if (Auth::user()->hasRole('owner')) {
            return view('products.index', $viewData);
        } elseif (Auth::user()->hasRole('customer')) {
            return view('home', $viewData);
        }
    } else {
        // Jika email belum terverifikasi, arahkan pengguna ke halaman notifikasi verifikasi
        return redirect()->route('verification.notice');
    }
}

        // Jika pengguna belum login
        return view('home', $viewData);
    }



    public function create()
    {
        // Fetch all categories for the select dropdown
        $categories = Category::all();

        // Pass categories to the view
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validate and save the product
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'required|integer|min:0', // Validate stock input
        ]);

        $product = new Product();
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->category_id = $request->input('category_id');
        $product->stock = $request->input('stock');

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = $imagePath;
        }

        $product->save();
        event(new OrderUpdated([
            'customerCount' => User::where('role_id', 1)->count(),
            'orderCount' => Order::where('status', 'paid')->count(),
            'countProcessing' => Order::where('status', 'cash')->count(),
            'countCooking' => Order::where('status', 'processing')->count(),
            'countDiambil' => Order::where('status', 'delivered')->count(),
        ]));

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all(); // Get all categories for the select dropdown

        return view('products.edit', [
            'product' => $product,
            'categories' => $categories
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'stock' => 'required|integer|min:0', // Validate stock input
        ]);

        $imagePath = $request->file('image') ? $request->file('image')->store('products', 'public') : $product->image;

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
            'stock' => $request->stock,
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}

