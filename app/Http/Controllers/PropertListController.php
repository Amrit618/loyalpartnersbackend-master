<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PropertyList;
use App\PropertyItems;
use App\PropertyListImages;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use Validator;
class PropertListController extends Controller
{
    //

    public function createPropertyList(Request $request , $propertyId)
    {
        // $user = Auth::user();
        // if($user)
        // {
            $validator = Validator::make($request->all(),[
                'listname'=>'required',
                'thumbnail'=>'required'
            ]) ;
            if($validator->fails())
            {
                return response()->json(['success'=>false,'error'=>$validator->errors()]);
            }
            else{
                $thumbnail = $request->thumbnail;
                $thumbImages = base64_decode($thumbnail);
               $thumbName=time().'.'.'jpg';
               $thumbImage =  \Image::make($thumbImages);
            //    $thumbImage->insert('public/storage/property_image/1589043346.jpg','center');
               $thumbImage->save('public/storage/property_image/'.$thumbName,50);
               $thumburl = Storage::url('property_image/'.$thumbName);

                $propertyList = PropertyList::create([
                    'property_id'=>$propertyId,
                    'listname'=>$request->listname,
                    'thumbnail'=>$thumburl
                ]);
                if($propertyList)
                {
                    return response()->json(['success'=>true,'message'=>'successfully created property list']);
                }
                else{
                    return response()->json(['success'=>false,'message'=>'error creating property list']);
                }
            }
        // }
    }

    public function getPropertyList($id)
    {
        $propertyList = PropertyList::where('property_id',$id)->get();
        return response()->json(['success'=>true,'data'=>$propertyList]);
    }

    public function deletePropertyList(Request $request , $id)
    {
        $deletePropertyitem = PropertyItems::where('property_list_id',$id)->delete();
        $deletePropertyImage = PropertyListImages::where('property_list_id',$id)->delete();
        $deleteList = PropertyList::where('id',$id)->delete();
        if($deleteList)
        {
            return response()->json(['success'=>true, 'message'=>'deleted successful']);
        }
        else{
            return response()->json(['success'=>false, 'message'=>'error deleting']);

        }
    }
}
