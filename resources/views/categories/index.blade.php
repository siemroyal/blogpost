@extends("layouts.app")
@section("title","Categories")
@section("content")
<a href="{{ route('categories.create') }}" class="btn btn-primary mb-2">Create Category</a>
<table class="table table-border table-hover table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Parent Category</th>
            <th>Slug</th>
            <th>Image</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $category)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $category->name }}</td>
            <td>{{ $category->parent->name ?? 'No Parent' }}</td>
            <td>{{ $category->slug }}</td>

            <td>
                <img
                    src="{{ $category->image && file_exists(public_path('storage/' . $category->image))
                        ? asset('storage/' . $category->image)
                        : asset('images/no-image.jpg') }}"
                    alt="{{ $category->name }}"
                    width="50"
                    height="50"
                >
            </td>

            <td>{{ $category->created_at }}</td>
            <td>{{ $category->updated_at }}</td>
            <td>
                <a href="{{ route('categories.show',$category->id) }}"
                    class="btn btn-sm btn-info">View</a>
                <a href="{{ route('categories.edit',$category->id) }}"
                    class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('categories.destroy',$category->id) }}" method="POST"
                    style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-center">
    {{ $categories->links() }}
</div>
@endsection
{{-- {{
1. តើទំនាក់ទំនងរវាង Table ទៅកាន់ Table ក្នុង Database​ ជាអ្វី?
    - One to One
    - Many to One
    - One to Many
    - Many to Many
2. កំណត់ពី Table ណាជា Parent Table និង Table ណាជា Child Table ក្នុងទំនាក់ទំនង
    - One to One
    - Many to One
    - One to Many
    - Many to Many
    ?
    - Parent Table: Table ដែលមិនមាន Foreign Key
3. ត្រូវបង្កើត Dynamic Relationship Methods នៅក្នុង Model ណា?
    - Parent Model (ត្រូវតែបង្កើត return hasMany() or hasOne())
    - Child Model (ត្រូវតែបង្កើត return belongsTo())
    - Parent Model: ត្រូវបង្កើត Dynamic Relationship Methods នៅក្នុង Parent Model ដើម្បីយកទិន្នន័យពី Child Model
4. ប្រើប្រាស់​ Dynamic Relationship method នៅក្នុង Controller
    - $parents = ParentModel::with('childRelationshipMethod')->get();
    - $children = ChildModel::with('parentRelationshipMethod')->get();
}} --}}
