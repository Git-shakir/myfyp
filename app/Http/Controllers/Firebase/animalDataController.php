<?php
//myproject\app\Http\Controllers\Firebase\animalDataController.php
namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use Carbon\Carbon;





class animalDataController extends Controller
{
    //protected $database;
    //protected $tablename;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tablename =  'animalsData';
    }

    public function index()
    {
        // Fetch data from Firebase
        $animalsData = $this->database->getReference($this->tablename)->getValue();
        $total_animalDatas = $this->database->getReference($this->tablename)->getSnapshot()->numChildren();

        // Add dynamically calculated age to each record
        foreach ($animalsData as $key => &$item) {
            if (isset($item['bdate'])) {
                $item['age'] = $this->calculateAge($item['bdate']);
            } else {
                $item['age'] = 'N/A'; // Handle cases where birthdate is missing
            }
        }

        return view('firebase.animalData.index', compact('animalsData', 'total_animalDatas'));
    }

    /**
     * Calculate the age based on the birthdate (DD-MM-YYYY format).
     *
     * @param string $birthDate
     * @return string
     */
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




    public function showAssignments()
    {
        // Fetch all animals from Firebase
        $animalsData = $this->database->getReference('animalsData')->getValue();

        return view('firebase.animalData.index', compact('animalsData'));
    }

    public function listenForNewTags()
    {
        $uidLogs = $this->database->getReference('/uidLogs')->getValue();

        foreach ($uidLogs as $key => $log) {
            $uid = $log['uid'];

            // Skip already processed UIDs
            if (isset($log['processed']) && $log['processed'] === true) {
                continue;
            }

            // Check if UID exists in animalsData
            $existingAnimal = $this->database->getReference('animalsData')
                ->orderByChild('uid')
                ->equalTo($uid)
                ->getSnapshot()
                ->getValue();

            if (!empty($existingAnimal)) {
                // Existing UID: Set the trigger for editing
                $livestockUid = array_key_first($existingAnimal);
                $this->database->getReference('/triggers/edit_uid')->set($livestockUid);

                // Mark the UID as processed
                $this->database->getReference('/uidLogs/' . $key)->update(['processed' => true]);
            } else {
                // New UID: Set the trigger for adding
                $this->database->getReference('/triggers/new_uid')->set($uid);

                // Mark the UID as processed
                $this->database->getReference('/uidLogs/' . $key)->update(['processed' => true]);
            }
        }
    }


    // public function index()
    // {
    //     $animalsData = $this->database->getReference($this->tablename)->getValue();
    //     $total_animalDatas = $reference = $this->database->getReference($this->tablename)->getSnapshot()->numChildren();

    //     return view('firebase.animalData.index', compact('animalsData', 'total_animalDatas'));
    // }

    public function reports(Request $request)
    {
        $search = $request->get('search');
        $filter = $request->get('filter');

        // Fetch all animal data
        $animalsData = $this->database->getReference($this->tablename)->getValue();
        $total_animalDatas = $this->database->getReference($this->tablename)->getSnapshot()->numChildren();

        foreach ($animalsData as $key => &$item) {
            if (isset($item['bdate'])) {
                $item['age'] = $this->calculateAge($item['bdate']);
            } else {
                $item['age'] = 'N/A'; // Handle cases where birthdate is missing
            }
        }

        if ($animalsData) {
            // Filter data
            $animalsData = collect($animalsData)->filter(function ($item) use ($search, $filter) {
                if ($search && $filter) {
                    return stripos($item[$filter] ?? '', $search) !== false;
                } elseif ($search) {
                    // Search across all fields
                    return stripos($item['animalid'] ?? '', $search) !== false ||
                        stripos($item['species'] ?? '', $search) !== false ||
                        stripos($item['breed'] ?? '', $search) !== false;
                }
                return true;
            })->toArray();
        }

        return view('reports', compact('animalsData', 'total_animalDatas'));
    }



    public function create()
    {
        return view('firebase.animalData.create');
    }

    public function store(Request $request)
    {
        Log::info('Store method called'); // Log the method is being triggered

        try {
            // Validation rules
            $validated = $request->validate([
                'animalid' => 'required|string|max:255',
                'species' => 'required|string|max:255',
                'breed' => 'required|string|max:255',
                'bdate' => 'required|date_format:d-m-Y',
                'sex' => 'required|string|in:Male,Female', // Accept only Male or Female
                'weight' => 'required|numeric|min:0', // Must be a positive number
                'mname' => 'required|string|max:255',
                'mphone' => 'required|numeric|digits_between:10,15', // Must be numeric and 10-15 digits
                'flocation' => 'required|string|max:255',
                'temperature' => 'required|string|max:10',
                'genApp' => 'required|string|in:Normal,Abnormal', // New field: General Appearance
                'mucous' => 'required|string|in:Normal,Abnormal', // New field: Mucous Membrane
                'integument' => 'required|string|in:Normal,Abnormal', // New field: Integument
                'nervous' => 'required|string|in:Normal,Abnormal', // New field: Nervous
                'musculoskeletal' => 'required|string|in:Normal,Abnormal', // New field: Musculoskeletal
                'eyes' => 'required|string|in:Normal,Abnormal', // New field: Eyes
                'ears' => 'required|string|in:Normal,Abnormal', // New field: Ears
                'gastrointestinal' => 'required|string|in:Normal,Abnormal', // New field: Gastrointestinal
                'respiratory' => 'required|string|in:Normal,Abnormal', // New field: Respiratory
                'cardiovascular' => 'required|string|in:Normal,Abnormal', // New field: Cardiovascular
                'reproductive' => 'required|string|in:Normal,Abnormal', // New field: Reproductive
                'urinary' => 'required|string|in:Normal,Abnormal', // New field: Urinary
                'mGland' => 'required|string|in:Normal,Abnormal', // New field: Mammary Gland
                'lymphatic' => 'required|string|in:Normal,Abnormal', // New field: Lymphatic
            ], [
                // Custom error messages
                'animalid.required' => 'Please provide the Livestock ID.*',
                'species.required' => 'Please specify the species of the livestock.*',
                'breed.required' => 'The breed of the livestock is required.*',
                'bdate.required' => 'The birth date is required.',
                'bdate.date' => 'Please enter the birth date in the format DD-MM-YYYY.*',
                'sex.required' => 'Please select the sex of the livestock.*',
                'weight.required' => 'The weight of the livestock is required.*',
                'weight.numeric' => 'The weight must be a valid number.*',
                'weight.min' => 'The weight must be a positive number.',
                'mname.required' => 'The manager name is required.*',
                'mphone.required' => 'The manager phone number is required.*',
                'mphone.numeric' => 'The manager phone number must contain only numbers.*',
                'mphone.digits_between' => 'The manager phone number must be between 10 and 15 digits.*',
                'flocation.required' => 'Please provide the farm location.*',
                'temperature.required' => 'Temperature is required.',
                'genApp.required' => 'General Appearance is required.',
                'mucous.required' => 'Mucous Membrane status is required.',
                'integument.required' => 'Integument status is required.',
                'nervous.required' => 'Nervous system status is required.',
                'musculoskeletal.required' => 'Musculoskeletal status is required.',
                'eyes.required' => 'Eyes status is required.',
                'ears.required' => 'Ears status is required.',
                'gastrointestinal.required' => 'Gastrointestinal status is required.',
                'respiratory.required' => 'Respiratory status is required.',
                'cardiovascular.required' => 'Cardiovascular status is required.',
                'reproductive.required' => 'Reproductive status is required.',
                'urinary.required' => 'Urinary system status is required.',
                'mGland.required' => 'Mammary Gland status is required.',
                'lymphatic.required' => 'Lymphatic system status is required.',
            ]);
            Log::info('Validation passed.', $validated);

            // Fetch the latest UID from uidLogs
            $latestUidLog = $this->database->getReference('uidLogs')
                ->orderByKey()
                ->limitToLast(1)
                ->getValue();

            $latestUidEntry = reset($latestUidLog); // Get the first (and only) element
            $uid = $latestUidEntry['uid'] ?? null;

            if (!$uid) {
                throw new \Exception('No UID found in uidLogs.');
            }

            // Proceed to save data after validation passes
            $postData = $request->only([
                'animalid',
                'species',
                'breed',
                'bdate',
                'sex',
                'weight',
                'mname',
                'mphone',
                'flocation',
            ]);

            // Add UID to the data
            $postData['uid'] = $uid;

            // Add physical examination details separately
            $postData['physicalExamination'] = [
                'temperature' => $request->temperature,
                'genApp' => $request->genApp,
                'mucous' => $request->mucous,
                'integument' => $request->integument,
                'nervous' => $request->nervous,
                'musculoskeletal' => $request->musculoskeletal,
                'eyes' => $request->eyes,
                'ears' => $request->ears,
                'gastrointestinal' => $request->gastrointestinal,
                'respiratory' => $request->respiratory,
                'cardiovascular' => $request->cardiovascular,
                'reproductive' => $request->reproductive,
                'urinary' => $request->urinary,
                'mGland' => $request->mGland,
                'lymphatic' => $request->lymphatic,
                'examined_at' => now()->toDateString(), // Add the current timestamp
            ];

            $this->database->getReference('animalsData')->push($postData);
            Log::info('Data pushed to Firebase successfully.', $postData);

            $this->logActivity('add', 'Added a new animal with ID ' . $request->animalid, $request->animalid);
            Log::info('Activity logged successfully.');

            return redirect()->route('list-animalData')->with('status', 'Livestock added successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('General error occurred:', ['message' => $e->getMessage()]);
            return redirect()->back()->with('status', 'An error occurred while adding the livestock.');
        }
    }


    public function edit($livestockUid)
    {

        // Log the route parameter for debugging
        Log::info('Key from route parameter:', ['livestockUid' => $livestockUid]);

        if (!$livestockUid) {
            Log::warning('livestockUid is missing in the request.');
            return redirect('animalsData')->with('status', 'livestockUid is missing!');
        }

        // Fetch animal data using the UID
        $editdata = $this->database->getReference('animalsData/' . $livestockUid)->getValue();
        Log::info('Fetched data for edit:', ['data' => $editdata]);

        if (!$editdata) {
            return redirect('animalsData')->with('status', 'Animal data not found!');
        }

        // If scanned timestamp is required, remove this block if it's unused
        $scannedTimestamp = ''; // Default to an empty string or remove this if unused

        // Pass the data to the view
        return view('firebase.animalData.edit', compact('editdata', 'livestockUid', 'scannedTimestamp'));
    }

    public function update(Request $request, $livestockUid)
    {
        // Validation rules
        $request->validate([
            'animalid' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'breed' => 'required|string|max:255',
            'bdate' => 'required|date',
            'sex' => 'required|string|in:Male,Female',
            'weight' => 'required|numeric|min:0',
            'mname' => 'required|string|max:255',
            'mphone' => 'required|numeric|digits_between:10,15',
            'flocation' => 'required|string|max:255',
        ], [
            'mphone.required' => 'The manager phone field is required.',
            'mphone.numeric' => 'The manager phone must be a valid number.',
            'mphone.digits_between' => 'The manager phone must be between 10 and 15 digits.',
            'flocation.required' => 'The farm location field is required.',
            'flocation.string' => 'The farm location must be a valid string.',
            'flocation.max' => 'The farm location must not exceed 255 characters.',
        ], [
            'mphone' => 'manager phone',
            'flocation' => 'farm location',
        ]);

        // Fetch current data before updating
        $currentData = $this->database->getReference('animalsData/' . $livestockUid)->getValue();

        if (!$currentData) {
            return redirect('animalsData')->with('status', 'Animal data not found!');
        }

        // Exclude the 'history' field from the current data
        if (isset($currentData['history'])) {
            unset($currentData['history']);
        }

        // Save the current data into the 'history' field with a timestamp
        $timestamp = now()->toDateTimeString();
        $this->database->getReference('animalsData/' . $livestockUid . '/history/' . $timestamp)->set($currentData);

        // Prepare the new data to update
        $updateData = $request->only([
            'animalid',
            'species',
            'breed',
            'bdate',
            'sex',
            'weight',
            'mname',
            'mphone',
            'flocation'
        ]);

        // Add the timestamp for the processed_at field
        $updateData['processed_at'] = now()->toDateTimeString();

        // Update the animal data in Firebase
        $this->database->getReference('animalsData/' . $livestockUid)->update($updateData);

        // Log the activity
        $this->logActivity('Update', 'Update animal with ID ' . $request->animalid, $request->animalid);

        return redirect('animalsData')->with('status', 'Livestock Data Updated Successfully');
    }


    public function destroy($livestockUid)
    {
        $currentAnimalData = $this->database->getReference('animalsData/' . $livestockUid)->getValue();

        if (!$currentAnimalData) {
            return redirect('animalsData')->with('status', 'Animal not found');
        }

        $animalId = $currentAnimalData['animalid'] ?? $livestockUid;

        $this->database->getReference('animalsData/' . $livestockUid)->remove();

        $this->logActivity('delete', 'Deleted animal with ID ' . $animalId, $animalId);

        return redirect('animalsData')->with('status', 'Livestock Data Deleted Successfully');
    }


    public function getUid()
    {
        // Fetch data from the 'uidLogs' node in Firebase
        $rfidLogs = $this->database->getReference('/uidLogs')->getValue();

        // Pass the RFID logs to the Blade view
        return view('firebase.uidLogs.index', compact('rfidLogs'));
    }

    public function getLivestockDetails(Request $request, $animalId)
    {
        $timestamp = $request->query('timestamp');

        // Fetch the animal's data
        $animalData = $this->database->getReference('animalsData')
            ->orderByChild('animalid')
            ->equalTo($animalId)
            ->getSnapshot()
            ->getValue();

        if ($animalData) {
            $animal = array_values($animalData)[0];

            // Define a range of ±2 seconds for matching timestamps
            $timestampLowerBound = strtotime($timestamp) - 2;
            $timestampUpperBound = strtotime($timestamp) + 2;

            foreach ($animal['history'] as $recordTimestamp => $record) {
                $recordTime = strtotime($recordTimestamp);
                if ($recordTime >= $timestampLowerBound && $recordTime <= $timestampUpperBound) {
                    return response()->json($record);
                }
            }

            if (isset($animal['history'][$timestamp])) {
                // Return the specific historical entry
                return response()->json($animal['history'][$timestamp]);
            } else {
                return response()->json(['error' => 'Details not found for the given timestamp'], 404);
            }
        } else {
            return response()->json(['error' => 'Animal not found'], 404);
        }
    }

    protected function logActivity($action, $description, $animalId = null)
    {
        $this->database->getReference('/activityLogs')->push([
            'action' => $action,                   // e.g., 'add', 'update', 'delete'
            'animal_id' => $animalId ?? 'N/A',    // Save the actual animal ID (not Firebase key)
            'description' => $description,        // Description of the action
            'timestamp' => now()->toDateTimeString(), // Current timestamp
        ]);
    }

    // In animalDataController.php
    public function showActivityLogs()
    {
        // Fetch logs from Firebase
        $logs = $this->database->getReference('activityLogs')->getValue();

        // Convert logs to an array and sort them by timestamp (newest first)
        $logs = $logs ? collect($logs)->sortByDesc('timestamp')->toArray() : [];

        // Pass logs to the view
        return view('firebase.activityLogs.activityIndex', compact('logs'));
    }

    public function activityLogs()
    {
        $activityLogs = $this->database->getReference('activityLogs')->getValue();

        // Ensure all logs have the required fields
        $activityLogs = $activityLogs ? collect($activityLogs)->map(function ($log) {
            return [
                'processed_at' => $log['processed_at'] ?? 'No Timestamp',
                'action' => $log['action'] ?? 'No Action',
                'animal_id' => $log['animal_id'] ?? 'N/A',
                'description' => $log['description'] ?? 'No Description',
            ];
        })->sortByDesc('processed_at')->toArray() : [];

        return view('firebase.activityLogs.activityIndex', compact('activityLogs'));
    }

    public function getAnimalHistory($animalId)
    {
        // Query the animal data by animal ID
        $animalData = $this->database->getReference('animalsData')
            ->orderByChild('animalid')
            ->equalTo($animalId)
            ->getSnapshot()
            ->getValue();

        if ($animalData) {
            // Extract history from the first matching record
            $animal = array_values($animalData)[0];
            return response()->json($animal['history'] ?? []);
        }

        return response()->json(['error' => 'Animal not found or no history available'], 404);
    }

    public function getPhyExamination($animalId)
    {
        Log::info("Fetching physical examination data for Animal ID: {$animalId}");

        try {
            // Fetch physical examination data from Firebase
            $phyExaminationData = $this->database->getReference("animalsData/{$animalId}/physicalExamination")->getValue();

            if (!$phyExaminationData) {
                Log::warning("No physical examination data found for Animal ID: {$animalId}");
                return response()->json([], 200);
            }

            Log::info("Physical Examination Data Retrieved:", $phyExaminationData);
            return response()->json($phyExaminationData, 200);
        } catch (\Exception $e) {
            Log::error("Error fetching physical examination data: {$e->getMessage()}");
            return response()->json(['error' => 'Failed to fetch physical examination data.'], 500);
        }
    }
}
