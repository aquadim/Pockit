<?php
// Модель пароля

namespace Pockit\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "passwords")]
class Password
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
    private string $value;

    // IV
    #[ORM\Column(type: "string")]
    private string $iv;

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
    public function setValue(string $value)
    {
        $this->value = $value;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Set iv
     *
     * @param string
     */
    public function setIv(string $iv)
    {
        $this->iv = $iv;
    }

    /**
     * Get iv
     *
     * @return string
     */
    public function getIv(): string
    {
        return $this->iv;
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

    public function setPassword(string $password, string $secret_key) {
        $ivlen = openssl_cipher_iv_length("aes-128-cbc");
		$iv = openssl_random_pseudo_bytes($ivlen);
		$encrypted = openssl_encrypt(
            $password,
            'aes-128-cbc',
            $secret_key,
            $options=0,
            $iv
        );
        $this->iv = $iv;
        $this->value = $encrypted;
    }

    public function toArray() : array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'hidden' => $this->hidden,
            'repr' => $this->name
        ];
    }
}
