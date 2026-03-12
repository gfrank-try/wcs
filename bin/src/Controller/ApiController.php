<?php
namespace App\Controller;

use App\Entity\Product;
use App\Entity\Order as OrderEntity;
use App\Entity\OrderItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/products", name="api_products", methods={"GET"})
     */
    public function products(EntityManagerInterface $em): JsonResponse
    {
        $repo = $em->getRepository(Product::class);
        $items = $repo->findAll();
        $data = array_map(function(Product $p) {
            return [
                'id' => $p->getId(),
                'name' => $p->getName(),
                'shortname' => $p->getShortname(),
                'description' => $p->getDescription(),
                'ingredients' => $p->getIngredients(),
                'price' => (string)$p->getPrice(),
                'image' => $p->getImage(),
                'tax' => $p->getTax() ? $p->getTax()->getPercent() : null,
            ];
        }, $items);

        return new JsonResponse($data);
    }

    /**
     * @Route("/api/products/{id}", name="api_product", methods={"GET"})
     */
    public function product(EntityManagerInterface $em, int $id): JsonResponse
    {
        $p = $em->getRepository(Product::class)->find($id);
        if (!$p) {
            return new JsonResponse(['error' => 'not found'], 404);
        }
        return new JsonResponse([
            'id' => $p->getId(),
            'name' => $p->getName(),
            'shortname' => $p->getShortname(),
            'description' => $p->getDescription(),
            'ingredients' => $p->getIngredients(),
            'price' => (string)$p->getPrice(),
            'image' => $p->getImage(),
            'tax' => $p->getTax() ? $p->getTax()->getPercent() : null,
        ]);
    }

    /**
     * Create an order.
     * Expects JSON: {"customer":{...}, "items":[{"product_id":1,"quantity":2}, ...]}
     * @Route("/api/orders", name="api_orders_create", methods={"POST"})
     */
    public function createOrder(Request $req, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($req->getContent(), true);
        if (!isset($data['customer']) || !isset($data['items']) || !is_array($data['items'])) {
            return new JsonResponse(['error' => 'invalid payload'], 400);
        }

        $cust = $data['customer'];
        $order = new OrderEntity();
        $order->setFirstname($cust['firstname'] ?? '');
        $order->setSurname($cust['surname'] ?? '');
        $order->setAddress($cust['address'] ?? '');
        $order->setZip($cust['zip'] ?? '');
        $order->setCity($cust['city'] ?? '');
        $order->setEmail($cust['email'] ?? '');
        $order->setPhone($cust['phone'] ?? '');

        foreach ($data['items'] as $it) {
            $product = $em->getRepository(Product::class)->find($it['product_id'] ?? 0);
            if (!$product) continue;
            $qty = max(1, (int)($it['quantity'] ?? 1));
            $oi = new OrderItem();
            $oi->setProduct($product);
            $oi->setQuantity($qty);
            $oi->setPrice((string)$product->getPrice());
            $order->addItem($oi);
            $em->persist($oi);
        }

        $em->persist($order);
        $em->flush();

        return new JsonResponse(['status' => 'ok', 'order_id' => $order->getId()], 201);
    }
}
