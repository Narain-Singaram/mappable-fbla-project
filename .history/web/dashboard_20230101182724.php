<?php
// Include required files
include "../template/web_defaults.php";
include "../template/navbar.php";

// Connect to database
$connection = mysqli_connect("localhost", "username", "password", "database_name");

// Get number of global users
$number_of_global_users_query = "SELECT COUNT(*) AS id FROM users";
$number_of_global_users_result = mysqli_query($connection, $number_of_global_users_query);
$number_of_global_users_row = mysqli_fetch_assoc($number_of_global_users_result);
$number_of_global_users = $number_of_global_users_row['id'];

// Get user list ordered by points and gems
$user_list_points_query = "SELECT * FROM users ORDER BY points DESC LIMIT 10";
$user_list_points_result = mysqli_query($connection, $user_list_points_query);

$user_list_gems_query = "SELECT * FROM users ORDER BY gems DESC LIMIT 10";
$user_list_gems_result = mysqli_query($connection, $user_list_gems_query);

// Get sum of all gems, points and experience in users table
$total_gems_query = "SELECT SUM(gems) AS gem_sum FROM users";
$total_gems_result = mysqli_query($connection, $total_gems_query);
$total_gems_row = mysqli_fetch_assoc($total_gems_result);
$total_sum_gems = $total_gems_row['gem_sum'];

$total_points_query = "SELECT SUM(points) AS point_sum FROM users";
$total_points_result = mysqli_query($connection, $total_points_query);
$total_points_row = mysqli_fetch_assoc($total_points_result);
$total_sum_points = $total_points_row['point_sum'];

$total_experience_query = "SELECT SUM(experience) AS experience_sum FROM users";
$total_experience_result = mysqli_query($connection, $total_experience_query);
$total_experience_row = mysqli_fetch_assoc($total_experience_result);
$total_sum_experience = $total_experience_row['experience_sum'];
?>

<!-- HTML and PHP code for displaying the data on a web page -->


<!-- NAVBAR -->
<section id="content" class="bg-gray-100">
<!-- MAIN -->
<main>
<h1 class="text-3xl font-semibold">Welcome to the Dashboard, <?php echo " <a href='profile.php?profile_username=$userLoggedIn' class='text-blue-600'>$full_name</a>"; ?></h1>

<div class="flex flex-wrap mt-6 -mx-3">
<div class="w-full px-3 mb-6 lg:mb-0 lg:w-7/12 rounded-2xl">
<div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
<div class="flex-auto p-4">
<div class="flex flex-wrap -mx-3">
<div class="max-w-full px-3 lg:w-1/2 lg:flex-none">
<div class="flex flex-col h-full">
<p class="text-xs w-max text-neutral-500">Built by developers</p>
<h5 class="text-3xl my-2 font-bold">Mappable Dashboard</h5>
<p class="pb-4 text-mg text-neutral-500">Constructed by student developers in West Forsyth High School to improve student engagement and commitment all throughout school. </p>
<a class="font-semibold leading-normal text-sm text-slate-500">
Read More
	<i class="uil uil-angle-right-b"></i>
</a>
</div>
</div>
<div class="max-w-full px-3 mt-12 ml-auto text-center lg:mt-0 lg:w-5/12 lg:flex-none">
<div class="h-full bg-slate-200 rounded-2xl p-3">
<div class="relative flex items-center justify-center h-full">
<img class="relative z-20 w-full w-44" src="../assets/images/your_developers.png" alt="rocket">
	</div>
		</div>
			</div>
				</div>
					</div>
						</div>
							</div>
<div class="w-full max-w-full px-3 lg:w-5/12 lg:flex-none">
<div class="border-black/12.5 shadow-soft-xl relative flex h-full min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border p-4">
<div class="relative h-full overflow-hidden bg-cover rounded-xl" style="background-image: url('../assets/img/ivancik.jpg')">
<span class="absolute top-0 left-0 w-full h-full bg-center bg-cover bg-gradient-to-tl from-gray-900 to-slate-800 opacity-80"></span>
<div class="relative z-10 flex flex-col flex-auto h-full p-4">
<h5 class="pt-2 mb-6 font-bold text-white">Work with the rockets</h5>
<p class="text-white">Wealth creation is an evolutionarily recent positive-sum game. It is all about who take the opportunity first.</p>
<a class="mt-auto mb-0 font-semibold leading-normal text-white group text-sm" href="javascript:;">
Read More
<i class="fas fa-arrow-right ease-bounce text-sm group-hover:translate-x-1.25 ml-1 leading-normal transition-all duration-200" aria-hidden="true"></i>
</a>
</div>
	</div>
		</div>
			</div>
				</div>

<section class="py-2 rounded-2xl my-3">
<div class="container grid grid-cols-1 gap-6 mx-auto sm:grid-cols-2 xl:grid-cols-4">
<div class="flex p-4 space-x-4 rounded-2xl md:space-x-6 bg-white">
<div class="flex justify-center p-2 align-middle rounded-lg sm:p-4 bg-blue-100">
	<i class="uil uil-user text-3xl px-1 text-black"></i>
</div>
<div class="flex flex-col justify-center align-middle">
	<p class="text-3xl font-semibold leading-none"> <?php while (
     $row = $number_of_global_users->fetch_assoc()
 ) {
     echo $row["id"] . "<br>";
 } ?></p>
	<p class="capitalize">Users</p>
</div>
</div>
<div class="flex p-4 space-x-4 rounded-2xl md:space-x-6 bg-white">
<div class="flex justify-center p-2 align-middle rounded-lg sm:p-4 bg-green-100">
<i class="bx bx-diamond text-3xl px-1 text-black"></i>
</div>
<div class="flex flex-col justify-center align-middle">
	<p class="text-3xl font-semibold leading-none"><?php echo $total_sum_gems; ?></p>
	<p class="capitalize">Overall Gems</p>
</div>
</div>
<div class="flex p-4 space-x-4 rounded-2xl md:space-x-6 bg-white">
<div class="flex justify-center p-2 align-middle rounded-lg sm:p-4 bg-yellow-200">
<img class='w-12 inline' src='../assets/images/points.png'>
</div>
<div class="flex flex-col justify-center align-middle">
	<p class="text-3xl font-semibold leading-none"><?php echo $total_sum_points; ?></p>
	<p class="capitalize">Overall Points</p>
</div>
</div>
<div class="flex p-4 space-x-4 rounded-2xl md:space-x-6 bg-white">
<div class="flex justify-center p-2 align-middle rounded-lg sm:p-4 dark:bg-violet-100">
	<img class='w-12 inline -rotate-12' src='../assets/images/experience_points.png'>
</div>
<div class="flex flex-col justify-center align-middle">
	<p class="text-3xl font-semibold leading-none"><?php echo $experience_sum_points; ?></p>
	<p class="capitalize">Experience Points</p>
</div>
</div>
</div>
	</section>

<div class="w-1/2 max-w-full px-3 lg:w-6/12 lg:flex-none display-inline box-sizing:border-box float-left pt-6">
<div class="border-black/12.5 shadow-soft-xl relative flex h-full min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border p-2">

<table class="w-full text-sm text-left text-gray-500">
<thead class="text-xs text-gray-700 uppercase bg-gray-50">
	<tr>
		<th scope="col" class="py-3 px-6">
			Ranking
		</th>
		<th scope="col" class="py-3 px-6">
			Name
		</th>
		<th scope="col" class="py-3 px-6">
			School
		</th>
		<th scope="col" class="py-3 px-6">
			Grade
		</th>
		<th scope="col" class="py-3 px-6">
			Points
		</th>
	</tr>
</thead>
<tbody>
<?php
$leaderboard_i = 0;

foreach ($user_list_points_query as $row) {
    $leaderboard_i = $leaderboard_i + 1;
    $_SESSION["leaderboard"] = $leaderboard_i;
    $user_list_points_first_name = $row["first_name"];
    $user_list_points_last_name = $row["last_name"];
    $user_list_points_school = $row["school"];
    $grade = $row["grade"];
    $user_list_points = $row["points"];

    echo "
<tr class='bg-white'>
<th scope='row' class='py-4 px-6 font-medium text-gray-900 whitespace-nowrap'>
	$leaderboard_i
</th>
<td class='py-4 px-6'>
	$user_list_points_first_name $user_list_points_last_name
</td>
<td class='py-4 px-6'>
	$user_list_points_school
</td>
<td class='py-4 px-6'>
	$grade
</td>
<td class='py-4 px-6'>
	$user_list_points
</td>
</tr>
";
}
?>
        </tbody>
    </table>
</div>
	</div>
		</div>
</div>
</div>


<div class="content-data">
<div class="head">
</div>

<div class="w-1/2 max-w-full px-3 lg:w-6/12 lg:flex-none display-inline box-sizing:border-box float-left pt-6">
<div class="border-black/12.5 shadow-soft-xl relative flex h-full min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border p-2">

<table class="w-full text-sm text-left text-gray-500">
<thead class="text-xs text-gray-700 uppercase bg-gray-50">
	<tr>
		<th scope="col" class="py-3 px-6">
			Ranking
		</th>
		<th scope="col" class="py-3 px-6">
			Name
		</th>
		<th scope="col" class="py-3 px-6">
			School
		</th>
		<th scope="col" class="py-3 px-6">
			Grade
		</th>
		<th scope="col" class="py-3 px-6">
			Gems
		</th>
	</tr>
</thead>
<tbody>

<?php
$leaderboard_i = 0;

foreach ($user_list_gems_query as $row) {
    $leaderboard_i = $leaderboard_i + 1;
    $_SESSION["leaderboard"] = $leaderboard_i;
    $user_list_gems_first_name = $row["first_name"];
    $user_list_gems_last_name = $row["last_name"];
    $user_list_gems_school = $row["school"];
    $grade = $row["grade"];
    $user_list_gems = $row["gems"];

    echo "
<tr class='bg-white'>
<th scope='row' class='py-4 px-6 font-medium text-gray-900 whitespace-nowrap'>
	$leaderboard_i
</th>
<td class='py-4 px-6'>
	$user_list_gems_first_name $user_list_gems_last_name
</td>
<td class='py-4 px-6'>
	$user_list_gems_school
</td>
<td class='py-4 px-6'>
	$grade
</td>
<td class='py-4 px-6'>
	$user_list_gems
</td>
</tr>
";
}
?>
        </tbody>
    </table>
</div>
	</div>
		</div>
</div>
</div>
</div>
</div>
</div>
</main>
</section>
<!-- MAIN -->
</section>
	<!-- NAVBAR -->
</div>
</div>
</div>

