<?php

namespace App\Controller;

use App\Entity\LettreMotiv;
use App\Entity\Poste;
use App\Form\Type\MotivationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\String\Slugger\SluggerInterface;

class MotivationController extends AbstractController
{
    /**
     * @Route("/", name="motivation")
     */
    public function index(Request $request, SluggerInterface $slugger)
    {
        setlocale(LC_ALL, 'fr_FR');
        setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

        $lettreMotiv = new LettreMotiv();
        $form = $this->createForm(MotivationType::class, $lettreMotiv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('wordFilename')->getData();
            $mimeTypes = new MimeTypes();

            $mimeTypes = $mimeTypes->getMimeTypes('docx');
            dump($mimeTypes);

            $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->getClientOriginalExtension();
            $filenameWithoutExt = $safeFilename.'-'.uniqid();

            try {
                $brochureFile->move(
                    $this->getParameter('lettre_motiv_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                dump($e->getMessage());
            }
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../public/'.$this->getParameter('lettre_motiv_directory').$newFilename);

            $templateProcessor->setValues([
                'date' => strftime('%e %B %Y'),
                'nom_entreprise' => $form->get('NomEntreprise')->getData(),
                'adresse_entreprise' => ucfirst(strtolower($form->get('adresse')->getData())).'</w:t><w:br/><w:t>'.$form->get('villeCodeP')->getData(),
                'poste' => utf8_decode(strtolower($form->get('NomPoste')->getData())),
            ]);
            $templateProcessor->saveAs($this->getParameter('lettre_motiv_directory').$filenameWithoutExt.'_MODIFIED.docx');

            $phpWord = \PhpOffice\PhpWord\IOFactory::load('../public/'.$this->getParameter('lettre_motiv_directory').$filenameWithoutExt.'_MODIFIED.docx');
            \PhpOffice\PhpWord\Settings::setPdfRendererPath('../vendor/tecnickcom/tcpdf');
            \PhpOffice\PhpWord\Settings::setPdfRendererName('TCPDF');

            $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'PDF');
            $xmlWriter->save('../public/'.$this->getParameter('lettre_motiv_directory').$filenameWithoutExt.'.pdf');

            $filesystem = new Filesystem();
            try {
                $filesystem->remove([
                    '../public/'.$this->getParameter('lettre_motiv_directory').$filenameWithoutExt.'_MODIFIED.docx',
                    '../public/'.$this->getParameter('lettre_motiv_directory').$newFilename,
                ]);
            } catch (IOExceptionInterface $exception) {
            }

            $response = new BinaryFileResponse($this->getParameter('lettre_motiv_directory').$filenameWithoutExt.'.pdf');

            $disposition = HeaderUtils::makeDisposition(
                HeaderUtils::DISPOSITION_ATTACHMENT,
                $safeFilename.'.pdf'
            );
            $response->headers->set('Content-Type', 'application/pdf');

            $response->headers->set('Content-Disposition', $disposition);

            return $response;
        }

        return $this->render('motivation/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/postes.json", name="postes_json")
     */
    public function jsonPoste(Request $request)
    {
        $postes = $this->getDoctrine()->getRepository(Poste::class)->findAll();

        $normalizer = new ObjectNormalizer();
        $encoder = new JsonEncoder();

        $serializer = new Serializer([$normalizer], [$encoder]);

        $response = new Response($serializer->serialize($postes, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['id', 'lettreMotivs']]));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
