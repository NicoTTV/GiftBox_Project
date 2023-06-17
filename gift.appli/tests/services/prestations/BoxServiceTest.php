<?php

namespace gift\test\services\prestations;

use Faker\Factory;
use gift\app\models\Box;
use gift\app\models\Prestation;
use gift\app\services\box\BoxService;
use gift\app\services\Exceptions\BoxServiceBadDataException;
use gift\app\services\Exceptions\BoxServiceUpdateFailException;
use Illuminate\Database\Capsule\Manager;
use PHPUnit\Framework\TestCase;

require_once __DIR__."/../../../src/vendor/autoload.php";

class BoxServiceTest extends TestCase
{

    private static array $boxes = [];

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $db = new Manager();
        $db->addConnection(parse_ini_file(__DIR__ . '/../../conf/gift.db.conf.ini'));
        $db->setAsGlobal();
        $db->bootEloquent();

        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 4; $i++) {
            $presta = new Prestation();
            $presta->id = $faker->uuid();
            $presta->url = $faker->url;
            $presta->unite = $faker->word();
            $presta->libelle = $faker->word();
            $presta->description = $faker->sentence(5);
            $presta->tarif = $faker->numberBetween(10, 100);
            $presta->saveOrFail();
        }

    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
        foreach (Box::all() as $box) {
            $box->delete();
        }

        foreach (Prestation::all() as $presta) {
            $presta->delete();
        }
    }
    /**
     * @throws BoxServiceUpdateFailException
     * @throws BoxServiceBadDataException
     */
    public function testCreateBoxe()
    {
        $boxService = new BoxService();
        $faker = Factory::create('fr_FR');
        $libelle = $faker->word();
        $description = $faker->sentence(5);
        $kdo = $faker->numberBetween(0,1);
        $message_kdo = $faker->sentence(10);
        $url = $faker->url;

        $boxService->creation(['libelle' => $libelle, 'description' => $description,
            'kdo' => $kdo, 'message_kdo' => $message_kdo, 'url' => $url]);

        $this->assertEquals($libelle,Box::latest()->first()->libelle);
        $this->assertEquals($description, Box::latest()->first()->description);
        $this->assertEquals(Box::CREATED, Box::latest()->first()->statut);
        $this->assertEquals(0, Box::latest()->first()->montant);

        $this->assertEmpty(Box::latest()->first()->prestation()->get());
    }

    /**
     * @throws BoxServiceUpdateFailException
     * @throws BoxServiceBadDataException
     */
    public function testAjoutPrestationBoxe()
    {
        $boxService = new BoxService();
        $faker = Factory::create('fr_FR');
        $boxService->creation(['libelle' => $faker->word(), 'description' => $faker->sentence(5),
            'kdo' => $faker->numberBetween(0,1), 'message_kdo' => $faker->sentence(10), 'url' => $faker->url]);

        $prestations = Prestation::all();

        $this->assertCount(4, $prestations);

        $montant = 0;
        $idBox = Box::latest()->first()->id;
        foreach ($prestations as $prestation) {
            $boxService->ajoutPrestation($prestation->id, $idBox);
            $montant += $prestation->tarif;
            $this->assertEquals($prestation->id, Box::find($idBox)->prestation()->get()->last()->id);
        }

        $this->assertEquals(1, Box::find($idBox)->prestation()->find($prestations->last()->id)->pivot->quantite);

        $boxService->ajoutPrestation($prestations->last()->id, $idBox);
        $montant += $prestations->last()->tarif;

        $this->assertEquals(2, Box::find($idBox)->prestation()->find($prestations->last()->id)->pivot->quantite);

        $this->assertEquals($montant, Box::find($idBox)->montant);
    }

}