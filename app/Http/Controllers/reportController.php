<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Database;
use PDF; // Import the DomPDF facade
use Carbon\Carbon;


class ReportController extends Controller
{
    protected $database;

    public function __construct()
    {
        $this->database = app('firebase.database');
    }

    public function reports(Request $request)
    {
        // Fetch search, filter, and sex parameters
        $search = $request->get('search');
        $filter = $request->get('filter');
        $sex = $request->get('sex'); // Get the sex filter

        // Fetch all animalsData from Firebase
        $animalsData = $this->database->getReference('animalsData')->getValue();

        // Apply search and filter if provided
        if (!empty($search) && !empty($filter)) {
            $animalsData = array_filter($animalsData, function ($item) use ($search, $filter) {
                return isset($item[$filter]) && stripos($item[$filter], $search) !== false;
            });
        }

        // Apply sex filter if selected
        if (!empty($sex)) {
            $animalsData = array_filter($animalsData, function ($item) use ($sex) {
                return isset($item['sex']) && $item['sex'] === $sex;
            });
        }

        // Calculate the total number of filtered records
        $total_animalDatas = count($animalsData);

        return view('reports', compact('animalsData', 'total_animalDatas'));
    }

    public function generatePdf(Request $request)
    {
        // Fetch search, filter, and sex parameters
        $search = $request->get('search');
        $filter = $request->get('filter');
        $sex = $request->get('sex'); // Get the sex filter

        // Fetch all animalsData from Firebase
        $animalsData = $this->database->getReference('animalsData')->getValue();

        // Apply search and filter if provided
        if (!empty($search) && !empty($filter)) {
            $animalsData = array_filter($animalsData, function ($item) use ($search, $filter) {
                return isset($item[$filter]) && stripos($item[$filter], $search) !== false;
            });
        }

        // Apply sex filter if selected
        if (!empty($sex)) {
            $animalsData = array_filter($animalsData, function ($item) use ($sex) {
                return isset($item['sex']) && $item['sex'] === $sex;
            });
        }

        // Calculate the age for each record
        foreach ($animalsData as &$item) {
            if (isset($item['bdate'])) {
                $item['age'] = $this->calculateAge($item['bdate']);
            } else {
                $item['age'] = 'N/A'; // Handle cases where birthdate is missing
            }
        }

        // Calculate the total number of filtered records
        $total_animalDatas = count($animalsData);

        // Generate the PDF
        $pdf = PDF::loadView('reports-pdf', [
            'animalsData' => $animalsData,
            'total_animalDatas' => $total_animalDatas,
        ]);

        return $pdf->download('filtered-report.pdf');
    }

    private function calculateAge($birthDate)
    {
        try {
            // Parse the date using the correct format (DD-MM-YYYY)
            $birthDate = Carbon::createFromFormat('d-m-Y', $birthDate);
            $now = Carbon::now();

            // Calculate years, months, and days
            $years = $now->diffInYears($birthDate);
            $months = $now->diffInMonths($birthDate) % 12;
            $days = $now->diffInDays($birthDate->copy()->addYears($years)->addMonths($months));

            return "{$years} years, {$months} months, {$days} days";
        } catch (\Exception $e) {
            return 'Invalid date'; // Handle invalid dates gracefully
        }
    }

    public function deleteLog($key)
    {
        $firebase = app('firebase.database');
        $firebase->getReference('/activityLogs/' . $key)->remove();

        return response()->json(['success' => true]);
    }

    public function clearAllLogs()
    {
        $firebase = app('firebase.database');
        $firebase->getReference('/activityLogs')->remove();

        return response()->json(['success' => true]);
    }
}
