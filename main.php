<?php

interface Animal
{

}

interface GiveMilk
{
    /**
     * @return mixed
     */
    public function getMilk();
}

interface GiveEggs
{
    /**
     * @return mixed
     */
    public function getEggs();
}

/**
 * class Cow
 */
class Cow implements Animal, GiveMilk
{
    public $id;

    /**
     * Cow constructor.
     */
    public function __construct()
    {
        $this->id = rand(0, 6);
    }

    /**
     * A cow can produce 8-12 liters of milk
     */
    public function getMilk()
    {
        return rand(8, 12);
    }
}

/**
 * class Chicken
 */
class Chicken implements Animal, GiveEggs
{
    public $id;

    /**
     * Chicken constructor.
     */
    public function __construct()
    {
        $this->id = rand(0, 6);
    }

    /**
     * A chicken can carry 0-1 eggs per clutch
     */
    public function getEggs()
    {
        return rand(0, 1);
    }
}

interface Storage
{
    /**
     * @param $liters
     *
     * @return mixed
     */
    public function addMilk($liters);

    /**
     * @param $eggsCount
     *
     * @return mixed
     */
    public function addEggs($eggsCount);

    /**
     * @return mixed
     */
    public function getFreeForMilk();

    /**
     * @return mixed
     */
    public function getFreeForEggs();

    /**
     * @return mixed
     */
    public function howManyMilk();

    /**
     * @return mixed
     */
    public function howManyEggs();
}

/**
 * class Barn
 */
class Barn implements Storage
{
    private $milkLiters = 0;
    private $eggsCount = 0;
    private $milkTotal = 0;
    private $eggsTotal = 0;

    /**
     * Barn constructor.
     *
     * @param $milkTotal
     * @param $eggsTotal
     */
    public function __construct($milkTotal, $eggsTotal)
    {
        $this->milkTotal = $milkTotal;
        $this->eggsTotal = $eggsTotal;
    }

    /**
     * @param $liters
     */
    public function addMilk($liters)
    {
        $free = $this->getFreeForMilk();

        if ($free === 0) {
            return;
        }

        if ($free < $liters) {
            $this->milkLiters = $this->milkTotal;

            return;
        }

        $this->milkLiters += $liters;
    }

    /**
     * @param $eggsCount
     */
    public function addEggs($eggsCount)
    {
        $free = $this->getFreeForEggs();

        if ($free === 0) {
            return;
        }

        if ($free < $eggsCount) {
            $this->eggsCount = $this->eggsTotal;

            return;
        }

        $this->eggsCount += $eggsCount;
    }

    /**
     * @return int
     */
    public function getFreeForMilk()
    {
        return $this->milkTotal - $this->milkLiters;
    }

    /**
     * @return int
     */
    public function getFreeForEggs()
    {
        return $this->eggsTotal - $this->eggsCount;
    }

    /**
     * @return int
     */
    public function howManyMilk()
    {
        return $this->milkLiters;
    }

    /**
     * @return int
     */
    public function howManyEggs()
    {
        return $this->eggsCount;
    }
}

/**
 * class Farm
 */
class Farm
{
    private $name;
    private $storage;
    private $animals = [];

    /**
     * Farm constructor.
     *
     * @param string  $name
     * @param Storage $storage
     */
    public function __construct($name, Storage $storage)
    {
        $this->name    = $name;
        $this->storage = $storage;
    }

    /**
     * @return mixed
     */
    public function returnMilk()
    {
        return $this->storage->howManyMilk();

    }

    /**
     * @return mixed
     */
    public function returnEggs()
    {
        return $this->storage->howManyEggs();

    }

    /**
     * add animals
     *
     * @param Animal $animal
     */
    public function addAnimal(Animal $animal)
    {
        $this->animals[] = $animal;
    }


    /**
     * collected products
     */
    public function collectProducts()
    {
        foreach ($this->animals as $animal) {
            if ($animal instanceOf GiveMilk) {
                $milkLiters = $animal->getMilk();
                $this->storage->addMilk($milkLiters);
            }

            if ($animal instanceOf GiveEggs) {
                $eggsCount = $animal->getEggs();
                $this->storage->addEggs($eggsCount);
            }
        }
    }
}

/**
 * create a barn for a milks and an eggs
 */
$barn = new Barn($milkTotal = 300, $eggsTotal = 500);

$farm = new Farm('Farm', $barn);

/**
 * chicken in the farm
 */
for ($i = 0; $i < 20; $i++) {
    $farm->addAnimal(new Chicken());
}

/**
 * cow in the farm
 */
for ($i = 0; $i < 10; $i++) {
    $farm->addAnimal(new Cow());
}

$farm->collectProducts();

echo 'Подсчитываем общее кол-во собранной продукции' . '<br><br>';
echo 'Молоко: ' . $farm->returnMilk() . '<br>';
echo 'Яйца: ' . $farm->returnEggs() . '<br>';
