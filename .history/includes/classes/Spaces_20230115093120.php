<?php
class Spaces {
    private $user_object;
    private $connection;

    public function __construct($connection, $user) {
        $this->con = $connection;
        $this->user_object = new User_Info($connection, $user);
    }

    public function createSpace($space_id, $crt_user, $connectiontent, $type, $date) {
        // Sanitize input
        $space_id = filter_var($title, FILTER_SANITIZE_STRING);
        $crt_user = filter_var($type, FILTER_SANITIZE_STRING);
        $connectiontent = filter_var($date, FILTER_SANITIZE_STRING);
        $start_time = filter_var($start_time, FILTER_SANITIZE_STRING);
        $type = filter_var($end_time, FILTER_SANITIZE_STRING);
        $date = filter_var($description, FILTER_SANITIZE_STRING);

        if (empty($space_id) || empty($crt_user) || empty($connectiontent) || empty($type)) {
            throw new Exception("All fields are required.");
        }

        if (!preg_match('/^[\w\s\d]+$/', $space_id)) {
            throw new Exception("Title must be alphanumeric.");
        }
    
        if (!preg_match('/^[\w\s\d]+$/', $crt_user)) {
            throw new Exception("Type must be alphanumeric.");
        }

        if (!preg_match('/^[\w\s\d]+$/', $connectiontent)) {
            throw new Exception("Type must be alphanumeric.");
        }

        if (!preg_match('/^[\w\s\d]+$/', $type)) {
            throw new Exception("Type must be alphanumeric.");
        }
    
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            throw new Exception("Invalid date format. Date must be in YYYY-MM-DD format.");
        }

        // $stmt = $this->con->prepare("INSERT INTO teacher_events (authentifier, type, date, start_time, end_time, description, image, added_by, date_added) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)")
        // $stmt->bind_param("ssssss", )
    }

    public function load_current_space($space_id) {
        $userLoggedIn = $this->user_object->gettingUsername();

        $spaces_query = mysqli_query($this->con,"SELECT * FROM spc_msgs WHERE space_location = '$space_id' ORDER BY spacemsg_id ");
        $spc_content = '';
        
        if (mysqli_num_rows($spaces_query) > 0) {
            while($spc_rows = mysqli_fetch_array($spaces_query)) {
                $sender = $spc_rows['sender'];
                $connectiontent = $spc_rows['content'];
                $type = $spc_rows['type'];
                $time = $spc_rows['time'];

                $get_sender_info = mysqli_query($this->con, "SELECT * FROM users WHERE username='$sender'");
                $sender_info = mysqli_fetch_array($get_sender_info);

                $sender_name = $sender_info['first_name'] . ' ' . $sender_info['last_name'];
                $sender_pfp = $sender_info['first_name'][0] . $sender_info['last_name'][0];

                $select_space = mysqli_query($this->con,"SELECT * FROM spaces WHERE space_id = '$space_id'");

                $crt_main_spc = mysqli_fetch_array($select_space);
                $crt_spc_name = $crt_main_spc['name'];

                if ($sender == $userLoggedIn && $type == 'default' ) {
                    $spc_content .= "
                    <li class='flex justify-end my-3'>
                        <div class='bg-blue-400 rounded-full text-white relative max-w-xl px-3 py-2'>
                            <span class='block'>$connectiontent</span>
                        </div>
                    </li>
                ";
                }
                else if ($sender != $userLoggedIn && $type == 'default') {
                    $spc_content .= "
                    <li class='flex justify-start my-4'>
                        <span class='my-1 relative max-w-xl px-2 mx-2 py-2 bg-slate-100 rounded-full'>$sender_pfp</span>
                        <div class='bg-slate-100 rounded-full my-1 relative max-w-xl px-3 py-2'>
                            <span class='block'>$connectiontent</span>
                        </div>
                    </li>
                ";
                }

                switch($type){
                    case "new_member":
                        $spc_content .= "
                    <li class='m-auto bg-slate-200 w-fit px-4 py-2.5 rounded-full flex'>
                        <p class='text-slate-600 m-auto'>
                        <span class=''>
                            <i class='bx bx-party'></i>
                        </span>
                        $sender joined $crt_spc_name</p> 
                    </li>
                        ";
                    break;
                
                    case "red";
                        echo "red and large";
                    break;
                }
            }
            echo $spc_content;
        }
        else {
            echo '
        <div class="bg-slate-200/50 w-1/2 m-auto px-8 py-10 rounded-2xl">
            <svg class="m-auto py-2" xmlns="http://www.w3.org/2000/svg" width="180" height="180" viewBox="0 0 24 24" fill="none"><path d="M14.5 10.75h-6c-.41 0-.75-.34-.75-.75s.34-.75.75-.75h6c.41 0 .75.34.75.75s-.34.75-.75.75ZM11.5 13.75h-3c-.41 0-.75-.34-.75-.75s.34-.75.75-.75h3c.41 0 .75.34.75.75s-.34.75-.75.75Z" fill="#3b82f6"></path><path opacity=".4" d="M11.5 21a9.5 9.5 0 1 0 0-19 9.5 9.5 0 0 0 0 19Z" fill="#3b82f6"></path><path d="M21.3 21.999c-.18 0-.36-.07-.49-.2l-1.86-1.86a.706.706 0 0 1 0-.99c.27-.27.71-.27.99 0l1.86 1.86c.27.27.27.71 0 .99-.14.13-.32.2-.5.2Z" fill="#3b82f6"></path></svg>
            <h1 class="text-center text-slate-400 font-semibold py-2 text-2xl"> No Messages Yet </h1>
            <p class="text-center text-slate-400">Looks like no one has sent a message in the space. <br> Be the first one to send a message!</p>
            ';
        }
    }

    public function load_space_menu_links() {
        $userLoggedIn = $this->user_object->gettingUsername();
        $links_menu = "";

        $all_spcs_query = mysqli_query($this->con,"SELECT * FROM spaces");
        while($space = mysqli_fetch_array($all_spcs_query)) {
            $id = $space['space_id'];
            $space_name = $space['name'];
            $participant_arr = explode(',', $space['participants']);
    
            if (in_array($userLoggedIn, $participant_arr)) {
                if ($space_name != "") {
                    $links_menu .= "
                        <a class='bg-slate-200/60 hover:text-slate-600 rounded-xl m-2 px-3 py-2' href='space.php?space=$id'>$space_name</a>
                    ";
                }
            }
        }
        echo $links_menu;
    }

    public function load_space_div() {
        $userLoggedIn = $this->user_object->gettingUsername();
        $spcs_list = '';
        $all_spcs_query = mysqli_query($this->con, "SELECT * FROM spaces");
      
          while($space = mysqli_fetch_array($all_spcs_query)) {
            $id = $space['space_id'];
            $date = date("Y-m-d");
            $space_name = $space['name'];
            $space_bio = $space['bio'];
            $founder = $space['founder'];
            $participant_arr = explode(',', $space['participants']);
      
            if(isset($_POST["$id-spaces-request"])) {
              $join_spc = mysqli_query($connection, "UPDATE spaces SET participants=CONCAT(participants, '$userLoggedIn,') WHERE space_id='$id' AND founder='$founder'");
              $insert_spc_new_mem_msg = mysqli_query($connection, "INSERT INTO spc_msgs VALUES (NULL,'$id', '$userLoggedIn', '', 'new_member', '$date', 'no')");
              header("Location: space.php?space=$id ");
            }
                if (in_array($userLoggedIn, $participant_arr))
                  {
                    $spcs_list .= "
                    <div class='bg-white card rounded-2xl shadow-[rgba(7,_65,_50,_0.1)_0px_9px_50px] border-none'>
                      <div class='card-body'>
                        <h2 class='card-title'>Space $id</h2>
                        <p>If a dog chews shoes whose shoes does he choose?</p>
                        <div class='card-actions justify-end'>
                            <a href='space.php?space=$id' class='btn action_btn' name='$id-spaces-request'>Go To Space</a>
                      </div>
                    </div>
                  </div>
                    ";
                  }
                else
                  {
                    $spcs_list .= "
                    <div id='spaces_div' class='my-3 card rounded-2xl shadow-[rgba(7,_65,_50,_0.1)_0px_9px_50px] border-none'>
                      <div class='card-body'>
                        <h2 class='card-title'>
                        $space_name</h2>
                        <p>$space_bio</p>
                        <div class='card-actions justify-end'>
                          <form name='index.php' method='POST'>
                            <button class='btn action_btn' name='$id-spaces-request'> 
                              <svg class='mr-1' width='20' height='20' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
                              <path d='M21.101 9.58786H19.8979V8.41162C19.8979 7.90945 19.4952 7.5 18.999 7.5C18.5038 7.5 18.1 7.90945 18.1 8.41162V9.58786H16.899C16.4027 9.58786 16 9.99731 16 10.4995C16 11.0016 16.4027 11.4111 16.899 11.4111H18.1V12.5884C18.1 13.0906 18.5038 13.5 18.999 13.5C19.4952 13.5 19.8979 13.0906 19.8979 12.5884V11.4111H21.101C21.5962 11.4111 22 11.0016 22 10.4995C22 9.99731 21.5962 9.58786 21.101 9.58786Z' fill='#3b82f6'></path>
                              <path d='M9.5 15.0155C5.45422 15.0155 2 15.6623 2 18.2466C2 20.8299 5.4332 21.5 9.5 21.5C13.5448 21.5 17 20.8532 17 18.2689C17 15.6846 13.5668 15.0155 9.5 15.0155Z' fill='#3b82f6'></path>
                              <path opacity='0.4' d='M9.49999 12.5542C12.2546 12.5542 14.4626 10.3177 14.4626 7.52761C14.4626 4.73754 12.2546 2.5 9.49999 2.5C6.74541 2.5 4.53735 4.73754 4.53735 7.52761C4.53735 10.3177 6.74541 12.5542 9.49999 12.5542Z' fill='#3b82f6'></path>
                              </svg>
                                Join
                            </button>
                          </form>
                      </div>
                    </div>
                  </div>
                    ";
                  }
             }
          echo $spcs_list;
    }
}
?>