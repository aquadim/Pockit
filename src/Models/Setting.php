<?php
// Модель настройки

namespace Pockit\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "settings")]
class Setting
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue]
    private int|null $id = null;

    // Название
    #[ORM\Column(type: "string")]
    private string $value;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setValue(string $value)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function toArray() : array
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
            'repr' => $this->id
        ];
    }
}
