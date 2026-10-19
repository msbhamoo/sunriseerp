<?php

namespace App\Models;

/**
 * ClassModel
 *
 * Model for school classes (table `classes`).
 */
class ClassModel extends BaseModel
{
    protected $table = 'classes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['class', 'is_active'];

    /**
     * Get all classes or a single class by ID.
     */
    public function getAll(?int $id = null): array
    {
        if ($id !== null) {
            $record = $this->find($id);
            return $record ?: [];
        }

        return $this->orderBy('id', 'ASC')->findAll();
    }

    /**
     * Get all classes along with their associated sections from `class_sections`.
     */
    public function getClassesWithSections(): array
    {
        $classes = $this->orderBy('id', 'ASC')->findAll();

        foreach ($classes as &$class) {
            $class['sections'] = $this->db->table('class_sections')
                ->select('class_sections.id as class_section_id, class_sections.section_id, sections.section')
                ->join('sections', 'sections.id = class_sections.section_id')
                ->where('class_sections.class_id', $class['id'])
                ->orderBy('class_sections.id', 'ASC')
                ->get()
                ->getResultArray();
        }

        return $classes;
    }
}
