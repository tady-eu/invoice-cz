<?php

namespace Cat\Invoice;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Environment;

/**
 * Class InvoiceGeneratorFactory
 * @package Cat\Invoice
 * @require Mpdf
 */
class InvoiceGeneratorFactory
{
    /** @var string */
    protected $tempDir;

    /**
     * InvoiceFactory constructor.
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->tempDir = $parameterBag->get('kernel.cache_dir');
    }

    /**
     * @return InvoiceGenerator
     */
    public function create()
    {
        return new InvoiceGenerator($this->tempDir);
    }
}