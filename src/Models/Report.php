<?php
// Модель отчёта

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

    // Предмет отчёта
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
    #[ORM\Column(type: "text")]
    private string $markup;

    // Дата документа
    #[ORM\Column(type: "date")]
    private \DateTime $date_for;

    // Скрыт ли
    #[ORM\Column(type: "boolean")]
    private bool $hidden;

    public function getId() {
        return $this->id;
    }

    /**
     * Set subject
     *
     * @param string
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
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
     * @param \DateTime
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->created_at = $createdAt;
    }

    /**
     * Get created at
     *
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    /**
     * Set work type
     *
     * @param WorkType
     */
    public function setWorkType(WorkType $workType)
    {
        $this->work_type = $workType;
    }

    /**
     * Get work type
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
     * @param \DateTime
     */
    public function setDateFor(\DateTime $dateFor)
    {
        $this->date_for = $dateFor;
    }

    /**
     * Get date for
     *
     * @return \DateTime
     */
    public function getDateFor(): \DateTime
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

    public function toArray() : array {
        return [
            'id' => $this->id,
            'subject' => $this->subject->toArray(),
            'workType' => $this->work_type->toArray(),
            'workNumber' => $this->work_number,
            'comment' => $this->comment,
            'createdAt' => $this->created_at,
            'dateFor' => $this->date_for,
            'hidden' => $this->hidden
        ];
    }
}
