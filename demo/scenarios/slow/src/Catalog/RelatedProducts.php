<?php

namespace App\Catalog;

/**
 * "Customers also looked at": the products of the same category with the most similar names.
 */
class RelatedProducts
{
    public function __construct(private readonly Catalog $catalog)
    {
    }

    public function for(array $product, int $limit = 4): array
    {
        $candidates = array_filter($this->catalog->inCategory($product['category']), fn ($p) => $p['id'] !== $product['id']);

        $scores = [];
        foreach ($candidates as $candidate) {
            $score = 0;
            // compare against every product of the category to find the "cluster" of the candidate
            foreach ($candidates as $other) {
                similar_text($candidate['name'], $other['name'], $percent);
                $score += $percent * similar_text($product['name'], $other['name']);
            }
            $scores[$candidate['id']] = $score;
        }
        arsort($scores);

        return array_map(fn ($id) => $this->catalog->get($id), array_slice(array_keys($scores), 0, $limit));
    }
}
