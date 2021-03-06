<?php

namespace TadyEu\Invoice;

use TadyEu\Invoice\Model\Company;
use TadyEu\Invoice\Model\Item;

/**
 * Class Invoice
 * @package TadyEu\Invoice
 * @require Mpdf
 */
class Invoice
{
    /** @var Company */
    protected $supplier, $customer;

    /** @var Item[] */
    protected $items;

    /** @var \DateTime */
    protected $issuedAt, $taxAt, $dueAt;

    /** @var string|null */
    protected $number, $vs;

    /** @var boolean */
    protected $canceled = false;

    /**
     * @return Company
     */
    public function getSupplier(): Company
    {
        return $this->supplier;
    }

    /**
     * @param Company $supplier
     * @return Invoice
     */
    public function setSupplier(Company $supplier): Invoice
    {
        $this->supplier = $supplier;
        return $this;
    }

    /**
     * @return Company
     */
    public function getCustomer(): Company
    {
        return $this->customer;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @param string $number
     * @return Invoice
     */
    public function setNumber(string $number): Invoice
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVs(): ?string
    {
        return $this->vs;
    }

    /**
     * @param string|null $vs
     * @return Invoice
     */
    public function setVs(?string $vs): Invoice
    {
        $this->vs = $vs;
        return $this;
    }

    /**
     * @param Company $customer
     * @return Invoice
     */
    public function setCustomer(Company $customer): Invoice
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * @return Item[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param Item[] $items
     * @return Invoice
     */
    public function setItems(array $items): Invoice
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @return float
     */
    public function getItemsPriceAmmountWithoutVat()
    {
        return array_reduce($this->items, function (float $carry, Item $item) {
            return $carry + ($item->getAmountWithoutVat());
        }, 0);
    }

    /**
     * @return float
     */
    public function getItemsPriceAmmount()
    {
        return array_reduce($this->items, function (float $carry, Item $item) {
            return $carry + ($item->getAmount());
        }, 0);
    }

    /**
     * @return int
     */
    public function getItemsTotalQuantity()
    {
        return array_reduce($this->items, function (float $carry, Item $item) {
            return $carry + ($item->getQuantity());
        }, 0);
    }

    /**
     * @return \DateTime
     */
    public function getIssuedAt(): \DateTime
    {
        return $this->issuedAt;
    }

    /**
     * @param \DateTime $issuedAt
     * @return Invoice
     */
    public function setIssuedAt(\DateTime $issuedAt): Invoice
    {
        $this->issuedAt = $issuedAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTaxAt(): ?\DateTime
    {
        return $this->taxAt;
    }

    /**
     * @param \DateTime $taxAt
     * @return Invoice
     */
    public function setTaxAt(\DateTime $taxAt): Invoice
    {
        $this->taxAt = $taxAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDueAt(): \DateTime
    {
        return $this->dueAt;
    }

    /**
     * @param \DateTime $dueAt
     * @return Invoice
     */
    public function setDueAt(\DateTime $dueAt): Invoice
    {
        $this->dueAt = $dueAt;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCanceled(): bool
    {
        return (bool)$this->canceled;
    }

    /**
     * @param bool $canceled
     * @return Invoice
     */
    public function setCanceled(bool $canceled): Invoice
    {
        $this->canceled = $canceled;
        return $this;
    }

    /**
     * @return array
     */
    public function getVatMap()
    {
        $map = [];
        foreach ($this->getItems() as $item) {
            if(!isset($map[$item->getVatRate()])){
                $map[$item->getVatRate()] = 0;
            }
            $map[$item->getVatRate()] += (($item->getAmount() - $item->getAmountWithoutVat()) * $item->getQuantity());
        }
        return $map;
    }
}