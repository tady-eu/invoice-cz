<?php

namespace TadyEu\Invoice\Model;

class Company
{
    /** @var string */
    protected $name;

    /** @var string|null */
    protected $ico, $dic;

    /** @var string|null */
    protected $bankAccount, $iban, $swift;

    /** @var string */
    protected $city, $address, $postal;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Company
     */
    public function setName(string $name): Company
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIco(): ?string
    {
        return $this->ico;
    }

    /**
     * @param string|null $ico
     * @return Company
     */
    public function setIco(?string $ico): Company
    {
        $this->ico = $ico;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDic(): ?string
    {
        return $this->dic;
    }

    /**
     * @param string|null $dic
     * @return Company
     */
    public function setDic(?string $dic): Company
    {
        $this->dic = $dic;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBankAccount(): ?string
    {
        return $this->bankAccount;
    }

    /**
     * @param string|null $bankAccount
     * @return Company
     */
    public function setBankAccount(?string $bankAccount): Company
    {
        $this->bankAccount = $bankAccount;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIban(): ?string
    {
        return $this->iban;
    }

    /**
     * @param string|null $iban
     * @return Company
     */
    public function setIban(?string $iban): Company
    {
        $this->iban = $iban;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSwift(): ?string
    {
        return $this->swift;
    }

    /**
     * @param string|null $swift
     * @return Company
     */
    public function setSwift(?string $swift): Company
    {
        $this->swift = $swift;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return Company
     */
    public function setCity(string $city): Company
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return Company
     */
    public function setAddress(string $address): Company
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostal(): string
    {
        return $this->postal;
    }

    /**
     * @param string $postal
     * @return Company
     */
    public function setPostal(string $postal): Company
    {
        $this->postal = $postal;
        return $this;
    }
}