<?php
namespace test\doctrine\model;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity @ORM\Table(name="post")
 * Class DoctrineModelPost
 * @package model
 */
class Post implements \JsonSerializable
{
    /**
     * @ORM\Id @ORM\Column(type="integer",name="id") @ORM\GeneratedValue
     * @var int
     */
    protected $id;
    /**
     * @ORM\Column (type="string", name="title")
     * @var string
     */
    protected $title;
    /**
     * @ORM\Column (type="text", name="content")
     * @var string
     */
    protected $content;

    /**
     * @ORM\ManyToOne (targetEntity="Category", fetch="EAGER")
     * @ORM\JoinTable(name="category_id")
     * @var Category
     */
    protected $category;

    /**
     * @ORM\Column (type="datetime", name="date_created")
     * @var string
     */
    protected $date_created;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content)
    {
        $this->content = $content;
    }


    public function getCategory():Category
    {
        return $this->category;
    }


    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getDateCreated()
    {
        return $this->date_created;
    }

    /**
     * @param $date_created
     */
    public function setDateCreated($date_created)
    {
        $this->date_created = $date_created;
    }


    public function tags(){
        //todo
        return [];
    }

    public function comments(){
        //todo
        return [];
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'content' => $this->getContent()
        ];

    }


}