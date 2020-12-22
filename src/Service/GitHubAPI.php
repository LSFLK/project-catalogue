<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class GitHubAPI
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function fetchGitHubInformation($repoUrl): array
    {
        $apiBaseURL = "https://api.github.com/repos";
        $repoUrlPath = parse_url($repoUrl, PHP_URL_PATH);
        $repoAPIURL = $apiBaseURL.$repoUrlPath;

        $response = $this->client->request('GET', $repoAPIURL);
        $content = $response->toArray();

        return $content;
    }
}
