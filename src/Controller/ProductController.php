<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;

class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product_index")
     */
    public function index()
    {
        $data=[];
        $data['products']=$this->getDoctrine()
        ->getManager()
        ->getRepository(Product::class)
        ->findAll();
        return $this->render('product/index.html.twig', $data);
    }

    /**
     * @Route("/create", name="product_create")
     */
    public function create()
    {
        return $this->render('product/create.html.twig', []);
    }

    /**
     * @Route("/save_create", name="product_save_create")
     */
    public function saveCreate(Request $request)
    {
        if($request->isMethod('POST')){
            $product= new Product();
            $product->setName($request->request->get('name',''));
            $product->setDescription($request->request->get('description',''));
            $product->setPrice($request->request->get('price',0));
            $this->getDoctrine()->getManager()->persist($product);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('product_show',['id'=>$product->getId()]);
        }
        return $this->redirectToRoute('product_index');
    }

    /**
     * @Route("/update/{id}", name="product_update")
     */
    public function update($id)
    {

        $data=[];
        $data['product']=$this->getDoctrine()
        ->getManager()
        ->getRepository(Product::class)
        ->find($id);
        return $this->render('product/update.html.twig', $data);
    }

    /**
     * @Route("/save_update/{id}", name="product_save_update")
     */
    public function saveUpdate($id,Request $request)
    {
        if($request->isMethod('POST')){
            $product= $this->getDoctrine()
            ->getManager()
            ->getRepository(Product::class)
            ->find($id);
            $product->setName($request->request->get('name',''));
            $product->setDescription($request->request->get('description',''));
            $product->setPrice($request->request->get('price',0));
            $this->getDoctrine()->getManager()->persist($product);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('product_show',['id'=>$id]);
        }
        return $this->redirectToRoute('product_index');
    }

    /**
     * @Route("/show/{id}", name="product_show")
     */
    public function show($id,Request $request)
    {
        $data=[];
        $data['product']=$this->getDoctrine()
        ->getManager()
        ->getRepository(Product::class)
        ->find($id);
        return $this->render('product/show.html.twig', $data);
    }

    /**
     * @Route("/remove/{id}", name="product_remove")
     */
    public function remove($id)
    {
        $product=$this->getDoctrine()
        ->getManager()
        ->getRepository(Product::class)
        ->find($id);
        $this->getDoctrine()->getManager()->remove($product);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('product_index');
    }
}
