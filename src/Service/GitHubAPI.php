<?php

namespace App\Service;

use Symfony\Component\HttpClient\NativeHttpClient;

class GitHubAPI
{
    private $client, $gitHubInformation;

    public function __construct($repoUrl)
    {
        $client = new NativeHttpClient();
        $this->client = $client;
        $this->fetchGitHubInformation($repoUrl);
    }

    private function _fetchContent($requestUrl): array
    {
        $response = $this->client->request('GET', $requestUrl, [
            'headers' => ['Accept' => 'application/vnd.github.mercy-preview+json']
        ]);

        if($response->getStatusCode() === 200) {
            $content = $response->toArray();
            return $content;
        }

        return [];
    }

    private function fetchGitHubInformation($repoUrl)
    {
        $apiUrl = "https://api.github.com/repos";
        $repoUrlPath = parse_url($repoUrl, PHP_URL_PATH);
        $repoApiUrl = $apiUrl.$repoUrlPath;
        $content = $this->_fetchContent($repoApiUrl);
        $this->gitHubInformation = $content;
    }

    public function getGitHubInformation(): array
    {
        return $this->gitHubInformation;
    }

    public function getLicenceName(): string
    {
        $licence = isset($this->gitHubInformation['licence']) ? $this->gitHubInformation['licence'] : 'N/A';
        return $licence;
    }

    public function getStarsCount()
    {
        $starsCount = isset($this->gitHubInformation['stargazers_count']) ? $this->gitHubInformation['stargazers_count'] : 'N/A';
        return $starsCount;
    }

    public function getForksCount()
    {
        $forksCount = isset($this->gitHubInformation['forks_count']) ? $this->gitHubInformation['forks_count'] : 'N/A';
        return $forksCount;
    }

    public function getLanguages(): array
    {
        $languagesUrl = isset($this->gitHubInformation['languages_url']) ? $this->gitHubInformation['languages_url'] : null;
        
        if($languagesUrl) {
            $content = $this->_fetchContent($languagesUrl);
            $languages = array_keys($content);
            return $languages;
        }

        return [];
    }

    public function getTopics(): array
    {
        $topics = isset($this->gitHubInformation['topics']) ? $this->gitHubInformation['topics'] : [];
        return $topics;
    }
}