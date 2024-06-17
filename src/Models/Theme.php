<?php
// Модель темы

namespace Pockit\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "themes")]
class Theme
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue]
    private int|null $id = null;

    // Название
    #[ORM\Column(type: "string")]
    private string $name;

    // Автор
    #[ORM\Column(type: "string")]
    private string $author;

    // Код CSS
    #[ORM\Column(type: "string")]
    private string $css;

    // Расположение файла заднего фона стартовой страницы
    // Относительно /wwwroot/img/home
    #[ORM\Column(type: "string")]
    private string $homebg_location;

    // Можно ли удалить
    #[ORM\Column(type: "boolean")]
    private bool $can_be_deleted;

    /**
     * Set id
     *
     * @param int
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set author
     *
     * @param string
     */
    public function setAuthor(string $author)
    {
        $this->author = $author;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * Set css
     *
     * @param string
     */
    public function setCss(string $css)
    {
        $this->css = $css;
    }

    /**
     * Get css
     *
     * @return string
     */
    public function getCss(): string
    {
        return $this->css;
    }

    /**
     * Set can be deleted
     *
     * @param bool
     */
    public function setCanBeDeleted(bool $canBeDeleted)
    {
        $this->can_be_deleted = $canBeDeleted;
    }

    /**
     * Get can be deleted
     *
     * @return bool
     */
    public function canBeDeleted(): bool
    {
        return $this->can_be_deleted;
    }

    public function setHomeBgLocation(string $location) {
        $this->homebg_location = $location;
    }

    public function getHomeBgLocation() : string {
        return $this->homebg_location;
    }

    public function toArray() : array
    {
        return
        [
            'id' => $this->id,
            'name' => $this->name,
            'author' => $this->author,
            'css' => $this->css,
            'canBeDeleted' => $this->can_be_deleted,
            'homeBgLocation' => $this->homebg_location,
            'repr' => $this->name.'@'.$this->author
        ];
    }
}
