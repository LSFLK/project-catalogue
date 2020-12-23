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
        $content = $response->toArray();
        return $content;
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
        $licence = $this->gitHubInformation['licence'];

        if($licence) { return $licence['name']; }
        else { return null; }
    }

    public function getStarsCount()
    {
        return $this->gitHubInformation['stargazers_count'];
    }

    public function getForksCount()
    {
        return $this->gitHubInformation['forks_count'];
    }

    public function getLanguages(): array
    {
        $languagesUrl = $this->gitHubInformation['languages_url'];
        $content = $this->_fetchContent($languagesUrl);
        $languages = array_keys($content);
        return $languages;   
    }

    public function getTopics(): array
    {
        return $this->gitHubInformation['topics'];
    }
}
