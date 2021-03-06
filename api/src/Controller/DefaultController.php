<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PictureRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('index.html.twig');
    }

    /**
     * @Route("/protected", name="protected")
     * @IsGranted("ROLE_USER")
     */
    public function protected()
    {
        return $this->render('protected.html.twig');
    }

    /**
     * @Route("/admin", name="admin")
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function admin()
    {
        return $this->render('admin.html.twig');
    }

    /**
     * @Route("/image/{id}", name="picture")
     */
    public function ppicture($id, PictureRepository $repository){
        $picture = $repository->findBy([
            'code' => $id
        ]);
        return $this->render('picture.html.twig', [
            'picture' => $picture
        ]);
    }
}
