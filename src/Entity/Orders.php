<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\OrdersRepository", repositoryClass=OrdersRepository::class)
 */
class Orders
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var string|null
     * @ORM\Column(type="string",length=8)
     */
    private $orderCode;
    /**
     * @var string|null
     * @ORM\Column(type="text",length=500)
     */
    private $address;
    /**
     * @var Products|null
     * @ORM\ManyToOne (targetEntity="Products")
     */
    private $product;
    /**
     * @var integer|null
     * @ORM\Column(type="integer")
     */
    private $quantity;
    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $shippingDate;
    /**
     * @var User|null
     * @ORM\ManyToOne  (targetEntity="User")
     */
    private $user;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getOrderCode(): ?string
    {
        return $this->orderCode;
    }

    /**
     * @param string|null $orderCode
     */
    public function setOrderCode(?string $orderCode): void
    {
        $this->orderCode = $orderCode;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     */
    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return Products[]|Collection
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Products[]|Collection $product
     */
    public function setProduct($product): void
    {
        $this->product = $product;
    }

    /**
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int|null $quantity
     */
    public function setQuantity(?int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return \DateTime|null
     */
    public function getShippingDate(): ?\DateTime
    {
        return $this->shippingDate;
    }

    /**
     * @param \DateTime|null $shippingDate
     */
    public function setShippingDate(?\DateTime $shippingDate): void
    {
        $this->shippingDate = $shippingDate;
    }


}
