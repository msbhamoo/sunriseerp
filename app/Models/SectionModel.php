<?php

namespace App\Models;

/**
 * SectionModel
 *
 * Model for school sections (table `sections`).
 */
class SectionModel extends BaseModel
{
    protected $table = 'sections';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['section', 'is_active'];

    /**
     * Get all sections or a single section by ID.
     */
    public function getAll(?int $id = null): array
    {
        if ($id !== null) {
            $record = $this->find($id);
            return $record ?: [];
        }

        return $this->orderBy('section', 'ASC')->findAll();
    }

    /**
     * Get sections assigned to a specific class ID.
     */
    public function getByClassId(int $classId): array
    {
        return $this->db->table('class_sections')
            ->select('class_sections.id as class_section_id, class_sections.section_id, sections.section')
            ->join('sections', 'sections.id = class_sections.section_id')
            ->where('class_sections.class_id', $classId)
            ->orderBy('class_sections.id', 'ASC')
            ->get()
            ->getResultArray();
    }
}
