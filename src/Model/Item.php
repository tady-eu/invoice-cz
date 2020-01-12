<?php

namespace TadyEu\Invoice\Model;

class Item
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $quantity = 1;

    /** @var float */
    protected $amount;

    /** @var float|null */
    protected $amountWithoutVat;

    /** @var integer|null */
    protected $vatRate;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Item
     */
    public function setName(string $name): Item
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getQuantity(): string
    {
        return $this->quantity;
    }

    /**
     * @param string $quantity
     * @return Item
     */
    public function setQuantity(string $quantity): Item
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return Item
     */
    public function setAmount(float $amount): Item
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getAmountWithoutVat(): ?float
    {
        return $this->amountWithoutVat;
    }

    /**
     * @param float|null $amountWithoutVat
     * @return Item
     */
    public function setAmountWithoutVat(?float $amountWithoutVat): Item
    {
        $this->amountWithoutVat = $amountWithoutVat;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getVatRate(): ?int
    {
        return $this->vatRate;
    }

    /**
     * @param int|null $vatRate
     * @return Item
     */
    public function setVatRate(?int $vatRate): Item
    {
        $this->vatRate = $vatRate;
        return $this;
    }
}