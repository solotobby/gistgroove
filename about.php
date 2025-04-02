
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aout Us</title>
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
            <h1><a href="/" style="color: white; text-decoration: none;"><img src="gistlogo.png" height="150" width="250"></a></h1>
        </nav>
    </header>

    <div class="container">
        <a onclick="window.history.back();"  class="back-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
            </svg>
            Back
        </a>

        <article class="post-detail">
            <div class="post-header">
                <div class="post-icon-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="white">
                        <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-7 12h-2v-2h2v2zm0-4h-2V6h2v4z"/>
                    </svg>
                </div>
                <div>
                   
                    <h1 style="margin: 1rem 0;"> About Us </h1>
                    
                </div>
            </div>

            <div class="post-content">
               
             Gistgroove.com is a product of Gistgroove Media Ltd, United Kingdom. We accept original trending posts from independent writers, bloggers and freelancers who write short local, community and international trending gists, guest posts, event updates and user generated contents (UGC).
             We want you to share with other members of the community about What’s trending around you?
              <br>
            We support original (non AI-generated), non-defamatory, non-abusive, unbiased and non spurious news and content from diverse categories, communities and niches. We support  information dissemination while keeping our community safe.
            

             </div>

             </article>

        
    </div>
</body>
</html>

