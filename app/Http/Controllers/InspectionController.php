<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inspection;
use Validator;
use App\TenantInformation;
use Illuminate\Support\Facades\Auth;
class InspectionController extends Controller
{
    //

    public function generateInspection(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $manager_id=$user->id;
            $validator = Validator::make($request->all(), [
            'inspection_date'=>'required',
            'inspection_time'=>'required',
            'message'=>'required',
        ]);
            if ($validator->fails()) {
                return response()->json(['success'=>false,'error'=>$validator->errors()]);
            } else {
                $inspect = Inspection::create([
                'manager_id'=>$manager_id,
                'property_id'=>$request->property_id,
                'inspection_date'=>$request->inspection_date,
                'inspection_time'=>$request->inspection_time,
                'message'=>$request->message,
                'status'=>'pending'
            ]);
                if ($inspect) {
                    $tenant = TenantInformation::where('property_id',$request->property_id)->get();
                    if(count($tenant)>0)
                    {
                        $to_name = 'Loyal Partners';
                        $to_email = $tenant[0]->tenant_email;
                        $inspect['name']=$tenant[0]->tenant_name;
                        $data = array("data"=>$inspect);
                        \Mail::send('inspection', $data, function ($message) use ($to_name, $to_email) {
                            $message->to($to_email,$to_name )
                        ->subject('Loyal Partners : Property Inspection');
                            $message->from('loyalpartners91@gmail.com', 'Property Inspection');
                        });
                        return response()->json(['success'=>true, 'message'=>'inspection date created']);
                    }
                    else{

                        return response()->json(['success'=>true, 'message'=>'inspection date created']);
                    }
                } else {
                    return response()->json(['success'=>false, 'message'=>'Error creating inspection date']);
                }
            }
        }
        else{
            return response()->json(['success'=>false,'message'=>'Authorization failed']);
        }
    }


    public function getInspection(Request $request)
    {
        $user = Auth::user();
        if($user)
        {
            $id = $user->id;
            $inspections = Inspection::where('manager_id',$id)->where('status','pending')->with('inspection')->get();
           if(count($inspections)>0)
           {

               return response()->json(['success'=>true,'data'=>$inspections]);
            }
            else{
                return response()->json(['success'=>false,'message'=>'No Data']);
            }
        }
    }

    public function completeInspection(Request $request,$id)
    {
        $update = Inspection::where('id',$id)->update([
            'status'=>'complete'
        ]);
        if($update)
        {
            return response()->json(['success'=>true]);
        }
        else{
            return response()->json(['success'=>false]);
        }
    }
}
