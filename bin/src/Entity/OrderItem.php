<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="order_items")
 */
class OrderItem
{
    /** @ORM\Id() @ORM\GeneratedValue() @ORM\Column(type="integer") */
    private ?int $id = null;

    /** @ORM\ManyToOne(targetEntity=Product::class) @ORM\JoinColumn(nullable=false) */
    private Product $product;

    /** @ORM\ManyToOne(targetEntity=Order::class, inversedBy="items") @ORM\JoinColumn(nullable=false) */
    private Order $order;

    /** @ORM\Column(type="integer") */
    private int $quantity = 1;

    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    private string $price = '0.00';

    public function getId(): ?int { return $this->id; }
    public function getProduct(): Product { return $this->product; }
    public function setProduct(Product $p): self { $this->product = $p; return $this; }
    public function getOrder(): Order { return $this->order; }
    public function setOrder(Order $o): self { $this->order = $o; return $this; }
    public function getQuantity(): int { return $this->quantity; }
    public function setQuantity(int $q): self { $this->quantity = $q; return $this; }
    public function getPrice(): string { return $this->price; }
    public function setPrice(string $p): self { $this->price = $p; return $this; }
}
