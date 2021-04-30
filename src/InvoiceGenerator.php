<?php

namespace TadyEu\Invoice;

use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Nette\Utils\Strings;
use rikudou\CzQrPayment\QrPayment;
use Twig\Environment;
use Twig\Extra\Intl\IntlExtension;
use Twig\TwigFilter;

/**
 * Class InvoiceGenerator.
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
    protected $templateFile = __DIR__.'/Template/Default/pdf.html.twig';

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

    public function getTemplateFile(): string
    {
        return $this->templateFile;
    }

    public function setTemplateFile(string $templateFile): InvoiceGenerator
    {
        $this->templateFile = $templateFile;

        return $this;
    }

    /**
     * @param $str
     *
     * @return string
     */
    public function formatCurrencyDefaultCallback($str)
    {
        if (!$str) {
            $str = 0;
        }
        $number = round($str, 0, PHP_ROUND_HALF_DOWN);

        return number_format($number, 0, ',', ' ').' Kč';
    }

    /**
     * @throws \rikudou\CzQrPayment\QrPaymentException
     *
     * @return QrPayment
     */
    public function generateQRCode(Invoice $invoice)
    {
        $iban = new \IBAN($invoice->getSupplier()->getBankAccount());
        $qrPayment = new QrPayment($iban->Account(), $iban->Bank());

        $qrPayment->setInternalId($invoice->getVs());
        $qrPayment->setVariableSymbol($invoice->getVs());
        $qrPayment->setAmount($invoice->getItemsPriceAmmount());
        $qrPayment->setCurrency('CZK');
        $qrPayment->setComment('Faktura '.$invoice->getNumber());

        return $qrPayment;
    }

    /**
     * @param $str
     *
     * @return mixed
     */
    public function formatCurrency($str)
    {
        return call_user_func($this->currencyFormatterCallback, $str);
    }

    public function ibanToAccountNumber($iban)
    {
        $iban = new \IBAN($iban);

        return $iban->Account().'/'.$iban->Bank();
    }

    public function generatePdf(Invoice $invoice, $output = Destination::INLINE)
    {
        $twig = $this->getTwig();

        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $defaultMpdfOptions = [
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_footer' => 10,
            'dpi' => 300,
            'img_dpi' => 300,
            'fontDir' => array_merge($fontDirs, [
                $this->getTemplateDirPath().'/fonts',
            ]),
            'fontdata' => $fontData
                + [
                    'futura-book' => [
                        'R' => 'Futura-Boo.ttf',
                    ],
                    'futura-demi' => [
                        'R' => 'Futura-Dem.ttf',
                    ],
                    'futura-extbold' => [
                        'R' => 'Futura-ExtBol.ttf',
                    ],
                ],
            'default_font' => 'futura-book',
            'format' => 'A4',
            'tempDir' => $this->tempDir,
        ];

        $mpdf = new Mpdf(array_merge($defaultMpdfOptions, $this->mpdfOptions));

        $mpdf->SetDisplayMode('fullpage');
        $mpdf->showImageErrors = true;
        $mpdf->SetBasePath($this->getTemplateDirPath());
        $twig->addExtension(new IntlExtension());
        $template = $twig->load(basename($this->getTemplateFile()));

        $qrImage = $this->generateQRCode($invoice)->getQrImage();
        $qrImage->setWriterByName('svg');
        $qrImage->setWriterOptions(['exclude_xml_declaration' => true]);
        $qrImage->setSize(1450);

        $data = [
            'canceled' => $invoice->isCanceled(),
            'quantity' => $invoice->getItemsTotalQuantity(),
            'customer' => $invoice->getCustomer(),
            'supplier' => $invoice->getSupplier(),
            'items' => $invoice->getItems(),
            'amountWithoutVat' => $invoice->getItemsPriceAmmountWithoutVat(),
            'amount' => $invoice->getItemsPriceAmmount(),
            'vatMap' => $invoice->getVatMap(),
            'issuedAt' => $invoice->getIssuedAt(),
            'taxAt' => $invoice->getTaxAt(),
            'dueAt' => $invoice->getDueAt(),
            'vs' => $invoice->getVs(),
            'number' => $invoice->getNumber(),
            'qrImage' => $qrImage->writeString(),
        ];

        $rendered = $template->render($data);
        $mpdf->WriteHTML($rendered);

        $number = $invoice->getVs() ?: $invoice->getNumber();
        $subjectNameSafe = Strings::webalize($invoice->getCustomer()->getName());
        $filename = $number.'-'.$subjectNameSafe;

        return ['filename' => $filename, 'data' => $mpdf->Output($filename.'.pdf', $output)];
    }

    /**
     * @return Environment
     */
    protected function getTwig()
    {
        $loader = new \Twig\Loader\FilesystemLoader($this->getTemplateDirPath());
        $twig = new \Twig\Environment($loader);
        $twig->addFilter(new TwigFilter('money', [$this, 'formatCurrency']));
        $twig->addFilter(new TwigFilter('ibanToAccountNumber', [$this, 'ibanToAccountNumber']));

        return $twig;
    }

    protected function getTemplateDirPath()
    {
        return dirname($this->getTemplateFile()).'/';
    }
}
