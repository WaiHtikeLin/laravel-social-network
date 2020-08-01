<?php

namespace App\Http\Controllers;

use App\UserInformation;
use Illuminate\Http\Request;
use Auth;

class UserInfoController extends Controller
{


    public function addEmail(Request $request)
    {
      $data=$this->validateIt($request,'email');
      return $this->addInfo($data,'emails');
    }

    private function validateIt($request,$field)
    {
      switch ($field) {
        case 'email': $data=$request->validate(
                                [
                                  'field' => 'required|email'
                                ]
                              );
                      return $data['field'];
          // code...


        case 'phone' : $data=$request->validate(
                              [
                                'field' => 'required|numeric'
                              ]
                            );

                        return $data['field'];

        case 'site' : $data=$request->validate(
                              [
                                'field' => 'required|regex:/^\w+(\.\w+)+$/'
                              ]
                            );
                      return $data['field'];
        default:
          // code...
          break;
      }
    }

    public function addPhone(Request $request)
    {
      $data=$this->validateIt($request,'phone');
      return $this->addInfo($data,'ph_numbers');
    }

    public function addSite(Request $request)
    {
      $data=$this->validateIt($request,'site');
      return $this->addInfo($data,'websites');
    }

    public function updateEdu(Request $request,$id=null)
    {
      $data=$request->validate(
        [
          'name' => 'required',
          'from' => 'nullable|date',
          'to' => 'nullable|date'
        ]
      );

      return $this->massUpdateInfo($data,'education',$id);
    }

    public function updateWork(Request $request,$id=null)
    {
      $data=$request->validate(
        [
          'name' => 'required',
          'from' => 'nullable|date',
          'to' => 'nullable|date'
        ]
      );
      return $this->massUpdateInfo($data,'work',$id);
    }

    public function updateAddress(Request $request,$id=null)
    {
      $data=$request->validate(
        [
          'country' => 'required',
          'city' => 'required',
          'from' => 'nullable|date',
          'to' => 'nullable|date'

        ]
      );
      return $this->massUpdateInfo($data,'address',$id);
    }

    public function addAbout(Request $request)
    {
      return $this->updateInfo($request->input('about'),'about');
    }

    public function addBio(Request $request)
    {
      return $this->updateInfo($request->input('bio'),'bio');
    }



    public function editEdu($id)
    {
      $user=Auth::user();
      return view('user.addedu',['edu'=>$user->information->education[$id],'id'=>$id]);
    }

    public function editWork($id)
    {
      $user=Auth::user();

      return view('user.addwork',['work'=>$user->information->work[$id],'id'=>$id]);
    }

    public function editAddress($id)
    {
      $user=Auth::user();

      return view('user.addaddress',['address'=>$user->information->address[$id],'id'=>$id]);
    }



    public function massUpdateInfo($data,$field,$id=null)
    {
      $user=Auth::user();
      $info=$user->information;
      $fields=$info->$field;
      if($id!=null)
        $fields[$id]=$data;
      else
        $fields[]=$data;
      $info->$field=$fields;
      $info->save();

      return redirect('edit/profile');
    }

    public function addInfo($data,$field)
    {
      $user=Auth::user();
      $name=$data;
      $info=$user->information;
      $fields=$info->$field;
      $fields[]=$name;
      $id=count($fields)-1;
      $info->$field=$fields;
      $info->save();

      $field=substr($field,0,strlen($field)-1);
      return ['name'=>$name,'id'=>$id,'type'=>$field];
    }

    public function updateInfo($data,$field,$id=null)
    {
      $user=Auth::user();

      $info=$user->information;
      if ($id==null) {
        $info->$field=$data;
        $info->save();
        return true;
      }
      $fields=$info->$field;
      $fields[$id]=$data;
      $info->$field=$fields;
      $info->save();
      return true;
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
      $data=[];

      if($field=='education' || $field=='work' || $field=='address')
      {
        for ($i=0; $i < count($fields) ; $i++)
          $data[]=['id'=>$i,'type'=>'redirect','field'=>$field,'name'=>$fields[$i]['name']];
      }

      else
      {
        $field=substr($field,0,strlen($field)-1);
        for ($i=0; $i < count($fields) ; $i++)
          $data[]=['id'=>$i,'type'=>$field,'name'=>$fields[$i]];
      }
      return $data;
    }

    public function editEmail(Request $request,$id)
    {
      $data=$this->validateIt($request,'email');
      return $this->updateInfo($data,'emails',$id);
    }

    public function editPhone(Request $request,$id)
    { $data=$this->validateIt($request,'phone');
      return $this->updateInfo($data,'ph_numbers',$id);
    }

    public function editSite(Request $request,$id)
    { $data=$this->validateIt($request,'site');
      return $this->updateInfo($data,'websites',$id);
    }
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
