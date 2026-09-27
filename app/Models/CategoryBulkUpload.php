<?php

namespace App\Models;

use App\Models\Category;
use App\Models\CategoryTranslation;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use App\Traits\PreventDemoModeChanges;

class CategoryBulkUpload implements ToCollection, WithHeadingRow
{
    use PreventDemoModeChanges;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            if (empty($row['name'])) {
                continue;
            }

            $category = new Category();

            $category->name = trim($row['name']);

            $category->digital = (int)($row['digital'] ?? 0);

            $category->order_level = (int)($row['ordering_number'] ?? 0);

            $category->banner = !empty($row['banner'])
                ? $this->uploadImage($row['banner'])
                : null;

            $category->icon = !empty($row['icon'])
                ? $this->uploadImage($row['icon'])
                : null;

            $category->cover_image = !empty($row['cover_image'])
                ? $this->uploadImage($row['cover_image'])
                : null;

            $category->meta_title = $row['meta_title'] ?? null;
            $category->meta_description = $row['meta_description'] ?? null;
            $category->meta_keywords = $row['meta_keywords'] ?? null;

            if (!empty($row['parent_category_id']) && $row['parent_category_id'] != 0) {

                $parent = Category::find($row['parent_category_id']);

                if ($parent) {
                    $category->parent_id = $parent->id;
                    $category->level = $parent->level + 1;
                }
            }

            $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $row['name'])) . '-' . Str::random(5);

            $category->save();

            $translation = CategoryTranslation::firstOrNew([
                'lang' => env('DEFAULT_LANGUAGE'),
                'category_id' => $category->id
            ]);

            $translation->name = $category->name;
            $translation->save();

            if (!empty($row['filtering_attribute_id'])) {

                $attributeIds = array_filter(
                    array_map('trim', explode(',', $row['filtering_attribute_id']))
                );

                $category->attributes()->sync($attributeIds);
            }
        }

    }


    private function uploadImage($url)
    {
        try {
            $upload = new Upload;
            $upload->external_link = trim($url);
            $upload->type = 'image';
            $upload->save();

            return $upload->id;
        } catch (\Exception $e) {
            return null;
        }
    }
}
