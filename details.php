
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['title']) ?></title>
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

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary);
            text-decoration: none;
            margin-bottom: 2rem;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            transition: background 0.2s;
        }

        .back-link:hover {
            background: rgba(108, 92, 231, 0.1);
        }

        .post-detail {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .post-header {
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
            margin-bottom: 2rem;
        }

        .post-icon-lg {
            width: 64px;
            height: 64px;
            background: var(--secondary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .post-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: #666;
            font-size: 0.9rem;
            margin: 1rem 0;
        }

        .post-meta svg {
            width: 16px;
            height: 16px;
            vertical-align: middle;
            margin-right: 0.3rem;
        }

        .post-content {
            font-size: 1.1rem;
            line-height: 1.8;
            margin: 2rem 0;
        }

        .comments-section {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #eee;
        }

        .comment {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            padding: 1.5rem;
            background: var(--background);
            border-radius: 12px;
        }

        .comment-icon {
            width: 40px;
            height: 40px;
            background: var(--secondary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .comment-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: #666;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }

        .social-share {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .social-share a {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.2rem;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            transition: background 0.2s;
        }

        .social-share a:hover {
            background: var(--accent);
        }

        @media (max-width: 600px) {
            .post-header {
                flex-direction: column;
                gap: 1rem;
            }
            
            .post-icon-lg {
                width: 48px;
                height: 48px;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <h1><a href="/" style="color: white; text-decoration: none;">📝 Gistgroove</a></h1>
        </nav>
    </header>

    <div class="container">
        <a onclick="window.history.back();"  class="back-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
            </svg>
            Back to Posts
        </a>

        <article class="post-detail">
            <div class="post-header">
                <div class="post-icon-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="white">
                        <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-7 12h-2v-2h2v2zm0-4h-2V6h2v4z"/>
                    </svg>
                </div>
                <div>
                    <span class="category-tag">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="white">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                        </svg>
                        <?= htmlspecialchars($post['category_name']) ?>
                    </span>
                    <h1 style="margin: 1rem 0;"> <?= htmlspecialchars($post['title']) ?></h1>
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
                        <span>
                            <!-- Stat (Views) Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="#666">
                                <!-- This icon represents a simple bar chart -->
                                <path d="M3 17h3v-7H3v7zm5 0h3v-10H8v10zm5 0h3v-13h-3v13zm5 0h3v-4h-3v4z"/>
                            </svg>
                            <!-- Assuming $post['views'] contains the view count; adjust as needed -->
                            <?= isset($total_views) ? htmlspecialchars($total_views) . ' Views' : '0 Views' ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="post-content">
                <?= htmlspecialchars($post['body']) ?>

             </div>

            <div class="social-share">
                <!-- <a href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="white">
                        <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                    </svg>
                    Twitter
                </a>
                <a href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="white">
                        <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
                    </svg>
                    Facebook
                </a>
                <a href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="white">
                        <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                    </svg>
                    LinkedIn
                </a> -->
            </div>
        </article>
    </div>
</body>
</html>
