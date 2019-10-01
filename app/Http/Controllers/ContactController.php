<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;

class ContactController extends Controller
{
    public function getData()
    {
       $contact = Contact::all();
       return response($contact);
    }

    public function postStore(Request $request)
    {
    	Contact::create($request->all());
    	return ['success' => true,  'message' => 'Data Insert Successfully'];
    }

    public function postUpdate(Request $request)
    {
    	/*$contact = Contact::find($id);
    	$contact->name = $request->name;
    	$contact->phone = $request->phone;
    	$contact->email = $request->email;
    	$contact->religion = $request->religion;
    	$contact->save();*/

    	Contact::find($request->id)->update($request->all());
    	return ['success' => true,  'message' => 'Data Updated Successfully'];
    }

    public function postDelete(Request $request)
    {
    	Contact::find($request->id)->delete();
    	return ['success' => true,  'message' => 'Data Deleted Successfully'];
    }
}
