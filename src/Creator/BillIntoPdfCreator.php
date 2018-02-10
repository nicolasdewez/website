<?php

namespace App\Creator;

use App\Entity\Bill;
use Knp\Snappy\Pdf;
use Twig\Environment as Twig;

class BillIntoPdfCreator
{
    /** @var Pdf */
    private $pdf;

    /** @var Twig */
    private $twig;

    /** @var string */
    private $directory;

    /** @var string */
    private $SIREN;

    public function __construct(Pdf $pdf, Twig $twig, string $SIREN, string $directory)
    {
        $this->pdf = $pdf;
        $this->twig = $twig;
        $this->directory = $directory;
        $this->SIREN = $SIREN;
    }

    public function execute(Bill $bill): string
    {
        $path = sprintf('%s/%s.pdf', $this->directory, $bill->getCode());

        $this->pdf->generateFromHtml(
            $this->twig->render(
                'admin/bill/pdf.html.twig',
                [
                    'bill' => $bill,
                    'SIREN' => $this->SIREN,
                ]
            ),
            $path,
            [],
            true
        );

        return $path;
    }

    public function clean(string $path): void
    {
        if (!file_exists($path)) {
            return;
        }

        unlink($path);
    }
}
