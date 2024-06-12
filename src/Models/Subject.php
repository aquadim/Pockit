<?php
// Модель дисциплины

namespace Pockit\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "agst_subjects")]
class Subject
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue]
    private int|null $id = null;

    // Шифр дисциплины
    #[ORM\Column(type: "string")]
    private string $code;

    // Преподаватель
    #[ORM\ManyToOne(Teacher::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Teacher $teacher;

    // Имя
    #[ORM\Column(type: "string")]
    private string $title;

    // Имя для программы
    #[ORM\Column(type: "string")]
    private string $my_name;

    // Скрыт ли
    #[ORM\Column(type: "boolean")]
    private bool $hidden;

    /**
     * Set code
     *
     * @param
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Get code
     *
     * @return
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set teacher
     *
     * @param
     */
    public function setTeacher($teacher)
    {
        $this->teacher = $teacher;
    }

    /**
     * Get teacher
     *
     * @return
     */
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * Set my name
     *
     * @param
     */
    public function setMyName($myName)
    {
        $this->my_name = $myName;
    }

    /**
     * Get my name
     *
     * @return
     */
    public function getMyName()
    {
        return $this->my_name;
    }
    
    /**
     * Set name
     *
     * @param
     */
    public function setName($name)
    {
        $this->title = $name;
    }

    /**
     * Get name
     *
     * @return
     */
    public function getName()
    {
        return $this->title;
    }

    /**
     * Set hidden
     *
     * @param bool
     */
    public function setHidden(bool $hidden)
    {
        $this->hidden = $hidden;
    }

    /**
     * Get hidden
     *
     * @return bool
     */
    public function isHidden(): bool
    {
        return $this->hidden;
    }

    public function toArray() : array {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'title' => $this->title,
            'teacher' => $this->teacher->toArray(),
            'myName' => $this->my_name,
            'hidden' => $this->hidden,
            'repr' => $this->my_name
        ];
    }
}
