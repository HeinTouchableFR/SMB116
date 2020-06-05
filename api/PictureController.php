<?php

namespace App\Controller\Api;

use App\Entity\Picture;
use App\Repository\PictureRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationList;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Picture controller.
 * @Route("/", name="api_picture")
 */
class PictureController extends AbstractFOSRestController{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {

        $this->em = $em;
    }

    /**
     * @Route("/delete/{id}", name="admin.picture.delete", methods="DELETE")
     * @param Picture $item
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Picture $item, Request $request){
        $data = json_decode($request->getContent(), true);
        if($this->isCsrfTokenValid('delete' . $item->getId(), $data['_token'])){
            $this->em->remove($item);
            $this->em->flush();
            return new JsonResponse(['success' => 1]);
        }
        return new JsonResponse(['error' => "Token invalide"], 400);

    }

    /**
     * Insert picture in database and copy file into server
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/picture", name="_post_picture")
     * @ParamConverter("picture",
     *     converter="fos_rest.request_body",
     *     options={
     *         "validator"={ "groups"="Create" }
     *     })
     */
    public function postImage(Picture $picture, ConstraintViolationList $violations)
    {
        if (count($violations)) {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }

        $user = $this->getUser();
        $picture->setFilename(time().'.jpg');
        $target_path = 'img/'.$user->getLogin().'/'.$picture->getFilename();
        $imagedata = str_replace('data:image/jpeg;base64,', '', $picture->getImageFile());
        $imagedata = str_replace('data:image/jpg;base64,', '', $imagedata);
        $imagedata = str_replace(' ', '+', $imagedata);
        $imagedata = base64_decode($imagedata);
        $picture->setCode(str_replace('.jpg', '',$picture->getFilename()));
        $picture->setFilename($target_path);
        $picture->setUser($user);
        if (!is_dir('img/' . $user->getLogin())) {
            // dir doesn't exist, make it
            mkdir('img/' . $user->getLogin());
        }
        file_put_contents($target_path, $imagedata);
        $em = $this->getDoctrine()->getManager();

        $em->persist($picture);
        $em->flush();

        return $picture;
    }
/*
    /**
     * @Route("/{id}", name="admin.picture.show")
     * @param Picture $item
     * @return Response
     */
    /*public function index($id, PictureRepository $repository){
        $picture = $repository->findBy([
            'code' => $id
        ]);
        return $this->render('picture.html.twig', [
            'picture' => $picture
        ]);
    }
*/
}
