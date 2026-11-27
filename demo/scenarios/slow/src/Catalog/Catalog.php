<?php

namespace App\Catalog;

/**
 * Product catalog. Generated here instead of a database to keep the example small.
 */
class Catalog
{
    private const CATEGORIES = ['coffee' => 460, 'tea' => 14, 'accessories' => 36];
    private const WORDS = ['Arabica', 'Robusta', 'Dark', 'Light', 'Single Origin', 'Blend', 'Organic', 'Decaf', 'House', 'Espresso', 'Filter', 'Green', 'Black', 'Earl', 'Chai', 'Cup', 'Grinder', 'Kettle', 'Filter Paper', 'Scale'];

    private ?array $products = null;

    public function all(): array
    {
        if (null === $this->products) {
            mt_srand(42);
            $id = 1;
            foreach (self::CATEGORIES as $category => $count) {
                for ($i = 0; $i < $count; ++$i) {
                    $name = ucfirst($category).' '.self::WORDS[mt_rand(0, 19)].' '.self::WORDS[mt_rand(0, 19)].' No. '.$i;
                    $this->products[$id] = ['id' => $id, 'name' => $name, 'category' => $category, 'price' => mt_rand(400, 4000) / 100];
                    ++$id;
                }
            }
        }

        return $this->products;
    }

    public function get(int $id): ?array
    {
        return $this->all()[$id] ?? null;
    }

    public function inCategory(string $category): array
    {
        return array_values(array_filter($this->all(), fn ($p) => $p['category'] === $category));
    }

    public function search(string $term): array
    {
        return array_values(array_filter($this->all(), fn ($p) => false !== stripos($p['name'], $term)));
    }
}
