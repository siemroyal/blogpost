<?php

namespace App\Http\Controllers\backend;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$categories = Category::where('status',1)->paginate(10);
        $categories = Category::with('children')
        ->where('status',1)->paginate(50);

        // $categories = Category::with(['children' => function ($query) {
        // $query->where('status', 1);
        // }])
        // ->where('status', 1)
        // ->paginate(10);

        // $categories = Category::with('children')
        // ->where('status', 1)
        // ->whereHas('children', function ($query) {
        //     $query->where('status', 1);
        // })
        // ->paginate(10);

        // $categories = Category::with(['children' => function ($q) {
        // $q->where('status', 1);
        // }])
        // ->whereNull('parent_id')
        // ->where('status', 1)
        // ->paginate(10);

        return view('categories.index',compact('categories'));
        //1. Associative Array
        //$students = ['Dara', 'Sophea', 'Rithy', 'Vanna','Sokun', 'Chenda', 'Ratanak'];
        //$skills = ['PHP', 'Laravel', 'JavaScript', 'VueJS', 'HTML', 'CSS', 'MySQL'];
        //return view('categories.index',['students'=>$students,'skills'=>$skills]);
        //2. Using compact
        //return view('categories.index',compact('students','skills'));
        //3. Uing with method

        // return view('categories.index')->with([
        //     'students' => $students,
        //     'skills'   => $skills,
        // ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //$categories = Category::where('status',1)->get();
        //$categories = Category::where('parent_id',null)->where('status',1)->get();
        $categories = Category::where('status',1)
        ->select('id','name','parent_id')
        ->orderBy('name','DESC')
        ->whereNull('parent_id')
        ->limit(20)
        ->get();
        // $categories = DB::select('SELECT id,name,parent_id FROM categories
        // WHERE status=1 AND parent_id IS NULL ORDER BY name DESC LIMIT 20');
        // SELECT id,name,parent_id FROM categories WHERE status=1 AND parent_id IS NULL ORDER BY name DESC LIMIT 20
        return view('categories.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:categories,name|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:0,1',
        ]);
        $slug = Str::slug($validated['name']);
        $status = $validated['status'] ?? 0;
        $imagePath = null;
        if($request->hasFile('image')){
            $image = $request->file('image');
            $imageName = time().'_'.$image->getClientOriginalName();
            //$image->move(public_path('categories'), $imageName);
            $image->storeAs('categories', $imageName, 'public');
            //php artisan storage:link
            $imagePath = 'categories/'.$imageName;
        }
        $catogory = Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'parent_id' => $validated['parent_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'status' => $status,
        ]);
        return redirect()->route('categories.index')->with('success','Category created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::findOrFail($id);
        return view('categories.show',compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::where('status',1)
        ->select('id','name','parent_id')
        ->orderBy('name','DESC')
        ->whereNull('parent_id')
        ->limit(20)
        ->get();
        return view('categories.edit',compact('category','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:categories,name,'.$category->id.'|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:0,1',
        ]);
        $slug = Str::slug($validated['name']);
        $status = $validated['status'] ?? 0;
        $imagePath = $category->image;
        if($request->hasFile('image')){
            $image = $request->file('image');
            $imageName = time().'_'.$image->getClientOriginalName();
            //$image->move(public_path('categories'), $imageName);
            $image->storeAs('categories', $imageName, 'public');
            //php artisan storage:link
            $imagePath = 'categories/'.$imageName;
        }
        $category->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'parent_id' => $validated['parent_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'status' => $status,
        ]);
        return redirect()->route('categories.index')->with('success','Category updated successfully!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('categories.index')->with('success','Category deleted successfully!');
    }
}
