<?php

namespace TadyEu\Invoice\Model;

class Company
{
    /** @var string */
    protected $name;

    /** @var null|string */
    protected $ico;
    protected $dic;

    /** @var null|string */
    protected $bankAccount;
    protected $iban;
    protected $swift;

    /** @var string */
    protected $city;
    protected $address;
    protected $postal;
    protected $country;

    /** @var null|string */
    protected $email;

    /** @var null|string */
    protected $logo;

    /** @var null|string */
    protected $url;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Company
    {
        $this->name = $name;

        return $this;
    }

    public function getIco(): ?string
    {
        return $this->ico;
    }

    public function setIco(?string $ico): Company
    {
        $this->ico = $ico;

        return $this;
    }

    public function getDic(): ?string
    {
        return $this->dic;
    }

    public function setDic(?string $dic): Company
    {
        $this->dic = $dic;

        return $this;
    }

    public function getBankAccount(): ?string
    {
        return $this->bankAccount;
    }

    public function setBankAccount(?string $bankAccount): Company
    {
        $this->bankAccount = $bankAccount;

        return $this;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(?string $iban): Company
    {
        $this->iban = $iban;

        return $this;
    }

    public function getSwift(): ?string
    {
        return $this->swift;
    }

    public function setSwift(?string $swift): Company
    {
        $this->swift = $swift;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): Company
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): Company
    {
        $this->address = $address;

        return $this;
    }

    public function getPostal(): string
    {
        return $this->postal;
    }

    public function setPostal(string $postal): Company
    {
        $this->postal = $postal;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): Company
    {
        $this->email = $email;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    /**
     * @return self
     */
    public function setLogo(?string $logo)
    {
        $this->logo = $logo;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @return self
     */
    public function setUrl(?string $url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @return self
     */
    public function setCountry(?string $country)
    {
        $this->country = $country;

        return $this;
    }
}
