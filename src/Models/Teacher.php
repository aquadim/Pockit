<?php
// Модель дисциплины

namespace Pockit\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "agst_teachers")]
class Teacher
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue]
    private int|null $id = null;

    // Фамилия
    #[ORM\Column(type: "string")]
    private string $surname;

    // Имя
    #[ORM\Column(type: "string")]
    private string $name;

    // Отчество
    #[ORM\Column(type: "string")]
    private string $patronymic;

    /**
     * Set id
     *
     * @param
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get id
     *
     * @return
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set surname
     *
     * @param
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * Get surname
     *
     * @return
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set name
     *
     * @param
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set patronymic
     *
     * @param
     */
    public function setPatronymic($patronymic)
    {
        $this->patronymic = $patronymic;
    }

    /**
     * Get patronymic
     *
     * @return
     */
    public function getPatronymic()
    {
        return $this->patronymic;
    }

    public function getFullName() : string {
        return $this->surname . ' ' .
            mb_substr($this->name, 0, 1) . '. ' .
            mb_substr($this->patronymic, 0, 1) . '.';
    }

    public function toArray()
    {
        $full_name = $this->getFullName();
            
        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'patronymic' => $this->patronymic,
            'full' => $full_name,
            'repr' => $full_name
        ];
    }
}
