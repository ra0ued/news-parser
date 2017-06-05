<?php

namespace AppBundle\Controller;

use AppBundle\Entity\SearchModel;
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
        $news = $this->getDoctrine()
            ->getRepository('AppBundle:News')
            ->getAllNews($page);

        $hashtags = $this->getDoctrine()
            ->getRepository('AppBundle:Hashtag')
            ->findPopular();

        $search = new SearchModel();

        $searchForm = $this->createFormBuilder($search)
            ->add('keyword', SearchType::class)
            ->add('search', SubmitType::class, ['label' => 'Поиск'])
            ->getForm();

        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchModel = $searchForm->getData();

            $news = $this->getDoctrine()
                ->getRepository('AppBundle:News')
                ->findByText($searchModel->keyword);
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
     * @Route("/{tag}", name="news")
     * @param $tag
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newsAction($tag)
    {
        $news = $this->getDoctrine()
            ->getRepository('AppBundle:News')
            ->findByHashtag($tag);

        return $this->render('news/hashtag.html.twig', [
            'news' => $news,
            'tag' => $tag,
        ]);
    }
}