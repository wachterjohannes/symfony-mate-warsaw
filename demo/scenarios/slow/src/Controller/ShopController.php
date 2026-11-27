<?php

namespace App\Controller;

use App\Catalog\Catalog;
use App\Catalog\RelatedProducts;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShopController extends AbstractController
{
    public function __construct(private readonly Catalog $catalog)
    {
    }

    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('home.html.twig', ['categories' => ['coffee', 'tea', 'accessories']]);
    }

    #[Route('/category/{slug}', name: 'category')]
    public function category(string $slug): Response
    {
        return $this->render('category.html.twig', ['slug' => $slug, 'products' => $this->catalog->inCategory($slug)]);
    }

    #[Route('/product/{id}', name: 'product', requirements: ['id' => '\d+'])]
    public function product(int $id, RelatedProducts $related): Response
    {
        $product = $this->catalog->get($id) ?? throw $this->createNotFoundException();

        return $this->render('product.html.twig', ['product' => $product, 'related' => $related->for($product)]);
    }

    #[Route('/search', name: 'search')]
    public function search(Request $request): Response
    {
        $q = (string) $request->query->get('q', '');

        return $this->render('category.html.twig', ['slug' => 'Search: '.$q, 'products' => '' === $q ? [] : $this->catalog->search($q)]);
    }

    #[Route('/cart', name: 'cart')]
    public function cart(Request $request): Response
    {
        $items = $request->getSession()->get('cart', []);

        return $this->render('cart.html.twig', ['items' => array_map(fn ($id) => $this->catalog->get($id), $items)]);
    }
}
