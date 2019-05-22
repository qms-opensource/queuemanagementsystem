<?php 
    namespace App\Http\Traits;
    use App\Module;
    use App\User;
    use App\UserPermission;
    use App\AssignModule;
    use Illuminate\Support\Facades\Auth;
    use Config;
    trait Permissions{

        /*
        * @getUserPermissions
        * Method return array or false
        * This method is used to get the users permissions by sending the module id 
        * If for the particular module permissions exist in the Special Permissions permissions will be override
        */

        public static function getUserPermissions($moduleId){
            try{
                $userDetails = self::loggedUserInfo();
                $permissionsAssigned = [];
                $userPermissions = UserPermission::where('user_id',$userDetails['user_id'])->where('module_id',$moduleId)->where('status',1)->get();
                if($userPermissions->count()>0){
                    $permissions  = $userPermissions->first();
                    return $userPermission = unserialize($permissions->permissions);
                }else{
                    $getModulePermissions = AssignModule::where('role_id',$userDetails['role_id'])->where('module_id',$moduleId)->where('status',1)->get();
                    if($getModulePermissions->count()>0){
                        $permissions  = $getModulePermissions->first();
                        return $userPermission = unserialize($permissions->permissions);
                    }
                    return false;
                }
            }catch(Exception $e){
                echo $e->getMessage();
            }
        }

        /*
        * Function return array of Permissions or false
        * Return all the permissions based up on the role if permissions exists in the special permissions table roles permissions * will be overide
        */
        
        public static function getAllPermissions(){
            try{
                $userDetails = self::loggedUserInfo();
                $allModules = Module::all();
                $myModules = [];
                foreach($allModules as $tempModules){
                    $myModules[$tempModules->id] = $tempModules->status;
                }
                $permissions = "";
                $permissionss = "";
                $userPermissions = UserPermission::where('user_id',$userDetails['user_id'])->where('status',1)->get();
                if($userPermissions->count()>0){
                    $permissions = self::makeArrayForPermissions($userPermissions);
                }
                $getModulePermissions = AssignModule::where('role_id',$userDetails['role_id'])->where('status',1)->get();
                if($getModulePermissions->count()>0){
                    $permissionss = self::makeArrayForPermissions($getModulePermissions);
                }
                if(!empty($permissions)):
                    $permissions = $permissions + $permissionss;
                else:
                    $permissions = $permissionss;
                endif;
                $newPermi = [];
                if(!empty($permissions)):
                    foreach($permissions as $key=>$tempPermissions):
                        $permissions[$key]['status'] = $myModules[$key];
                    endforeach;
                endif;
                return $permissions;
            }catch(Exception $e){
                echo $e->getMessage();
            }
        }

        /*
        * Function used to built the permissions array
        * Return array of Permissions
        */

        public static function makeArrayForPermissions($permissions){
            $allPermissionsArr = [];
            foreach($permissions as $permission):
                $allPermissionsArr[$permission->module_id] = unserialize($permission->permissions); 
            endforeach;
            return $allPermissionsArr;
        }

        /*
        * Method used to check the permissions of the modules
        * return true or false
        */
        public static function hasPermissions($permissions,$type){
            try{
                if($permissions == ""){
                    return true;
                }else{
                    if(self::allPermissions($permissions) == false){
                        return "fail";
                    }
                    if(!empty($type)){
                        if( !empty($permissions[$type]) && $permissions[$type] == 1 ){
                            return [$type => true];
                        }
                        return "fail";
                    }
                }
            }catch(Exception $e){
                echo $e->getMessage();
            }
        }

        public static function allPermissions($permissions){
            if(is_array($permissions)){
                if(in_array(1,$permissions)){
                    return true;
                }
                return false;
            }else{
                return true;
            }
        }

        public static function showUnauthorizedPage(){
            return view('errors/unauthorized');
        }

        public static function loggedUserInfo(){
            $userId =  Auth::id();
            $user = User::where('id',$userId)->first();
            $roleId = $user->role_id;
            return ['user_id' => $userId,'role_id' => $roleId];
        }

        public static function checkModuleStatus($moduleId){
            $count = Module::getStatusOfModule($moduleId);
            if($count>0){
                return "fail";
            }
            return true;
        }

        public static function checkPermissions($moduleId,$type){
            $myInfo = self::loggedUserInfo();
            $moduleStatus = self::checkModuleStatus($moduleId);
            if($myInfo['role_id']!=1){
                $permissions = self::getUserPermissions($moduleId);
                $checkStatus = self::hasPermissions($permissions,$type);
                $allPermissions = self::getAllPermissions();
                return ['moduleStatus' => $moduleStatus,'myInfo' => $myInfo,'module_id'=>$moduleId,'checkStatus'=>$checkStatus,'allPermissions' => $allPermissions,'permissions' => $permissions];
            }
            return ['moduleStatus' => $moduleStatus,'myInfo' => $myInfo,'module_id'=>'','checkStatus'=>'','allPermissions' => '','permissions' => ''];
        }

        public static function showModuleDeactive(){
            $myInfo = self::loggedUserInfo();
            if($myInfo['role_id']!=1){
                return view('errors/moduleNotExist'); 
            }else{
                return view('errors/moduleDeactive');
            }
        }
    }
?>