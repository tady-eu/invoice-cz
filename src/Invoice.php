<?php

namespace Cat\Invoice;

use Cat\Invoice\Model\Company;
use Cat\Invoice\Model\Item;

/**
 * Class Invoice
 * @package Cat\Invoice
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
            return $carry + ($item->getAmountWithoutVat() * $item->getQuantity());
        }, 0);
    }

    /**
     * @return float
     */
    public function getItemsPriceAmmount()
    {
        return array_reduce($this->items, function (float $carry, Item $item) {
            return $carry + ($item->getAmount() * $item->getQuantity());
        }, 0);
    }

    /**
     * @return bool
     */
    public function isVatPriced()
    {
        foreach ($this->items as $item) {
            if ($item->getAmountWithoutVat() !== null) {
                return true;
            }
        }
        return false;
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
    public function getTaxAt(): \DateTime
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