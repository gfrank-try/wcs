<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="tax")
 */
class Tax
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /** @ORM\Column(type="integer") */
    private int $percent = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPercent(): int
    {
        return $this->percent;
    }

    public function setPercent(int $percent): self
    {
        $this->percent = $percent;
        return $this;
    }
}
