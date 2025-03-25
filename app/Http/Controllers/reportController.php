<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Database;
use PDF; // Import the DomPDF facade
use Carbon\Carbon;
use Kreait\Firebase\Factory;


class ReportController extends Controller
{
    protected $database;

    public function __construct()
    {
        $this->database = app('firebase.database');
    }

    public function reports(Request $request)
    {
        // Retrieve filters from the request
        $livestockId = $request->get('animalid');
        $search = $request->get('search');
        $species = $request->get('species');
        $breed = $request->get('breed');
        $sex = $request->get('sex');
        $manager = $request->get('manager');
        $age = $request->get('age');

        // Fetch all animal data from Firebase
        $animalsData = $this->database->getReference('animalsData')->getValue();
        $total_animalDatas = $this->database->getReference('animalsData')->getSnapshot()->numChildren();

        // Ensure $animalsData is an array
        if (!$animalsData || !is_array($animalsData)) {
            $animalsData = []; // Default to an empty array if no data is retrieved
        }

        // Add age calculation to each record
        foreach ($animalsData as $key => &$item) {
            if (isset($item['bdate'])) {
                $item['age'] = $this->calculateAge($item['bdate']);
            } else {
                $item['age'] = 'N/A'; // Handle cases where birthdate is missing
            }
        }

        // Apply filters if data is not empty
        if (!empty($animalsData)) {
            $animalsData = collect($animalsData)->filter(function ($item) use ($livestockId, $search, $species, $breed, $sex, $manager, $age) {
                $matchesLivestockId = true;
                $matchesSpecies = true;
                $matchesBreed = true;
                $matchesSearch = true;
                $matchesSex = true;
                $matchesManager = true;
                $matchesAge = true;

                // Filter by Livestock ID
                if (!empty($livestockId)) {
                    $matchesLivestockId = isset($item['animalid']) && stripos($item['animalid'], $livestockId) !== false;
                }

                // Filter by species
                if (!empty($species)) {
                    $matchesSpecies = isset($item['species']) && $item['species'] === $species;
                }

                // Filter by breed
                if (!empty($breed)) {
                    $matchesBreed = isset($item['breed']) && $item['breed'] === $breed;
                }

                // Apply general search
                if (!empty($search)) {
                    $matchesSearch = stripos($item['animalid'] ?? '', $search) !== false ||
                        stripos($item['species'] ?? '', $search) !== false ||
                        stripos($item['breed'] ?? '', $search) !== false;
                }

                // Filter by sex
                if (!empty($sex)) {
                    $matchesSex = isset($item['sex']) && $item['sex'] === $sex;
                }

                // Filter by manager
                if (!empty($manager)) {
                    $matchesManager = stripos($item['mname'] ?? '', $manager) !== false;
                }

                // Filter by age
                if (!empty($age)) {
                    if ($age == 1) {
                        $matchesAge = isset($item['age']) && $item['age'] > 1;
                    } elseif ($age == 2) {
                        $matchesAge = isset($item['age']) && $item['age'] > 2;
                    }
                }

                return $matchesLivestockId && $matchesSpecies && $matchesBreed && $matchesSearch && $matchesSex && $matchesManager && $matchesAge;
            });

            // Sort the data by 'animalid' in ascending order
            $animalsData = $animalsData->sortBy(function ($item) {
                return $item['animalid'];
            })->toArray();

            // dd($animalsData);
        }

        // Return the filtered data to the view
        return view('reports', compact('animalsData', 'total_animalDatas'));
    }


    public function generatePdf(Request $request)
    {
        // Retrieve filters from the request
        $search = $request->get('search');
        $species = $request->get('species');
        $breed = $request->get('breed');
        $sex = $request->get('sex');
        $manager = $request->get('manager');
        $age = $request->get('age');

        // Fetch all animalsData from Firebase
        $animalsData = $this->database->getReference('animalsData')->getValue();

        // Ensure $animalsData is an array
        if (!$animalsData || !is_array($animalsData)) {
            $animalsData = []; // Default to an empty array if no data is retrieved
        }

        // Add age calculation to each record
        foreach ($animalsData as &$item) {
            if (isset($item['bdate'])) {
                $item['age'] = $this->calculateAge($item['bdate']);
            } else {
                $item['age'] = 'N/A'; // Handle cases where birthdate is missing
            }
        }

        // Apply filters
        if (!empty($animalsData)) {
            $animalsData = collect($animalsData)->filter(function ($item) use ($search, $species, $breed, $sex, $manager, $age) {
                $matchesSpecies = true;
                $matchesBreed = true;
                $matchesSearch = true;
                $matchesSex = true;
                $matchesManager = true;
                $matchesAge = true;

                // Filter by species
                if (!empty($species)) {
                    $matchesSpecies = isset($item['species']) && $item['species'] === $species;
                }

                // Filter by breed
                if (!empty($breed)) {
                    $matchesBreed = isset($item['breed']) && $item['breed'] === $breed;
                }

                // Apply general search
                if (!empty($search)) {
                    $matchesSearch = stripos($item['animalid'] ?? '', $search) !== false ||
                        stripos($item['species'] ?? '', $search) !== false ||
                        stripos($item['breed'] ?? '', $search) !== false;
                }

                // Filter by sex
                if (!empty($sex)) {
                    $matchesSex = isset($item['sex']) && $item['sex'] === $sex;
                }

                // Filter by manager
                if (!empty($manager)) {
                    $matchesManager = stripos($item['mname'] ?? '', $manager) !== false;
                }

                // Filter by age
                if (!empty($age)) {
                    if ($age == 1) {
                        $matchesAge = isset($item['age']) && $item['age'] > 1;
                    } elseif ($age == 2) {
                        $matchesAge = isset($item['age']) && $item['age'] > 2;
                    }
                }

                return $matchesSpecies && $matchesBreed && $matchesSearch && $matchesSex && $matchesManager && $matchesAge;
            })->toArray();
        }

        // Calculate the total number of filtered records
        $total_animalDatas = count($animalsData);

        // Generate the PDF
        $pdf = PDF::loadView('reports-pdf', [
            'animalsData' => $animalsData,
            'total_animalDatas' => $total_animalDatas,
        ]);

        // Download the generated PDF
        return $pdf->download('livestock report.pdf');
    }

    public function generateIndividualPdf(Request $request, $animalId)
    {
        // Fetch all animalsData from Firebase
        $animalsData = $this->database->getReference('animalsData')->getValue();

        // Ensure $animalsData is an array
        if (!isset($animalsData) || !is_array($animalsData)) {
            return response()->json(['error' => 'Animal not found'], 404);
        }

        // Find the specific animal by animalId
        $animal = collect($animalsData)->firstWhere('animalid', $animalId);

        if (!$animal) {
            return response()->json(['error' => 'Animal not found'], 404);
        }

        // Calculate age
        if (isset($animal['bdate'])) {
            $animal['age'] = $this->calculateAge($animal['bdate']);
        } else {
            $animal['age'] = 'N/A'; // Handle missing birthdate
        }

        // Checkup history
        $checkupHistory = isset($animal['checkup']) ? $animal['checkup'] : [];

        // Fix sorting and filtering logic
        if (!empty($checkupHistory)) {
            $checkupHistory = collect($checkupHistory)
                ->sortByDesc(fn($checkup) => $checkup['examined_at'] ?? '0000-00-00') // Sort by descending date
                ->take(7) // Take the 7 most recent entries
                ->sortBy(fn($checkup) => $checkup['examined_at'] ?? '9999-99-99') // Re-sort to ascending order
                ->map(function ($checkup) {
                    // Format 'examined_at' if it exists
                    if (isset($checkup['examined_at'])) {
                        $checkup['examined_at'] = \Carbon\Carbon::parse($checkup['examined_at'])->format('d-m-y');
                    }
                    return $checkup;
                })
                ->values() // Reindex to maintain proper order
                ->toArray();
        }

        // Generate the PDF
        $pdf = PDF::loadView('individual-report-pdf', [
            'animal' => $animal,
            'checkupHistory' => $checkupHistory,
        ]);

        return $pdf->download("Livestock-{$animalId}-report.pdf");
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
