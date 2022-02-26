<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\PropertyReview;

class PropertyReviewController extends Controller
{
    //

    public function getReview(Request $request, $id)
    {
        $review = PropertyReview::where('property_id',$id)->get();
        if(count($review)>0)
        {
            return response()->json(['success'=>true,'data'=>$review]);
        }
        else{
            return response()->json(['success'=>false,'message'=>'No data']);
        }
    }

    public function saveReview(Request $request , $id)
    {
        $validator = Validator::make($request->all(),[
            'review'=>'required'
        ]);
        if($validator->fails()){
            return response()->json(['success'=>false,'error'=>$validator->errors()]);
        }
        else{
            $checkReview = PropertyReview::where('property_id',$id)->get();
            if(count($checkReview)>0)
            {
                $review = PropertyReview::where('property_id',$id)
                        ->update([
                            'review'=>$request->review
                        ]);
                if($review)
                {
                    return response()->json(['success'=>true, 'message'=>'review  updated']);
                }
                else{
                    return response()->json(['success'=>false,'message'=>'error updating  review']);

                }
            }
            else{
                $review = PropertyReview::create([
                    'property_id'=>$id,
                    'review'=>$request->review
                ]);
                if ($review) {
                    return response()->json(['success'=>true, 'message'=>'review created']);
                } else {
                    return response()->json(['success'=>false,'message'=>'error creating review information']);
                }
            }

        }
    }
}
