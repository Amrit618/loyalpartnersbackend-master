<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;
use App\TenantInformation;
use PDF;
class TenantController extends Controller
{
    //
    public function createTenant(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'property_id'=>'required',
            'tenant_name'=>'required',
            'tenant_contact'=>'required',
            'tenant_email'=>'required'
        ]);
        if($validator->fails()){
            return response()->json(['success'=>false,'error'=>$validator->errors()]);
        }
        else{
            $checkTenant = TenantInformation::where('property_id',$request->property_id)->get();
            if(count($checkTenant)>0)
            {
                $tenant = TenantInformation::where('property_id',$request->property_id)
                    ->update([
                    'tenant_name'=>$request->tenant_name,
                    'tenant_contact'=>$request->tenant_contact,
                    'tenant_email'=>$request->tenant_email
                ]);
                if ($tenant) {
                    return response()->json(['success'=>true, 'message'=>'tenant information updated']);
                } else {
                    return response()->json(['success'=>false,'message'=>'error updating tenant information']);
                }
            }
            else{
                $tenant = TenantInformation::create([
                'property_id'=>$request->property_id,
                'tenant_name'=>$request->tenant_name,
                'tenant_contact'=>$request->tenant_contact,
                'tenant_email'=>$request->tenant_email
            ]);

                if ($tenant) {
                    return response()->json(['success'=>true, 'message'=>'tenant information saved']);
                } else {
                    return response()->json(['success'=>false,'message'=>'error saving tenant information']);
                }
            }
        }
    }

    public function getTenant (Request $request, $id)
    {
        $tenantInfo = TenantInformation::where('property_id',$id)->get();
        if(count($tenantInfo)>0)
        {
            return response()->json(['success'=>true,'data'=>$tenantInfo]);
        }
        else{
            return response()->json(['success'=>false,'message'=>'No Data']);
        }
    }

    public function test(){
        $pdf = PDF::loadView('report');
        return $pdf->download('invoice.pdf');
    }
}
