<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SpiritualResource extends Model
{
    public const TYPE_YOUTUBE = 'youtube';
    public const TYPE_BOOK = 'book';
    public const TYPE_ARTICLE = 'article';
    public const TYPE_PDF = 'pdf';
    public const TYPE_LINK = 'link';

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    public const TYPES = [
        self::TYPE_YOUTUBE => 'YouTube Video',
        self::TYPE_BOOK => 'Book',
        self::TYPE_ARTICLE => 'Article',
        self::TYPE_PDF => 'PDF',
        self::TYPE_LINK => 'Link',
    ];

    public const STATUSES = [
        self::STATUS_PENDING => 'Pending Review',
        self::STATUS_APPROVED => 'Approved',
        self::STATUS_REJECTED => 'Rejected',
    ];

    protected $fillable = [
        'title',
        'description',
        'type',
        'url',
        'file_path',
        'cover_image',
        'author',
        'source',
        'status',
        'rejection_reason',
        'submitted_by',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            SpiritualResourceCategory::class,
            'spiritual_resource_category',
            'spiritual_resource_id',
            'spiritual_resource_category_id'
        )->withTimestamps();
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function fileUrl(): ?string
    {
        if (!$this->file_path) {
            return null;
        }

        return route('spiritual-resources.media', [$this->id, 'file']);
    }

    public function coverUrl(): ?string
    {
        if (!$this->cover_image) {
            return null;
        }

        return route('spiritual-resources.media', [$this->id, 'cover']);
    }

    /**
     * Normalize a URL for duplicate comparison.
     */
    public static function normalizeUrl(?string $url): ?string
    {
        if ($url === null || trim($url) === '') {
            return null;
        }

        $url = trim($url);
        $url = preg_replace('#^https?://#i', '', $url);
        $url = rtrim($url, '/');

        return strtolower($url);
    }

    /**
     * Check whether a duplicate non-rejected resource already exists.
     */
    public static function hasDuplicate(?string $url, string $title, string $type, ?int $excludeId = null): bool
    {
        $query = static::query()->where('status', '!=', self::STATUS_REJECTED);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $normalized = static::normalizeUrl($url);

        if ($normalized) {
            return $query->whereNotNull('url')
                ->get(['id', 'url'])
                ->contains(fn (SpiritualResource $resource) => static::normalizeUrl($resource->url) === $normalized);
        }

        return $query->where('title', $title)->where('type', $type)->exists();
    }
}
