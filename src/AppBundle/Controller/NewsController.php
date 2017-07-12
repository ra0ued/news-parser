<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Hashtag;
use AppBundle\Entity\News;
use AppBundle\Entity\SearchModel;
use AppBundle\Repository\HashtagRepository;
use AppBundle\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class NewsController extends Controller
{
    /**
     * @Route("/{page}", name="index", requirements={"page": "\d+"})
     * @param int $page
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(int $page = 1, Request $request)
    {
        /** @var NewsRepository $newsRepository */
        $newsRepository = $this->getDoctrine()->getRepository(News::class);
        $news = $newsRepository->getAllNews($page);

        /** @var HashtagRepository $hashtagsRepository */
        $hashtagsRepository = $this->getDoctrine()->getRepository(Hashtag::class);
        $hashtags = $hashtagsRepository->findPopular();

        $search = new SearchModel();

        $searchForm = $this->createFormBuilder($search)
            ->add('keyword', SearchType::class)
            ->add('search', SubmitType::class, ['label' => 'Поиск'])
            ->getForm();

        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchModel = $searchForm->getData();

            $news = $newsRepository->findByText($searchModel->keyword);
        }

        $limit = 20;
        $maxPages = ceil(count($news) / $limit);
        $thisPage = $page;

        return $this->render('news/index.html.twig', [
            'maxPages' => $maxPages,
            'thisPage' => $thisPage,
            'form' => $searchForm->createView(),
            'news' => $news,
            'hashtags' => $hashtags,
        ]);
    }

    /**
     * @Route("/{tag}", name="news", requirements={"page": "\w+\d+"})
     * @param $tag
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newsAction($tag)
    {
        /** @var NewsRepository $newsRepository */
        $newsRepository = $this->getDoctrine()->getRepository(News::class);
        $news = $newsRepository->findByHashtag($tag);

        return $this->render('news/hashtag.html.twig', [
            'news' => $news,
            'tag' => $tag,
        ]);
    }
}