<?php
//myproject\app\Http\Controllers\Firebase\animalDataController.php
namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;




class animalDataController extends Controller
{
    //protected $database;
    //protected $tablename;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tablename =  'animalsData';
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
                $animalKey = array_key_first($existingAnimal);
                $this->database->getReference('/triggers/edit_uid')->set($animalKey);

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


    public function index()
    {
        $animalsData = $this->database->getReference($this->tablename)->getValue();
        $total_animalDatas = $reference = $this->database->getReference($this->tablename)->getSnapshot()->numChildren();

        return view('firebase.animalData.index', compact('animalsData', 'total_animalDatas'));
    }

    public function create()
    {
        return view('firebase.animalData.create');
    }

    public function store(Request $request)
    {
        // Validation rules
        $request->validate([
            'animalid' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'breed' => 'required|string|max:255',
            'bdate' => 'required|date_format:d-m-Y',
            'age' => 'required|integer|min:0', // Must be a number and non-negative
            'sex' => 'required|string|in:Male,Female', // Accept only Male or Female
            'weight' => 'required|numeric|min:0', // Must be a positive number
            'mname' => 'required|string|max:255',
            'mphone' => 'required|numeric|digits_between:10,15', // Must be numeric and 10-15 digits
            'flocation' => 'required|string|max:255',
        ], [
            'animalid.required' => 'Please provide the Livestock ID.*',
            'species.required' => 'Please specify the species of the livestock.*',
            'breed.required' => 'The breed of the livestock is required.*',
            'bdate.required' => 'The birth date is required.',
            'bdate.date' => 'Please enter the birth date in the format DD-MM-YYYY.*',
            'age.required' => 'The age of the livestock is required.*',
            'age.integer' => 'The age must be a valid number.',
            'sex.required' => 'Please select the sex of the livestock.*',
            'weight.required' => 'The weight of the livestock is required.*',
            'weight.numeric' => 'The weight must be a valid number.*',
            'weight.min' => 'The weight must be a positive number.',
            'mname.required' => 'The manager name is required.*',
            'mphone.required' => 'The manager phone number is required.*',
            'mphone.numeric' => 'The manager phone number must contain only numbers.*',
            'mphone.digits_between' => 'The manager phone number must be between 10 and 15 digits.*',
            'flocation.required' => 'Please provide the farm location.*',
        ]);


        // Proceed to save data after validation passes
        $postData = $request->only([
            'animalid',
            'species',
            'breed',
            'bdate',
            'age',
            'sex',
            'weight',
            'mname',
            'mphone',
            'flocation'
        ]);

        $postData['processed_at'] = now()->toDateTimeString();

        $this->database->getReference('animalsData')->push($postData);

        $this->logActivity('add', 'Added a new animal with ID ' . $request->animalid, $request->animalid);

        return redirect('animalsData')->with('status', 'New Livestock Added Successfully');
    }

    public function edit(Request $request)
    {
        $uid = $request->query('uid'); // Get 'uid' from the query string

        if (!$uid) {
            return redirect('animalsData')->with('status', 'UID is missing!');
        }

        // Fetch animal data using the UID
        $editdata = $this->database->getReference('animalsData/' . $uid)->getValue();

        if (!$editdata) {
            return redirect('animalsData')->with('status', 'Animal data not found!');
        }

        // Retrieve scanned timestamp from the query string (if available)
        $scannedTimestamp = $request->query('scanned_timestamp', ''); // Default to an empty string if not provided

        // Pass the data to the view
        return view('firebase.animalData.edit', compact('editdata', 'uid', 'scannedTimestamp'));
    }



    public function update(Request $request, $id)
    {
        // Validation rules
        $request->validate([
            'animalid' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'breed' => 'required|string|max:255',
            'bdate' => 'required|date',
            'age' => 'required|integer|min:0',
            'sex' => 'required|string|in:Male,Female',
            'weight' => 'required|numeric|min:0',
            'mname' => 'required|string|max:255',
            'mphone' => 'required|numeric|digits_between:10,15',
            'flocation' => 'required|string|max:255',
        ], [
            // Custom messages for mphone
            'mphone.required' => 'The manager phone field is required.',
            'mphone.numeric' => 'The manager phone must be a valid number.',
            'mphone.digits_between' => 'The manager phone must be between 10 and 15 digits.',
            // Custom messages for flocation
            'flocation.required' => 'The farm location field is required.',
            'flocation.string' => 'The farm location must be a valid string.',
            'flocation.max' => 'The farm location must not exceed 255 characters.',
        ], [
            // Custom attributes
            'mphone' => 'manager phone',
            'flocation' => 'farm location',
        ]);

        // Fetch current data before updating
        $currentData = $this->database->getReference('animalsData/' . $id)->getValue();

        if (!$currentData) {
            return redirect('animalsData')->with('status', 'Animal data not found!');
        }

        // Save the current data into the 'history' field with a timestamp
        $timestamp = now()->toDateTimeString();
        $this->database->getReference('animalsData/' . $id . '/history/' . $timestamp)->set($currentData);

        $updateData = $request->only([
            'animalid',
            'species',
            'breed',
            'bdate',
            'age',
            'sex',
            'weight',
            'mname',
            'mphone',
            'flocation'
        ]);

        $updateData['processed_at'] = now()->toDateTimeString();

        $this->database->getReference('animalsData/' . $id)->update($updateData);

        $this->logActivity('Update', 'Update animal with ID ' . $request->animalid, $request->animalid);

        return redirect('animalsData')->with('status', 'Livestock Data Updated Successfully');
    }

    public function destroy($id)
    {
        $currentAnimalData = $this->database->getReference('animalsData/' . $id)->getValue();

        if (!$currentAnimalData) {
            return redirect('animalsData')->with('status', 'Animal not found');
        }

        $animalId = $currentAnimalData['animalid'] ?? $id;

        $this->database->getReference('animalsData/' . $id)->remove();

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
}
