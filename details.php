
<?php
require_once 'db.php';

if (!isset($_GET['slug'])) {
    die("Post does not specified");
}

$slug = $_GET['slug'];

try {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE slug = ?");
    $stmt->execute([$slug]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$post) {
        die("Post not found");
    }
} catch(PDOException $e) {
    die("Error fetching post: " . $e->getMessage());
}


// Function to get the visitor's IP address
function getVisitorIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // In case of proxy, the client's IP may be passed in this header
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    return $_SERVER['REMOTE_ADDR'];
}

$visitor_ip = getVisitorIP();

// Record the visit in the page_views table
try {
    $post_id = $post['id'];
    $stmt = $pdo->prepare("INSERT INTO post_views (post_id, ip_address, visited_at) VALUES (?, ?, NOW())");
    $stmt->execute([$post_id, $visitor_ip]);
} catch(PDOException $e) {
    die("Error storing view: " . $e->getMessage());
    // Log error if desired, but don't halt page execution
}

// Query total number of views for this post
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM post_views WHERE post_id = ?");
$stmt->execute([$post_id]);
$total_views = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Query unique views (based on distinct IP addresses)
$stmt = $pdo->prepare("SELECT COUNT(DISTINCT ip_address) as unique_post FROM post_views WHERE post_id = ?");
$stmt->execute([$post_id]);
$unique_views = $stmt->fetch(PDO::FETCH_ASSOC)['unique_post'];


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="images/faviconss.png">
    <title><?= htmlspecialchars($post['title']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="Gistgroove" content="Discover what's trending in your area. Get real-time updates on news, entertainment, viral stories, and more. Share trends with the world!">    
    <meta name="author" content="Gistgroove">
    <meta name="keywords" content="trending news, viral updates, local trends, latest buzz, breaking news">
    <link rel="canonical" href="https://gistgroove.com"/>

    <meta property="og:title" content="Trending Now in Your Area - Latest Updates">
    <meta property="og:description" content="Find out what's trending near you and share viral news instantly!">
    <meta property="og:image" content="https://gistgroove.com/gistlogo.png">
    <meta property="og:url" content="https://gistgroove.com/details.php?slug=<?= htmlspecialchars($post['slug']) ?>">
    <meta property="og:type" content="website">

     <!-- Basic Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@gist_groove"> 
    <meta name="twitter:title" content="Trending Now in Your Area - Stay Updated">
    <meta name="twitter:description" content="Find out what's trending near you and share viral news instantly!">
    <meta name="twitter:image" content="https://gistgroove.com/gistlogo.png">
    <meta name="twitter:url" content="https://gistgroove.com/details.php?slug=<?= htmlspecialchars($post['slug']) ?>">
    <link rel="stylesheet" href="assets/css/style.css?v1.5.0">
   
   
    <script async="async" data-cfasync="false" src="//pl26337750.profitableratecpm.com/293606dd35843d6209f6545f1ca62d2c/invoke.js"></script>
    <div id="container-293606dd35843d6209f6545f1ca62d2c"></div>

    <script type='text/javascript' src='//pl26337784.profitableratecpm.com/0a/29/18/0a291848963d048c2570a7958ef472df.js'></script>


    <script type="text/javascript">
        atOptions = {
            'key' : 'd8673243f5c19b0a82c8d8e3e7e98c6e',
            'format' : 'iframe',
            'height' : 300,
            'width' : 160,
            'params' : {}
        };
    </script>
    <script type="text/javascript" src="//www.highperformanceformat.com/d8673243f5c19b0a82c8d8e3e7e98c6e/invoke.js"></script>


</head>

<body class="nk-body " data-menu-collapse="lg">
    <div class="nk-app-root ">
        <?php include 'header.php'; ?>
        <main class="nk-pages">
            <section class="section has-mask">
                <div class="nk-mask bg-pattern-dot bg-blend-around mt-n5 mb-10p mh-50vh"></div>
                <div class="container">
                    <div class="section-content">
                        <div class="row g-gs justify-content-center">
                            <div class="col-xxl-8 col-xl-9 col-lg-10">
                                <div class="text-center mb-5">
                                    <h6 class="overline-title text-primary"> <?= htmlspecialchars($post['category_name']) ?> </h6>
                                    <h1 class="title"><?= htmlspecialchars($post['title']) ?></h1>
                                    <ul class="list list-row gx-2">
                                        <li>
                                            <div class="sub-text fs-5"><?= htmlspecialchars($post['username']) ?></div>
                                        </li>
                                        <li><em class="icon mx-0 ni ni-dot"></em></li>
                                        <li>
                                            <div class="sub-text fs-5"><?= date('M j, Y', strtotime($post['created_at'])) ?></div>
                                        </li>
                                        <li><em class="icon mx-0 ni ni-dot"></em></li>
                                        <li>
                                            <div class="sub-text fs-5"><?= isset($total_views) ? htmlspecialchars($total_views) . ' Views' : '0 Views' ?></div>
                                        </li>
                                        
                                    </ul>
                                    <!-- <div class="my-5">
                                        <img class="rounded-4 w-100" src="images/blog/cover.jpg" alt="">
                                    </div> -->
                                </div>
                                <div class="d-flex">
                                    <div class="block-typo">
                                        <?php 
                                            $html = $post['body'];
                                            // Remove HTML tags and decode any HTML entities
                                            $plainText = strip_tags($html);
                                            $plainText = html_entity_decode($plainText);
                                            
                                            // Display formatted version (with line breaks for each paragraph)
                                            $formatted = nl2br(trim($plainText));
                                        ?>

                                        <?= $formatted ?>

                                   

                                        <!-- <div class="block-text pt-lg-4">

                                        
                                            <h5>Leanne</h5> 
                                            <p>Must explain to you how all this mistaken idea of denouncing pleasure and praising born and I will give you a complete account of the system.</p>

                                            <h5>John Mark</h5>
                                            <p>Must explain to you how all this mistaken idea of denouncing pleasure and praising born and I will give you a complete account of the system.</p>



                                            <form data-action="" method="post" class="form-submit-init">
                                                <div class="row g-4">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="text" name="user-name" class="form-control form-control-lg" placeholder="Your Name" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                  
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="email" name="user-email" class="form-control form-control-lg" placeholder="Your Email Address" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                   
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input type="text" name="user-subject" class="form-control form-control-lg" placeholder="Subject" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                  
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <textarea name="user-message" class="form-control form-control-lg" placeholder="Enter your message" required></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                  
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <button class="btn btn-primary" type="submit" id="submit-btn">Send Message</button>
                                                        </div>
                                                        <div class="form-result mt-4"></div>
                                                    </div>
                                                </div>
                                               
                                            </form>

                                        </div> -->
                                   </div>
                                    <ul class="btn-list gy-3 ps-xl-6 ps-lg-4 ps-3">
                                        <li><a class="link-secondary" href="https://www.facebook.com/sharer/sharer.php?u=https://gistgroove.com/?slug=<?= $post['slug'] ?>"><em class="icon fs-3 ni ni-facebook-circle"></em></a></li>
                                        <li><a class="link-secondary" href="https://twitter.com/intent/tweet?url=https://gistgroove.com/?slug=<?= $post['slug'] ?>"><em class="icon fs-3 ni ni-twitter"></em></a></li>
                                        <li><a class="link-secondary" href="https://www.linkedin.com/sharing/share-offsite/?url=https://gistgroove.com/?slug=<?= $post['slug'] ?>"><em class="icon fs-3 ni ni-linkedin-round"></em></a></li>
                                    </ul>

                                    
                                </div>


                            </div><!-- .col -->
                        </div><!-- .row -->
                        
                    </div><!-- .section-content -->

                        


                </div><!-- .container -->
            </section><!-- .section -->

        
           
            <!-- <section class="section section-sm section-0">
                <div class="container">
                    <div class="section-head">
                        <div class="row justify-content-center text-center">
                            <div class="col-lg-9 col-xl-8 col-xxl-6">
                                <h2 class="title">Similar Posts</h2>
                            </div>
                        </div>
                    </div>
                    
                    <div class="section-content">
                        <div class="swiper-init position-relative swiper-button-hide-disabled" data-breakpoints='{"0":{"slidesPerView":1}, "778": {"slidesPerView": 2},"1200":{"slidesPerView":3}}' data-space-between="32">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="card border-0 shadow-tiny rounded-4">
                                        <div class="card-body p-4">
                                            <a class="d-block" href="blog-single.html"><img class="rounded-4 w-100" src="images/blog/a.jpg" alt=""></a>
                                            <a href="#" class="badge px-3 py-2 mt-4 mb-3 text-bg-primary-soft fw-normal rounded-pill">CopyGen</a>
                                            <h3><a href="blog-single.html" class="link-dark line-clamp-2">How Content Generators Work &amp; How To Use Them Effectively</a></h3>
                                            <div class="d-flex pt-4">
                                                <div class="media media-lg media-middle media-lg rounded-pill">
                                                    <img src="images/avatar/author/sm-d.jpg" alt="">
                                                </div>
                                                <div class="media-info ms-3">
                                                    <h6 class="mb-1">Ashley Lawson</h6>
                                                    <ul class="list list-row gx-1">
                                                        <li>
                                                            <div class="sub-text">Feb 10, 2023</div>
                                                        </li>
                                                        <li><em class="icon mx-0 ni ni-dot"></em></li>
                                                        <li>
                                                            <div class="sub-text">11 min read</div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="swiper-button-prev btn btn-icon btn-dark btn-soft rounded-pill position-absolute top-50 start-0 translate-middle z-index-1"><em class="icon ni ni-arrow-long-left"></em></div>
                            <div class="swiper-button-next btn btn-icon btn-dark btn-soft rounded-pill position-absolute top-50 start-100 translate-middle z-index-1"><em class="icon ni ni-arrow-long-right"></em></div>
                        </div>
                    </div>
                </div>
            </section> -->
            
            <!-- .section -->
            <!-- <section class="section section-bottom-0">
                <div class="container">
                    <div class="section-wrap bg-primary bg-opacity-10 rounded-4">
                        <div class="section-content bg-pattern-dot-sm p-4 p-sm-6">
                            <div class="row justify-content-center text-center">
                                <div class="col-xl-8 col-xxl-9">
                                    <div class="block-text">
                                        <h6 class="overline-title text-primary">Boost your writing productivity</h6>
                                        <h2 class="title">End writer’s block today</h2>
                                        <p class="lead mt-3">It’s like having access to a team of copywriting experts writing powerful copy for you in 1-click.</p>
                                        <ul class="btn-list btn-list-inline">
                                            <li><a href="#" class="btn btn-lg btn-primary"><span>Start writing for free</span><em class="icon ni ni-arrow-long-right"></em></a></li>
                                        </ul>
                                        <ul class="list list-row gy-0 gx-3">
                                            <li><em class="icon ni ni-check-circle-fill text-success"></em><span>No credit card required</span></li>
                                            <li><em class="icon ni ni-check-circle-fill text-success"></em><span>Cancel anytime</span></li>
                                            <li><em class="icon ni ni-check-circle-fill text-success"></em><span>10+ tools to expolore</span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section> -->

        </main>
       <?php include 'footer.php'; ?>
    </div>
    <script src="assets/js/bundle.js?v1.5.0"></script>
    <script src="assets/js/scripts.js?v1.5.0"></script>
</body>

</html>