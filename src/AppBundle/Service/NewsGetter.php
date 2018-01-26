<?php

namespace AppBundle\Service;

use Abraham\TwitterOAuth\TwitterOAuth;
use AppBundle\Entity\Hashtag;
use AppBundle\Entity\News;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

class NewsGetter
{
    private $connection;
    /**
     * @var Container
     */
    private $container;

    public function __construct($consumerKey, $consumerSecret, $oauthToken, $oauthTokenSecret, Container $container)
    {
        $this->connection = new TwitterOAuth(
            $consumerKey,
            $consumerSecret,
            $oauthToken,
            $oauthTokenSecret);
        $this->container = $container;
    }

    public function getTweet(int $count = 1, string $username = 'bbcrussian')
    {
        return $this->connection->get("statuses/user_timeline", ["screen_name" => $username, "count" => $count, "exclude_replies" => true]);
    }

    public function updateNews()
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->container->get('doctrine.orm.default_entity_manager');
        $tweets = $this->getTweet(10);

        foreach ($tweets as $tweet) {
            $news = $entityManager->getRepository(News::class)
                ->findOneBy(['tweetId' => $tweet->id_str]);

            if (!$news) {
                $news = new News();
                $news->setTweetId($tweet->id_str);
                $time = new \DateTime($tweet->created_at);
                $news->setTime($time);
                $news->setText($tweet->text);

                if (!empty($tweet->entities->hashtags)) {
                    $hashtags = $tweet->entities->hashtags;
                    foreach ($hashtags as $hashtag) {
                        $tag = $entityManager->getRepository(Hashtag::class)
                            ->findOneBy(['text' => $hashtag->text]);

                        if (!$tag) {
                            $tag = new Hashtag();
                            $tag->setText($hashtag->text);
                        }

                        $tag->addNews($news);
                        $entityManager->persist($tag);

                        $news->addHashtag($tag);
                    }
                }

                $entityManager->persist($news);
            }
        }

        $entityManager->flush();
    }
}