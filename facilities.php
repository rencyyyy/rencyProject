<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
    <?php require('inc/links.php'); ?>
    <title>Facilities - <?php echo $settings_r['site_title'] ?></title>
</head>
<body>
<!--header-->
<?php require('inc/header.php')?>
    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">OUR FACILITIES</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3"> Immerse yourself in our haven of relaxation and recreation. Take a dip in our sparkling pool, surrounded by lush greenery.<br> Unwind in our luxurious spa, offering a menu of pampering treatments. Stay active in our state-of-the-art fitness center.<br> Savor delicious cuisine at our on-site restaurant, showcasing the vibrant flavors of Porac.<br> Escape the ordinary and discover your perfect escape at Hotel Renpauco.</p>
    </div>
    <div class="container">
        <div class="row">
            <?php 
                $res = selectAll('facilities');
                $path = FACILITIES_IMG_PATH;

                while($row = mysqli_fetch_assoc($res)){
                    echo<<<data
                        <div class="col-lg-4 col-md-6 mb-5 px-4">
                            <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
                                <div class="d-flex align-items-center mb-4">
                                    <img src="$path$row[icon]" width="40px" alt="icon">
                                    <h5 class="m-0 ms-3">$row[name]</h5>
                                </div>
                                <p>$row[description]</p>
                            </div>
                        </div>
                    data;
                }
            ?>
<!--      
            <div class="col-lg-4 col-md-6 mb-5 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
                    <div class="d-flex align-items-center mb-4">
                        <img src="webimages/icons/laundry.png" width="40px" alt="Swimming icon">
                        <h5 class="m-0 ms-3">Laundry</h5>
                    </div>
                    <p>Step into our laundry haven! Soft blue walls and fluffy towels create a spa-like atmosphere. Sunlight streams through the window, warming the tiled floor as I fold clean clothes to the rhythm of my favorite playlists. It's the perfect place to unwind after a busy day.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-5 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
                    <div class="d-flex align-items-center mb-4">
                        <img src="webimages/icons/swimming.png" width="68px" alt="Swimming icon">
                        <h5 class="m-0 ms-3">Swimming pool</h5>
                    </div>
                    <p>Embark on a romantic rendezvous under the twinkling stars. Candlelight dances on the water's surface, creating a mesmerizing ambiance as you and your loved one share a secluded dip. This is intimacy redefined, amidst the gentle embrace of your private sanctuary.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-5 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
                    <div class="d-flex align-items-center mb-4">
                        <img src="webimages/icons/foodndrinks.png" width="47px" alt="Swimming icon">
                        <h5 class="m-0 ms-3">Restaurant</h5>
                    </div>
                    <p>Welcome to your new favorite hangout! Our friendly restaurant is the heart of the community, a place where laughter mingles with the aroma of freshly baked bread. We serve up homestyle comfort food and generous portions, all made with love.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-5 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
                    <div class="d-flex align-items-center mb-4">
                        <img src="webimages/icons/parking.png" width="40px" alt="Swimming icon">
                        <h5 class="m-0 ms-3">Parking lot</h5>
                    </div>
                    <p>Experience parking made smart at our hotel! Our state-of-the-art parking system guides you to available spaces with digital signage, eliminating the frustration of circling endlessly. And rest easy knowing your car is in a secure, monitored environment.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-5 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
                    <div class="d-flex align-items-center mb-4">
                        <img src="webimages/icons/fitness.png" width="40px" alt="Swimming icon">
                        <h5 class="m-0 ms-3">Fitness area</h5>
                    </div>
                    <p>Whether you're a seasoned athlete or a novice enthusiast, our expansive fitness playground has something for everyone. Conquer your goals in our spacious weight room, get your heart pumping in our cardio zone, or test your limits on our climbing wall.</p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-5 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
                    <div class="d-flex align-items-center mb-4">
                        <img src="webimages/icons/spa.png" width="40px" alt="Swimming icon">
                        <h5 class="m-0 ms-3">Spa</h5>
                    </div>
                    <p>We believe in the power of personalization. Our expert therapists will listen to your unique needs and create a customized experience that addresses your specific goals. Whether you seek deep relaxation, muscle tension relief, or skin rejuvenation, we have a treatment that's perfect for you.</p>
                </div>
            </div>
-->
        </div>
    </div> 
    <!--<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Obcaecati blanditiis repellat quos dolorum quaerat ipsa ipsam officiis voluptatum quasi in! Eveniet vero a amet placeat saepe aperiam quidem expedita repellendus?</p>-->

  
<!-- footer -->
<?php require('inc/footer.php')?>

<!--JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</body>
</html>