<?php


namespace App\DataFixtures;


use App\ServiceInterface\DatatreeServiceInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;

class InitialFixtures extends Fixture
{

    protected $service;

    public function __construct( DatatreeServiceInterface $datatreeService)
    {
        $this->service = $datatreeService;
    }

    public function load(ObjectManager $manager)
    {

        $finder = new Finder();
        $finder->in(__DIR__ . '/../../sql/');
        $finder->name('api_base_data.sql');
//        $finder->name('common_config.sql');
        $finder->files();
        $conn = $this->service->getEntityManager()->getConnection();
        foreach ($finder as $file) {
            echo "Importing: {$file->getBasename()} " . PHP_EOL;
            $sqlFile = $file->getContents();
            $sqlArr = explode(";", $sqlFile);
            foreach ($sqlArr as $sql) {
                $sql = trim($sql);
                if (!empty($sql)) {
                    $result = $conn->exec($sql);
                    var_dump($result);
                    echo "Exec Effect ".$result." Rows.\n";
                }
            }

        }
        echo "Initial Database Success \n";
    }
//
//    public static function getGroups(): array
//    {
//        return ['init'];
//    }
}
