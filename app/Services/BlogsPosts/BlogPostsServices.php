<?php


namespace App\Services\BlogsPosts;


use App\Dto\ParamDto;
use App\Repository\BlogPosts\BlogPostsRepository;

class BlogPostsServices
{
    protected $blogPostsRepository;

    public function __construct(BlogPostsRepository $blogPostsRepository)
    {
        $this->blogPostsRepository = $blogPostsRepository;
    }

    public function getBlogPosts(ParamDto $params)
    {
        return $this->blogPostsRepository->getBlogPosts($params);
    }
    public function getBlogBySlug($slug)
    {
        return $this->blogPostsRepository->getBlogBySlug($slug);
    }
}
