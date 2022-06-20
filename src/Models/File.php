<?php

declare(strict_types=1);

namespace Coddin\DataTables\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Auth\User;

/**
 * phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
 * @property string $name
 * @property Carbon $created_at
 */
final class File extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'mime_type',
        'size',
        'uploader_id',
        'relationable_type',
        'relationable_id',
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'preview_url',
        'thumbnail_url',
    ];

    /** @var string[] */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function relationable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getPreviewUrlAttribute(): string
    {
        return url("storage/media/{$this->created_at->format('Y/m')}/{$this->name}");
    }

    public function getThumbnailUrlAttribute(): string
    {
        $urls = collect(
            [
                'image' => [
                    'mimes' => [
                        'image/gif',
                        'image/avif',
                        'image/apng',
                        'image/png',
                        'image/svg+xml',
                        'image/webp',
                        'image/jpeg',
                    ],
                    'thumbnail_url' => url(
                        $this->preview_url,
                    ),
                ],
                'audio' => [
                    'mimes' => [
                        'audio/mpeg',
                        'audio/aac',
                        'audio/wav',
                    ],
                    'thumbnail_url' => asset('img/file-types/file-type-audio.svg'),
                ],
                'video' => [
                    'mimes' => [
                        'video/mp4',
                        'video/webm',
                        'video/mpeg',
                        'video/x-msvideo',
                    ],
                    'thumbnail_url' => asset('img/file-types/file-type-video.svg'),
                ],
                'document' => [
                    'mimes' => [
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/pdf',
                    ],
                    'thumbnail_url' => asset('img/file-types/file-type-document.svg'),
                ],
                'archive' => [
                    'mimes' => [
                        'application/zip',
                        'application/x-7z-compressed',
                        'application/gzip',
                        'application/vnd.rar',
                    ],
                    'thumbnail_url' => asset('img/file-types/file-type-archive.svg'),
                ],
            ],
        );

        $fileType = $urls->first(
            function ($item) {
                return in_array($this->mime_type, $item['mimes']);
            },
        );

        if ($fileType !== null && is_string($fileType['thumbnail_url'])) {
            return $fileType['thumbnail_url'];
        }

        return asset("img/file-types/file-type-other.svg");
    }
}
