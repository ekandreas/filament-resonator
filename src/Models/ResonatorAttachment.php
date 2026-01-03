<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Models;

use EkAndreas\Resonator\Http\Integrations\Resend\Requests\GetReceivedAttachmentRequest;
use EkAndreas\Resonator\Http\Integrations\Resend\ResendConnector;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class ResonatorAttachment extends Model
{
    protected $table = 'resonator_attachments';

    protected $fillable = [
        'email_id',
        'resend_id',
        'filename',
        'content_type',
        'content_disposition',
        'content_id',
        'size',
        'local_path',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
        ];
    }

    public function email(): BelongsTo
    {
        return $this->belongsTo(ResonatorEmail::class, 'email_id');
    }

    public function getDownloadUrl(): ?string
    {
        if (! $this->resend_id || ! $this->email?->resend_id) {
            return null;
        }

        $cacheKey = "resonator_attachment_url_{$this->id}";

        return Cache::remember($cacheKey, 55 * 60, function () {
            try {
                $connector = new ResendConnector;
                $request = new GetReceivedAttachmentRequest(
                    $this->email->resend_id,
                    $this->resend_id
                );
                $response = $connector->send($request);

                return $response->json('download_url');
            } catch (\Exception $e) {
                return null;
            }
        });
    }

    protected function humanReadableSize(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! $this->size) {
                    return null;
                }

                $units = ['B', 'KB', 'MB', 'GB'];
                $size = $this->size;
                $unit = 0;

                while ($size >= 1024 && $unit < count($units) - 1) {
                    $size /= 1024;
                    $unit++;
                }

                return number_format($size, 2) . ' ' . $units[$unit];
            }
        );
    }

    public function isImage(): bool
    {
        return str_starts_with($this->content_type ?? '', 'image/');
    }

    public function isPdf(): bool
    {
        return $this->content_type === 'application/pdf';
    }
}
