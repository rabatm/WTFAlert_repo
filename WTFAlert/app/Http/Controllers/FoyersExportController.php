<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Foyer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log; // log
use App\Mail\FoyersListePdf;
use Dompdf\Dompdf;
use Dompdf\Options;

class FoyersExportController extends Controller
{
    protected function buildPdfContent($foyers, $columns)
    {
        $html = view('pdf.foyers', compact('foyers','columns'))->render();
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return $dompdf->output();
    }

    public function pdf(Request $request)
    {
        try {
            $data = $request->validate([
                'foyer_ids' => 'required|array',
                'foyer_ids.*' => 'integer',
                'columns' => 'required|array'
            ]);
            $foyers = Foyer::with(['habitants.user','secteurs'])
                ->whereIn('id', $data['foyer_ids'])
                ->get();
            $pdfContent = $this->buildPdfContent($foyers, $data['columns']);
            $filename = 'liste_foyers_'.now()->format('Ymd_His').'.pdf';
            return response($pdfContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$filename.'"'
            ]);
        } catch (\Throwable $e) {
            Log::error('Export PDF erreur: '.$e->getMessage(), ['trace'=>$e->getTraceAsString()]);
            return response()->json(['ok'=>false,'message'=>'Erreur serveur PDF'], 500);
        }
    }

    public function email(Request $request)
    {
        try {
            $data = $request->validate([
                'foyer_ids' => 'required|array',
                'foyer_ids.*' => 'integer',
                'columns' => 'required|array',
                'emails' => 'required|string'
            ]);
            $emails = collect(preg_split('/[;,\s]+/', $data['emails']))
                ->filter(function($e){return filter_var($e, FILTER_VALIDATE_EMAIL);})->values();
            if ($emails->isEmpty()) {
                return response()->json(['ok'=>false,'message'=>'Aucun email valide'], 422);
            }
            $foyers = Foyer::with(['habitants.user','secteurs'])
                ->whereIn('id', $data['foyer_ids'])
                ->get();
            $pdfContent = $this->buildPdfContent($foyers, $data['columns']);
            $filename = 'liste_foyers_'.now()->format('Ymd_His').'.pdf';
            $emails->each(function($to) use ($pdfContent,$filename){
                Mail::to($to)->send(new FoyersListePdf($pdfContent, $filename));
            });
            return response()->json(['ok'=>true,'count'=>$emails->count()]);
        } catch (\Throwable $e) {
            Log::error('Export email PDF erreur: '.$e->getMessage(), ['trace'=>$e->getTraceAsString()]);
            return response()->json(['ok'=>false,'message'=>'Erreur serveur email'], 500);
        }
    }
}
