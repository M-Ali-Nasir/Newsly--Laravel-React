<?php

namespace App\Services;

use GuzzleHttp\Client;

class NewsService
{

    protected $client;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->client = new Client(
            [
                'timeout'  => 120.0,
            ]
        );
    }

    public function fetchNewsFromNewsAPI()
    {
        $apiKey = env('NEWSAPI');
        $url = "https://newsapi.org/v2/top-headlines?country=us&apiKey={$apiKey}";

        $response = $this->client->get($url);
        $data = json_decode($response->getBody(), true);

        return $data['articles'] ?? [];
    }

    // Fetch news from The Guardian API
    public function fetchNewsFromTheGuardian()
    {
        $apiKey = env('GUARDIAN_API_KEY');
        $pageSize = 50;
        // Include 'byline' in the 'show-fields' parameter to get the author's name
        $url = "https://content.guardianapis.com/search?api-key={$apiKey}&show-fields=all,byline&page-size={$pageSize}";

        $response = $this->client->get($url);
        $data = json_decode($response->getBody(), true);

        return $data['response']['results'] ?? [];
    }

    // Fetch news from New York Times API
    public function fetchNewsFromNYTimes()
    {
        $apiKey = env('NYTIMES_API_KEY');
        $url = "https://api.nytimes.com/svc/mostpopular/v2/viewed/1.json?api-key={$apiKey}";

        $response = $this->client->get($url);
        $data = json_decode($response->getBody(), true);

        return $data['results'] ?? [];
    }



    // saving news articles in database

    public function fetchAndStoreNews()
    {
        // Fetch news from all sources
        $newsAPINews = $this->fetchNewsFromNewsAPI();
        $guardianNews = $this->fetchNewsFromTheGuardian();
        $nyTimesNews = $this->fetchNewsFromNYTimes();

        // Process and store articles
        $this->storeArticles($newsAPINews, 'NewsAPI');
        $this->storeArticles($guardianNews, 'The Guardian');
        $this->storeArticles($nyTimesNews, 'New York Times');
    }

    // Helper function to store articles in the database
    protected function storeArticles($articles, $sourceName)
    {
        foreach ($articles as $article) {
            // Determine the title and other fields dynamically
            $title = $article['title'] ?? $article['webTitle'] ?? 'Untitled';
            $description = $article['description'] ?? $article['fields']['trailText'] ?? $article['abstract'] ?? 'No description available';
            $url = $article['url'] ?? $article['webUrl'] ?? '#';
            $publishedAt = $article['published_at'] ?? $article['webPublicationDate'] ?? now();
            $content = $article['content'] ?? $article['fields']['bodyText'] ?? 'No content available';
            $author = $article['author'] ?? $article['fields']['byline'] ?? $article['byline'] ?? 'Unknown';
            $category = $this->determineCategory($title, $description, $content);
            // Save the article in the database
            \App\Models\Article::updateOrCreate(
                ['title' => $title],  // Use the determined title
                [
                    'source' => $sourceName,
                    'author' => $author,
                    'title' => $title,
                    'description' => $description,
                    'url' => $url,
                    'published_at' => $publishedAt,
                    'content' => $content,
                    'category_id' => $category->id,
                ]
            );
        }
    }

    //determining the category
    protected function determineCategory($title, $description, $content)
    {
        // Define keywords for each category
        $categories = [
            'Technology' => ['technology', 'tech', 'software', 'hardware', 'gadgets', 'innovation'],
            'Business' => ['business', 'economy', 'finance', 'corporate', 'stock', 'market'],
            'Sports' => ['sports', 'football', 'cricket', 'tennis', 'motorsports', 'basketball'],
            'Health' => ['health', 'medicine', 'fitness', 'wellness', 'nutrition', 'mental health'],
            'Entertainment' => ['entertainment', 'movies', 'music', 'celebrity', 'television'],
            'Science' => ['science', 'research', 'biology', 'physics', 'chemistry', 'space'],
            'World' => ['world', 'global', 'international', 'foreign'],
            'Politics' => ['politics', 'election', 'government', 'policy', 'diplomacy'],
            'Education' => ['education', 'school', 'university', 'learning', 'academic'],
            'Environment' => ['environment', 'climate', 'nature', 'wildlife', 'sustainability'],
            'Lifestyle' => ['lifestyle', 'living', 'home', 'hobbies', 'diy', 'parenting'],
            'Travel' => ['travel', 'tourism', 'destination', 'trip', 'adventure'],
            'Food' => ['food', 'cooking', 'recipe', 'cuisine', 'nutrition'],
            'Opinion' => ['opinion', 'editorial', 'commentary', 'views'],
            'Economy' => ['economy', 'growth', 'recession', 'trade', 'industry'],
            'Finance' => ['finance', 'banking', 'investment', 'stocks', 'loans'],
            'Automotive' => ['automotive', 'cars', 'vehicles', 'motors', 'transport'],
            'Real Estate' => ['real estate', 'property', 'housing', 'commercial', 'rent', 'mortgage'],
            'Fashion' => ['fashion', 'style', 'clothing', 'beauty', 'trends'],
            'Culture' => ['culture', 'society', 'tradition', 'heritage', 'art'],
            'History' => ['history', 'historical', 'heritage', 'past', 'archaeology'],
            'Religion' => ['religion', 'faith', 'spirituality', 'church', 'temple', 'mosque'],
            'Art' => ['art', 'painting', 'sculpture', 'gallery', 'exhibition'],
            'Books' => ['books', 'literature', 'reading', 'author', 'novel'],
            'Technology Trends' => ['technology trends', 'tech news', 'innovation'],
            'Movies' => ['movies', 'films', 'cinema', 'hollywood', 'bollywood'],
            'Music' => ['music', 'song', 'concert', 'album', 'band'],
            'Television' => ['television', 'tv', 'series', 'show', 'broadcast'],
            'Gadgets' => ['gadgets', 'devices', 'electronics', 'smartphones'],
            'Startups' => ['startups', 'entrepreneurship', 'venture', 'innovation'],
            'Gaming' => ['gaming', 'video games', 'esports', 'gameplay'],
            'Social Media' => ['social media', 'facebook', 'twitter', 'instagram', 'social network'],
            'Weather' => ['weather', 'forecast', 'climate', 'temperature', 'storm'],
            'Space' => ['space', 'astronomy', 'nasa', 'universe', 'cosmos'],
            'Animals' => ['animals', 'wildlife', 'pets', 'zoology'],
            'Health and Wellness' => ['health and wellness', 'well-being', 'self-care'],
            'Fitness' => ['fitness', 'exercise', 'workout', 'gym', 'training'],
            'Nutrition' => ['nutrition', 'diet', 'healthy eating', 'vitamins'],
            'Parenting' => ['parenting', 'children', 'family', 'kids', 'motherhood', 'fatherhood'],
            'Beauty' => ['beauty', 'skincare', 'makeup', 'cosmetics'],
            'DIY' => ['diy', 'do it yourself', 'craft', 'handmade'],
            'Motorsports' => ['motorsports', 'racing', 'formula 1', 'nascar'],
            'Crime' => ['crime', 'law', 'legal', 'police', 'criminal'],
            'National' => ['national', 'country', 'domestic', 'local'],
            'Local' => ['local', 'community', 'regional'],
            'Travel Guides' => ['travel guides', 'travel tips', 'destination guide'],
            'Recipes' => ['recipes', 'cooking', 'baking', 'food preparation'],
            'Mental Health' => ['mental health', 'anxiety', 'depression', 'therapy'],
        ];

        foreach ($categories as $categoryName => $keywords) {
            foreach ($keywords as $keyword) {
                if (stripos($title, $keyword) !== false || stripos($description, $keyword) !== false || stripos($content, $keyword) !== false) {
                    // Find the category in the database and return it
                    return \App\Models\Category::where('name', $categoryName)->first();
                }
            }
        }

        // If no matching category was found, create or return a default category
        return \App\Models\Category::firstOrCreate(['name' => 'General', 'slug' => 'general']);
    }
}
