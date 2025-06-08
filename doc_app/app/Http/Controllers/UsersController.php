<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use App\Models\User;
use App\Models\Doctor;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = array();
        $user = Auth::user();
        $doctor = User::where('type', 'doctor')->get();
        $details = $user->user_details;
        $doctorData = Doctor::all();
        // return today appointment together with user data
        $date = now()->format('n/j/Y'); 
        $appointment = Appointments::where('status', 'upcoming')->where('date', $date)->first();

        // collect user data and all docter details
        foreach ($doctorData as $data) {
            // sorting docter name and docter details
            foreach($doctor as $info) {
                if($data['doc_id'] == $info['id']) {
                    $data['doctor_name'] = $info['name'];
                    $data['doctor_profile'] = $info['profile_photo_url'];
                    if(isset($appointment) && $appointment['doc_id'] == $info['id']) {
                        $data['appointments'] = $appointment;
                    }
                }
            }
        }

        $user['doctor'] = $doctorData;
        $user['details'] = $details;

        return $user;
    }

    /**
     * Update Doctor Favorite List.
     */
    public function storeFavDoc(Request $request)
    {
        $saveFav = UserDetails::where('user_id', Auth::user()->id)->first();

        $docList = json_encode($request->get('favList'));

        $saveFav->fav = $docList;
        $saveFav->save();

        return response()->json([
            'success'=>'The Favorite List is updated!'
        ], 200);
    }

    /**
     * Login.
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required',
        ]);   

        $user = User::where('email', $request->email)->first();

        if (!$user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email'=>['The provided credentials are incorrect'],
            ]);
        }

        return $user->createToken($request->email)->plainTextToken;
    }

    /**
     * Register.
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'email'=>'required|email',
            'password'=>'required',
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'type'=>'user',
            'password'=>Hash::make($request->password),
        ]);

        $userInfo = UserDetails::create([
            'user_id'=>$user->id,
            'status'=>'active',
        ]);

        return $user;
    }

    /**
     * Logout.
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request) {
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return response()->json([
            'success'=>'Logout SUccessfully!',
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
