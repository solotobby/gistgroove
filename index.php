<?php
require_once 'db.php';

// Pagination settings
$per_page = 10; // Posts per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); // Ensure page is at least 1
$offset = ($page - 1) * $per_page;

try {
    // Get total posts count
    $total_stmt = $pdo->query("SELECT COUNT(*) FROM posts");
    $total_posts = $total_stmt->fetchColumn();
    
    // Calculate total pages
    $total_pages = ceil($total_posts / $per_page);
    
    // Get posts for current page
    $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Gistgroove">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="images/faviconss.png">
    <title>Gistgroove | Real-Time Updates & Local News</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="Gistgroove" content="Discover what's trending in your area. Get real-time updates on news, entertainment, viral stories, and more. Share trends with the world!">    
    <meta name="author" content="Gistgroove">
    <meta name="keywords" content="trending news, viral updates, local trends, latest buzz, breaking news">
    <link rel="canonical" href="https://gistgroove.com"/>

    <meta property="og:title" content="Trending Now in Your Area - Latest Updates">
    <meta property="og:description" content="Find out what's trending near you and share viral news instantly!">
    <meta property="og:image" content="https://gistgroove.com/gistlogo.png">
    <meta property="og:url" content="https://gistgroove.com">
    <meta property="og:type" content="website">

     <!-- Basic Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@gist_groove"> 
    <meta name="twitter:title" content="Trending Now in Your Area - Stay Updated">
    <meta name="twitter:description" content="Find out what's trending near you and share viral news instantly!">
    <meta name="twitter:image" content="https://gistgroove.com/gistlogo.png">
    <meta name="twitter:url" content="https://gistgroove.com">


    <link rel="stylesheet" href="assets/css/style.css?v1.5.0"> 

    <style>
  .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin: 2rem 0;
        }

        .page-item {
            padding: 0.5rem 1rem;
            background: white;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.2s;
        }

        .page-item a {
            color: var(--primary);
            text-decoration: none;
        }

        .page-item:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.15);
        }

        .page-item.active {
            background: var(--primary);
        }

        .page-item.active a {
            color: blue;
        }

        .page-item.disabled {
            opacity: 0.5;
            pointer-events: none;
        }
    </style>



</head>

<body class="nk-body " data-menu-collapse="lg">
    <div class="nk-app-root ">
        <?php include 'header.php'; ?>
        <main class="nk-pages">
            <section class="section section-bottom-0 has-shape has-mask">
                <div class="nk-shape bg-shape-blur-m mt-8 start-50 top-0 translate-middle-x"></div>
                <div class="nk-shape bg-shape-blur-m mt-8 start-50 top-50 translate-middle-x"></div>
                <div class="nk-mask bg-pattern-dot bg-blend-around mt-n5 mb-10p mh-50vh"></div>
                <div class="container">
                    <div class="section-head">
                        <div class="row justify-content-center text-center">
                            <div class="col-lg-9 col-xl-7 col-xxl-6">
                                <!-- <h6 class="overline-title text-primary">Blog</h6> -->
                                <h1 class="title">Real-Time Updates & Local News</h1>

                                <!-- <label id="blog-search" class="d-flex align-items-center border rounded-3 py-2 px-4 mt-5 mt-lg-7 bg-white">
                                    <em class="icon me-3 fs-3 ni ni-search"></em>
                                    <input type="text" name="blog-search" class="form-control form-control-lg form-control-plaintext" placeholder="Search for articles" required>
                                </label> -->

                            </div>
                        </div>
                    </div><!-- .section-head -->
                    <div class="section-content">
                        <div class="row g-gs">
                        <?php foreach ($posts as $post): ?>
                            <div class="col-md-12 col-xl-12 col-lg-12">
                                <div class="card border-0 shadow-tiny rounded-4">
                                    <div class="card-body p-4">
                                        <!-- <a class="d-block" href="blog-single.html"><img class="rounded-4 w-100" src="images/blog/a.jpg" alt=""></a> -->
                                        <a href="details.php?slug=<?= $post['slug'] ?>" class="badge px-3 py-2 mt-4 mb-3 text-bg-primary-soft fw-normal rounded-pill"><?= htmlspecialchars($post['category_name']) ?></a>
                                        <h3><a href="details.php?slug=<?= $post['slug'] ?>" class="link-dark line-clamp-2"><?= htmlspecialchars($post['title']) ?></a></h3>
                                        <p>
                                            <?= substr(htmlspecialchars($post['body']), 0, 30) ?>...
                                        </p>
                                        <div class="d-flex pt-4">                                            
                                            <div class="media media-lg media-middle media-lg rounded-pill">
                                                <img src="images/avatar/author/sm-d.jpg" alt="">
                                            </div>

                                            <div class="media-info ms-3">
                                                <h6 class="mb-1"><?= htmlspecialchars($post['username']) ?></h6>
                                                <ul class="list list-row gx-1">
                                                    <li>
                                                        <div class="sub-text"><?= date('M j, Y', strtotime($post['created_at'])) ?></div>
                                                    </li>
                                                    <!-- <li><em class="icon mx-0 ni ni-dot"></em></li>
                                                    <li>
                                                        <div class="sub-text">11 min read</div>
                                                    </li> -->
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- .card -->
                            </div><!-- .col -->
                        <?php endforeach; ?>
                        
                        </div><!-- .row -->
                    </div><!-- .section-content -->
                    <div class="section-actions text-center">
                        <div class="pagination">
                            <?php if($page > 1): ?>
                                <div class="page-item">
                                    <a href="?page=<?= $page - 1 ?>">&laquo; Previous</a>
                                </div>
                            <?php endif; ?>

                            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                <div class="page-item <?= $i === $page ? 'active' : '' ?>">
                                    <a href="?page=<?= $i ?>"><?= $i ?></a>
                                </div>
                            <?php endfor; ?>

                            <?php if($page < $total_pages): ?>
                                <div class="page-item">
                                    <a href="?page=<?= $page + 1 ?>">Next &raquo;</a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <!-- <ul class="btn-list btn-list-inline g-gs">
                            <li><a href="#" class="btn btn-primary btn-soft btn-lg"><em class="icon ni ni-arrow-long-left"></em><span>Previous</span></a></li>
                            <li><a href="#" class="btn btn-primary btn-lg"><span>Next</span><em class="icon ni ni-arrow-long-right"></em></a></li>
                        </ul> -->
                    </div><!-- .section-actions -->
                </div><!-- .container -->
            </section><!-- .section -->

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