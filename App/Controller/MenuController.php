<?php

namespace App\Controller;

use App\Form\MenuFormType;
use App\Model\MenuItem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MenuController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request): Response
    {
        $menuItem = new MenuItem();

        $form = $this->createForm(MenuFormType::class, ['items' => [$menuItem]]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $menuItemSubmit = $form->get('items')->getData();
            $contents = file_get_contents('C:\Users\Mathieu\PhpstormProjects\MenuKaart\App\SavedMenuItems\MenuItems.class');

            if (!$contents){
                $newItemsList = $menuItemSubmit;
            } else{
                $newItemsList = unserialize($contents);
                $newItemsList[] = $menuItemSubmit[0];
            }
            file_put_contents(
                'C:\Users\Mathieu\PhpstormProjects\MenuKaart\App\SavedMenuItems\MenuItems.class',
                serialize($newItemsList),
                FILE_APPEND
            );

            $this->addFlash('success', 'Menu item has been saved.');
        }

        $contents = file_get_contents('C:\Users\Mathieu\PhpstormProjects\MenuKaart\App\SavedMenuItems\MenuItems.class');
        return $this->render('index.html.twig', [
            'form' => $form->createView(),
            !$contents ? : 'menuItems' => unserialize($contents)
        ]);
    }
}