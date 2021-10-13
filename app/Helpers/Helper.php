<?php
use App\Models\GeneralSetting;
use App\Models\EmailTemplate;
use App\Models\User;
use App\Models\Domains;
use App\Models\DomainScan;
use App\Models\DomainsUser;
use App\Models\ProbsCategory;
use App\Models\OverallRatingMessages;
use App\Models\Industry;
use App\Models\AssignRole;
use Carbon\Carbon;
use App\Models\subscription;




function getParentName($id){
   $parentData = ProbsCategory::where('id',$id)->first();
   return $parentData->category_name;
}

function paginationValue(){
    $pagination_value = getGeneralSetting('pagination_value');
   return $pagination_value;
}


function getParentsInfo($id){
        $data = array();
        static $i = 0;
        while($id!=''){
            $probsInfo = ProbsCategory::where('id',$id)
                                    ->where('parent_id','!=','')
                                    ->first();
           if($probsInfo){
                $data['parent'.$i] = $probsInfo;
                $i++; 
                return getParentsInfo($probsInfo->parent_id);
                // return response()->json([
                //         'status' => true,
                //         'data' => $probsInfo
                //     ]);
                return $data;
           }else{
                // return response()->json([
                //         'status' => true,
                //         'message' => "There is no parent for this.",
                //         'data' => $probsInfo
                //     ]);
                return $data; 
           }
        }
        // return response()->json([
        //                 'status' => true,
        //                 'data' => $data
        //             ]); 

        return $data;
       
  }


function getExpiryDate($userid){
  $current_date = date('Y-m-d');
  $subscription = subscription::where('user_id',$userid)->where('subscription_type','Membership')->orderBy('expire_date','desc')->first();

    if($subscription['expire_date'] > $current_date){
      $current_date = $subscription['expire_date'];
    }
    $add_days = getGeneralSetting('free_access_days');
     // dd($add_days);

     $expiry_date = date("Y-m-d", strtotime("+$add_days days", strtotime($current_date)));

     // dd($expiry_date);
  return $expiry_date;
}

function implodeArray($dataArray){
  if(@is_array($dataArray)) {
    return @implode(', ', $dataArray);
  }
  return '';
}

function generateInvoiceNumber($subscription_id, $date){
    $num = $subscription_id;
    $invoiceYear = date('Y', strtotime($date));
    $invoiceNumber = sprintf($invoiceYear."%06d",$subscription_id);
    return $invoiceNumber;
}

function menuName(){
   return $menuArray = array(
           'admin-users' => 'Manage Admin Users',
           'portal-users' => 'Manage Portal Users',
           // 'transaction-history' => 'Transaction History',
           // 'domains' => 'Domains',
           // 'probs-category' => 'Probs Category',
           // 'probs-sub-category' => 'Probs Sub Category',
           'email-management' => 'Email Management',
           'content-management' => 'Content Management',
           // 'dynamic-content' => 'Dynamic Content',
           'banner-management' => 'Banner Management',
           // 'features-management' => 'Features Management',
           'faq' => 'FAQ',
           // 'manage-industry' => 'Manage Industry',
           // 'manage-avg-rating-text' => 'Manage Avg Rating Text',
           // 'news-letter' => 'News Letter',
           // 'promo-code' => 'Promo Code',
           'general-settings' => 'General Settings',
           'promotion' => 'Promotions',
           'product' => 'Product',
           'variant' => 'Variant',
           'ingredient' => 'Ingredient'
          );
}

function addPermissionForSuperAdmin(){
      $superAdminInfo = User::where('type',"super_admin")->get();
      $super_admin_id = $superAdminInfo[0]->id;
      $menuArray =  menuName();
      foreach ($menuArray as $key => $value) {
          $roles = new AssignRole;
          $roles->user_id = $super_admin_id;
          $roles->menu_key = $key;
          $roles->read = "1";
          $roles->write = "1";
          $roles->save();
      }                       
}

function menuPermissionByType($user_id,$field){
      $menuObj = AssignRole::where('user_id',$user_id)
                              ->where($field,'1')
                              ->pluck('menu_key');
      $menuArr = json_decode(json_encode($menuObj));                       
      return $menuArr;                           
}


function time_elapsed_string($datetime, $full = false) {
    if($datetime == null) {
      return 'Never Login';
    }

    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function checkAndAddDomainUser($domain_id, $userid, $ptype, $value){
  $domainsUserInfo = DomainsUser::where('domain_id', $domain_id)
    ->where('user_id', $userid)
    ->first();
  
  $current_date = date('Y-m-d');

  if($domainsUserInfo){
    if($domainsUserInfo->expiry_date > $current_date) {
      $current_date = $domainsUserInfo->expiry_date;
    }
  }

  if($ptype == 'month'){
      $expiry_date = date("Y-m-d", strtotime("+1 month", strtotime($current_date)));
  }else if($ptype == 'adminfield'){
      $add_days = getGeneralSetting('signup_access_(in_days)');
      $expiry_date = date('Y-m-d',strtotime($current_date) + (24*3600*$add_days));
  }else{
      $expiry_date = date("Y-m-d", strtotime("+1 year", strtotime($current_date)));
  }


  $added_as = isset($value['added_as'])?$value['added_as']:'C';
  $type = isset($value['type'])?$value['type']:1;
  $industry = isset($value['industry'])?$value['industry']:0;
  $subscription_id = isset($value['subscription_id'])?$value['subscription_id']:0;
  
  if($domainsUserInfo){
    DomainsUser::where('id', $domainsUserInfo->id)
        ->update(['expiry_date'=>$expiry_date, 'type'=>$value['type'], 'industry'=>$value['industry']]);
  }
  else{
    //insert data in domain table
    $domainsUserInfo = new DomainsUser;
    $domainsUserInfo->domain_id = $domain_id;
    $domainsUserInfo->user_id = $userid;
    $domainsUserInfo->added_as = $added_as;
    $domainsUserInfo->type = $type;
    $domainsUserInfo->industry = $industry;
    $domainsUserInfo->subscription_id = $subscription_id;
    $domainsUserInfo->expiry_date = $expiry_date;
    $domainsUserInfo->save();
  }

  return $domainsUserInfo;
}

function showDate($date){
  $dateFormat = 'd/m/Y';
  if(in_array($date,  array('0000-00-00', '0000-00-00 00:00:00', '', NULL) )){
    // return date($dateFormat);
    return '_';
  }
  else {
    return date($dateFormat, strtotime($date));
  }
}


function categoryMgsByGrade($category_name,$grade){
  $grade_col = 'grade_'.strtolower($grade);
  $message = ProbsCategory::where('category_name',$category_name)->first();
  return $message[$grade_col];
}

function expiryDateReminder($date){
  if($date){
    $add_days = getGeneralSetting('reminder_for_expiry_date(in_days)');
    $currentDate = date('Y-m-d',strtotime(date('Y-m-d')) + (24*3600*$add_days));
    if(strtotime($date) <= strtotime($currentDate)){
      return 'Yes';
    }
  }
  return 'No';
}

function uploadImage($imageInfo, $folderName = '') {
  $imageName = '';
  if ($imageInfo->getClientOriginalName()) {
    $uploadFolder = "uploads";
    if ($folderName != '') {
      $uploadFolder .= '/' . $folderName;
    }
    $imageName = time() . '-' . $imageInfo->getClientOriginalName();
    $imageName = preg_replace('/[^A-Za-z0-9.]/', '-', $imageName);
    $imageInfo->move(public_path($uploadFolder), $imageName);
  }
  return $imageName;
}

### function to show image
function showImage($imageName, $folderName = '') {
  // dd($imageName, $folderName);
  //if ($imageName) {
  $uploadFolder = "uploads/";
  if ($folderName != '') {
    $uploadFolder .= $folderName . '/';
  }
  $imageAbsolutePath = public_path($uploadFolder . $imageName);
  if (file_exists($imageAbsolutePath) && $imageName != '') {
    $imageFullPath = URL::asset($uploadFolder . $imageName);
  } else {
    $imageFullPath = URL::asset($uploadFolder . 'noimage.png');
  }
  //}
  return $imageFullPath;
}


### function to delete image
function unlinkImage($imageName, $folderName = '') {
  if ($imageName) {
    $uploadFolder = "uploads/";
    if ($folderName != '') {
      $uploadFolder .= $folderName . '/';
    }
    $imageAbsolutePath = public_path($uploadFolder . $imageName);
    if (file_exists($imageAbsolutePath)) {
      unlink($imageAbsolutePath);
    }
  }
}


### function to get email
function sendEmail($userInfo, $template_code, $data = array()) {
  $email  = $userInfo['email'];
  $name   = $userInfo['name'];
  $template = EmailTemplate::where('variable_name',$template_code)->first();

  $page_title = getGeneralSetting('page_title');
  $defaultData['page_title'] = $page_title;
  $defaultData['help_email'] = getGeneralSetting('help_email');
  $defaultData['site_link'] = getGeneralSetting('site_link');

  $data = array_merge($data, $defaultData);

  if (!empty($template)) {
    $variables = explode(',', $template->variable);
    $subject = $template->title;
    $body = $template->description;

    foreach ($variables as $item) {  
      $keyIndex = str_replace(array('{', '}'), '', $item);
      if(isset($data[$keyIndex])) {
        $subject = str_replace($item, $data[$keyIndex], stripslashes(html_entity_decode($subject)));
        $body = str_replace($item, $data[$keyIndex], stripslashes(html_entity_decode($body)));
        // $body = nl2br($body);
      }
    }

    $sender = [
      'subject' => $subject,
      'email' => $email,
      'name' => $name,
      'from' => ['name' => $page_title, 'address'=>config('app.sender_email')]
    ];

    if(!empty($body) && !empty($email)){
      $bodyHtml = '<body style="font-family: arial; margin: 0px; letter-spacing: 0.5px; line-height: 1.6;">
        <div class="mailer" style="border: 1px solid #f5f5f5; background-color: #f7f7f7;  padding: 50px; margin: 0 auto; width: 700px;">
            <table class="table" width="100%" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
                <thead>
                    <tr>
                        <th style="background: #ffffff; width: 100%; padding: 30px;">
                           <img style="width: 200px;" src="'.asset('images/emailLogo.png').'" />
                        </th>
                    </tr>
                </thead>
                <tbody>
                  <tr><td style="color: #000000; font-size: 14px; padding: 15px 20px;">'.nl2br($body).'</td></tr>
                  <tr>
                      <td style="color: #000000; font-size: 14px; padding: 15px 20px 40px;">Thanks,<br>
                      '.getGeneralSetting('page_title').'
                      </td>
                  </tr>
                  <tr><td style="height: 40px;"></td></tr>
                  <tr style="background-color: #f7f7f7; color: #000000; font-size: 14px; text-align: center;">
                    <td style="padding: 30px 20px 0;">'.getGeneralSetting('copyright').'</td>
                  </tr>
                </tbody>
            </table>
        </div>
    </body>';                   

      Mail::send('emails.default', ['body' => $bodyHtml], function($message) use ($sender){
          $message->to(
            $sender['email'],
            $sender['name']
          )
          ->subject($sender['subject'])
          ->from(
            $sender['from']['address'],
            $sender['from']['name']
          );
      });
    }
  }

}


### general get setting value on basis of the passed title
function getGeneralSetting($title) {
  $settingInfo = GeneralSetting::where('title', $title)->first();
  if (isset($settingInfo)) {
    return trim($settingInfo->value);
  }
  return '';
}

function getProfile($id,$data=""){
  if(Auth::check()) {     
      $userData = User::find($id);
      if($data==""){
        if($userData->profile_image){
          return showImage($userData->profile_image);  
        }else{
            return asset('img/default-icon.png');
        }
      }else{
        return ucwords($userData->name);  
      }
  }
}

function sendEmailMailChimp($email) {
    $list_id = '211212';
    $api_key = '12122121';

    $data_center = substr($api_key, strpos($api_key, '-') + 1);

    $url = 'https://' . $data_center . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members';
    $userInfo = User::where('email', $email)->first();
    if (!$userInfo) {
      $f_name = '';
      $l_name = '';
    } else {
      $f_name = $userInfo->name;
      $l_name = $userInfo->l_name;
    }
    $json = json_encode([
      'email_address' => $email,
      'status' => 'pending', //pass 'subscribed' or 'pending'
      'merge_fields' => [
        'FNAME' => $f_name,
        'LNAME' => $l_name,
      ],
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $api_key);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    $result = curl_exec($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    // dd($status_code);
}

