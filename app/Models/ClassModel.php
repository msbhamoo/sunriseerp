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

    /**
     * Get class by ID or all classes (compatible with CI3 legacy controller/views)
     */
    public function get(?int $id = null): array
    {
        if ($id !== null) {
            $record = $this->find($id);
            return $record ?: [];
        }

        return $this->orderBy('id', 'ASC')->findAll();
    }

    /**
     * Delete class and corresponding class_sections
     */
    public function remove(int $id): bool
    {
        $this->db->transStart();
        $this->db->table('classes')->where('id', $id)->delete();
        $this->db->table('class_sections')->where('class_id', $id)->delete();
        $this->logAudit("Delete On classes id {$id}", $id, 'Delete');
        $this->db->transComplete();

        return $this->db->transStatus();
    }

    /**
     * Check if class name exists
     */
    public function check_data_exists(string $className): ?object
    {
        return $this->db->table('classes')->where('class', $className)->get()->getRow();
    }

    /**
     * Get sections for a class
     */
    public function get_section(int $id): array
    {
        return $this->db->table('class_sections')
            ->select('sections.id, sections.section')
            ->join('sections', 'class_sections.section_id = sections.id')
            ->where('class_id', $id)
            ->get()
            ->getResultArray();
    }
}
