<?php

namespace App\Models;

use stdClass;

/**
 * ClasssectionModel
 *
 * Model for linking classes and sections (`class_sections`).
 */
class ClasssectionModel extends BaseModel
{
    protected $table = 'class_sections';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['class_id', 'section_id'];

    public function get(?int $classId = null): array
    {
        return $this->db->table('class_sections')
            ->select('class_sections.id, class_sections.section_id, sections.section')
            ->join('sections', 'sections.id = class_sections.section_id')
            ->where('class_sections.class_id', $classId)
            ->orderBy('class_sections.id', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getByID(?int $id = null): array
    {
        $builder = $this->db->table('classes');
        if ($id !== null) {
            $builder->where('id', $id);
        } else {
            $builder->orderBy('id', 'DESC');
        }

        $classes = $builder->get()->getResultArray();
        $array = [];

        foreach ($classes as $class) {
            $obj = new stdClass();
            $obj->id = $class['id'];
            $obj->class = $class['class'];
            $obj->route_id = $class['class'];
            $obj->vehicles = $this->getSectionsByClass((int)$class['id']);
            $array[] = $obj;
        }

        return $array;
    }

    public function getSectionsByClass(int $classId): array
    {
        return $this->db->table('class_sections')
            ->select('class_sections.id as class_section_id, class_sections.class_id, class_sections.section_id, sections.*')
            ->join('sections', 'sections.id = class_sections.section_id')
            ->where('class_sections.class_id', $classId)
            ->orderBy('class_sections.id', 'ASC')
            ->get()
            ->getResult();
    }

    public function add(array $classData, array $sections = []): int|bool
    {
        $this->db->transStart();

        if (!empty($classData['id'])) {
            $classId = (int)$classData['id'];
            $this->db->table('classes')->where('id', $classId)->update($classData);
            $action = 'Update';
        } else {
            $this->db->table('classes')->insert($classData);
            $classId = (int)$this->db->insertID();
            $action = 'Insert';
        }

        $this->logAudit("Class {$action} on ID {$classId}", $classId, $action);

        if (!empty($sections)) {
            $batch = [];
            foreach ($sections as $sectionId) {
                // Check if not already attached
                $exists = $this->db->table('class_sections')
                    ->where('class_id', $classId)
                    ->where('section_id', (int)$sectionId)
                    ->countAllResults();

                if ($exists === 0) {
                    $batch[] = [
                        'class_id'   => $classId,
                        'section_id' => (int)$sectionId,
                    ];
                }
            }
            if (!empty($batch)) {
                $this->db->table('class_sections')->insertBatch($batch);
            }
        }

        $this->db->transComplete();

        return $this->db->transStatus() !== false ? $classId : false;
    }

    public function updateClass(array $classData): bool
    {
        if (empty($classData['id'])) {
            return false;
        }

        $id = (int)$classData['id'];
        $this->db->table('classes')->where('id', $id)->update($classData);
        $this->logAudit("Class updated on ID {$id}", $id, 'Update');
        return true;
    }

    public function remove(int $classId, array $sections = []): void
    {
        $this->removeSections($classId, $sections);
    }

    public function removeSections(int $classId, array $sections): void
    {
        if (!empty($sections)) {
            $this->db->table('class_sections')
                ->where('class_id', $classId)
                ->whereIn('section_id', $sections)
                ->delete();
        }
    }
}
