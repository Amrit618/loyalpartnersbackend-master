<?php

namespace App\Http\Controllers;

use App\Property;
use App\Report;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PDF;
use Validator;
use App\PropertyReview;

class PropertyController extends Controller
{
    //
    public function createProperty(Request $request)
    {
        // $user = Auth::user();
        // if($user)
        // {
        $validator = Validator::make($request->all(), [
            'property_name' => 'required',
            'description' => 'required',
            'owner' => 'required',
            'thumbnail' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->errors()]);
        } else {
            $user_id = 1;
            $length = 8;
            $new_pw = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
            $user = User::create([
                'name' => $request->property_name,
                'email' => $request->owner,
                'email_verified_at' => date('y-m-d'),
                'password' => bcrypt($new_pw),
                'role' => 'House_Owner',
            ]);
            if ($user) {
                $user_id = $user->id;
                $to_name = 'Loyal Partner';
                $to_email = $request->owner;
                $data = array("password" => $new_pw);
                \Mail::send('owner', $data, function ($message) use ($to_name, $to_email) {
                    $message->to($to_email, $to_name)
                        ->subject('Loyal Partners : Property Owner');
                    $message->from('loyalpartners91@gmail.com', 'Property Owner');
                });
            }

            $thumbnail = $request->thumbnail;
            $thumbImages = base64_decode($thumbnail);
            $thumbName = time() . '.' . 'jpg';
            $thumbImage = \Image::make($thumbImages);
            // $thumbImage->insert('public/storage/property_image/1589043346.jpg','center');
            $thumbImage->save('public/storage/property_image/' . $thumbName, 50);
            $thumburl = Storage::url('property_image/' . $thumbName);

            $property = Property::create([
                'user_id' => $user_id,
                'property_name' => $request->property_name,
                'description' => $request->description,
                'thumbnail' => $thumburl,
            ]);
            if ($property) {
                return response()->json(['success' => true, 'message' => 'Property created successful', 'data' => $property]);
            } else {
                return response()->json(['success' => false, 'message' => 'error creating property']);
            }
        }
        // }
    }

    //GET ALL PROPERTIES
    public function getProperty()
    {
        $property = Property::with('owner')->where('status',true)->get();
        return response()->json(['success' => true, 'data' => $property]);
    }

    //Hide Property
    public function hideProperty($id)
    {
        $property=Property::where('id',$id)->update(['status'=>false]);
        $user_id = Property::where('id',$id)->select('user_id')->get();
        $delUser = $user_id[0]->user_id;
        $user= User::where('id',$delUser)->update(['email'=>date("h:i:s")]);
        if($property)
        {
            return response()->json(['success'=>true]);
        }
        else{
            return response()->json(['success'=>false]);
        }
    }

    public function updateProperty(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->errors()], 401);
        } else {
            $update = Property::where('id', $id)->update([
                'property_name' => $request->name,
                'description' => $request->description,
            ]);
            if ($update) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        }
    }

    public function propertyReport(Request $request, $id)
    {
        $user = Auth::user();
        if ($user) {
            $reviews = "No Review";
            $data = Property::where('id', $id)->with('propertylist.propertyitems')->with('propertylist.images')->get();
            $review = PropertyReview::where('property_id',$id)->get();
                if(count($review)>0)
            {
            $data[0]->reviews = $review[0]->review;
        }
        else{
            $data[0]->reviews = $reviews;
            }
            $data[0]->inspectorEmail = $user->email;
            // return view('report', ['data' => $data[0]]);
            $filename = $data[0]->property_name . '_' . $data[0]->id . '/' . date("Y-m-d") . '/report.pdf';
            $pdf = PDF::loadView('report', ['data' => $data[0]]);
            $uploadDropBox = Storage::disk('dropbox')->put($filename, $pdf->output());
            $url = Storage::disk('dropbox')->url($filename);
            if ($url) {
                $report = Report::create([
                    'owner_id' => $data[0]->user_id,
                    'inspector_id' => $user->id,
                    'report_link' => $url,
                    'inspector_email' => $user->email,
                    'property_name' => $data[0]->property_name,
                ]);
                if ($report) {
                    return response()->json(['success' => true]);
                } else {
                    return response()->json(['success' => true]);
                }
            } else {
                return response()->json(['success' => false]);
            }
            // return $pdf->download('test.pdf');
        } else {
            return response()->json(['success' => false, 'message' => 'Unauthorized']);
        }

    }

    public function getReportsManager(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $reports = Report::where('inspector_id', $user->id)->get();
            return response()->json(['success' => true, 'data' => $reports]);
        } else {
            return response()->json(['sucess' => false, 'message' => 'unauthorized']);
        }
    }
    public function getReportsOwner(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $reports = Report::where('owner_id', $user->id)->get();
            return response()->json(['success' => true, 'data' => $reports]);
        } else {
            return response()->json(['sucess' => false, 'message' => 'unauthorized']);
        }
    }
}
