<?php

namespace App\Traits;

use Barryvdh\DomPDF\Facade\Pdf;

trait PdfReportDailyTrait
{
    /**
     * Generate a PDF for a transaction.
     *
     * @param  \App\Models\Transaction  $transaction
     * @param  string  $viewName
     * @param  string  $fileName
     * @return \Illuminate\Http\Response
     */
    public function generatePdf($transaction, $viewName, $fileName)
    {
        // Generate PDF using the specified view
        $pdf = Pdf::loadView($viewName, compact('transaction'));

        // Download PDF
        return $pdf->download($fileName);
    }
}
