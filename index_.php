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

    <meta charset="UTF-8">
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

    <!-- Add this right after the opening <body> tag -->



<!--     <style>
        :root {
            --primary: #6c5ce7;
            --secondary: #a8a5e6;
            --background: #f8f9fa;
            --text: #2d3436;
            --accent: #e84393;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        body {
            background-color: var(--background);
            color: var(--text);
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        header {
            background: var(--primary);
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        nav {
            max-width: 800px;
            margin: 0 auto;
            color: white;
        }

        .posts-list {
            list-style: none;
        }

        .post-item {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
        }

        .post-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .post-icon {
            width: 48px;
            height: 48px;
            background: var(--secondary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .post-content {
            flex-grow: 1;
        }

        .post-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: #666;
            font-size: 0.9rem;
            margin: 0.5rem 0;
        }

        .post-meta svg {
            width: 16px;
            height: 16px;
            vertical-align: middle;
            margin-right: 0.3rem;
        }

        .read-more {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.2rem;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            margin-top: 1rem;
            transition: background 0.2s;
        }

        .read-more:hover {
            background: var(--accent);
        }

        .category-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.8rem;
            background: var(--secondary);
            color: white;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        @media (max-width: 600px) {
            .post-item {
                flex-direction: column;
                gap: 1rem;
            }
            
            .post-icon {
                width: 40px;
                height: 40px;
            }
        }

        /* Add to existing CSS */
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .container {
        flex: 1;
    }

    footer {
        background: var(--primary);
        color: white;
        padding: 2rem 1rem;
        margin-top: 3rem;
    }

    .footer-content {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 2rem;
    }

    .footer-section {
        flex: 1;
        min-width: 250px;
    }

    .footer-section h3 {
        margin-bottom: 1rem;
        color: var(--secondary);
    }

    .footer-links {
        list-style: none;
    }

    .footer-links a {
        color: white;
        text-decoration: none;
        display: block;
        margin-bottom: 0.5rem;
        transition: opacity 0.2s;
    }

    .footer-links a:hover {
        opacity: 0.8;
    }

    .footer-bottom {
        text-align: center;
        margin-top: 2rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(255,255,255,0.1);
    }

    @media (max-width: 768px) {
        .footer-section {
            flex-basis: 100%;
            text-align: center;
        }
    }

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
            color: white;
        }

        .page-item.disabled {
            opacity: 0.5;
            pointer-events: none;
        }

    </style> -->
</head>

    <style>
/* Add these styles to the existing CSS */

         :root {
            --primary: #6c5ce7;
            --secondary: #a8a5e6;
            --background: #f8f9fa;
            --text: #2d3436;
            --accent: #e84393;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        body {
            background-color: var(--background);
            color: var(--text);
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        header {
            background: var(--primary);
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        nav {
            max-width: 800px;
            margin: 0 auto;
            color: white;
        }

        .posts-list {
            list-style: none;
        }

        .post-item {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
        }

        .post-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .post-icon {
            width: 48px;
            height: 48px;
            background: var(--secondary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .post-content {
            flex-grow: 1;
        }

        .post-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: #666;
            font-size: 0.9rem;
            margin: 0.5rem 0;
        }

        .post-meta svg {
            width: 16px;
            height: 16px;
            vertical-align: middle;
            margin-right: 0.3rem;
        }

        .read-more {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.2rem;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            margin-top: 1rem;
            transition: background 0.2s;
        }

        .read-more:hover {
            background: var(--accent);
        }

        .category-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.8rem;
            background: var(--secondary);
            color: white;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        @media (max-width: 600px) {
            .post-item {
                flex-direction: column;
                gap: 1rem;
            }
            
            .post-icon {
                width: 40px;
                height: 40px;
            }
        }

        /* Add to existing CSS */
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .container {
        flex: 1;
    }

    footer {
        background: var(--primary);
        color: white;
        padding: 2rem 1rem;
        margin-top: 3rem;
    }

    .footer-content {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 2rem;
    }

    .footer-section {
        flex: 1;
        min-width: 250px;
    }

    .footer-section h3 {
        margin-bottom: 1rem;
        color: var(--secondary);
    }

    .footer-links {
        list-style: none;
    }

    .footer-links a {
        color: white;
        text-decoration: none;
        display: block;
        margin-bottom: 0.5rem;
        transition: opacity 0.2s;
    }

    .footer-links a:hover {
        opacity: 0.8;
    }

    .footer-bottom {
        text-align: center;
        margin-top: 2rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(255,255,255,0.1);
    }

    @media (max-width: 768px) {
        .footer-section {
            flex-basis: 100%;
            text-align: center;
        }
    }

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
            color: white;
        }

        .page-item.disabled {
            opacity: 0.5;
            pointer-events: none;
        }

        
header {
    background: var(--primary);
    padding: 1rem 0;
    position: relative;
}

.nav-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-brand img {
    transition: transform 0.3s ease;
}

.nav-brand img:hover {
    transform: scale(1.05);
}

.nav-links {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.nav-links ul {
    display: flex;
    gap: 1.5rem;
    list-style: none;
    margin: 0;
    padding: 0;
}

.nav-links a {
    color: white;
    text-decoration: none;
    font-weight: 500;
    padding: 0.5rem;
    transition: color 0.3s ease;
}

.nav-links a:hover {
    color: var(--secondary);
}

.nav-search form {
    display: flex;
    align-items: center;
    background: rgba(255,255,255,0.1);
    border-radius: 25px;
    padding: 0.3rem;
}

.nav-search input {
    border: none;
    background: transparent;
    color: white;
    padding: 0.5rem 1rem;
    outline: none;
    min-width: 200px;
}

.nav-search input::placeholder {
    color: rgba(255,255,255,0.7);
}

.nav-search button {
    background: var(--secondary);
    border: none;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.3s ease;
}

.nav-search button:hover {
    background: var(--accent);
}

/* Mobile Styles */
.nav-toggle {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
    padding: 1rem;
    z-index: 1000;
}

.hamburger {
    display: block;
    width: 25px;
    height: 2px;
    background: white;
    position: relative;
    transition: all 0.3s ease;
}

.hamburger::before,
.hamburger::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    background: white;
    transition: all 0.3s ease;
}

.hamburger::before {
    top: -6px;
}

.hamburger::after {
    top: 6px;
}

/* Mobile Menu */
@media (max-width: 768px) {
    .nav-toggle {
        display: block;
    }

    .nav-links {
        position: fixed;
        top: 70px;
        left: 0;
        width: 100%;
        background: var(--primary);
        flex-direction: column;
        gap: 1rem;
        padding: 2rem;
        transform: translateY(-100%);
        opacity: 0;
        transition: all 0.3s ease;
        z-index: 999;
    }

    .nav-links.active {
        transform: translateY(0);
        opacity: 1;
    }

    .nav-links ul {
        flex-direction: column;
        width: 100%;
        text-align: center;
    }

    .nav-search {
        width: 100%;
    }

    .nav-search form {
        width: 100%;
    }

    .nav-search input {
        width: 100%;
    }
}

/* Toggle Animation */
.nav-toggle.active .hamburger {
    background: transparent;
}

.nav-toggle.active .hamburger::before {
    transform: rotate(45deg);
    top: 0;
}

.nav-toggle.active .hamburger::after {
    transform: rotate(-45deg);
    top: 0;
}
</style>

<script>
// Add this JavaScript before closing </body>
document.querySelector('.nav-toggle').addEventListener('click', function() {
    this.classList.toggle('active');
    document.querySelector('.nav-links').classList.toggle('active');
});
</script>
    
<body>
<!--     <header>
        <nav>
            <h1><a href="/" style="color: white; text-decoration: none;"><img src="gistlogo.png" height="150" width="250"></a></h1>
        </nav>
    </header> -->

    <header>
    <nav>
        <div class="nav-container">
            <div class="nav-brand">
                <a href="/"><img src="gistlogo.png" height="70" width="120" alt="Gistgroove Logo"></a>
            </div>
            
            <button class="nav-toggle" aria-label="Toggle navigation">
                <span class="hamburger"></span>
            </button>
            
            <div class="nav-links">
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="trending.php">Trending</a></li>
                    <li><a href="categories.php">Categories</a></li>
                    <li><a href="about.php">About</a></li>
                </ul>
                
                <div class="nav-search">
                    <form action="search.php" method="GET">
                        <input type="text" placeholder="Search posts..." name="query">
                        <button type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="white">
                                <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</header>

    <div class="container">
        <ul class="posts-list">
            <!-- Sample Posts -->
            <?php foreach ($posts as $post): ?>
            <li class="post-item">
                <div class="post-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="white">
                        <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-7 12h-2v-2h2v2zm0-4h-2V6h2v4z"/>
                    </svg>
                </div>
                <div class="post-content">
                    <span class="category-tag">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="white">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                        </svg>
                        <?= htmlspecialchars($post['category_name']) ?>
                    </span>
                    <h2 style="margin: 0.5rem 0;"><?= htmlspecialchars($post['title']) ?></h2>
                    <div class="post-meta">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="#666">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                            </svg>
                            <?= htmlspecialchars($post['username']) ?>
                        </span>
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="#666">
                                <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                            </svg>
                            <?= date('M j, Y', strtotime($post['created_at'])) ?>
                        </span>
                    </div>
                    <p>
                        <?= substr(htmlspecialchars($post['body']), 0, 150) ?>...

                       
                    </p>
                    
                    <a href="details.php?slug=<?= $post['slug'] ?>" class="read-more">
                        Read More
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="white">
                            <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
                        </svg>
                    </a>
                </div>
            </li>
            <?php endforeach; ?>

            <!-- <li class="post-item">
                <div class="post-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="white">
                        <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                    </svg>
                </div>
                <div class="post-content">
                    <span class="category-tag">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="white">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-5.5-2.5l7.51-3.49L17.5 6.5 9.99 9.99 6.5 17.5zm5.5-6.6c.61 0 1.1.49 1.1 1.1s-.49 1.1-1.1 1.1-1.1-.49-1.1-1.1.49-1.1 1.1-1.1z"/>
                        </svg>
                        Lifestyle
                    </span>
                    <h2 style="margin: 0.5rem 0;">Another Great Post</h2>
                    <div class="post-meta">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="#666">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                            </svg>
                            Jane Smith
                        </span>
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="#666">
                                <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                            </svg>
                            5 hours ago
                        </span>
                    </div>
                    <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo...</p>
                    <a href="details.html" class="read-more">
                        Read More
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="white">
                            <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
                        </svg>
                    </a>
                </div>
            </li> -->
        </ul>

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


    </div>
</body>

<footer>
    <div class="footer-content">
        <div class="footer-section">
            <img src="gistlogo.png" height="150" width="250"><br>
            <p>Trending News | Viral Updates | What's Hot in Your City!</p>
        </div>
        
        <div class="footer-section">
            <h3>Quick Links</h3>
            <ul class="footer-links">
                 <li><a href="about.php">About Us</a></li>
                <!--<li><a href="#">Contact</a></li> -->
                <li><a href="privacy.php">Privacy Policy</a></li>
                <li><a href="terms.php">Terms of Service</a></li>
            </ul>
        </div>
        
        <div class="footer-section">
            <h3>Connect</h3>
            <ul class="footer-links">
                <li><a href="#">Twitter</a></li>
                <li><a href="#">Facebook</a></li>
                <li><a href="#">Instagram</a></li>
                <li><a href="#">LinkedIn</a></li>
            </ul>
        </div>
    </div>
    
    <div class="footer-bottom">
        <p>&copy; 2025 Gistgroove. All rights reserved.</p>
    </div>
</footer>
</html>
