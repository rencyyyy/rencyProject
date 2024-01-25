<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
    <?php require('inc/links.php'); ?>
    <title>Room details - <?php echo $settings_r['site_title'] ?></title>
</head>
<body>
<!--header-->
<?php require('inc/header.php')?>

<?php 
  if(!isset($_GET['id'])){
    redirect('rooms.php');
  }

  $data = filteration($_GET);
  $room_res = select("SELECT * FROM `rooms` WHERE `id`=? AND `status`=? AND `removed`=?",[$data['id'],1,0],'iii');

  if(mysqli_num_rows($room_res)==0){
    redirect('rooms.php');
  }

  $room_data = mysqli_fetch_assoc($room_res);

?>

  <div class="container">
    <div class="row">

      <div class="col-12 my-5 mb-4 px-4">
        <h2 class="fw-bold"><?php echo $room_data['name'] ?></h2>
        <div style="font-size: 14px;">
          <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
          <span> > </span>
          <a href="rooms.php" class="text-secondary text-decoration-none">ROOMS</a>
        </div>
      </div>

    <div class="col-lg-7 col-md-12 px-4">
      <div id="roomCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
          <?php 
                $room_img = ROOMS_IMG_PATH."thumbnail.jpg";
                $img_q = mysqli_query($con, "SELECT * FROM `room_images` 
                  WHERE `room_id`='$room_data[id]'");

                if(mysqli_num_rows($img_q)>0){
                  $active_class = 'active';

                  while($img_res = mysqli_fetch_assoc($img_q)){
                      echo"
                      <div class='carousel-item $active_class'>
                        <img src='".ROOMS_IMG_PATH.$img_res['image']."' class='d-block w-100 rounded'>
                      </div>
                    ";
                    $active_class='';
                  }                                    
                }else {
                  echo "<div class='carousel-item active'>
                    <img src='$room_img' class='d-block w-100'>
                  </div>";
                }

          ?>        
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#roomCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#roomCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </div>
    
    <div class="col-lg-5 col-md-12 px-4">
      <div class="card mb-4 border-0 shadow-sm rounded-3">
        <div class="card-body">
          <?php
          
            echo<<<price
              <h4>₱$room_data[price]/night</h4>
            price;

            echo<<<rating
              <div class="mb-3">
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-half text-warning"></i>
              </div>
            rating;

            $fea_q = mysqli_query($con,"SELECT f.name FROM `features`f 
             INNER JOIN `room_features`rfea ON f.id = rfea.features_id 
             WHERE rfea.room_id = '$room_data[id]'");

            $features_data = "";
            while($fea_row = mysqli_fetch_assoc($fea_q)){
            $features_data .="<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
               $fea_row[name]
            </span>";
            }

            echo<<<features
              <div class="mb-3">
                <h6 class="mb-1">Features</h6>
                $features_data
              </div>
            features;

            $fac_q = mysqli_query($con,"SELECT f.name FROM `facilities` f 
              INNER JOIN `room_facilities` rfac ON f.id = rfac.facilities_id 
              WHERE rfac.room_id = '$room_data[id]'");
            
            $facilities_data = "";
            while($fac_row = mysqli_fetch_assoc($fac_q)){
            $facilities_data .="<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
               $fac_row[name] 
            </span>";
            }

            echo<<<facilities
              <div class="mb-3">
                <h6 class="mb-1">Facilities</h6>
                $facilities_data
              </div>
            facilities;

            echo<<<guests
                <div class="mb-3">
                  <h6 class="mb-1">Guests</h6>
                  <span class="badge rounded-pill bg-light text-dark text-wrap">
                    $room_data[adult] Adult
                  </span>
                  <span class="badge rounded-pill bg-light text-dark text-wrap">
                    $room_data[children] Children
                  </span>      
                </div>
            guests;

            echo<<<area
              <div class="mb-3">
                <h6 class="mb-1">Area</h6>
                <span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                  $room_data[area] sq. ft.
                </span> 
              </div>
            area;

            // $book_btn = "";

            if(!$settings_r['shutdown']){
              $login=0;
              if(isset($_SESSION['login']) && $_SESSION['login']==true){
                $login=1;
              }
              echo<<<book
                <button onclick='checkLoginToBook($login,$room_data[id])' class="btn w-100 text-black btn-outline-dark  book-now-btn shadow-none mb-1">Book now</button>
              book;
            }

            

          ?>
        </div>
    </div>        
    
    <div class="col-12 mt-4 px-4">
      <div class="mb-5">
        <h5>Description</h5>
        <p>
          <?php echo $room_data['description'] ?>
        </p>
      </div>

      <div>
        <h5 class="mb-3">Reviews & Ratings</h5>
        <div>
          <div class="d-flex align-items-center mb-2">
                <img src="webimages/testimonialimages/Rency.jpg" width="30px">
                <h6 class="m-0 ms-2">Rency C. Delos Santos</h6>
              </div>
              <p>
                Renpauco was a great place to stay for business. Quiet, comfortable rooms, efficient staff, and a convenient location make it ideal for work travelers.
              </p>
              <div class="rating">
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
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
    
  </div>
  </div>
  </div>
<!-- footer -->
<?php require('inc/footer.php')?>

<!--JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>