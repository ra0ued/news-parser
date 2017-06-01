<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Hashtag
 *
 * @ORM\Table(name="hashtag")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HashtagRepository")
 */
class Hashtag
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=255)
     */
    private $text;

    /**
     * Many Hashtags have Many News.
     * @ORM\ManyToMany(targetEntity="News", mappedBy="hashtags")
     */
    private $news;

    /**
     * Hashtag constructor.
     */
    public function __construct() {
        $this->news = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Hashtag
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set news
     *
     * @param string $news
     * @return Hashtag
     */
    public function setNews($news)
    {
        $this->news = $news;

        return $this;
    }

    /**
     * Get news
     *
     * @return string 
     */
    public function getNews()
    {
        return $this->news;
    }

    public function addNews(News $news)
    {
        $this->news[] = $news;
    }
}
