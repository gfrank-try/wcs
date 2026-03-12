<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="products")
 */
class Product
{
    /** @ORM\Id() @ORM\GeneratedValue() @ORM\Column(type="integer") */
    private ?int $id = null;

    /** @ORM\Column(type="string", length=255) */
    private string $name = '';

    /** @ORM\Column(type="string", length=255, nullable=true) */
    private ?string $shortname = null;

    /** @ORM\Column(type="text", nullable=true) */
    private ?string $description = null;

    /** @ORM\Column(type="json", nullable=true) */
    private ?array $ingredients = null;

    /** @ORM\Column(type="string", length=255, nullable=true) */
    private ?string $image = null;

    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    private string $price = '0.00';

    /**
     * @ORM\ManyToOne(targetEntity=Tax::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Tax $tax = null;

    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function setName(string $n): self { $this->name = $n; return $this; }
    public function getShortname(): ?string { return $this->shortname; }
    public function setShortname(?string $s): self { $this->shortname = $s; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $d): self { $this->description = $d; return $this; }
    public function getIngredients(): ?array { return $this->ingredients; }
    public function setIngredients(?array $i): self { $this->ingredients = $i; return $this; }
    public function getImage(): ?string { return $this->image; }
    public function setImage(?string $img): self { $this->image = $img; return $this; }
    public function getPrice(): string { return $this->price; }
    public function setPrice(string $p): self { $this->price = $p; return $this; }
    public function getTax(): ?Tax { return $this->tax; }
    public function setTax(?Tax $t): self { $this->tax = $t; return $this; }
}
