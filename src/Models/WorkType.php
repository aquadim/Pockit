<?php
// Модель дисциплины

namespace Pockit\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "agst_worktypes")]
class WorkType
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue]
    private int|null $id = null;

    // Название в именительном падеже
    #[ORM\Column(type: "string")]
    private string $name_nom;

    // Название в родительном падеже
    #[ORM\Column(type: "string")]
    private string $name_gen;

    // Скрыт ли
    #[ORM\Column(type: "boolean")]
    private bool $hidden;

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name nom
     *
     * @param string
     */
    public function setNameNom(string $nameNom)
    {
        $this->name_nom = $nameNom;
    }

    /**
     * Get name nom
     *
     * @return string
     */
    public function getNameNom(): string
    {
        return $this->name_nom;
    }

    /**
     * Set name gen
     *
     * @param string
     */
    public function setNameGen(string $nameGen)
    {
        $this->name_gen = $nameGen;
    }

    /**
     * Get name gen
     *
     * @return string
     */
    public function getNameGen(): string
    {
        return $this->name_gen;
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
            'id'=>$this->id,
            'nameNom' => $this->name_nom,
            'nameGen' => $this->name_gen,
            'hidden' => $this->hidden,
            'repr' => $this->name_nom
        ];
    }
}
