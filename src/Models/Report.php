<?php
// Модель дисциплины

namespace Pockit\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "agst_reports")]
class Report
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue]
    private int|null $id = null;

    // Предмет дисциплины
    #[ORM\ManyToOne(Subject::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Subject $subject;

    // Тип работы
    #[ORM\ManyToOne(WorkType::class)]
    #[ORM\JoinColumn(nullable: false)]
    private WorkType $work_type;

    // Номер работы
    #[ORM\Column(type: "string")]
    private string $work_number;

    // Комментарий
    #[ORM\Column(type: "string")]
    private string $comment;

    // Дата создания
    #[ORM\Column(type: "date")]
    private \DateTime $created_at;

    // Разметка
    #[ORM\Column(type: "string")]
    private string $markup;

    // Дата документа
    #[ORM\Column(type: "date")]
    private \DateTime $date_for;

    // Скрыт ли
    #[ORM\Column(type: "boolean")]
    private bool $hidden;

    /**
     * Set subject
     *
     * @param string
     */
    public function setSubject(string $subject)
    {
        $this->subject = $subject;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * Set work number
     *
     * @param string
     */
    public function setWorkNumber(string $workNumber)
    {
        $this->work_number = $workNumber;
    }

    /**
     * Get work number
     *
     * @return string
     */
    public function getWorkNumber(): string
    {
        return $this->work_number;
    }

    /**
     * Set comment
     *
     * @param string
     */
    public function setComment(string $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * Set created at
     *
     * @param DateTimeImmutable
     */
    public function setCreatedAt(DateTimeImmutable $createdAt)
    {
        $this->created_at = $createdAt;
    }

    /**
     * Get created at
     *
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * Set created at
     *
     * @param WorkType
     */
    public function setWorkType(WorkType $workType)
    {
        $this->work_type = $workType;
    }

    /**
     * Get created at
     *
     * @return WorkType
     */
    public function getWorkType(): WorkType
    {
        return $this->work_type;
    }

    /**
     * Set markup
     *
     * @param string
     */
    public function setMarkup(string $markup)
    {
        $this->markup = $markup;
    }

    /**
     * Get markup
     *
     * @return string
     */
    public function getMarkup(): string
    {
        return $this->markup;
    }

    /**
     * Set date for
     *
     * @param DateTimeImmutable
     */
    public function setDateFor(DateTimeImmutable $dateFor)
    {
        $this->date_for = $dateFor;
    }

    /**
     * Get date for
     *
     * @return DateTimeImmutable
     */
    public function getDateFor(): DateTimeImmutable
    {
        return $this->date_for;
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
}
