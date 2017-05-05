<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Form;
use AppBundle\Products\Command\NewProductCommand;
use Qweluke\CSVImporterBundle\Exception\InvalidProductException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProductController
 * @package AppBundle\Controller\Admin
 *
 * @Route("/admin")
 */
class ProductController extends Controller
{


    /**
     * @Route("/new-product")
     * @Security("has_role('ROLE_USER')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {

        $object = new NewProductCommand();
        $formProduct = $this->createForm(Form\Product::class, $object);
        $formProduct->handleRequest($request);

        if ($formProduct->isSubmitted() && $formProduct->isValid()) {
            try {
                $this->get('product_handler')->handle($formProduct->getData());
                return $this->redirectToRoute('homepage');
            } catch (InvalidProductException $ex) {

            }
        }

        return $this->render(':admin:productForm.html.twig', [
            'formProduct' => $formProduct->createView()
        ]);
    }
}
