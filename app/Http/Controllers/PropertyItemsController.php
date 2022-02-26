<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PropertyItems;
use App\PropertyList;
use Validator;
use Illuminate\Support\Facades\Auth;
class PropertyItemsController extends Controller
{
    //
    public function createPropertyItems(Request $request,$id)
    {
        // $user = Auth::user();
        // if($user)
        // {
            $validator = Validator::make($request->all(),[
                'name'=>'required'
            ]);
            if($validator->fails()){
                return response()->json(['success'=>false,'error'=>$validator->errors()]);
            }
            else{
                $property = PropertyItems::create([
                    'property_list_id'=>$id,
                    'name'=>$request->name
                    ]);
                if($property)
                {
                    return response()->json(['success'=>true,'message'=>'Property items created successful']);
                }
                else{
                    return response()->json(['success'=>false,'message'=>'error creating property items']);
                }
            }
        // }
    }


    public function getPropertyItems(Request $request,$id)
    {
        // $propertyItems =PropertyItems::where('property_list_id',$id)->get();
        $propertyItems =PropertyList::with('propertyItems')->with('images')->where('id',$id)->get();
        return response()->json(['success'=>true,'data'=>$propertyItems]);
    }

    public function updateProperty(Request $request, $id)
    {
        $updateItems = PropertyItems::where('id',$id)->update([
            $request->key => $request->value
        ]);
        return response()->json(['success'=>true, 'message'=>'updated successful']);
    }

    

    public function deleteProperty(Request $request , $id)
    {
        $deleteItems = PropertyItems::where('id',$id)->delete();
        if($deleteItems)
        {
            return response()->json(['success'=>true , 'message'=>'Items deleted']);
        }
        else{
            return response()->json(['success'=>false , 'message'=>'Items deleting failed']);

        }
    }
}
