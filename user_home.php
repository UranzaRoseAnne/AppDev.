
<?php
session_start(); // Start session

// Check if user is not logged in
if (!isset($_SESSION['email']) || !isset($_SESSION['password'])) {
    header("Location: login.php"); // Redirect to login page
    exit();
}

$email = $_SESSION['email'];
$password = $_SESSION['password'];

// Load XML file
$xml = simplexml_load_file('data.xml');

// Find the user node based on the email
$userNode = $xml->xpath("//user[email='$email']");

// Get the username
$username = (string)$userNode[0]->username;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="style1.css">

</head>
<body>
    <nav class="navbar">
        <div class="logo-section">
            <div class="logo-details">
                <h3>SOUND & LIGHT BOOKING SYSTEM</h3>
            </div>
        </div>
    </nav>
    <!-- Sticky Sidebar -->
    <div class="sidebar">
    <div class="logo_details">
      <i class="bx bx-menu-alt-right" id="btn"></i>
    </div>
    <ul class="nav-list">
      <li class="active">
      <a href="user_home.php">
          <i class="bx bx-folder"></i>
          <span class="link_name">Booked</span>
        </a>
      </li>
      <li>
      <li>
        <a href="user_package.php">
        <i class="bx bx-package"></i>
          <span class="link_name">Packages</span>
        </a>
      </li>
      <li class="profile">
        <div class="profile_details">
          <img src="images/profile.png" alt="profile image">
          <div class="profile_content">
          <div class="name"><?php echo $username; ?></div>
            <div class="designation">User</div>
          </div>
        </div>
        <div  class="logout-icon">
            <a href="logout.php"  id="log_out">
            <i class="bx bx-log-out"  id="log_out"></i></a>
        </div>
      </li>
    </ul>
  </div>
  <section class="home-section">
    <div class="dashboard-container">
        <div class="text bx bx-home">&nbsp;Home <div class=' bx bx-chevron-right'></div> Booked</div>
    </div>
    <div class="content-container">
        <div class="dashboard-name">
            <h3>Booked</h3>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <colgroup>
                    <col style="width: 12%;">
                    <col style="width: 11%;">
                    <col style="width: 11%;">
                    <col style="width: 11%;">
                    <col style="width: 11%;">
                    <col style="width: 11%;">
                    <col style="width: 11%;">
                    <col style="width: 11%;">
                    <col style="width: 11%;">
                </colgroup>
                </colgroup>
                <thead>
                <tr>
                        <th scope="col" class="text-center">Cliet Name</th>
                        <th scope="col" class="text-center">Package</th>
                        <th scope="col" class="text-center">Event Date</th>
                        <th scope="col" class="text-center">Motif</th>
                        <th scope="col" class="text-center">Venue</th>
                        <th scope="col" class="text-center">Phone Number</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col" class="text-center">Remaks</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                </thead>
                <tbody>
                <?php
// Load XML file
$xml = simplexml_load_file('data.xml');


if (!isset($_SESSION['email'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}
$userEmail = $_SESSION['email'];

// Loop through each user in the XML
foreach ($xml->user as $user) {
    // Check if the user's email matches the logged-in user's email
    if ((string)$user->email == $userEmail) {
        // Check if the user has any bookings
        if (isset($user->booking)) {
            // Loop through each booking of the user
            foreach ($user->booking as $booking) {
                // Define style based on status
                $statusStyle = '';
                switch (strtolower($booking->status)) {
                    case 'pending':
                        $statusStyle = 'margin-left:10px;margin-top:6px; background-color: yellow; color: white; text-shadow: 1px 1px 2px black; border: 1px solid gray; padding: 5px 30px; display: inline-block; text-decoration: none; border-radius: 5px; text-align: center;';
                        break;
                    case 'accept':
                        $statusStyle = 'margin-left:10px;margin-top:6px;background-color: green; color: white; text-shadow: 1px 1px 2px black; border: 1px solid gray; padding: 5px 33px; display: inline-block; text-decoration: none; border-radius: 5px; text-align: center;';
                        break;
                    case 'reject':
                        $statusStyle = 'margin-left:10px;margin-top:6px;background-color: red; color: white; text-shadow: 1px 1px 2px black; border: 1px solid gray; padding: 5px 36px; display: inline-block; text-decoration: none; border-radius: 5px; text-align: center;';
                        break;
                    default:
                        $statusStyle = '';
                        break;
                }

                // Display booking details
                echo "<tr>";
                echo "<td class='text-center'>{$booking->clientName}</td>";
                echo "<td class='text-center'>{$booking->packageName}</td>";
                echo "<td class='text-center'>{$booking->eventDate}</td>";
                echo "<td class='text-center'>{$booking->motif}</td>";
                echo "<td class='text-center'>{$booking->venue}</td>";
                echo "<td class='text-center'>{$booking->phoneNumber}</td>";
                // Apply inline style to status column
                echo "<td style='$statusStyle'>{$booking->status}</td>";
                echo "<td class='text-center'>{$booking->remarks}</td>"; // Assuming remarks will be fetched from XML, if available
                echo "<td class='text-center'>";
                // Check status and enable/disable button accordingly
                if ($booking->status == "Reject") {
                    // Enable button for Rejected bookings
                    echo "<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#editModal{$booking->id}'>Edit & Resend</button>";
                } else {
                    // Disable button for Approved or Pending bookings
                    echo "<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#editModal{$booking->id}' disabled>Edit & Resend</button>";
                }
                echo "</td>";
                echo "</tr>";

                // Edit Modal
                echo "<div class='modal fade' id='editModal{$booking->id}' tabindex='-1' role='dialog' aria-labelledby='editModalLabel{$booking->id}' aria-hidden='true'>";
                echo "<div class='modal-dialog' role='document'>";
                echo "<div class='modal-content'>";
                echo "<div class='modal-header'>";
                echo "<h5 class='modal-title' id='editModalLabel'>Edit Package</h5>";
                echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
                echo "<span aria-hidden='true'>&times;</span>";
                echo "</button>";
                echo "</div>";
                echo "<div class='modal-body'>";
                echo "<form action='edit_and_resend.php' method='post'>";
                // Add a hidden input field to store the booking ID
                echo "<input type='hidden' name='email' value='{$user->email}' />";
                echo "<input type='hidden' name='editBookingId' value='{$booking->id}' />";
                echo "<div class='form-group'>";
                echo "<label for='editClientName'>Client Name</label>";
                echo "<input type='text' class='form-control' id='editClientName' name='editClientName' value='{$booking->clientName}' required>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<label for='set'>Package</label>";
                echo "<select class='form-control' id='packagesSelect' name='set'required>";
                echo "<option value=''>Select Package</option>";
                foreach ($xml->package as $package) {
                    echo "<option value='{$package->name}'>{$package->name}</option>";
                }
                echo "</select>";
                echo "</div>"; 
                echo "<div class='form-group'>";
                echo "<label for='eventDate'>Event Date</label>";
                echo "<input type='date' class='form-control' id='eventDate' name='eventDate' required>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<label for='motif'>Motif</label>";
                echo "<input type='text' class='form-control' id='motif' name='motif' value='{$booking->motif}' required>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<label for='venue'>Venue</label>";
                echo "<input type='text' class='form-control' id='venue' name='venue' value='{$booking->venue}' required>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<label for='phoneNumber'>Phone Number</label>";
                echo "<input type='tel' class='form-control' id='phoneNumber' name='phoneNumber' value='{$booking->phoneNumber}' required>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<input type='hidden' id='status' name='status' value='Pending'>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<input type='hidden' id='remarks' name='remarks' value='N/A'>";
                echo "</div>";
                echo "<button type='submit' class='btn btn-primary'>Save Changes</button>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
        }
    }
}
?>

</tbody>
            </table>
        </div>
        <button type="button" class="btn btn-success mt-3" data-toggle="modal" data-target="#addPackageModal">
            <i class="bx bx-plus"></i> Book Now
        </button>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="addPackageModal" tabindex="-1" role="dialog" aria-labelledby="addPackageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPackageModalLabel">Book Now</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
    <!-- Form for adding a new package -->
    <form action="insert_book.php" method="post">
    <?php
    if (isset($_SESSION['email'])) {
        $loggedInEmail = $_SESSION['email'];
        echo "<input type='hidden' class='form-control' id='email' name='email' value='$loggedInEmail' readonly>";
    } else {
        // Handle if session email is not set
        echo "<input type='hidden' class='form-control' id='email' name='email'>";
    }
    ?>

        <div class="form-group">
            <label for="clientName">Client Name</label>
            <input type="text" class="form-control" id="clientName" name="clientName" placeholder="Enter Name" required>
        </div>
        <div class="form-group">
            <label for="set">Package</label>
            <select class="form-control" id="packagesSelect" name="set" required>
            <option value="">Select Package</option>
            <?php
            $xml = simplexml_load_file('data.xml');
            // Loop through each package
            foreach ($xml->package as $package) {
            echo "<option value='{$package->name}'>{$package->name}</option>";
            }
            ?>
            </select>
        </div>
        <div class="form-group">
            <label for="eventDate">Event Date</label>
            <input type="date" class="form-control" id="eventDate" name="eventDate" required>
        </div>
        <div class="form-group">
            <label for="motif">Motif</label>
            <input type="text" class="form-control" id="motif" name="motif" placeholder="Enter Motif" required>
        </div>
        <div class="form-group">
            <label for="venue">Venue</label>
            <input type="text" class="form-control" id="venue" name="venue" placeholder="Enter Venue" required>
        </div>
        <div class="form-group">
            <label for="phoneNumber">Phone Number</label>
            <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="Enter Phone Nummber" required>
        </div>
        <div class="form-group">
            <input type="hidden" id="status" name="status" value="Pending">
        </div>
        <div class="form-group">
            <input type="hidden" id="remarks" name="remarks" value="N/A">
        </div>
        <!-- Move the button inside the form -->
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>

        </div>
    </div>
</div>
    <!-- Bootstrap JavaScript and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
