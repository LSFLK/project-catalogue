<?php

namespace App\Service;

use App\Entity\GitRepo;
use Symfony\Component\HttpClient\NativeHttpClient;

class GitHubAPI
{
    private $client, $gitRepo, $gitHubInformation;

    public function __construct(GitRepo $gitRepo)
    {
        $client = new NativeHttpClient();
        $this->client = $client;
        $this->gitRepo = $gitRepo;
        $this->gitHubInformation = $this->fetchGitHubInformation($gitRepo->getUrl());
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
        return $content;
    }

    public function getGitHubInformation(): array
    {
        return $this->gitHubInformation;
    }

    public function getLicenseName(): string
    {
        $license = isset($this->gitHubInformation['license']) ? $this->gitHubInformation['license'] : null;
        $licenseName = isset($license['name']) ? $license['name'] : 'N/A';
        return $licenseName;
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

    public function getGitRepoRequiredData(): array
    {
        $gitRepoRequiredData = [
            'name' => $this->gitRepo->getName(),
            'url'  => $this->gitRepo->getUrl(),
            'licenseName' => $this->getLicenseName(),
            'starsCount'  => $this->getStarsCount(),
            'forksCount'  => $this->getForksCount(),
        ];

        return $gitRepoRequiredData;
    }
}