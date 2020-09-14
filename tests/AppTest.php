<?php

namespace App\Tests;

use App\Entity\LettreMotiv;
use App\Entity\Poste;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppTest extends WebTestCase
{
    public function testJsonPostesForAutocompleteField()
    {
        $client = static::createClient();
        $client->request('GET', '/postes.json');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $data = [
            ['nom' => 'développeur mobile'],
            ['nom' => 'développeur web'],
            ['nom' => 'développeur web et mobile'],
        ];
        $this->assertEquals($data, json_decode($client->getResponse()->getContent(), true));
    }

    public function testForDocxToPdf()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $form = $crawler->selectButton('Envoyer')->form();
        $form['motivation[NomEntreprise]']->setValue('Apple');
        $form['motivation[adresse]']->setValue('54 rue du calvaire');
        $form['motivation[villeCodeP]']->setValue('97000 Paris');
        $form['motivation[NomPoste]']->setValue('développeur web et mobile');
        $form['motivation[wordFilename]']->upload('public/test.docx');

        $client->submit($form);

        $this->assertEquals('', $client->getResponse()->getContent());

        $motiv = self::$container->get('doctrine')->getManager()->getRepository(LettreMotiv::class)
            ->findOneBy(['NomEntreprise' => 'Apple']);

        $this->assertSame('54 rue du calvaire', $motiv->getAdresse());

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }

    public function testForDocxToPdfExistingPoste()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $form = $crawler->selectButton('Envoyer')->form();
        $form['motivation[NomEntreprise]']->setValue('Apple');
        $form['motivation[adresse]']->setValue('54 rue du calvaire');
        $form['motivation[villeCodeP]']->setValue('97000 Paris');
        $form['motivation[NomPoste]']->setValue('développeur web');
        $form['motivation[wordFilename]']->upload('public/test.docx');

        $client->submit($form);

        $this->assertEquals('', $client->getResponse()->getContent());

        $motiv = self::$container->get('doctrine')->getManager()->getRepository(Poste::class)
            ->findBy(['nom' => 'développeur web']);

        $this->assertSame(1, count($motiv));
        $this->assertSame('développeur web', $motiv[0]->getNom());

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }

    public function testForDocxToPdfNewPoste()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $form = $crawler->selectButton('Envoyer')->form();
        $form['motivation[NomEntreprise]']->setValue('Apple');
        $form['motivation[adresse]']->setValue('54 rue du calvaire');
        $form['motivation[villeCodeP]']->setValue('97000 Paris');
        $form['motivation[NomPoste]']->setValue('développeur web et mobile spécialisé WINDEV');
        $form['motivation[wordFilename]']->upload('public/test.docx');

        $client->submit($form);

        $this->assertEquals('', $client->getResponse()->getContent());

        $motiv = self::$container->get('doctrine')->getManager()->getRepository(Poste::class)
            ->findOneBy(['nom' => 'développeur web et mobile spécialisé WINDEV']);

        $this->assertSame('développeur web et mobile spécialisé WINDEV', $motiv->getNom());

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }

    public function testForDocxToPdfWrongFileType()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $form = $crawler->selectButton('Envoyer')->form();
        $form['motivation[NomEntreprise]']->setValue('Apple');
        $form['motivation[adresse]']->setValue('54 rue du calvaire');
        $form['motivation[villeCodeP]']->setValue('97000 Paris');
        $form['motivation[NomPoste]']->setValue('développeur web et mobile spécialisé WINDEV');
        $form['motivation[wordFilename]']->upload('public/test.txt');

        $crawler = $client->submit($form);

        $newCrawler = $crawler->filter('span.help.is-danger')
            ->text()
        ;
        $this->assertEquals('Le fichier "test.txt" n\'est pas un fichier Word (.docx) !', $newCrawler);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }
}
