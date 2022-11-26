<?php
class Teacher_Events {
    private $user_object;
    private $con;

    public function __construct($con, $user) {
        $this->con = $con;
        $this->user_object = new User_Info($con, $user);
    }

    public function event_feed($title, $type, $date, $start_time, $end_time, $description, $image, $user_to) {
        $title = strip_tags($title);
        $description = strip_tags($description);
    
        $delete_all_spaces = preg_replace('/\s+/' /* this means space */, '', $description);

        if($delete_all_spaces != ""){
            $date_added = date('Y-m-d H:i:s');
            $added_by = $this->user_object->gettingUsername();
    
            if($user_to == $added_by) {
                $user_to = "none";
            }

        $push_event_submit_query = mysqli_query($this->con, "INSERT INTO teacher_events VALUES(NULL, '$title', '$type', '$date', '$start_time', '$end_time', '$description', '$image', '$added_by', '$user_to', '$date_added', '', 'no')");
        $id_return = mysqli_insert_id($this->con);
    }
}

public function load_requested_feed() {
    $userLoggedIn = $this->user_object->gettingUsername();
    $event_data_query = mysqli_query($this->con, "SELECT * FROM teacher_events WHERE user_deleted='no' ORDER BY event_id DESC");
    $requested_content = '';

    while($event_row = mysqli_fetch_array($event_data_query)) {
            $id = $event_row['event_id'];
            $title = $event_row['title'];
            $type = $event_row['type'];
            $start_time = $event_row['start']; 
            $end_time = $event_row['end']; 
            $description = $event_row['description'];
            $image = $event_row['image'];
            $added_by = $event_row['added_by'];
            $date_added = $event_row['date_added'];
    
            if($event_row['user_to'] == 'none') {
                $user_to = '';
            }
    
            $create_event_query = mysqli_query($this->con, "SELECT * FROM users WHERE username='$added_by'");
            $row = mysqli_fetch_array($create_event_query);
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $username = $row['username'];
            $position = $row['position'];
            $created_account_date = $row['date'];
    
            $fn_i = $first_name[0];
            $ln_i = $last_name[0];

            $check_requests = mysqli_query($this->con,"SELECT * FROM authentifications WHERE id='$id' AND requester='$userLoggedIn'");

            $match_request_rows = mysqli_num_rows($check_requests);
            
        
        if($match_request_rows == 1) {
    
            $requested_content .="
        <li class='py-3 sm:py-4 mb-3 rounded-2xl shadow-[rgba(7,_65,_50,_0.1)_0px_9px_50px] px-2 py-1'>
            <div class='flex items-center space-x-4'>
                <button class='absolute bg-slate-200 w-8 h-8 text-md text-gray-700 rounded-full font-semibold'>
                <span class=''> $first_name[0]$last_name[0] </span>
                </button>
            <div class='flex-shrink-0'>
                <img class='object-cover w-16 h-10 rounded-lg shadow-md' src='../assets/event_images/$image'>
            </div>

        <div class='flex-1 min-w-0'>
                    <p class='text-sm font-medium text-gray-900 truncate'>
                        $first_name $last_name
                    </p>
                    <p class='text-sm text-gray-500 truncate'>
                        $description
                    </p>
                </div>
                <div class='inline-flex cursor-pointer active:scale-105 items-center text-sm text-yellow-500 bg-red-100 p-2 rounded-xl text-gray-900'>
                <svg width='20' height='20' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
                <path opacity='0.4' d='M19.6433 9.48844C19.6433 9.55644 19.1103 16.2972 18.8059 19.1341C18.6153 20.875 17.493 21.931 15.8095 21.961C14.516 21.99 13.2497 22 12.0039 22C10.6812 22 9.38772 21.99 8.13216 21.961C6.50508 21.922 5.38178 20.845 5.20089 19.1341C4.88772 16.2872 4.36449 9.55644 4.35477 9.48844C4.34504 9.28345 4.41117 9.08846 4.54539 8.93046C4.67765 8.78447 4.86827 8.69647 5.06862 8.69647H18.9392C19.1385 8.69647 19.3194 8.78447 19.4624 8.93046C19.5956 9.08846 19.6627 9.28345 19.6433 9.48844Z' fill='#ef4444'></path>
                <path opacity='0.4' d='M19.6433 9.48844C19.6433 9.55644 19.1103 16.2972 18.8059 19.1341C18.6153 20.875 17.493 21.931 15.8095 21.961C14.516 21.99 13.2497 22 12.0039 22C10.6812 22 9.38772 21.99 8.13216 21.961C6.50508 21.922 5.38178 20.845 5.20089 19.1341C4.88772 16.2872 4.36449 9.55644 4.35477 9.48844C4.34504 9.28345 4.41117 9.08846 4.54539 8.93046C4.67765 8.78447 4.86827 8.69647 5.06862 8.69647H18.9392C19.1385 8.69647 19.3194 8.78447 19.4624 8.93046C19.5956 9.08846 19.6627 9.28345 19.6433 9.48844Z' fill='#ef4444'></path>
                <path d='M21 5.97686C21 5.56588 20.6761 5.24389 20.2871 5.24389H17.3714C16.7781 5.24389 16.2627 4.8219 16.1304 4.22692L15.967 3.49795C15.7385 2.61698 14.9498 2 14.0647 2H9.93624C9.0415 2 8.26054 2.61698 8.02323 3.54595L7.87054 4.22792C7.7373 4.8219 7.22185 5.24389 6.62957 5.24389H3.71385C3.32386 5.24389 3 5.56588 3 5.97686V6.35685C3 6.75783 3.32386 7.08982 3.71385 7.08982H20.2871C20.6761 7.08982 21 6.75783 21 6.35685V5.97686Z' fill='#ef4444'></path>
                </svg>
                </div>
            </div>
        </li>
    ";
    }
            }
    echo $requested_content;
}


public function load_regular_feed() {
    $userLoggedIn = $this->user_object->gettingUsername();
    $event_data_query = mysqli_query($this->con, "SELECT * FROM teacher_events WHERE user_deleted='no' ORDER BY event_id DESC");
    
    $event_content = '';
    
    if(isset($_POST['auth_submit'])) {
    
      $event_id = $_POST['event_id'];
    
      $verifying_checkin = mysqli_query($this->con, "SELECT COUNT(*) AS checkin_crosscheck FROM authentifications WHERE id='$event_id'");
    
      $cross_check_result = mysqli_fetch_assoc($verifying_checkin);
    
      $comments = $_POST['auth_comments'];
      $authentifier = $_POST['authentifier'];
      $title = $_POST['auth_title'];
      $image = $_POST['auth_image'];
      $auth_query = mysqli_query($this->con, "INSERT INTO authentifications VALUES($event_id, '$authentifier', '$userLoggedIn', '$title', '$image', '$comments', 'no', 'no') ORDER BY id DESC");
    
      echo $cross_check_result['checkin_crosscheck'];
      header('Location: index.php');
    }
    
    while($event_row = mysqli_fetch_array($event_data_query)) {
        $id = $event_row['event_id'];
        $title = $event_row['title'];
        $type = $event_row['type'];
        $date = $event_row['date'];
        $start_time = $event_row['start']; 
        $end_time = $event_row['end']; 
        $description = $event_row['description'];
        $image = $event_row['image'];
        $added_by = $event_row['added_by'];
        $date_added = $event_row['date_added'];

        $date = date_create($date);
        $reformated_date = date_format($date,'m/d/Y');

        if($event_row['user_to'] == 'none') {
            $user_to = '';
        }

        $create_event_query = mysqli_query($this->con, "SELECT * FROM users WHERE username='$added_by'");
        $row = mysqli_fetch_array($create_event_query);
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $username = $row['username'];
        $position = $row['position'];
        $level = $row['levels'];
        $created_account_date = $row['date'];

        $fn_i = $first_name[0];
        $ln_i = $last_name[0];

        $check_requests = mysqli_query($this->con,"SELECT * FROM authentifications WHERE id='$id' AND requester='$userLoggedIn'");

        $match_request_rows = mysqli_num_rows($check_requests);
        

        if($match_request_rows == 0) {
    
            $event_content .="                     
        <div class='relative shadow-[rgba(7,_65,_50,_0.1)_0px_9px_50px] transition ease-in px-3 pb-4 pt-2 rounded-2xl my-4'>
            <div>
                <div>
                    <div class='flex align-center'> 
                    <div class='inline-flex overflow-hidden relative justify-center items-center w-12 h-12 mr-2 text-xl bg-slate-300/30 rounded-full'>
    <span class='font-semibold text-gray-600'>$first_name[0]$last_name[0]</span>
</div>
                        <ul class='mt-2'>
                            <li>
                                <h3>
                                $first_name $last_name 
                                <span class='bg-blue-300/20 text-blue-500 text-xs font-semibold px-2 py-1 tracking-wide rounded'>Lvl. $level $position</span>
                                </h3>
                            </li>
                            <li><span class='text-gray-400 text-sm'>$date_added</span>
                            </li>
                        </ul>
                    </div>
            <div>
                <h1 class='rounded-2xl bg-slate-300/30 my-3 px-4 py-3 text-2xl font-bold text-black'>$title</h1>
            </div>
                    <div class='post-images'>
                        <img class='mb-3 rounded-2xl overflow-hidden w-max h-max' src='../assets/event_images/$image'> 
                    </div>
                <div>
                $reformated_date <br>
                $start_time $end_time
                </div>
                    <p class='break-all'>$description</p>
                </div>

<main id='action_buttons' class='flex flex-column'>
          <form action='index.php' method='POST' enctype='multipart/form-data' class='inline'>
          <input type='hidden' name='event_id' value='$id'>
          <input type='hidden' name='authentifier' value='$added_by'>
          <input type='hidden' name='auth_title' value='$title'>
          <input type='hidden' name='auth_image' value='$image'>
            <button name='auth_submit' type='submit' class='active:scale-105 inline px-1 py-2 rounded-full text-xl'> 
            <svg width='35' height='35' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
            <path opacity='0.4' d='M18.8088 9.021C18.3573 9.021 17.7592 9.011 17.0146 9.011C15.1987 9.011 13.7055 7.508 13.7055 5.675V2.459C13.7055 2.206 13.5026 2 13.253 2H7.96363C5.49517 2 3.5 4.026 3.5 6.509V17.284C3.5 19.889 5.59022 22 8.16958 22H16.0453C18.5058 22 20.5 19.987 20.5 17.502V9.471C20.5 9.217 20.298 9.012 20.0465 9.013C19.6247 9.016 19.1168 9.021 18.8088 9.021Z' fill='#10b981'></path>
            <path opacity='0.4' d='M16.0842 2.5673C15.7852 2.2563 15.2632 2.4703 15.2632 2.9013V5.5383C15.2632 6.6443 16.1742 7.5543 17.2792 7.5543C17.9772 7.5623 18.9452 7.5643 19.7672 7.5623C20.1882 7.5613 20.4022 7.0583 20.1102 6.7543C19.0552 5.6573 17.1662 3.6913 16.0842 2.5673Z' fill='#10b981'></path>
            <path d='M15.1052 12.8837C14.8142 13.1727 14.3432 13.1747 14.0512 12.8817L12.4622 11.2847V16.1117C12.4622 16.5227 12.1282 16.8567 11.7172 16.8567C11.3062 16.8567 10.9732 16.5227 10.9732 16.1117V11.2847L9.38223 12.8817C9.09223 13.1747 8.62023 13.1727 8.32923 12.8837C8.03823 12.5947 8.03723 12.1227 8.32723 11.8307L11.1892 8.9557C11.1902 8.9547 11.1902 8.9547 11.1902 8.9547C11.2582 8.8867 11.3402 8.8317 11.4302 8.7947C11.5202 8.7567 11.6182 8.7367 11.7172 8.7367C11.8172 8.7367 11.9152 8.7567 12.0052 8.7947C12.0942 8.8317 12.1752 8.8867 12.2432 8.9537C12.2442 8.9547 12.2452 8.9547 12.2452 8.9557L15.1072 11.8307C15.3972 12.1227 15.3972 12.5947 15.1052 12.8837Z' fill='#10b981'></path>
            </svg>
            </button>
          </form>  

          <button class='active:scale-105 inline px-1 py-2 rounded-full text-xl'>
            <svg width='35' height='35' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
            <path opacity='0.4' d='M12.02 2C6.21 2 2 6.74 2 12C2 13.68 2.49 15.41 3.35 16.99C3.51 17.25 3.53 17.58 3.42 17.89L2.75 20.13C2.6 20.67 3.06 21.07 3.57 20.91L5.59 20.31C6.14 20.13 6.57 20.36 7.081 20.67C8.541 21.53 10.36 21.97 12 21.97C16.96 21.97 22 18.14 22 11.97C22 6.65 17.7 2 12.02 2Z' fill='#6366f1'></path>
            <path opacity='0.4' d='M11.9805 13.2901C11.2705 13.2801 10.7005 12.7101 10.7005 12.0001C10.7005 11.3001 11.2805 10.7201 11.9805 10.7301C12.6905 10.7301 13.2605 11.3001 13.2605 12.0101C13.2605 12.7101 12.6905 13.2901 11.9805 13.2901ZM7.37009 13.2901C6.67009 13.2901 6.09009 12.7101 6.09009 12.0101C6.09009 11.3001 6.66009 10.7301 7.37009 10.7301C8.08009 10.7301 8.65009 11.3001 8.65009 12.0101C8.65009 12.7101 8.08009 13.2801 7.37009 13.2901ZM15.3103 12.0101C15.3103 12.7101 15.8803 13.2901 16.5903 13.2901C17.3003 13.2901 17.8703 12.7101 17.8703 12.0101C17.8703 11.3001 17.3003 10.7301 16.5903 10.7301C15.8803 10.7301 15.3103 11.3001 15.3103 12.0101Z' fill='#6366f1'></path>
            </svg>
          </button>

          <button class='active:scale-105 inline px-0.5 py-2 rounded-full text-xl'>
          <svg width='35' height='35' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
          <path opacity='0.4' d='M11.9912 18.6215L5.49945 21.8641C5.00921 22.1302 4.39768 21.9525 4.12348 21.4643C4.0434 21.3108 4.00106 21.1402 4 20.9668V13.7088C4 14.4284 4.40573 14.8726 5.47299 15.3701L11.9912 18.6215Z' fill='#f97316'></path>
          <path opacity='0.4' d='M8.89526 2H15.0695C17.7773 2 19.9735 3.06605 20 5.79337V20.9668C19.9989 21.1374 19.9565 21.3051 19.8765 21.4554C19.7479 21.7007 19.5259 21.8827 19.2615 21.9598C18.997 22.0368 18.7128 22.0023 18.4741 21.8641L11.9912 18.6215L5.47299 15.3701C4.40573 14.8726 4 14.4284 4 13.7088V5.79337C4 3.06605 6.19625 2 8.89526 2ZM8.22492 9.62227H15.7486C16.1822 9.62227 16.5336 9.26828 16.5336 8.83162C16.5336 8.39495 16.1822 8.04096 15.7486 8.04096H8.22492C7.79137 8.04096 7.43991 8.39495 7.43991 8.83162C7.43991 9.26828 7.79137 9.62227 8.22492 9.62227Z' fill='#f97316'></path>
          </svg>
        </button>
            </div>
    <div class='-top-0 -right-0 absolute dropdown'>
          <label tabindex='0' class='px-3 py-2 active:scale-125 cursor-pointer text-sm'><i class='uil uil-ellipsis-h'></i></label>
          <ul tabindex='0' class='dropdown-content menu p-2 shadow-[rgba(7,_65,_50,_0.1)_0px_9px_50px] bg-white rounded-2xl w-52'>
            <li><a>View Profile</a></li>
            <li><a>Save to Bookmarks</a></li>
            <li><a>Share</a></li>
            <li><a>Report</a></li>
          </ul>
        </div>
    </div>
</main>
    
    ";
}
        //Today's date
        $current_date = date('Y-m-d');

        //Filter & Delete events if date has already passed
        if($current_date > $date) {
            mysqli_query($this->con,"DELETE FROM teacher_events WHERE event_id='$id' AND added_by='$userLoggedIn'");
        }
        }
    echo $event_content;
    }

    public function live_events() {
        $live_event_query = mysqli_query($this->con, "SELECT * FROM teacher_events WHERE user_deleted='no' ORDER BY event_id DESC");

        $live_event_content = '';

        while($live = mysqli_fetch_array($live_event_query)) {
            $id = $live['event_id'];
            $title = $live['title'];
            $type = $live['type'];
            $date = $live['date'];
            $start_time = $live['start']; 
            $end_time = $live['end']; 
            $description = $live['description'];
            $image = $live['image'];
            $added_by = $live['added_by'];
            $date_added = $live['date_added'];
        
            //Today's date
            $current_date = date('Y-m-d');
            //time without PM or AM
            $current_time = date('h:i');
            //time with PM or AM
            $current_time_w_a = date('h:iA');

            //change all time variables into integers to verify which time is bigger
            $array_current_time = explode(":", $current_time);
            $int_current_time = $array_current_time[0] . $array_current_time[1];
        
            $array_start_time = explode(":", $start_time);
            $int_start_time = $array_start_time[0] . $array_start_time[1];
        
            $array_end_time = explode(":", $end_time);
            $int_end_time = $array_end_time[0] . $array_end_time[1];

            //if the current time is in PM, add 12 hours and if the current time is in AM, subtract 12 hours
            if($current_time . 'PM' == $current_time_w_a ) {
                $int_current_time = $int_current_time + 1200;
            }
            
            if($current_date == $date) {
            /* integer version of current time - integer version start time must be above 0 for it to be live
                integer version of current time - integer version end time must be less than 0 for it to be live
            */

              if($int_current_time > $int_start_time && $int_current_time < $int_end_time) {
                $add_live_events = mysqli_query($this->con, "UPDATE teacher_events SET live='yes' WHERE event_id='$id' AND added_by='$added_by'");
                $live_event_content .="
                    $id $int_current_time

                    <div class='relative'>
    <div class='rounded-full' src='/docs/images/people/profile-picture-5.jpg'>fasdfsa</div>
</div>


<div class='p-6 relative rounded-2xl shadow-[rgba(7,_65,_50,_0.1)_0px_9px_50px] hover:-translate-y-1 transition ease-in'>
    <header class='mb-2'>
        <h5 class='inline text-2xl font-bold tracking-tight text-gray-900'>
        $title
        </h5>
        <span class='text-xs tracking-normal uppercase font-semibold text-emerald-500 bg-emerald-200 px-2 py-1 active:scale-10 rounded-full'>Live</span>
    </header>
    <p class='mb-3 font-normal text-gray-700'>$description</p>
    <a class='inline-flex items-center py-2 px-3 text-sm font-medium text-center text-blue-500 bg-blue-200/60 cursor-pointer rounded-xl'>
        <svg class='mr-1' width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
        <path d='M21.101 9.58786H19.8979V8.41162C19.8979 7.90945 19.4952 7.5 18.999 7.5C18.5038 7.5 18.1 7.90945 18.1 8.41162V9.58786H16.899C16.4027 9.58786 16 9.99731 16 10.4995C16 11.0016 16.4027 11.4111 16.899 11.4111H18.1V12.5884C18.1 13.0906 18.5038 13.5 18.999 13.5C19.4952 13.5 19.8979 13.0906 19.8979 12.5884V11.4111H21.101C21.5962 11.4111 22 11.0016 22 10.4995C22 9.99731 21.5962 9.58786 21.101 9.58786Z' fill='#3b82f6'></path>
        <path d='M9.5 15.0155C5.45422 15.0155 2 15.6623 2 18.2466C2 20.8299 5.4332 21.5 9.5 21.5C13.5448 21.5 17 20.8532 17 18.2689C17 15.6846 13.5668 15.0155 9.5 15.0155Z' fill='#3b82f6'></path>
        <path opacity='0.4' d='M9.49999 12.5542C12.2546 12.5542 14.4626 10.3177 14.4626 7.52761C14.4626 4.73754 12.2546 2.5 9.49999 2.5C6.74541 2.5 4.53735 4.73754 4.53735 7.52761C4.53735 10.3177 6.74541 12.5542 9.49999 12.5542Z' fill='#3b82f6'></path>
        </svg>
    Join Event
    </a>
    <span class='-top-0 -right-0 absolute w-3 h-3 bg-green-400 border-2 border-white rounded-full animate-ping opacity-75'></span>
</div>

                    
                "; 
              }
              else if($int_current_time < $int_start_time || $int_current_time > $int_end_time) {
                $remove_live_events = mysqli_query($this->con, "UPDATE teacher_events SET live='no' WHERE event_id='$id' AND added_by='$added_by'");
              }
            }
            else {
            //if current != date then set as not live
                $remove_live_events = mysqli_query($this->con, "UPDATE teacher_events SET live='no' WHERE event_id='$id' AND added_by='$added_by'");
            }
          }
          echo $live_event_content;
    }
}
?>