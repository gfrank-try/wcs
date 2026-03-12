<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="orders")
 */
class Order
{
    /** @ORM\Id() @ORM\GeneratedValue() @ORM\Column(type="integer") */
    private ?int $id = null;

    /** @ORM\Column(type="datetime") */
    private \DateTimeInterface $createdAt;

    /** @ORM\Column(type="string", length=255) */
    private string $firstname = '';
    /** @ORM\Column(type="string", length=255) */
    private string $surname = '';
    /** @ORM\Column(type="text") */
    private string $address = '';
    /** @ORM\Column(type="string", length=20) */
    private string $zip = '';
    /** @ORM\Column(type="string", length=255) */
    private string $city = '';
    /** @ORM\Column(type="string", length=255) */
    private string $email = '';
    /** @ORM\Column(type="string", length=50) */
    private string $phone = '';

    /** @ORM\OneToMany(targetEntity=OrderItem::class, mappedBy="order", cascade={"persist"}) */
    private Collection $items;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getCreatedAt(): \DateTimeInterface { return $this->createdAt; }
    public function getFirstname(): string { return $this->firstname; }
    public function setFirstname(string $v): self { $this->firstname = $v; return $this; }
    public function getSurname(): string { return $this->surname; }
    public function setSurname(string $v): self { $this->surname = $v; return $this; }
    public function getAddress(): string { return $this->address; }
    public function setAddress(string $v): self { $this->address = $v; return $this; }
    public function getZip(): string { return $this->zip; }
    public function setZip(string $v): self { $this->zip = $v; return $this; }
    public function getCity(): string { return $this->city; }
    public function setCity(string $v): self { $this->city = $v; return $this; }
    public function getEmail(): string { return $this->email; }
    public function setEmail(string $v): self { $this->email = $v; return $this; }
    public function getPhone(): string { return $this->phone; }
    public function setPhone(string $v): self { $this->phone = $v; return $this; }

    /** @return Collection|OrderItem[] */
    public function getItems(): Collection { return $this->items; }
    public function addItem(OrderItem $item): self { if (!$this->items->contains($item)) { $this->items[] = $item; $item->setOrder($this); } return $this; }
}
