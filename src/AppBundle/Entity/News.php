<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * News
 *
 * @ORM\Table(name="news")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NewsRepository")
 */
class News
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
     * @ORM\Column(name="tweet_id", type="string", length=255, unique=true)
     */
    private $tweetId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="datetime")
     */
    private $time;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * Many News have Many Hashtags.
     * @ORM\ManyToMany(targetEntity="Hashtag", inversedBy="news")
     * @ORM\JoinTable(name="news_hashtags")
     */
    private $hashtags;

    /**
     * News constructor.
     */
    public function __construct() {
        $this->hashtags = new ArrayCollection();
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
     * Set tweetId
     *
     * @param string $tweetId
     * @return News
     */
    public function setTweetId($tweetId)
    {
        $this->tweetId = $tweetId;

        return $this;
    }

    /**
     * Get tweetId
     *
     * @return string 
     */
    public function getTweetId()
    {
        return $this->tweetId;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     * @return News
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return News
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
     * Set hashtags
     *
     * @param string $hashtags
     * @return News
     */
    public function setHashtags($hashtags)
    {
        $this->hashtags = $hashtags;

        return $this;
    }

    /**
     * Get hashtags
     *
     * @return string 
     */
    public function getHashtags()
    {
        return $this->hashtags;
    }

    public function addHashtag(Hashtag $hashtag)
    {
        $hashtag->addNews($this);
        $this->hashtags[] = $hashtag;
    }
}
