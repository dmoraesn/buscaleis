<?php

namespace App\Models;

use App\Helpers\ViewHelper; // Importar o Helper
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute; // Para Accessors
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str; // Para slugs e Str::limit

class Materia extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'data_apresentacao' => 'date',
        'em_tramitacao' => 'boolean',
    ];

    // --- RELACIONAMENTOS ---
    public function tipo(): BelongsTo { return $this->belongsTo(TipoMateria::class, 'tipo_id'); }
    public function regimeTramitacao(): BelongsTo { return $this->belongsTo(RegimeTramitacao::class, 'regime_tramitacao_id'); }
    public function autores(): BelongsToMany { return $this->belongsToMany(Autor::class, 'autor_materia'); }
    public function tramitacoes(): HasMany { return $this->hasMany(Tramitacao::class)->orderBy('data_ordem', 'desc'); }
    public function categorias(): BelongsToMany { return $this->belongsToMany(Categoria::class, 'categoria_materia'); }

    //========= ACCESSORS (VIEW MODEL) =========//

    /**
     * Define o título completo da matéria para o card.
     */
    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn () => optional($this->tipo)->descricao . ' Nº ' . $this->numero . '/' . $this->ano
        );
    }

    /**
     * Retorna o nome do regime de tramitação.
     */
    protected function regime(): Attribute
    {
        return Attribute::make(
            get: fn () => optional($this->regimeTramitacao)->descricao ?? 'Não especificado'
        );
    }

    /**
     * Retorna a URL completa da matéria.
     */
    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => route('materias.show', $this)
        );
    }

    /**
     * Retorna a data de apresentação formatada para o frontend.
     */
    protected function formattedPresentedAt(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->data_apresentacao?->format('d/m/Y')
        );
    }

    /**
     * Retorna o autor principal (o primeiro da lista) para o card.
     */
    protected function author(): Attribute
    {
        return Attribute::make(
            get: fn () => (object)['name' => optional($this->autores->first())->nome ?? 'Não informado']
        );
    }

    /**
     * Retorna a ementa com limite e destaque (usado na busca).
     * O termo de busca é injetado via Controller no momento da query.
     */
    protected function ementaSnippet(): Attribute
    {
        return Attribute::make(
            get: function () {
                $snippet = Str::limit($this->ementa, 150);
                // A ViewHelper.php agora deve estar no seu projeto
                // O termo de busca está disponível no Request, que pode ser acessado pelo Controller.
                return ViewHelper::highlightText($snippet, request('q'));
            }
        );
    }

    /**
     * Lógica de situação para o Card (label e cor)
     */
    public function getSituationDetails(): array
    {
        if ($this->em_tramitacao) {
            return ['label' => 'Em Tramitação', 'color_class' => 'bg-yellow-500'];
        }
        return ['label' => 'Tramitação Encerrada', 'color_class' => 'bg-green-500'];
    }

    /**
     * Lógica de Categoria (simples, usa a primeira categoria de IA)
     */
    public function getCategoryDetails(): array
    {
        $category = optional($this->categorias->first());
        $slug = Str::slug($category->nome ?? 'padrao');

        $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75" />'; // Fallback
        $color = 'text-gray-500';
        $bgColor = 'bg-gray-100';

        // Lógica de ícones (simplificada e reutilizada do componente CategoryTag)
        if (Str::contains($slug, ['saude', 'saude-publica'])) { $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12H20M4 12c.5-1.5 2-4 8-4s7.5 2.5 8 4M4 12c.5 1.5 2 4 8 4s7.5-2.5 8-4" />'; $color = 'text-red-600'; $bgColor = 'bg-red-100'; }
        if (Str::contains($slug, ['urbanismo', 'obras'])) { $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1 1 0 01-1.414 0l-4.243-4.243a1 1 0 010-1.414l.707-.707a1 1 0 011.414 0l4.95 4.95a1 1 0 001.414 0l4.95-4.95a1 1 0 011.414 0l.707.707a1 1 0 010 1.414z" />'; $color = 'text-yellow-600'; $bgColor = 'bg-yellow-100'; }

        return ['icon_svg' => $icon, 'color_class' => $color, 'bg_color_class' => $bgColor, 'label' => $category->nome ?? 'Geral'];
    }

    //========= ESCOPOS (QUERY SCOPES) - Continuam os mesmos =========//
    public function scopeSearch(Builder $query, ?string $searchTerm): Builder { /* ... */ return $query; }
    public function scopeFilterBySituacao(Builder $query, ?array $situacoes): Builder { /* ... */ return $query; }
    public function scopeFilterByTipo(Builder $query, ?array $tipos): Builder { /* ... */ return $query; }
    public function scopeFilterByAno(Builder $query, ?string $ano): Builder { /* ... */ return $query; }
    public function scopeFilterByCategorySlug(Builder $query, ?string $categorySlug): Builder { /* ... */ return $query; }
}
