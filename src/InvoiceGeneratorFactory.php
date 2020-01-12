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

    /** @var string */
    protected $templateTwigFilePath;

    /**
     * InvoiceFactory constructor.
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(string $tempDirPath, string $templateTwigFilePath = null)
    {
        $this->tempDir = $tempDirPath;
        $this->templateTwigFilePath = $templateTwigFilePath;
    }

    /**
     * @return InvoiceGenerator
     */
    public function create()
    {
        $generator = new InvoiceGenerator($this->tempDir);
        if($this->templateTwigFilePath){
            $generator->setTemplateFile($this->templateTwigFilePath);
        }
        return $generator;
    }
}