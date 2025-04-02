<?php
require_once 'db.php';

// Pagination settings
$per_page = 5; // Posts per page
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gistgroove</title>
    <style>
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

    </style>
</head>
<body>
    <header>
        <nav>
            <h1><a href="/" style="color: white; text-decoration: none;"><img src="gistlogo.png" height="50" width="100"></a></h1>
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
            <h3>Gistgroove</h3>
            <p>Short, meaningful posts about things that matter.</p>
        </div>
        
        <div class="footer-section">
            <h3>Quick Links</h3>
            <ul class="footer-links">
                <!-- <li><a href="#">About Us</a></li>
                <li><a href="#">Contact</a></li> -->
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
