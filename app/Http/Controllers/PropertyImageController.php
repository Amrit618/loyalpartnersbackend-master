<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\PropertyListImages;
class PropertyImageController extends Controller
{
    //

    public function addImage(Request $request)
    {
         // $user = Auth::user();
        // if($user)
        // {
            $validator = Validator::make($request->all(),[
                'property_list_id'=>'required',
                'thumbnail'=>'required'
            ]);
            if($validator->fails()){
                return response()->json(['success'=>false,'error'=>$validator->errors()]);
            }
            else{
                $thumbnail = $request->thumbnail;
                 $thumbImages = base64_decode($thumbnail);
                $thumbName=time().'.'.'jpg';
                $thumbImage =  \Image::make($thumbImages);
                $thumbImage->save('public/storage/property_image/'.$thumbName,50);
                $thumburl = Storage::url('property_image/'.$thumbName);

                $property = PropertyListImages::create([
                   'property_list_id'=>$request->property_list_id,
                    'image'=>$thumburl
                ]);
                if($property)
                {
                    return response()->json(['success'=>true,'message'=>'Property created successful','data'=>$property]);
                }
                else{
                    return response()->json(['success'=>false,'message'=>'error creating property']);
                }
            }
    }

    public function deleteImage(Request $request, $id)
    {
        $deleteImage = PropertyListImages::where('id',$id)->delete();
        if($deleteImage)
        {
            return response()->json(['success'=>true]);
        }
        else{
            return response()->json(['success'=>false]);
        }
    }
}
