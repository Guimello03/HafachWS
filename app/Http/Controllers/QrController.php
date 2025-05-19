<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guardian;
use App\Models\DeviceGroup;
use App\Models\Student;
use App\Models\Functionary;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Barryvdh\Snappy\Facades\SnappyPdf; // ✅ Aqui trocamos do DomPDF pro Snappy

class QrController extends Controller
{
    public function preview(Request $request)
    {
        $school = activeSchool();

        $type = $request->get('type');
        $personId = $request->get('person_uuid');

        $people = collect();
        $types = [
            'student' => Student::class,
            'guardian' => Guardian::class,
            'functionary' => Functionary::class,
        ];

        if ($type && isset($types[$type])) {
            $model = $types[$type];
            $query = $model::where('school_id', $school->uuid);

            if ($personId) {
                $query->where('uuid', $personId);
            }

            $people = $query->get()->map(function ($p) {
                $qrData = $p->uuid;

                $result = Builder::create()
                    ->writer(new PngWriter())
                    ->data($qrData)
                    ->encoding(new Encoding('UTF-8'))
                    ->size(200)
                    ->margin(10)
                    ->build();

                $p->qr_base64 = base64_encode($result->getString());

                return $p;
            });
        }

        return view('school.partials.qr-preview', compact('people', 'type', 'personId'))->render();
    }

    public function downloadPdf(Request $request)
    {
        $school = activeSchool();

        $type = $request->input('type');
        $personId = $request->input('person_uuid', '');

        $people = collect();
        $types = [
            'student' => Student::class,
            'guardian' => Guardian::class,
            'functionary' => Functionary::class,
        ];

        if ($type && isset($types[$type])) {
            $model = $types[$type];
            $query = $model::where('school_id', $school->uuid);

            if (!empty($personId)) {
                $query->where('uuid', $personId);
            }

            $people = $query->get()->map(function ($p) {
                $qrData = $p->uuid;

                $result = Builder::create()
                    ->writer(new PngWriter())
                    ->data($qrData)
                    ->encoding(new Encoding('UTF-8'))
                    ->size(200)
                    ->margin(10)
                    ->build();

                $p->qr_base64 = base64_encode($result->getString());

                return $p;
            });
        }

        if ($people->isEmpty()) {
            return redirect()->back()->with('error', 'Nenhum usuário encontrado para gerar o PDF.');
        }

        // ✅ Usa o Snappy agora!
        $pdf = SnappyPdf::loadView('pdf.qr-codes', compact('people'));

        return $pdf->download("qr-codes-{$type}.pdf");
    }
}
