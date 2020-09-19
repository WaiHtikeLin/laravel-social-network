<?php

namespace App\Http\Controllers;

use App\UserInformation;
use Illuminate\Http\Request;
use Auth;

class UserInfoController extends Controller
{

    public function __construct()
    {
      $this->middleware("auth");
    }

    public function updateEmail(Request $request)
    {
      $data=$this->validateIt($request,'email');
      return $this->massUpdateInfo($data,'email','emails');
    }

    private function validateIt($request,$field)
    {
      switch ($field) {
        case 'email': return $request->validate(
                                [
                                  'name' => 'required|email',
                                  'privacy' => 'required'
                                ]
                              );

          // code...


        case 'phone' : return $request->validate(
                              [
                                'name' => 'required|numeric',
                                'privacy' => 'required'
                              ]
                            );



        case 'site' : return $request->validate(
                              [
                                'name' => 'required|regex:/^\w+(\.\w+)+$/',
                                'privacy' => 'required'
                              ]
                            );

        case 'other' : return $request->validate(
                              [
                                'name' => 'required',
                                'privacy' => 'required'
                              ]
                            );
        default:
          // code...
          break;
      }
    }

    public function updatePhone(Request $request)
    {
      $data=$this->validateIt($request,'phone');
      return $this->massUpdateInfo($data,'phone','ph_numbers');
    }

    public function updateSite(Request $request)
    {
      $data=$this->validateIt($request,'site');
      return $this->massUpdateInfo($data,'site','websites');
    }

    public function updateEdu(Request $request,$id=null)
    {

      $data=$this->validateIt($request,'other');
      return $this->massUpdateInfo($data,'education','education',$id);
    }

    public function updateWork(Request $request,$id=null)
    {

      $data=$this->validateIt($request,'other');
      return $this->massUpdateInfo($data,'work','work',$id);
    }

    public function updateAddress(Request $request,$id=null)
    {

      $data=$this->validateIt($request,'other');
      return $this->massUpdateInfo($data,'address','address',$id);
    }

    public function updateAbout(Request $request)
    { $data=$this->validateIt($request,'other');
      return $this->updateInfo($data,'about');
    }

    public function updateBio(Request $request)
    { $data=$this->validateIt($request,'other');
      return $this->updateInfo($data,'bio');
    }



    // public function editEdu($id)
    // {
    //   $user=Auth::user();
    //   return view('user.addedu',['edu'=>$user->information->education[$id],'id'=>$id]);
    // }
    //
    // public function editWork($id)
    // {
    //   $user=Auth::user();
    //
    //   return view('user.addwork',['work'=>$user->information->work[$id],'id'=>$id]);
    // }
    //
    // public function editAddress($id)
    // {
    //   $user=Auth::user();
    //
    //   return view('user.addaddress',['address'=>$user->information->address[$id],'id'=>$id]);
    // }



    public function massUpdateInfo($data,$type,$field,$id=null)
    {
      $user=Auth::user();
      $info=$user->information;
      $fields=$info->$field;
      if($id!=null)
        $fields[$id]=$data;
      else
        { if($fields)
            $id=count($fields);
          else
            $id=0;
          $fields[]=$data;
        }

      $info->$field=$fields;
      $info->save();

      return ['id'=>$id, 'type'=>$type, 'name'=>$data['name'],
      'privacy'=>$data['privacy'], 'field'=>$field];
    }

    public function updateInfo($data,$field)
    {
      $user=Auth::user();
      $info=$user->information;
      $info->$field=$data;
      $info->save();

      return ['id'=>'', 'type'=>$field, 'name'=>$data['name'],
      'privacy'=>$data['privacy'], 'field'=>$field];
    }


    public function deleteInfo($field,$id=null)
    {
      $user=Auth::user();
      $info=$user->information;
      if($id==null)
      {
        $info->$field='';
        $info->save();
        return true;
      }

      $fields=$info->$field;
      array_splice($fields,$id,1);
      $info->$field=$fields;
      $info->save();


      return true;
    }

    // public function editEmail(Request $request,$id)
    // {
    //   $data=$this->validateIt($request,'email');
    //   return $this->updateInfo($data,'emails',$id);
    // }
    //
    // public function editPhone(Request $request,$id)
    // { $data=$this->validateIt($request,'phone');
    //   return $this->updateInfo($data,'ph_numbers',$id);
    // }
    //
    // public function editSite(Request $request,$id)
    // { $data=$this->validateIt($request,'site');
    //   return $this->updateInfo($data,'websites',$id);
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserInformation  $userInformation
     * @return \Illuminate\Http\Response
     */
    public function show(UserInformation $userInformation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserInformation  $userInformation
     * @return \Illuminate\Http\Response
     */
    public function edit(UserInformation $userInformation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserInformation  $userInformation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserInformation $userInformation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserInformation  $userInformation
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserInformation $userInformation)
    {
        //
    }
}
