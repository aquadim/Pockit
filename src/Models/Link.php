<?php
// Модель ссылки

namespace Pockit\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "links")]
class Link
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue]
    private int|null $id = null;

    // Название
    #[ORM\Column(type: "string")]
    private string $name;

    // Значение
    #[ORM\Column(type: "string")]
    private string $href;

    // Скрыт ли
    #[ORM\Column(type: "boolean")]
    private bool $hidden;

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
     * Set value
     *
     * @param string
     */
    public function setHref(string $href)
    {
        $this->href = $href;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getHref(): string
    {
        return $this->href;
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

    public function toArray() : array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'href' => $this->href,
            'hidden' => $this->hidden,
            'repr' => $this->name
        ];
    }
}
