<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request){
        $perPage = $request->input('per_page', 10);
        
        $allowedPerPages = [10, 25, 50, 100];

        if (!in_array($perPage, $allowedPerPages)) {
            $perPage = 10;
        }
        
        $products = Product::latest()->paginate($perPage)->withQueryString();
        
        return Inertia::render('products/index', [
            'products' => $products,
            'perPage' => (int) $perPage,
        ]);
    }  
    
    public function create(){
        return Inertia::render('products/create', []);
    }

    public function store(Request $request){
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        Product::create($data);

        return redirect()->route('products.index')->with('message', 'Producto creado exitosamente');
    }
    
    public function edit(Product $product){
        return inertia::render('products/edit', compact('product'));
    }    
    
    public function update(Request $request, Product $product){

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        $product->update([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('products.index')->with('message', 'Producto '.$product['name'].', actualizado exitosamente');
    }
    
    public function delete(Product $product){
        $product->delete();
        return redirect()->route('products.index')->with('message', 'Producto eliminado exitosamente');
    }
}
