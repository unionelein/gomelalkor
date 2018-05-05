<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AppConfig;
use AppBundle\Entity\Product;
use AppBundle\Entity\Category;
use AppBundle\Services\AppManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    const COUNT_PER_PAGE = 20;

    /**
     * @Route("/prokat/{n}", name="prokat", defaults={"n"=1}, requirements={"n"="\d+"})
     *
     * @return Response
     */
    public function prokatAction($n)
    {
        return $this->redirectToRoute("homepage", ['typeName' => AppManager::TYPE_URL[AppManager::PROKAT]], 301);
    }

    /**
     * @Route("/instrumenti/{n}", name="instrumenti", defaults={"n"=1}, requirements={"n"="\d+"})
     *
     * @return Response
     */
    public function instrumentiAction($n)
    {
        return $this->redirectToRoute("homepage", ['typeName' => AppManager::TYPE_URL[AppManager::PROKAT], 'page' => $n], 301);
    }

    /**
     * @Route("/pokypka/{n}", name="pokypka", defaults={"n"=1}, requirements={"n"="\d+"})
     *
     * @return Response
     */
    public function pokypkaAction($n)
    {
        return $this->redirectToRoute("homepage", ['typeName' => AppManager::TYPE_URL[AppManager::POKUPKA]], 301);
    }

    /**
     * @Route("/stroymaterialy/{n}", name="stroymaterialy", defaults={"n"=1}, requirements={"n"="\d+"})
     *
     * @return Response
     */
    public function stroymaterialyAction($n)
    {
        return $this->redirectToRoute('homepage', ['typeName' => AppManager::TYPE_URL[AppManager::POKUPKA], 'page' => $n], 301);
    }

    /**
     * @Route("/stroimaterialy/{n}", name="stroimaterialy", defaults={"n"=1}, requirements={"n"="\d+"})
     *
     * @return Response
     */
    public function stroimaterialyAction($n)
    {
        return $this->redirectToRoute('homepage', ['typeName' => AppManager::TYPE_URL[AppManager::POKUPKA], 'page' => $n], 301);
    }

    /**
     * @Route("{typeName}/{categorySlug}/{page}", name="products_by_category_and_type", defaults={"categorySlug" = null, "page" = 1}, requirements={"page"="\d+","typeName"="(instrumenti|stroymaterialy)"})
     *
     * @param $categorySlug
     * @return string
     */
    public function getProductsByCategoryAndTypeAction($typeName, $categorySlug, $page)
    {
        return $this->redirectToRoute('products_by_category', ['categorySlug' => $categorySlug, 'page' => $page], 301);
    }

    /**
     * @Route("/contacts", name="contacts")
     *
     * @return Response
     */
    public function contactsAction()
    {
        $config = $this->getDoctrine()->getRepository(AppConfig::class)->findOneBy(['name' => AppConfig::CONTACTS_INFO]);
        $info   = $config ? $config->getValue() : '';

        return $this->render('@App/contacts.html.twig', [
            'info' => $info,
        ]);
    }

    /**
     * @Route("/{page}", name="mainpage", defaults={"page" = 1}, requirements={"page"="\d+"})
     *
     * @param $page
     * @return Response
     */
    public function mainAction($page)
    {
        if ($page < 1) {
            throw $this->createNotFoundException('404');
        }

        $em = $this->getDoctrine()->getManager();

        $products   = $em->getRepository(Product::class)->findBy([], ['id' => 'desc']);
        $categories = $em->getRepository(Category::class)->getCategories();

        $countOfPages = ceil(count($products)/self::COUNT_PER_PAGE);
        $products     = array_slice($products, ($page-1)*self::COUNT_PER_PAGE, self::COUNT_PER_PAGE);

        return $this->render('@App/main.html.twig', [
            'products'     => $products,
            'categories'   => $categories,
            'countOfPages' => $countOfPages,
            'currentPage'  => $page,
        ]);
    }

    /**
     * @Route("/{typeName}/{page}", name="homepage", defaults={"page" = 1}, requirements={"page"="\d+","typeName"="(instrumenty|strojmaterialy)"})
     *
     * @param $typeName
     * @param $page
     * @return JsonResponse|Response
     */
    public function pageAction($typeName, $page)
    {
        if ($page < 1 || !\in_array($typeName, AppManager::TYPE_URL)) {
            throw $this->createNotFoundException('404');
        }

        $em = $this->getDoctrine()->getManager();

        $type       = AppManager::getTypeByUrl($typeName);
        $products   = $em->getRepository(Product::class)->findBy(['type' => $type], ['id' => 'desc']);
        $categories = $em->getRepository(Category::class)->getCategoriesByType($type);

        $countOfPages = ceil(count($products)/self::COUNT_PER_PAGE);
        $products     = array_slice($products, ($page-1)*self::COUNT_PER_PAGE, self::COUNT_PER_PAGE);

        $config        = $em->getRepository(AppConfig::class)->findOneBy(['name' => AppConfig::DELIVERY_PRICE]);
        $deliveryPrice = $config ? $config->getValue() : 0;

        switch ($type) {
            case AppManager::POKUPKA:
                $title = 'Магазин стройматериалов. Купить стройматериалы в Гомеле | Алькор';
                $description = 'Хотите купить стройматериалы по вменяемым ценами и без надувательства с качеством? Вам сюда. При заказе от ' . $deliveryPrice . 'р доставка - бесплатно!';
                break;
            case AppManager::PROKAT:
                $title = 'Прокат инструмента в Гомеле. Аренда электроинструмента | Алькор';
                $description = 'Прокат электроинструмента в Гомеле. Широкий выбор: бензопилы, перфораторы, дрели и др. При заказе от ' . $deliveryPrice . 'р доставка - бесплатно!';
                break;
            default:
                $title = null;
                $description = null;
        }

        return $this->render('@App/main.html.twig', [
            'products'     => $products,
            'categories'   => $categories,
            'type'         => $type,
            'countOfPages' => $countOfPages,
            'currentPage'  => $page,
            'title'        => $title,
            'description'  => $description,
        ]);
    }

    /**
     * @Route("/{categorySlug}/{page}", name="products_by_category", defaults={"categorySlug" = null, "page" = 1}, requirements={"page"="\d+"})
     *
     * @param $categorySlug
     * @param $page
     * @return Response
     */
    public function getProductsByCategoryAction($categorySlug, $page)
    {
        if ($page < 1) {
            throw $this->createNotFoundException('404');
        }

        $em = $this->getDoctrine()->getManager();

        $category   = $em->getRepository(Category::class)->findOneBy(['slug' => $categorySlug]);

        if (!$category) {
            throw $this->createNotFoundException('404');
        }

        $products   = $em->getRepository(Product::class)->findBy(['category' => $category], ['id' => 'desc']);
        $categories = $em->getRepository(Category::class)->getCategories();

        $countOfPages = ceil(count($products)/self::COUNT_PER_PAGE);
        $products     = array_slice($products, ($page-1)*self::COUNT_PER_PAGE, self::COUNT_PER_PAGE);

        $config        = $em->getRepository(AppConfig::class)->findOneBy(['name' => AppConfig::DELIVERY_PRICE]);
        $deliveryPrice = $config ? $config->getValue() : 0;

        $type = \count($products) ? $products[0]->getType() : null;

        switch ($type) {
            case AppManager::POKUPKA:
                $title = 'Купить ' . $category->getName() . ' в Гомеле | Алькор';
                $description = 'Магазин стройматериалов. Купить ' . $category->getName().' по доступным ценам. При заказе от ' . $deliveryPrice . 'р доставка - бесплатно!';
                break;
            case AppManager::PROKAT:
                $title = 'Прокат ' . $category->getName() . ' в Гомеле | Алькор';
                $description = 'Прокат электроинструмента в Гомеле. Аренда ' . $category->getName() . '. При заказе от ' . $deliveryPrice . 'р доставка - бесплатно!';
                break;
            default:
                $title = null;
                $description = null;
        }

        return $this->render('@App/main.html.twig', [
            'products'        => $products,
            'categories'      => $categories,
            'currentCategory' => $categorySlug,
            'countOfPages'    => $countOfPages,
            'currentPage'     => $page,
            'title'           => $title,
            'description'     => $description,
        ]);
    }

    /**
     * @Route("/{categorySlug}/{productSlug}", name="product")
     *
     * @param  $categorySlug
     * @param  $productSlug
     * @return Response
     */
    public function productAction($categorySlug, $productSlug)
    {
        $em = $this->getDoctrine()->getManager();

        $product    = $em->getRepository(Product::class)->findProductByCategorySlugAndProductSlug($categorySlug, $productSlug);
        $categories = $em->getRepository(Category::class)->getCategories();

        if (!$product) {
            throw $this->createNotFoundException('404');
        }

        $config        = $em->getRepository(AppConfig::class)->findOneBy(['name' => AppConfig::DELIVERY_PRICE]);
        $deliveryPrice = $config ? $config->getValue() : 0;

        switch ($product->getType()) {
            case AppManager::POKUPKA:
                $title = 'Купить ' . $product->getName() . ' в Гомеле | Алькор';
                $description = 'Магазин стройматериалов. Купить ' . $product->getName() . '.Качественные стройматериалы по доступным ценам. При заказе от ' . $deliveryPrice . 'р доставка - бесплатно!';
                break;
            case AppManager::PROKAT:
                $title = 'Прокат ' . $product->getName() . ' в Гомеле | Алькор';
                $description = 'Прокат электроинструмента в Гомеле. ' . $product->getName() . ' в прокат. При заказе от ' . $deliveryPrice . 'р доставка - бесплатно!';
                break;
            default:
                $title = null;
                $description = null;
        }

        return $this->render('@App/main.html.twig', [
            'page'            => 'product',
            'product'         => $product,
            'categories'      => $categories,
            'type'            => $product->getType(),
            'currentCategory' => $categorySlug,
            'deliveryPrice'   => $deliveryPrice,
            'title'           => $title,
            'description'     => $description,
        ]);
    }
}
