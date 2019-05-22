<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;
use Image;
use Validator;

class SettingsController extends Controller
{
    /*
		**	@function Name: edit
		**	@param: user id
		**	@description: user edit form
		**	@return: user data, roles list
		**  Author Name: IDS
		*/
		public function edit($id)
		{
			$checkAccess = $this->checkUserPermissions('manage_settings');
			if($checkAccess == false)
				return redirect('unauthorized-access');
			$settingData = Setting::find($id);
			if(!empty($settingData)) {
				return view('Settings.edit',['settingData'=>$settingData]);
			} else {
				return redirect('/pagenotfound');
			}
		}
		
		/*
		**	@function Name: update
		**	@param: $request : form data.
		**	@description: update settings to DB
		**	@return: void 
		**  Author Name: IDS
		*/
		public function update(Request $request)
		{
			$id = $request->input('settingid');
			$validator = Validator::make($request->all(), [
				/* 'app_display_name' => 'required|max:50',
	            'app_display_system' => 'required|max:50',
				'mail_driver' => 'required|max:30',
				'mail_port' => 'required|max:10',
	            'mail_host' => 'required|max:20',
				'mail_user' => 'required|max:30',
	            'mail_encryption' => 'required|max:20', */
				//'app_logo' =>'mimetypes:image/png,image/jpeg,image/jpg,image/gif'
			]);
			if ($validator->fails()) {
				return redirect('manage-settings/edit/'.$id)
							->withInput()->withErrors($validator,'edit');
			}
			$data = $request->all();
			if(\Input::file())
			{
				$image = \Input::file('app_logo');
				$filename  = time() . '.' . $image->getClientOriginalExtension();
				$path = public_path('uploads/' . $filename);
				Image::make($image->getRealPath())->resize(99, 99)->save($path);
			}
			
			$settingdata = array();
			$settingdata['display_name'] = $data['app_display_name'];
			$settingdata['display_system'] = $data['app_display_system'];
			if(!empty($filename))
			{
				$settingdata['logo_path'] = $filename;
			}
			
			    $settingdata['mail_driver'] = !empty($data['mail_driver'])? $data['mail_driver']:'';
			
				$settingdata['mail_port'] = !empty($data['mail_port'])? $data['mail_port']:'';
			
				$settingdata['mail_host'] = !empty($data['mail_host']) ? $data['mail_host']:'';
			
				$settingdata['mail_username'] = !empty($data['mail_user']) ? $data['mail_user']:'';
			
				$settingdata['mail_encryption'] = !empty($data['mail_encryption']) ? $data['mail_encryption']:'';
			
				$settingdata['mail_port'] = !empty($data['mail_port']) ? $data['mail_port']:'';
			if(!empty($data['mail_password']))
			{
				$settingdata['mail_password'] = \Hash::make($data['mail_password']);
			}
			//echo"<pre>";
			//print_r($settingdata);
			//die;
				
			$updateSettings = Setting::find($id)->update($settingdata);
			if($updateSettings){
				$request->session()->flash('alert-success',"Settings updated Successfully");
				return redirect('manage-settings/edit/'.$id);
			}else{
				$request->session()->flash('alert-danger',"Something going wrong. Please try again later.");
				return redirect('manage-settings/edit/'.$id);
			}		
	
		}
}
