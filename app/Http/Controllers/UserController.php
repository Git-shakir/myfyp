<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\DateHelper;

class UserController extends Controller
{
    public function showAnimals()
    {
        // Fetch all animal data from the database
        $animalsData = Animal::all();

        // Add age details for each animal
        $animalsData = $animalsData->map(function ($item) {
            $item['age'] = DateHelper::calculateAge($item['bdate'])['formattedAge'] ?? 'N/A';
            return $item;
        });

        // Pass the data to the view
        return view('animals.index', [
            'animalsData' => $animalsData
        ]);
    }
}
