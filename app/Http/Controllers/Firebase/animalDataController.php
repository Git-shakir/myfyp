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

        if ($animalsData) {
            // Add dynamically calculated age to each record
            foreach ($animalsData as $key => &$item) {
                if (isset($item['bdate'])) {
                    $item['age'] = $this->calculateAge($item['bdate']);
                } else {
                    $item['age'] = 'N/A'; // Handle cases where birthdate is missing
                }
            }

            // Sort the data by 'animalid'
            $animalsData = collect($animalsData)
                ->sortBy('animalid') // Sort by animalid
                ->toArray();

            // Fetch the user's role from the session
            $userRole = session('firebase_user.role', 'N/A');
        }

        return view('firebase.animalData.index', compact('animalsData', 'total_animalDatas', 'userRole'));
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

    public function startListeningForLogs()
    {
        Log::info('Starting Firebase polling for UID logs...');

        while (true) {
            // Fetch unprocessed logs
            $logs = $this->database->getReference('/uidLogs')
                ->orderByChild('processed')
                ->equalTo(false)
                ->getValue();

            if ($logs) {
                foreach ($logs as $key => $log) {
                    Log::info("Processing log: {$key}", $log);

                    // Process each log
                    $this->processUidLog($log, $key);
                }
            }

            // Pause for 2 seconds to prevent excessive polling
            sleep(2);
        }
    }

    private function processUidLog($log, $key)
    {
        $uid = $log['uid'];

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
            Log::info("Trigger set for editing: {$livestockUid}");
        } else {
            // New UID: Set the trigger for adding
            $this->database->getReference('/triggers/new_uid')->set($uid);
            Log::info("Trigger set for adding: {$uid}");
        }

        // Mark the log as processed
        $this->database->getReference('/uidLogs/' . $key)->update(['processed' => true]);
        Log::info("Log marked as processed: {$key}");
    }

    public function create()
    {
        return view('firebase.animalData.create');
    }

    public function store(Request $request)
    {
        // Validation rules
        $validated = $request->validate([
            'animalid' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $existingAnimal = $this->database->getReference('animalsData')->orderByChild('animalid')->equalTo($value)->getValue();
                    if ($existingAnimal) {
                        $fail('The Livestock ID is already in use.');
                    }
                },
            ],
            'species' => 'required|string|max:255',
            'breed' => 'required|string|max:255',
            'bdate' => 'required|date_format:d-m-Y',
            'sex' => 'required|string|in:Male,Female',
            'mname' => 'required|string|max:255',
            'mphone' => 'required|numeric|digits_between:10,15',
        ], [
            // Custom error messages
            'bdate.required' => 'The birth date is required.',
            'mname.required' => 'The manager name is required.',
            'mphone.required' => 'The manager phone number is required.',
            'mphone.numeric' => 'The manager phone number must contain only numbers.',
            'mphone.digits_between' => 'The manager phone number must be between 10 and 15 digits.',
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
            'mname',
            'mphone',
        ]);

        // Add UID to the data
        $postData['uid'] = $uid;

        $this->database->getReference('animalsData')->push($postData);
        Log::info('Data pushed to Firebase successfully.', $postData);

        // Retrieve the animal data to get the correct animal ID
        $animalId = $validated['animalid']; // Use the validated animal ID directly

        // Get the logged-in user's name from the session
        $userName = session('firebase_user.displayName', 'N/A');

        // Log the activity using the logActivity function
        $this->logActivity(
            'Add',
            "Added a new livestock with ID {$animalId} by {$userName}",
            $animalId,
            $userName
        );

        return redirect()->route('list-animalData')->with('status', 'Livestock added successfully');
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
        $validated = $request->validate([
            'animalid' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'breed' => 'required|string|max:255',
            'bdate' => 'required|date',
            'sex' => 'required|string|in:Male,Female',
            'mname' => 'required|string|max:255',
            'mphone' => 'required|numeric|digits_between:10,15',
        ], [
            // Custom error messages
            'bdate.required' => 'The birth date is required.',
            'mname.required' => 'The manager name is required.',
            'mphone.required' => 'The manager phone number is required.',
            'mphone.numeric' => 'The manager phone number must contain only numbers.',
            'mphone.digits_between' => 'The manager phone number must be between 10 and 15 digits.',
        ]);

        // Fetch current data before updating
        $currentData = $this->database->getReference('animalsData/' . $livestockUid)->getValue();

        if (!$currentData) {
            return redirect('animalsData')->with('status', 'Animal data not found!');
        }

        // Exclude the 'checkup' field from the current data
        $historyData = $currentData;
        unset($historyData['checkup']); // Remove the 'checkup' node if it exists

        // Prepare to save current data in the history node
        $timestamp = now()->toDateTimeString();
        $historyData['processed_at'] = $timestamp;

        // Save the history data
        $this->database->getReference('animalsData/' . $livestockUid . '/history/' . $timestamp)->set($historyData);

        // Prepare the new data to update
        $updateData = $request->only([
            'animalid',
            'species',
            'breed',
            'bdate',
            'sex',
            'mname',
            'mphone',
        ]);

        // Add the timestamp for the processed_at field
        $updateData['processed_at'] = $timestamp;

        // Update the animal data in Firebase
        $this->database->getReference('animalsData/' . $livestockUid)->update($updateData);

        // Get the logged-in user's name from the session
        $userName = session('firebase_user.displayName', 'N/A');

        // Log the activity
        $this->logActivity(
            'Update',
            "Updated livestock with ID {$validated['animalid']} by {$userName}",
            $validated['animalid'],
            $userName
        );

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

        // Get the logged-in user's name from the session
        $userName = session('firebase_user.displayName', 'N/A');

        // Log the activity using the logActivity function
        $this->logActivity(
            'Delete',
            "Deleted a livestock with ID {$animalId} by {$userName}",
            $animalId,
            $userName
        );

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
            // Get the first matching animal
            $animal = array_values($animalData)[0];

            // Calculate age if the birthdate exists
            if (isset($animal['bdate'])) {
                $animal['age'] = $this->calculateAge($animal['bdate']);
            } else {
                $animal['age'] = 'N/A'; // Handle cases where birthdate is missing
            }

            // Define a range of ±2 seconds for matching timestamps
            $timestampLowerBound = strtotime($timestamp) - 2;
            $timestampUpperBound = strtotime($timestamp) + 2;

            if (isset($animal['history'])) {
                foreach ($animal['history'] as $historyTimestamp => $historyDetails) {
                    $historyTimestampUnix = strtotime($historyTimestamp);

                    // Check if the timestamp falls within the ±2 second range
                    if ($historyTimestampUnix >= $timestampLowerBound && $historyTimestampUnix <= $timestampUpperBound) {
                        // Add the calculated age to the history details
                        $historyDetails['age'] = $animal['age'];

                        // Return the specific historical entry with age
                        return response()->json($historyDetails);
                    }
                }
            }

            return response()->json(['error' => 'Details not found for the given timestamp'], 404);
        } else {
            return response()->json(['error' => 'Animal not found'], 404);
        }
    }

    protected function logActivity($action, $description, $animalId = null, $userName = 'N/A')
    {
        $this->database->getReference('/activityLogs')->push([
            'action' => $action,                   // e.g., 'add', 'update', 'delete'
            'animal_id' => $animalId ?? 'N/A',    // Save the actual animal ID (not Firebase key)
            'description' => $description,        // Description of the action
            'user_name' => $userName,             // Include the user's name in the log
            'timestamp' => now()->toDateTimeString(), // Current timestamp
        ]);
    }

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

    public function getCheckupData($animalKey)
    {
        Log::info("Fetching checkup data for animalKey: $animalKey");

        if (!$animalKey) {
            Log::warning('animalKey is missing in the request.');
            return response()->json(['message' => 'animalKey is missing'], 400);
        }

        // Fetch the animal data
        $animalData = $this->database->getReference('animalsData/' . $animalKey)->getValue();

        if (!$animalData) {
            Log::warning("Animal data not found for UID: $animalKey");
            return response()->json(['message' => 'Animal data not found'], 404);
        }

        // Fetch the checkup data
        $checkupData = $animalData['checkup'] ?? [];
        if (empty($checkupData)) {
            Log::info("No checkup data found for UID: $animalKey");
            return response()->json([], 200); // Empty checkup data
        }

        Log::info("Checkup Data fetched:", $checkupData);
        return response()->json($checkupData);
    }


    public function checkup($livestockUid)
    {
        // Log the route parameter for debugging
        Log::info('Key from route parameter:', ['livestockUid' => $livestockUid]);

        if (!$livestockUid) {
            Log::warning('livestockUid is missing in the request.');
            return redirect('animalsData')->with('status', 'livestockUid is missing!');
        }

        // Fetch animal data using the UID
        $animalData = $this->database->getReference('animalsData/' . $livestockUid)->getValue();
        Log::info('Fetched data for checkup:', ['data' => $animalData]);

        if (!$animalData) {
            return redirect('animalsData')->with('status', 'Animal data not found!');
        }

        // Fetch the latest weight from the checkup node
        $checkupData = $animalData['checkup'] ?? [];
        $latestWeight = null;

        if (!empty($checkupData)) {
            $latestCheckup = end($checkupData); // Get the last checkup entry
            $latestWeight = $latestCheckup['weight'] ?? null; // Fetch the weight field if available
        }

        Log::info("Latest weight fetched: $latestWeight");

        // Pass the latest weight and other data to the view
        return view('firebase.animalData.checkup', compact('animalData', 'livestockUid', 'latestWeight'));
    }

    public function storeCheckup(Request $request)
    {
        // Validate the form input with custom error messages
        $data = $request->validate(
            [
                'livestockUid' => 'required|string',
                'weight' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'temperature' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'genApp' => 'required|string',
                'mucous' => 'required|string',
                'integument' => 'required|string',
                'nervous' => 'required|string',
                'musculoskeletal' => 'required|string',
                'eyes' => 'required|string',
                'ears' => 'required|string',
                'gastrointestinal' => 'required|string',
                'respiratory' => 'required|string',
                'cardiovascular' => 'required|string',
                'reproductive' => 'required|string',
                'urinary' => 'required|string',
                'mGland' => 'required|string',
                'lymphatic' => 'required|string',
            ],
            [
                'weight.regex' => 'The weight must be a number (up to two decimal places).',
                'temperature.regex' => 'The temperature must be a number (up to two decimal places).',
                'genApp.required' => 'The general appearance is required.',
                'mucous.required' => 'The mucous membrane is required.',
                'mGland.required' => 'The mammary gland is required.',
            ]
        );

        // Save the checkup data to Firebase
        $this->database->getReference("animalsData/{$data['livestockUid']}/checkup")->push([
            'temperature' => $data['temperature'],
            'weight' => $data['weight'],
            'genApp' => $data['genApp'],
            'mucous' => $data['mucous'],
            'integument' => $data['integument'],
            'nervous' => $data['nervous'],
            'musculoskeletal' => $data['musculoskeletal'],
            'eyes' => $data['eyes'],
            'ears' => $data['ears'],
            'gastrointestinal' => $data['gastrointestinal'],
            'respiratory' => $data['respiratory'],
            'cardiovascular' => $data['cardiovascular'],
            'reproductive' => $data['reproductive'],
            'urinary' => $data['urinary'],
            'mGland' => $data['mGland'],
            'lymphatic' => $data['lymphatic'],
            'examined_at' => now()->toDateString(), // Add current date
        ]);

        // Retrieve the animal data to get the animal ID
        $animalData = $this->database->getReference("animalsData/{$data['livestockUid']}")->getValue();
        $animalId = $animalData['animalid'] ?? 'N/A';

        // Get the logged-in user's name from the session
        $userName = session('firebase_user.displayName', 'N/A');

        // Log the activity using the logActivity function
        $this->logActivity(
            'Checkup',
            "Checkup performed for animal with ID {$animalId} by {$userName}",
            $animalId,
            $userName
        );

        // Redirect back to the list with a success message
        return redirect()->route('list-animalData')->with('success', 'Checkup data saved successfully.');
    }
}
