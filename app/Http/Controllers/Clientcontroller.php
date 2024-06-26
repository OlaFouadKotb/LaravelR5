<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Traits\Traits\UploadFile as TraitsUploadFile;
use Illuminate\Support\Facades\Storage;
use App\Traits\Traits\UploadFile;
class ClientController extends Controller
{
    use UploadFile;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::all();
        return view("clients", compact("clients"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('addClient');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $messages = $this->errMsg();

        $data = $request->validate([
            'clientName' => 'required|max:100|min:5',
            'phone' => 'required|min:11',
            'email' => 'required|email:rfc',
            'website' => 'required',
            'city' => 'required|max:30',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], $messages);

        // $imgExt = $request->image->getClientOriginalExtension();
        // $fileName = time() . '.' . $imgExt;
        // $path = 'assets/images';
        // $request->image->move($path, $fileName);
  $fileName= $this->upload($request->image,'assets/images');
        $data['image'] =$fileName;

        $data['active'] = isset($request->active);
        Client::create($data);
        return redirect('clients');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $client = Client::findOrFail($id);
        return view('showClient', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $client = Client::findOrFail($id);
        return view('editClient', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
   
public function update(Request $request, string $id)
{
    $data = $request->validate([
        'clientName' => 'required|string|min:3|max:100',
        'phone' => 'required|string|min:11|max:15',
        'email' => 'required|email:rfc,dns',
        'website' => 'required|url',
        'city' => 'required|string|max:100',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $client = Client::findOrFail($id);

    if ($request->hasFile('image')) {
        // Delete the old image if a new one is uploaded
        if ($client->image) {
            Storage::delete('assets/images/' . $client->image);
        }

        // Upload new image
        $fileName= $this->upload($request->image,'assets/images');
        // $imgExt = $request->image->getClientOriginalExtension();
        // $fileName = time() . '.' . $imgExt;
        // $path = 'assets/images';
        // $request->image->move($path, $fileName);

        $data['image'] = $fileName;
    }

    $client->update($data);

    return redirect('clients')->with('success', 'Client updated successfully.');
}
    /**
     * Display the list of trashed resources.
     */
    public function trash()
    {
        $trash = Client::onlyTrashed()->get();
        return view('trashClient', compact('trash'));
    }

    /**
     * Restore the specified resource from trash.
     */
    public function restore(string $id)
    {
        Client::withTrashed()->where('id', $id)->restore();
        return redirect('clients');
    }
    /* Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        Client::where('id',$id)->delete();
        return redirect('clients');
    }
    /**
     * Permanently delete the specified resource from storage.
     */
    public function forceDelete(Request $request)
    {
        $id = $request->id;
        Client::withTrashed()->where('id', $id)->forceDelete();
        return redirect('trashClient');
    }

    /**
     * Custom error messages for validation.
     */
    public function errMsg()
    {
        return [
            'clientName.required' => 'The Client Name is required',
            'clientName.min' => 'Client Name should be at least 10 characters long',
            'phone.required' => 'The Phone number is required',
            'phone.min' => 'Phone number should be at least 11 digits',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
            'website.required' => 'Website is required',
            'website.url' => 'Website must be a valid URL',
            'city.required' => 'City is required',
            'image.required' => 'An image is required',
            'image.image' => 'File must be an image',
            'image.mimes' => 'Image must be a type of: jpeg, png, jpg, gif, svg',
            'image.max' => 'Image size must be under 2MB',
        ];
    }
}