<?php

namespace App\Dto;


class ParamDto
{
    public function __construct(
        public int $page = 1,
        public int $perPage = 10,
        public ?string $search = null,
        public ?string $sortBy = null,
        public ?string $sortDirection = null,
        public ?string $is_featured = null,
        public ?bool $published = null
    ) {}

    public function toArray(): array
    {
        return [
            'page' => $this->page,
            'perPage' => $this->perPage,
            'search' => $this->search,
            'sortBy' => $this->sortBy,
            'sortDirection' => $this->sortDirection,
            'is_featured' => $this->is_featured,
            'published' => $this->published,
        ];
    }
}
