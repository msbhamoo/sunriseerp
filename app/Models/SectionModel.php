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
     * Get sections - compatible with CI3 Section_model get($id = null)
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
     * Insert or update a section
     */
    public function add(array $data): int|bool
    {
        if (isset($data['id'])) {
            $id = (int)$data['id'];
            $this->update($id, $data);
            $this->logAudit("Section updated on ID {$id}", $id, 'Update');
            return $id;
        }

        $id = $this->insert($data);
        $this->logAudit("Section added on ID {$id}", (int)$id, 'Insert');
        return (int)$id;
    }

    /**
     * Delete a section
     */
    public function remove(int $id): bool
    {
        $this->db->transStart();
        $this->db->table('sections')->where('id', $id)->delete();
        $this->db->table('class_sections')->where('section_id', $id)->delete();
        $this->logAudit("Delete On sections id {$id}", $id, 'Delete');
        $this->db->transComplete();

        return $this->db->transStatus();
    }

    /**
     * Get sections for a class - compatible with getClassBySection
     */
    public function getClassBySection(?int $classId = null): array
    {
        if (empty($classId)) {
            return [];
        }
        return $this->db->table('class_sections')
            ->select('class_sections.id, class_sections.section_id, sections.section')
            ->join('sections', 'sections.id = class_sections.section_id')
            ->where('class_sections.class_id', $classId)
            ->orderBy('class_sections.id', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get sections assigned to a specific class ID.
     */
    public function getByClassId(?int $classId = null): array
    {
        return $this->getClassBySection($classId);
    }
}
