<?php

namespace TadyEu\Invoice;

use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;
use rikudou\CzQrPayment\QrPayment;
use Twig\Environment;
use Twig\TwigFilter;

/**
 * Class InvoiceGenerator
 * @package TadyEu\InvoiceGenerator
 *
 * @require Mpdf (https://mpdf.github.io/)
 * @require QrPayment (https://github.com/RikudouSage/QrPaymentCZ)
 * @require endroid/qr-code (https://github.com/endroid/qr-code)
 */
class InvoiceGenerator
{
    /** @var string */
    protected $tempDir;

    /**
     * @var string Absolute path to template file (must be twig file)
     */
    protected $templateFile = __DIR__ . '/Template/Default/pdf.html.twig';

    /** @var Environment */
    protected $twig;

    /** @var callable */
    protected $currencyFormatterCallback;

    /** @var array Optional */
    protected $mpdfOptions;

    public function __construct(string $tempDir, array $mpdfOptions = [])
    {
        $this->mpdfOptions = $mpdfOptions;
        $this->currencyFormatterCallback = [$this, 'formatCurrencyDefaultCallback'];
        $this->tempDir = $tempDir;
    }

    /**
     * @return Environment
     */
    protected function getTwig()
    {
        $loader = new \Twig\Loader\FilesystemLoader($this->getTemplateDirPath());
        $twig = new \Twig\Environment($loader);
        $twig->addFilter(new TwigFilter('money', [$this, 'formatCurrency']));

        return $twig;
    }

    protected function getTemplateDirPath()
    {
        return dirname($this->getTemplateFile()) . '/';
    }

    /**
     * @return string
     */
    public function getTemplateFile(): string
    {
        return $this->templateFile;
    }

    /**
     * @param string $templateFile
     * @return InvoiceGenerator
     */
    public function setTemplateFile(string $templateFile): InvoiceGenerator
    {
        $this->templateFile = $templateFile;
        return $this;
    }

    /**
     * @param $str
     * @return string
     */
    public function formatCurrencyDefaultCallback($str)
    {
        if (!$str) $str = 0;
        $number = round($str, 0, PHP_ROUND_HALF_DOWN);
        return number_format($number, 0, ",", " ") . " Kč";
    }

    /**
     * @param Invoice $invoice
     * @return QrPayment
     * @throws \rikudou\CzQrPayment\QrPaymentException
     */
    public function generateQRCode(Invoice $invoice)
    {
        $explodedBankAccount = explode('/', $invoice->getSupplier()->getBankAccount());
        $bankCode = array_pop($explodedBankAccount);
        $qrPayment = new QrPayment($invoice->getSupplier()->getBankAccount(), $bankCode);
        if($invoice->getVs()){
            $qrPayment->setVariableSymbol($invoice->getVs());
        }
        $qrPayment->setAmount($invoice->getItemsPriceAmmount());
        $qrPayment->setCurrency('CZK');
        $qrPayment->setComment('Faktura ' . $invoice->getNumber());
        return $qrPayment;
    }

    /**
     * @param $str
     * @return mixed
     */
    public function formatCurrency($str)
    {
        return call_user_func($this->currencyFormatterCallback, $str);
    }

    public function generatePdf(Invoice $invoice)
    {
        $twig = $this->getTwig();

        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $defaultMpdfOptions = [
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 20,
            'margin_bottom' => 20,
            'margin_header' => 10,
            'margin_footer' => 10,
            'fontDir' => array_merge($fontDirs, [
                $this->getTemplateDirPath() . '/fonts',
            ]),
            'fontdata' => $fontData + [
                    'antonio' => [
                        'R' => 'Antonio-Regular.ttf'
                    ]
                ],
            'default_font' => 'antonio',
            'format' => 'A4',
            'tempDir' => $this->tempDir
        ];

        $mpdf = new Mpdf(array_merge($defaultMpdfOptions, $this->mpdfOptions));
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->showImageErrors = true;
        $mpdf->SetBasePath($this->getTemplateDirPath());
        $template = $twig->load(basename($this->getTemplateFile()));
        
        $rendered = $template->render([
            'customer' => $invoice->getCustomer(),
            'supplier' => $invoice->getSupplier(),
            'items' => $invoice->getItems(),
            'amountWithoutVat' => $invoice->getItemsPriceAmmountWithoutVat(),
            'amount' => $invoice->getItemsPriceAmmount(),
            'isVatPriced' => $invoice->isVatPriced(),
            'vatMap' => $invoice->getVatMap(),
            'issuedAt' => $invoice->getIssuedAt(),
            'taxAt' => $invoice->getTaxAt(),
            'dueAt' => $invoice->getDueAt(),
            'vs' => $invoice->getVs(),
            'number' => $invoice->getNumber(),
            'qrImage' => $this->generateQRCode($invoice)->getQrImage()->writeDataUri()
        ]);
        $mpdf->WriteHTML($rendered);
        $mpdf->Output();
    }
}