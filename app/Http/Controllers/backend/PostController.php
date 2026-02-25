<?php

namespace App\Http\Controllers\backend;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRequest;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct()
    {
        View::share('trashCount', Post::onlyTrashed()->count());
    }
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     $filters = $request->only(['search', 'status', 'category']);
    //     $posts = Post::with(['category', 'user'])
    //         ->when($filters['search'] ?? null, function ($q, $search) {
    //             $q->where('title', 'like', "%$search%")
    //             ->orWhere('body', 'like', "%$search%");
    //         })
    //         ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
    //         ->when($filters['category'] ?? null, fn ($q, $category) => $q->where('category_id', $category))
    //         ->latest()
    //         ->paginate(5);

    //     $categories = Category::orderBy('name')->get();

    //     return view('posts.index', compact('posts', 'categories'));
    // }

    public function index(Request $request)
    {
        $posts = Post::with(['category', 'user'])
            ->when($request->search, fn ($q) => $q->search($request->search))
            ->when($request->status, fn ($q) => $q->status($request->status))
            ->when($request->category, fn ($q) => $q->category($request->category))
            ->latest()
            ->paginate(5);

        $categories = Category::orderBy('name')->get();

        return view('posts.index', compact('posts', 'categories'));
    }

    // public function index(Request $request)
    // {
    //     $posts = Post::with(['category:id,name','user:id,name']);
    //     if($request->filled('search')){
    //         $search = $request->input('search');
    //         $q = $posts->where(function($query) use ($search){
    //             $query->where('title','like',"%{$search}%")
    //                 ->orWhere('body','like',"%{$search}%");
    //         });
    //         if($request->filled('status')){
    //             $status = $request->input('status');
    //             $q->where('status',$status);
    //         }
    //         if($request->filled('category')){
    //             $category = $request->input('category');
    //             $q->where('category_id',$category);
    //         }
    //     };
    //     $posts = $posts->latest()->paginate(5)->appends(request()->query());
    //     $categories = Category::orderBy('name', 'asc')->get();
    //     return view('posts.index', compact('posts','categories'));
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name', 'asc')->where('status', true)->get();
        return view('posts.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try{
            $data = $request->validated();
            $data['user_id'] = 1; // Temporary static user_id auth()->id();
            $data['slug'] = Str::slug($request->title);
            if($request->hasFile('post_image')){
                $imagePath = $request->file('post_image');
                $imageName = time().'_'.$imagePath->getClientOriginalName();
                $directory = 'posts';
                if(!Storage::disk('public')->exists($directory)){
                    Storage::disk('public')->makeDirectory($directory);
                }
                $imagePath->storeAs('posts', $imageName, 'public');
                $data['post_image'] = $directory.'/'.$imageName;
            }
            $post = Post::create($data);
            if($post){
                return redirect()->route('posts.index')->with('success','Post created successfully!');
            }else{
                return back()->with('error','Failed to create the post. Please try again.')->withInput();
            }
        }catch(\Exception $e){
            return back()->with('error','An error occurred while creating the post: '.$e->getMessage())
            ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::with(['category:id,name','user:id,name'])->findOrFail($id);
        //$post = Post::withUserAndCategory()->findOrFail($id);
        return view("posts.show")->with('post',$post);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categories = Category::orderBy('name', 'asc')
        ->where('status', true)->get();
        $post = Post::with(['category:id,name','user:id,name'])->findOrFail($id);
        return view('posts.edit',compact("post","categories"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Post $post)
    {
        try{
            $data = $request->validated();
            $data['user_id'] = 1;
            $data['slug'] = Str::slug($request->title);
            //Delete old image if exists
            //$post->post_image && file_exists(public_path('storage/' . $post->post_image)
            if($post->post_image && Storage::disk('public')->exists($post->post_image)){
                Storage::disk('public')->delete($post->post_image);
            }
            //Upload new image
            if($request->hasFile('post_image')){
                $imagePath = $request->file('post_image');
                $imageName = time().'_'.$imagePath->getClientOriginalName();
                $directory = 'posts';
                if(!Storage::disk('public')->exists($directory)){
                    Storage::disk('public')->makeDirectory($directory);
                }
                $imagePath->storeAs('posts', $imageName, 'public');
                $data['post_image'] = $directory.'/'.$imageName;
            }
            $p = $post->update($data);
            if($p){
                return redirect()->route('posts.index')->with('success','Post updated successfully!');
            }else{
                return back()->with('error','Failed to update the post. Please try again.')->withInput();
            }
        }catch(\Exception $e){
            return back()->with('error','An error occurred while updating the post: '.$e->getMessage())
            ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post moved to trash!');
    }

    public function trash()
    {
        $posts = Post::onlyTrashed()
            ->latest()
            ->paginate(10);

        return view('posts.trash', compact('posts'));
    }

    public function restore($id)
    {
        Post::onlyTrashed()
            ->findOrFail($id)
            ->restore();

        return redirect()
            ->route('posts.trash')
            ->with('success', 'Post restored successfully!');
    }

    public function forceDelete($id)
    {
        Post::onlyTrashed()
            ->findOrFail($id)
            ->forceDelete();

        return redirect()
            ->route('posts.trash')
            ->with('success', 'Post permanently deleted!');
    }
}
