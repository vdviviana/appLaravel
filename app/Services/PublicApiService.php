<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Entity;
use App\Models\Category;

class PublicApiService
{
    public function fetchEntities()
    {
        //error_log("fetchEntities");
        try {
            // Nueva URL archivada
            $response = Http::get('http://web.archive.org/web/20240403172734/https://api.publicapis.org/entries');
            //$response = Http::get('http://invalid-url')->throw();
            $data = $response->json();
            
            if (isset($data['entries'])) {
                $this->saveEntities($data['entries']);                
            }
        } catch (\Exception $e) {
            // Usar archivo local si la API falla
            error_log("Error conexipon API entries: " . $e->getMessage());
            $localData = json_decode(file_get_contents(storage_path('app/entries.json')), true);
            $this->saveEntities($localData['entries']);
        }
    }

    private function saveEntities($entries)
    {
        //error_log("saveEntries");
        $categories = Category::pluck('id', 'category'); // Obtener IDs de categorías

        foreach ($entries as $entry) {
            if (isset($categories[$entry['Category']])) {
                Entity::create([
                    'api' => $entry['API'],
                    'description' => $entry['Description'],
                    'link' => $entry['Link'],
                    'category_id' => $categories[$entry['Category']],
                ]);
            }
        }
    }
}
