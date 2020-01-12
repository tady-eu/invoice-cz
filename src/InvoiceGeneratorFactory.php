<?php

namespace TadyEu\Invoice;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Environment;

/**
 * Class InvoiceGeneratorFactory
 * @package TadyEu\Invoice
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
    public function __construct(string $tempDirPath)
    {
        $this->tempDir = $tempDirPath;
    }

    /**
     * @return InvoiceGenerator
     */
    public function create()
    {
        return new InvoiceGenerator($this->tempDir);
    }
}