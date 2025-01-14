<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ListingController extends Controller
{

    //show all listings
    public function index()
    {
        return view('listings.index', [
            'listings' => Listing::latest()->filter(request(['tag', 'search']))->paginate(6)
        ]);
    }
    //show create form
    public function create()
    {
        return view('listings.create');
    }
    //show single list
    public function show(Listing $listing)
    {
        return view('listings.show', [
            'listing' => $listing
        ]);
    }

    //store listing data
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings', 'company')],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required'

        ]);
        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }
        $formFields['user_id']=Auth::id();

        Listing::create($formFields);

        return redirect('/')->with('message', 'Listing created succesfully');
    }
    //show edit listig view
    public function edit(Listing $listing){
        return view('listings.edit',['listing'=>$listing]);
    }
    //update listing
    public function update(Request $request, Listing $listing)
    {   
        //make sure logged in user is owner
        if($listing->user_id!= Auth::user()->id){
            abort(403,'Unauthorized Action');
        }
        $formFields = $request->validate([
            'title' => 'required',
            'company' => 'required',
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required'

        ]);
        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $listing->update($formFields);

        return redirect('/listings/' . $listing->id)->with('message', 'Listing updated successfully');
    }
    //delete listing
    public function destroy(Listing $listing)
    {
        $listing->delete();
        return redirect('/')->with('message', 'Listing deleted successfully');
    }
    //manage listings
    public function manage(Listing $listing)
    {
        return view('listings.manage', ['listings' => Auth::user()->listings]);
    }

}
