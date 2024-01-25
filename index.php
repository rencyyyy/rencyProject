<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
    <?php require('inc/links.php'); ?>
    <title>Home - <?php echo $settings_r['site_title'] ?></title>
</head>
<body class="bg-light">
<?php require('inc/header.php')?>

<!--CAROUSEL -->
<div class="container-fluid px-lg-4 mt-4">
  <div class="swiper swiper-container">
    <div class="swiper-wrapper">
      <?php 
        $res = selectAll('carousel');
        while($row = mysqli_fetch_assoc($res)){
          $path = CAROUSEL_IMG_PATH;
          echo <<<data
              <div class="swiper-slide">
                <img src="$path$row[image]" class="w-100 d-block"/>
              </div>
          data;
        }
      ?>
    </div>
  </div>
</div>

<!-- AVAILABILITY FORM -->

<div class="container availability-form">
    <div class="row">
        <div class="col-lg-12 bg-white shadow p-4 rounded">
            <h5 class="mb-4">Check Availability</h5>
            <form>
                <div class="row align-items-end">
                    <div class="col-lg-3 mb-3">
                        <label class="form-label" style="font-weight: 500;">Check-in</label>
                        <input type="date" class="form-control shadow-none">
                    </div>
                    <div class="col-lg-3 mb-3">
                        <label class="form-label" style="font-weight: 500;">Check-out</label>
                        <input type="date" class="form-control shadow-none">
                    </div>
                    <div class="col-lg-3 mb-3">
                        <label class="form-label" style="font-weight: 500;">Adult</label>
                            <select class="form-select shadow-none">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="3">4</option>
                                    <option value="3">5</option>
                            </select>
                    </div>
                    <div class="col-lg-2 mb-3">
                        <label class="form-label" style="font-weight: 500;">Children</label>
                            <select class="form-select shadow-none">
                                    <option value="1">0</option>
                                    <option value="2">1</option>
                                    <option value="3">2</option>
                                    <option value="3">3</option>
                                    <option value="3">4</option>
                                    <option value="3">5</option>
                            </select>
                    </div>
                    <div class="col-lg-1 mb-lg-3 mt-2">
                        <button type="submit" class="btn btn-sm btn-outline-dark shadow-none">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- OUR ROOMS -->
<h2 class="mt-5 p-t-4 mb-4 text-center fw-bold h-font">OUR ROOMS</h2>
<div class="container">
    <div class="row">

    <?php
      $room_res = select("SELECT * FROM `rooms` WHERE `status`=? AND `removed`=? ORDER BY `id` DESC LIMIT 4",[1,0],'ii');

      while($room_data = mysqli_fetch_assoc($room_res)){

        //GET FEATURES OF ROOM

        $fea_q = mysqli_query($con,"SELECT f.name FROM `features`f 
          INNER JOIN `room_features`rfea ON f.id = rfea.features_id 
          WHERE rfea.room_id = '$room_data[id]'");

        $features_data = "";
        while($fea_row = mysqli_fetch_assoc($fea_q)){
          $features_data .=" <span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
            $fea_row[name]
          </span>";
        }

        //GET FACILITIES OF ROOM

        $fac_q = mysqli_query($con,"SELECT f.name FROM `facilities` f 
          INNER JOIN `room_facilities` rfac ON f.id = rfac.facilities_id 
          WHERE rfac.room_id = '$room_data[id]'");

        $facilities_data = "";
        while($fac_row = mysqli_fetch_assoc($fac_q)){
          $facilities_data .=" <span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
            $fac_row[name]
          </span>";
        }

        //GET THUMBNAIL OF IMAGES

        $room_thumb = ROOMS_IMG_PATH."thumbnail.jpg";
        $thumb_q = mysqli_query($con, "SELECT * FROM `room_images` 
          WHERE `room_id`='$room_data[id]' 
          AND `thumb`='1'");

        if(mysqli_num_rows($thumb_q)>0){
          $thumb_res = mysqli_fetch_assoc($thumb_q);
          $room_thumb = ROOMS_IMG_PATH.$thumb_res['image'];
        }

        $book_btn = "";

        if(!$settings_r['shutdown']){
          $login=0;
          if(isset($_SESSION['login']) && $_SESSION['login']==true){
            $login=1;
          }

          $book_btn = "<button onclick='checkLoginToBook($login,$room_data[id])' class='btn btn-sm btn-outline-dark shadow-none'>Book now</button>";
        }

        //PRINT ROOM CARD

        echo <<<data
          <div class="col-lg-3 col-md-6 mb-3">
              <div class="card border-0 shadow" style="max-width: 350px; margin: auto;">
                  <img src="$room_thumb" class="card-img-top" alt="Standard Room">
                  <div class="card-body">
                    <h5>$room_data[name]</h5>
                    <h6 class="mb-4">₱$room_data[price]/night</h6>
                    <div class="features mb-4">
                      <h6 class="mb-1">Features</h6>
                      $features_data
                    </div>
                    <div class="facilities mb-4">
                      <h6 class="mb-1">Facilities</h6>
                      $facilities_data
                    </div>
                    <div class="guests mb-4">
                      <h6 class="mb-1">Guests</h6>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                        $room_data[adult] Adult
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                        $room_data[children] Children
                      </span>
                    </div>
                    <div class="ratings mb-4">
                      <h6 class="mb-1">Ratings</h6>
                      <span class="badge rounded-pill bg-light">
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-half text-warning"></i>
                      </span>
                    </div>
                    <div class="d-flex justify-content-evenly mb-2">
                      $book_btn
                      <a href="room_details.php?id=$room_data[id]" class="btn btn-sm btn-outline-dark shadow-none">More details</a>    
                    </div>
                    </div>   
                  </div>
          </div>
        data;
      }
    ?>
  </div>
        <div class="col-lg-12 text-center mt-5">
            <a href="rooms.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More Rooms >>></a>
        </div>
    </div>
</div>

<!-- OUR SERVICES-->
<h2 class="mt-5 p-t-4 mb-4 text-center fw-bold h-font">OUR SERVICES</h2>
<div class="container">
    <div class="row justify-content-evenly px-lg-0 px-md-0 px-5">
        <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
            <img src="webimages/icons/laundry.png" width="100px" alt="Swimming icon">
            <h5 class="mt-3">Laundry room</h5>
        </div>
        <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
            <img src="webimages/icons/swimming.png" width="169px" alt="Swimming icon">
            <h5 class="mt-3">Swimming pool</h5>
        </div>
        <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
            <img src="webimages/icons/foodndrinks.png" width="120px" alt="Swimming icon">
            <h5 class="mt-3">Restaurant</h5>
        </div>
        <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
            <img src="webimages/icons/parking.png" width="100px" alt="Swimming icon">
            <h5 class="mt-3">Parking lot</h5>
        </div>
        <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
            <img src="webimages/icons/fitness.png" width="100px" alt="Swimming icon">
            <h5 class="mt-3">Fitness gym</h5>
        </div>
        <div class="col-lg-12 text-center mt-5">
            <a href="facilities.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">Check Room Facilities >>></a>
        </div>
    </div>
</div>

<!--TESTIMONIALS-->
<h2 class="mt-5 p-t-4 mb-4 text-center fw-bold h-font">TESTIMONIALS</h2>
<div class="container mt-5">
    <div class="swiper swiper-testimonials">
        <div class="swiper-wrapper mb-5">

          <div class="swiper-slide bg-white p-4">
            <div class="profile d-flex align-items-center p-4">
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
          <!---->
          <div class="swiper-slide bg-white p-4">
            <div class="profile d-flex align-items-center p-4">
              <img src="webimages/testimonialimages/JustineB.jpg" width="30px">
              <h6 class="m-0 ms-2">Justine Basibas</h6>
            </div>
            <p>
              Hotel Renpauco was a charming and unique stay with stylish, modern rooms and attentive staff. The quiet neighborhood provides a peaceful retreat.
            </p>
            <div class="rating">
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-half text-warning"></i>
            </div>
          </div>
          <!---->
          <div class="swiper-slide bg-white p-4">
            <div class="profile d-flex align-items-center p-4">
              <img src="webimages/testimonialimages/Jimver.jpg" width="30px">
              <h6 class="m-0 ms-2">Jimver Pol Dimaano</h6>
            </div>
            <p>
              An excellent choice for a families. Spacious rooms, friendly staff, and kid-friendly amenities make it a fun and relaxing vacation spot.
            </p>
            <div class="rating">
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
            </div>
          </div>
          <!---->
          <div class="swiper-slide bg-white p-4">
            <div class="profile d-flex align-items-center p-4">
              <img src="webimages/testimonialimages/Charls.jpg" width="30px">
              <h6 class="m-0 ms-2">Charls Pakingking</h6>
            </div>
            <p>
              A luxurious experience with exceptional staff, spacious rooms, and top-notch amenities. The pool and spa were particularly enjoyable. 
            </p>
            <div class="rating">
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
            </div>
          </div>
          <!---->
          <div class="swiper-slide bg-white p-4">
            <div class="profile d-flex align-items-center p-4">
              <img src="webimages/testimonialimages/Tims.jpg" width="30px">
              <h6 class="m-0 ms-2">Al Timothy Villaruel</h6>
            </div>
            <p>
              Clean rooms, friendly staff, and limited amenities. Environmental friendly!
            </p>
            <div class="rating">
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-half text-warning"></i>
              <i class="bi bi-star-half text-warning"></i>
            </div>
          </div>
          <!---->

        </div>
      <div class="swiper-pagination"></div>
  </div>
  <div class="col-lg-12 text-center mt-5">
        <a href="about.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">Know More >>></a>
  </div>
</div>

<!--REACH US-->

<h2 class="mt-5 p-t-4 mb-4 text-center fw-bold h-font">REACH US</h2>
<div class="container">
  <div class="row">
    <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white rounded">
      <iframe class="w-100 rounded" height="420" src="<?php echo $contact_r['iframe'] ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    <div class="col-lg-4 col-md-4">
      <div class="bg-white p-4 rounded mb-4 ">
        <h5>Contact us</h5>
        <a href="tel: +<?php echo $contact_r['pn1'] ?>" class="d-inline-block mb-2 text-decoration-none text-dark">
          <i class="bi bi-telephone-fill"></i> +<?php echo $contact_r['pn1'] ?>
        </a>
        <br>
        <?php
          if($contact_r['pn2']!=''){
            echo<<<data
              <a href="tel: +$contact_r[pn2]" class="d-inline-block mb-2 text-decoration-none text-dark">
                <i class="bi bi-telephone-fill"></i> +$contact_r[pn2]
              </a>
            data;
          }
        
        ?>
        
        <br>
        <a href="mailto:<?php echo $contact_r['email']?>" class="d-inline-block text-decoration-none text-dark">
        <i class="bi bi-envelope-paper-heart-fill"></i> <?php echo $contact_r['email']?>
        </a>
      </div>
      <!---->
      <div class="bg-white p-4 rounded mb-4">
        <h5>Follow us</h5>
        <?php 
          if($contact_r['tw']!=''){
            echo<<<data
              <a href="$contact_r[tw]" class="d-inline-block mb-3">
                <span class="badge bg-light text-dark fs-6 p-2">
                  <i class="bi bi-twitter-x me-1"></i> Twitter-X
                </span>
              </a>
              <br>
            data;
          }
        
        ?>
        <a href="<?php echo $contact_r['insta'] ?>" class="d-inline-block mb-3">
          <span class="badge bg-light text-dark fs-6 p-2">
            <i class="bi bi-instagram me-1"></i> Instagram
          </span>
        </a>
        <br>
        <a href="<?php echo $contact_r['fb'] ?>" class="d-inline-block mb-3">
          <span class="badge bg-light text-dark fs-6 p-2">
            <i class="bi bi-facebook me-1"></i> Facebook
          </span>
        </a>
        <br>
        <a href="<?php echo $contact_r['li'] ?>" class="d-inline-block mb-3">
          <span class="badge bg-light text-dark fs-6 p-2">
            <i class="bi bi-linkedin me-1"></i> Linkedin
          </span>
        </a>
      </div>
      <!---->
    </div>
  </div>
</div>

<!--FOOTER-->

<!-- PASSWORD RESET MODAL AND CODE -->
  <div class="modal fade" id="recoveryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
          <form id="recovery-form">
              <div class="modal-header">
                  <h5 class="modal-title d-flex align-items-center">
                  <i class="bi bi-shield-lock fs-3 me-2"></i> Set new Password
                  </h5>

              </div>
              <div class="modal-body">      
                      <div class="mb-4">
                          <label class="form-label">New Password</label>
                          <input type="password" name="pass" required class="form-control shadow-none">
                          <input type="hidden" name="email">
                          <input type="hidden" name="token">
                      </div>
                  <div class="mb-2 text-end">
                      <button type="button" class="btn shadow-none me-2" data-bs-dismiss="modal"> CANCEL</button>
                      <button type="submit" class="btn btn-dark shadow-none">SUBMIT</button>
                  </div>
              </div>
          </form>
      </div>
    </div>
  </div>

<?php require('inc/footer.php')?>

<?php

  if(isset($_GET['account_recovery'])){
    $data = filteration($_GET);

    $t_date = date("Y-m-d");

    $query = select("SELECT * FROM `user_cred` WHERE `email`=? AND `token`=? AND `t_expire`=? LIMIT 1",[$data['email'],$data['token'],$t_date],'sss');

    if(mysqli_num_rows($query)==1){
      echo<<<showModal
        <script>
          var myModal = document.getElementById('recoveryModal');

          myModal.querySelector("input[name='email']").value = '$data[email]';
          myModal.querySelector("input[name='token']").value = '$data[token]';

          var modal = bootstrap.Modal.getOrCreateInstance(myModal);
          modal.show();
        </script>
      showModal;
    }else {
      alert("error","Invalid or Expired Link!");
    }

  }

?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
    <script>
      var swiper = new Swiper(".swiper-container", {
      spaceBetween: 30,
      effect: "fade",
      loop: true,
      autoplay: {
          delay: 3500,
          disableOnInteraction: false,
      }
      });

      var swiper = new Swiper(".swiper-testimonials", {
        effect: "coverflow",
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: "auto",
        slidesPerView: "3",
        loop: true,
        coverflowEffect: {
          rotate: 50,
          stretch: 0,
          depth: 100,
          modifier: 1,
          slideShadows: false,
        },
        pagination: {
          el: ".swiper-pagination",
        },
        breakpoints: {
          320: {
            slidesPerView: 1,
          },
          640: {
            slidesPerView: 1,
          },
          768: {
            slidesPerView: 2,
          },
          1024: {
            slidesPerView: 3,
          },
        }
      });

      var swiper = new Swiper(".m-team", {
        spaceBetween: 40,
        loop: true,
        pagination: {
          el: ".swiper-pagination",
        },
        breakpoints: {
          320: {
            slidesPerView: 1,
          },
          640: {
            slidesPerView: 1,
          },
          768: {
            slidesPerView: 3,
          },
          1024: {
            slidesPerView: 3,
          },
        }
      });

      // RECOVER ACCOUNT

      let recovery_form = document.getElementById('recovery-form');
        
        recovery_form.addEventListener('submit', (e)=>{
            e.preventDefault();

            let data = new FormData();

            data.append('email',recovery_form.elements['email'].value);
            data.append('token',recovery_form.elements['token'].value);
            data.append('pass',recovery_form.elements['pass'].value);
            data.append('recover_user','');
            
            var myModal = document.getElementById('recoveryModal');
            var modal = bootstrap.Modal.getInstance(myModal);
            modal.hide();

            let xhr = new XMLHttpRequest();
            xhr.open("POST","ajax/login_register.php",true);

            xhr.onload = function(){
                if(this.responseText == 'failed'){
                  alert('error', "Account reset failed!");
                }else {
                  alert('success', "Account Reset Successful!");
                  recovery_form.reset();
                }
            }
            
            xhr.send(data);
      });
    </script>

</body>
</html>