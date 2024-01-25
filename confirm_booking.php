<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
    <?php require('inc/links.php'); ?>
    <title>Confirm Booking - <?php echo $settings_r['site_title'] ?></title>
</head>
<body>
<!--header-->
<?php require('inc/header.php')?>

<?php 

  /*
    CHECK ROOM ID FROM URL IF PRESENT OR NOT
    SHUTDOWN MODE (ACTIVE OR NOT)
    USER (LOGIN OR NOT)
  */

  if(!isset($_GET['id']) || $settings_r['shutdown']==true){
    redirect('rooms.php');
  }else if(!(isset($_SESSION['login']) && $_SESSION['login']==true)){
    redirect('rooms.php');
  }

  // FILTER AND GET ROOM AND USER DATA 

  $data = filteration($_GET);

  $room_res = select("SELECT * FROM `rooms` WHERE `id`=? AND `status`=? AND `removed`=?",[$data['id'],1,0],'iii');

  if(mysqli_num_rows($room_res)==0){
    redirect('rooms.php');
  }

  $room_data = mysqli_fetch_assoc($room_res);

  $_SESSION['room'] = [
    "id" => $room_data['id'],
    "name" => $room_data['name'],
    "price" => $room_data['price'],
    "payment" => null,
    "available" => false,
  ];

  $user_res = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1",[$_SESSION['uId']],"i");
  $user_data = mysqli_fetch_assoc($user_res);

?>

  <div class="container">
    <div class="row">

      <div class="col-12 my-5 mb-4 px-4">
        <h2 class="fw-bold">CONFIRM BOOKING</h2>
        <div style="font-size: 14px;">
          <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
          <span class="text-secondary"> > </span>
          <a href="rooms.php" class="text-secondary text-decoration-none">ROOMS</a>
          <span class="text-secondary"> > </span>
          <a href="#" class="text-secondary text-decoration-none">CONFIRM</a>
        </div>
      </div>

    <div class="col-lg-7 col-md-12 px-4">
      <?php

        $room_thumb = ROOMS_IMG_PATH."thumbnail.jpg";
        $thumb_q = mysqli_query($con, "SELECT * FROM `room_images` 
          WHERE `room_id`='$room_data[id]' 
          AND `thumb`='1'");

        if(mysqli_num_rows($thumb_q)>0){
          $thumb_res = mysqli_fetch_assoc($thumb_q);
          $room_thumb = ROOMS_IMG_PATH.$thumb_res['image'];
        }

        echo<<<data
          <div class="card p-3 shadow-sm rounded">
           <img src="$room_thumb" class="img-fluid rounded mb-3">
           <h5>$room_data[name]</h5>
           <h6>₱$room_data[price]/night</h6>

          </div>
        data;
      ?>
    </div>
    
    <div class="col-lg-5 col-md-12 px-4">
      <div class="card mb-4 border-0 shadow-sm rounded-3">
        <div class="card-body">
          <form action="#" id="booking_form">
            <h6 class="mb-3">BOOKING DETAILS</h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Name</label>
                  <input name="name" type="text" value="<?php echo $user_data['name'] ?>" class="form-control shadow-none" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Phone Number</label>
                  <input name="phonenum" type="number" value="<?php echo $user_data['phonenum'] ?>" class="form-control shadow-none" required>
                </div>
                <div class="col-md-12 mb-3">
                  <label class="form-label">Address</label>
                  <textarea name="address" class="form-control shadow-none" rows="1" required><?php echo $user_data['address'] ?></textarea>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Check-in</label>
                  <input name="checkin" onchange="check_availability()" type="date" class="form-control shadow-none" required>
                </div>
                <div class="col-md-6 mb-4">
                  <label class="form-label">Check-out</label>
                  <input name="checkout" onchange="check_availability()" type="date" class="form-control shadow-none" required>
                </div>
                <div class="col-12">
                  <div class="spinner-border text-info mb-3 d-none" id="info_loader" role="status">
                    <span class="visually-hidden">Loading...</span>
                  </div>

                  <h6 class="mb-3 text-danger" id="pay_info">Provide Check-in and Check-out Date!</h6>
                  
                  <button name="pay_now" class="btn w-100 text-dark btn-outline-dark custom-bg shadow-none mb-1" disabled>Pay Now</button>
                </div>
                
            </div>
          </form>
        </div>
    </div>
    
    </div>
  </div>
  </div>
    
<!--
      <div class="card mb-4 border-0 shadow">
        <div class="row g-0 p-3 align-items-center">
          <div class="col-md-5 mb-lg-0 mb-mb-0 mb-3">
          <img src="webimages/rooms/StandardRoom.png" class="img-fluid rounded">
          </div>
          <div class="col-md-5 px-lg-3 px-md-3 px-0">
            <h5 class="mb-3">Standard Room</h5>
              <div class="features mb-3">
                      <h6 class="mb-1">Features</h6>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          1 Room
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          1 Single Bed
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          1 Bathroom
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          1 Sofa
                      </span>
              </div>
              <div class="facilities mb-3">
                    <h6 class="mb-1">Facilities</h6>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        Wifi
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        Television
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        Air conditioner
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                       1 Room Heater
                    </span>
              </div>
              <div class="guests">
                <h6 class="mb-1">Guests</h6>
                <span class="badge rounded-pill bg-light text-dark text-wrap">
                  1 Adult
                </span>
                <span class="badge rounded-pill bg-light text-dark text-wrap">
                  0 Children
                </span>
                
              </div>
          </div>
          <div class="col-md-2 text-center">
            <h6 class="mb-4">₱2,999.00/night</h6>
            <a href="#" class="btn btn-sm w-100 text-black btn-outline-dark  book-now-btn shadow-none mb-2">Book now</a>
            <a href="#" class="btn btn-sm w-100 text-black btn-outline-dark book-now-btn shadow-none">Show more</a>
          </div>
          </div>
      </div>
      <div class="card mb-4 border-0 shadow">
        <div class="row g-0 p-3 align-items-center">
          <div class="col-md-5 mb-lg-0 mb-mb-0 mb-3">
          <img src="webimages/rooms/DeluxeRoom.png" class="img-fluid rounded">
          </div>
          <div class="col-md-5 px-lg-3 px-md-3 px-0">
            <h5 class="mb-3">Deluxe Room</h5>
              <div class="features mb-3">
                      <h6 class="mb-1">Features</h6>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          1 Room
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          1 Twin Bed
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          1 Bathroom
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          1 Sofa
                      </span>
              </div>
              <div class="facilities mb-3">
                    <h6 class="mb-1">Facilities</h6>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        Wifi
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        Television
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        Air conditioner
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                      1 Room Heater
                    </span>
              </div>
              <div class="guests">
                <h6 class="mb-1">Guests</h6>
                <span class="badge rounded-pill bg-light text-dark text-wrap">
                  2 Adults
                </span>
                <span class="badge rounded-pill bg-light text-dark text-wrap">
                  1 Children
                </span>
                
              </div>
          </div>
          <div class="col-md-2 mt-lg-0 mt-md-0 mt-4 text-center">
            <h6 class="mb-4">₱3,599.00/night</h6>
            <a href="#" class="btn btn-sm w-100 text-black btn-outline-dark  book-now-btn shadow-none mb-2">Book now</a>
            <a href="#" class="btn btn-sm w-100 text-black btn-outline-dark book-now-btn shadow-none">Show more</a>
          </div>
          </div>
      </div>
      <div class="card mb-4 border-0 shadow">
        <div class="row g-0 p-3 align-items-center">
          <div class="col-md-5 mb-lg-0 mb-mb-0 mb-3">
          <img src="webimages/rooms/SuiteRoom.png" class="img-fluid rounded">
          </div>
          <div class="col-md-5 px-lg-3 px-md-3 px-0">
            <h5 class="mb-3">Suite Room</h5>
              <div class="features mb-3">
                      <h6 class="mb-1">Features</h6>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          1 Room
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          1 Double Bed
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          1 Bathroom
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          1 Dining Area
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          2 Sofa
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          1 Jacuzzi
                      </span>
              </div>
              <div class="facilities mb-3">
                    <h6 class="mb-1">Facilities</h6>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        Wifi
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        Smart Television
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        Air conditioner
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        Heater
                    </span>
              </div>
              <div class="guests">
                <h6 class="mb-1">Guests</h6>
                <span class="badge rounded-pill bg-light text-dark text-wrap">
                  2 Adults
                </span>
                <span class="badge rounded-pill bg-light text-dark text-wrap">
                  3 Children
                </span>
                
              </div>
          </div>
          <div class="col-md-2 mt-lg-0 mt-md-0 mt-4 text-center">
            <h6 class="mb-4">₱3,999.00/night</h6>
            <a href="#" class="btn btn-sm w-100 text-black btn-outline-dark  book-now-btn shadow-none mb-2">Book now</a>
            <a href="#" class="btn btn-sm w-100 text-black btn-outline-dark book-now-btn shadow-none">Show more</a>
          </div>
          </div>
      </div>
      <div class="card mb-4 border-0 shadow">
        <div class="row g-0 p-3 align-items-center">
          <div class="col-md-5 mb-lg-0 mb-mb-0 mb-3">
          <img src="webimages/rooms/FamilyRoom.png" class="img-fluid rounded">
          </div>
          <div class="col-md-5 px-lg-3 px-md-3 px-0">
            <h5 class="mb-3">Family Room</h5>
              <div class="features mb-3">
                      <h6 class="mb-1">Features</h6>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          2 Room
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          4 Double Bed
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          2 Bathroom
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          1 Kitchenette
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          3 Sofa
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          1 Jacuzzi
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          1 Dining Area
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          1 Balcony
                      </span>
              </div>
              <div class="facilities mb-3">
                    <h6 class="mb-1">Facilities</h6>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        Wifi
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        Smart Television
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        Air conditioner
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                      2 Room Heater
                    </span>
              </div>
              <div class="guests">
                <h6 class="mb-1">Guests</h6>
                <span class="badge rounded-pill bg-light text-dark text-wrap">
                  5 Adults
                </span>
                <span class="badge rounded-pill bg-light text-dark text-wrap">
                  4 Children
                </span>
                
              </div>
          </div>
          <div class="col-md-2 mt-lg-0 mt-md-0 mt-4 text-center">
            <h6 class="mb-4">₱3,599.00/night</h6>
            <a href="#" class="btn btn-sm w-100 text-black btn-outline-dark  book-now-btn shadow-none mb-2">Book now</a>
            <a href="#" class="btn btn-sm w-100 text-black btn-outline-dark book-now-btn shadow-none">Show more</a>
          </div>
          </div>
      </div>
      <div class="card mb-4 border-0 shadow">
        <div class="row g-0 p-3 align-items-center">
          <div class="col-md-5 mb-lg-0 mb-mb-0 mb-3">
          <img src="webimages/rooms/ConnectingRoom.png" class="img-fluid rounded">
          </div>
          <div class="col-md-5 px-lg-3 px-md-3 px-0">
            <h5 class="mb-3">Connecting Room</h5>
              <div class="features mb-3">
                      <h6 class="mb-1">Features</h6>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          2 Room
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          2 Double Bed
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          2 Bathroom
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          1 Kitchenette
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          2 Sofa
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          1 Dining Area
                      </span>
              </div>
              <div class="facilities mb-3">
                    <h6 class="mb-1">Facilities</h6>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        Wifi
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                         2 Smart Television
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        2 Air conditioner
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        2 Room Heater
                    </span>
              </div>
              <div class="guests">
                <h6 class="mb-1">Guests</h6>
                <span class="badge rounded-pill bg-light text-dark text-wrap">
                  4 Adults
                </span>
                <span class="badge rounded-pill bg-light text-dark text-wrap">
                  4 Children
                </span>
                
              </div>
          </div>
          <div class="col-md-2 mt-lg-0 mt-md-0 mt-4 text-center">
            <h6 class="mb-4">₱4,999.00/night</h6>
            <a href="#" class="btn btn-sm w-100 text-black btn-outline-dark  book-now-btn shadow-none mb-2">Book now</a>
            <a href="#" class="btn btn-sm w-100 text-black btn-outline-dark book-now-btn shadow-none">Show more</a>
          </div>
          </div>
      </div>
      <div class="card mb-4 border-0 shadow">
        <div class="row g-0 p-3 align-items-center">
          <div class="col-md-5 mb-lg-0 mb-mb-0 mb-3">
          <img src="webimages/rooms/BusinessClassRoom.png" class="img-fluid rounded">
          </div>
          <div class="col-md-5 px-lg-3 px-md-3 px-0">
            <h5 class="mb-3">Business Room</h5>
              <div class="features mb-3">
                      <h6 class="mb-1">Features</h6>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          3 Rooms
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          2 Queen Bed
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          2 Bathroom
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          1 Meeting room
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          2 Sofa 1 Desk 2 Chairs
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          1 Dining Area
                      </span>
              </div>
              <div class="facilities mb-3">
                    <h6 class="mb-1">Facilities</h6>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        Wifi
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        Smart Television
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                         2 Printer
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        2 Air conditioner
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                       2 Room Heater
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        1 Telephone
                    </span>
              </div>
              <div class="guests">
                <h6 class="mb-1">Guests</h6>
                <span class="badge rounded-pill bg-light text-dark text-wrap">
                  3 Adults
                </span>
                <span class="badge rounded-pill bg-light text-dark text-wrap">
                  1 Children
                </span>
                
              </div>
          </div>
          <div class="col-md-2 mt-lg-0 mt-md-0 mt-4 text-center">
            <h6 class="mb-4">₱5,999.00/night</h6>
            <a href="#" class="btn btn-sm w-100 text-black btn-outline-dark  book-now-btn shadow-none mb-2">Book now</a>
            <a href="#" class="btn btn-sm w-100 text-black btn-outline-dark book-now-btn shadow-none">Show more</a>
          </div>
          </div>
      </div>
      <div class="card mb-4 border-0 shadow">
        <div class="row g-0 p-3 align-items-center">
          <div class="col-md-5 mb-lg-0 mb-mb-0 mb-3">
          <img src="webimages/rooms/HoneymoonSuite.png" class="img-fluid rounded">
          </div>
          <div class="col-md-5 px-lg-3 px-md-3 px-0">
            <h5 class="mb-3">Honeymoon Suite</h5>
              <div class="features mb-3">
                      <h6 class="mb-1">Features</h6>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          1 Room
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          1 Canopy Bed
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          1 Bathroom
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          1 Sofa
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          1 Dining Area
                      </span>
              </div>
              <div class="facilities mb-3">
                    <h6 class="mb-1">Facilities</h6>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        Wifi
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        1 Air conditioner
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        1 Room Heater
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        1 Telephone
                    </span>
              </div>
              <div class="guests">
                <h6 class="mb-1">Guests</h6>
                <span class="badge rounded-pill bg-light text-dark text-wrap">
                  2 Adults
                </span>
                <span class="badge rounded-pill bg-light text-dark text-wrap">
                  0 Children
                </span>
              </div>
          </div>
          <div class="col-md-2 mt-lg-0 mt-md-0 mt-4 text-center">
            <h6 class="mb-4">₱6,969.00/night</h6>
            <a href="#" class="btn btn-sm w-100 text-black btn-outline-dark  book-now-btn shadow-none mb-2">Book now</a>
            <a href="#" class="btn btn-sm w-100 text-black btn-outline-dark book-now-btn shadow-none">Show more</a>
          </div>
          </div>
      </div>
      <div class="card mb-4 border-0 shadow">
        <div class="row g-0 p-3 align-items-center">
          <div class="col-md-5 mb-lg-0 mb-mb-0 mb-3">
          <img src="webimages/rooms/PenthouseRoom.png" class="img-fluid rounded">
          </div>
          <div class="col-md-5 px-lg-3 px-md-3 px-0">
            <h5 class="mb-3">Penthouse</h5>
              <div class="features mb-3">
                      <h6 class="mb-1">Features</h6>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          4 Room
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          3 Twin Bed
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          2 King Bed
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          3 Bathroom
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          3 Sofa
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          2 Dining Area
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                          Balcony
                      </span>
              </div>
              <div class="facilities mb-3">
                    <h6 class="mb-1">Facilities</h6>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        Wifi
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        3 Air conditioner
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                        3 Room Heater
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                       2  Telephone
                    </span>
              </div>
              <div class="guests">
                <h6 class="mb-1">Guests</h6>
                <span class="badge rounded-pill bg-light text-dark text-wrap">
                  6 Adults
                </span>
                <span class="badge rounded-pill bg-light text-dark text-wrap">
                  6 Children
                </span>
              </div>
          </div>
          <div class="col-md-2 mt-lg-0 mt-md-0 mt-4 text-center">
            <h6 class="mb-4">₱6,999.00/night</h6>
            <a href="#" class="btn btn-sm w-100 text-black btn-outline-dark  book-now-btn shadow-none mb-2">Book now</a>
            <a href="#" class="btn btn-sm w-100 text-black btn-outline-dark book-now-btn shadow-none">Show more</a>
          </div>
          </div>
      </div>
-->  
    
<!-- footer -->
<?php require('inc/footer.php')?>
<script>
  let booking_form = document.getElementById('booking_form');
  let info_loader = document.getElementById('info_loader');
  let pay_info = document.getElementById('pay_info');

  function check_availability(){
    let checkin_val = booking_form.elements['checkin'].value;
    let checkout_val = booking_form.elements['checkout'].value;

    booking_form.elements['pay_now'].setAttribute('disable',true);

    if(checkin_val!='' && checkout_val!=''){

      pay_info.classList.add('d-none');
      pay_info.classList.replace('text-dark','text-danger');
      info_loader.classList.remove('d-none');

      let data = new FormData();

      data.append('check_availability','');
      data.append('check_in',checkin_val);
      data.append('check_out',checkout_val);

      let xhr = new XMLHttpRequest();
      xhr.open("POST","ajax/confirm_booking.php",true);

      xhr.onload = function(){
          let data = JSON.parse(this.responseText);

          if(data.status == 'check_in_out_equal'){
            pay_info.innerText = "You cannot check-out on the same day!";
          }else if(data.status == 'check_out_earlier'){
            pay_info.innerText = "Check-out date is earlier than Check-in date!";
          }else if(data.status == 'check_in_earlier'){
            pay_info.innerText = "Check-in date is earlier than today's date!";
          }else if(data.status == 'unavailable'){
            pay_info.innerText = "Room not available in this Check-in date!";
          }else {
            pay_info.innerHTML = "No. of Days: "+data.days+"<br>Total Amount to Pay: ₱"+data.payment;
            pay_info.classList.replace('text-danger','text-dark');
            booking_form.elements['pay_now'].removeAttribute('disabled');
          }

          pay_info.classList.remove('d-none');
          info_loader.classList.add('d-none');
      }
      xhr.send(data);
    }
}


</script>

<!--JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>