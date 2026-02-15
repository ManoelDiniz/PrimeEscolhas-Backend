<?php 

namespace App\Repository\BlogPosts;

use App\Models\BlogPosts;
use App\Dto\ParamDto;

class BlogPostsRepository
{
    public function getBlogPosts(ParamDto $params)
    {
        $query = BlogPosts::query();

        if ($params->search) {
            $query->where('title', 'like', '%' . $params->search . '%');
        }

        if ($params->sortBy && $params->sortDirection) {
            $query->orderBy($params->sortBy, $params->sortDirection);
        }

        return $query->paginate($params->perPage, ['*'], 'page', $params->page);
    }

    public function getBlogBySlug($slug)
    {
        return BlogPosts::where('slug', $slug)->first();
    }
}