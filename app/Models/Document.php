<?php

namespace App\Models;

use App\Enums\DocumentGroup;
use Database\Factories\DocumentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    /** @use HasFactory<DocumentFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'meta',
        'group',
        'type',
        'filename',
        'url',
        'size_bytes',
        'downloads',
        'visible',
        'sort',
    ];

    protected function casts(): array
    {
        return [
            'group' => DocumentGroup::class,
            'size_bytes' => 'integer',
            'downloads' => 'integer',
            'visible' => 'boolean',
            'sort' => 'integer',
        ];
    }

    public function isExternal(): bool
    {
        return $this->type === 'external';
    }

    /** Absolutní cesta k souboru na disku (jen pro type=file). */
    public function filePath(): ?string
    {
        return $this->filename === null
            ? null
            : rtrim(config('documents.path'), '/').'/'.$this->filename;
    }

    /** Lidská velikost: „240 kB", od 1 MB „2,3 MB". */
    public function sizeLabel(): ?string
    {
        if ($this->size_bytes === null) {
            return null;
        }

        if ($this->size_bytes >= 1024 * 1024) {
            return number_format($this->size_bytes / (1024 * 1024), 1, ',', ' ').' MB';
        }

        return number_format($this->size_bytes / 1024, 0, ',', ' ').' kB';
    }

    /** Cíl odkazu na veřejném webu (externí URL, nebo počítací routa). */
    public function href(): string
    {
        return $this->isExternal()
            ? (string) $this->url
            : route('documents.download', $this);
    }

    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort')->orderBy('id');
    }
}
