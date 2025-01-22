<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TariffController extends Controller
{
    // Method to handle the file upload
    public function upload(Request $request)
    {
        // Check if the file exists in the request
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Get the original filename and create a new unique name for storage
            $filename = time() . '_' . $file->getClientOriginalName();

            // Move the file to the 'uploads' directory inside 'public' folder
            $file->move(public_path('uploads'), $filename);

            // Return a success response with the filename
            return response()->json(['success' => true, 'filename' => $filename]);
        }

        // Return a failure response if no file is found
        return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
    }

    // Method to search for matching tariffs
    public function search(Request $request)
    {
        $language = $request->input('language');
        $words = $request->input('words');

        // Choose the appropriate table based on the detected language
        $languageTable = match($language) {
            'english' => 'english_tariffs',
            'turkish' => 'turkish_tariffs',
            'bosnian' => 'bosnian_tariffs',
        };

        $tariffs = DB::table($languageTable)->get();
        $matches = [];

        foreach ($tariffs as $tariff) {
            foreach ($words as $word) {
                $distance = levenshtein(strtolower($word), strtolower($tariff->naziv));
                $matches[] = [
                    'word' => $word,
                    'tariff' => $tariff->naziv,
                    'distance' => $distance,
                ];
            }
        }

        usort($matches, fn($a, $b) => $a['distance'] <=> $b['distance']);
        $topMatches = array_slice($matches, 0, 5);

        return response()->json($topMatches);
    }
}
