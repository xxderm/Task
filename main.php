<?php

enum AnimalType {
	case COW;
	case CHICKEN;
}

abstract class Production {
	public function __construct(int $count) {
		$this->mCount = $count;
	}
	abstract public function Title() : string;
	public function GetCount() : int {
		return $this->mCount;
	}
	protected int $mCount;
}

class Milk extends Production {
	public function __construct(int $count) {
		parent::__construct($count);
	}
	public function Title() : string {
		return "Milk";
	}
}

class Egg extends Production {
	public function __construct(int $count) {
		parent::__construct($count);
	}
	public function Title() : string {
		return "Egg";
	}
}

abstract class Animal {
	abstract public function Produce() : Production;
	abstract public function Title() : string;
	public function SetId(int $id) {
		$this->mId = $id;
	}
	public function GetId() : int {
		return $this->mId;
	}
	protected $mId;
}

class Chicken extends Animal {
	public function Title() : string {
		return 'Chicken';
	}
	public function Produce() : Production {
		return new Egg(rand(0, 1));
	}
}

class Cow extends Animal {
	public function Title() : string {
		return 'Cow';
	}
	public function Produce() : Production {
		return new Milk(8 + rand(0, 4));
	}
}

class AnimalFactory {
	public static function GetAnimal(AnimalType $type) : ?Animal {
		switch ($type) {
			case AnimalType::COW:
				return new Cow('Cow');
			case AnimalType::CHICKEN:
				return new Chicken('Chicken');
		}
	} 
}

class Farm {
	public function AddAnimal(AnimalType $type) {
		$animal = AnimalFactory::GetAnimal($type);
		$animal->SetId(count($this->mAnimals));
		array_push($this->mAnimals, $animal);
	}
	public function CollectProduction() {
		foreach($this->mAnimals as $value) {
			array_push($this->mProduction, $value->Produce());
		}
	}
	public function GetAnimals() {
		return $this->mAnimals;
	}
	public function GetProduction() {
		return $this->mProduction;
	}
	public function ClearProduction() {
		$this->mProduction = array();
	}
	private array $mAnimals = array();
	private array $mProduction = array();
}

function PrintAnimalInfo(Farm &$myFarm) {
	$animals = $myFarm->GetAnimals();
	$cowCount = 0;
	$chickenCount = 0;
	foreach ($animals as $animal) {
		if ($animal->Title() == 'Cow')
			++$cowCount;
		if ($animal->Title() == 'Chicken')
			++$chickenCount;
	}
	print(PHP_EOL . 'Count Cow: ' . $cowCount . ', Chicken: ' . $chickenCount);
}

function PrintProductInfo(Farm &$myFarm) {
	$eggCount = 0;
	$milkCount = 0;
	for ($i = 0; $i < 7; $i++) 
		$myFarm->CollectProduction();
	
	$production = $myFarm->GetProduction();
	foreach ($production as $product) {
		if ($product->Title() == 'Milk')
			$milkCount += $product->GetCount();
		if ($product->Title() == 'Egg')
			$eggCount += $product->GetCount();
	}
	$myFarm->ClearProduction();
	print(PHP_EOL . 'Count Milk: ' . $milkCount . ', egg: ' . $eggCount);
}

function main() {
	$myFarm = new Farm();

	for ($i = 0; $i < 10; $i++) 
		$myFarm->AddAnimal(AnimalType::COW);
	for ($i = 0; $i < 20; $i++) 
		$myFarm->AddAnimal(AnimalType::CHICKEN);
	PrintAnimalInfo($myFarm);
	
	PrintProductInfo($myFarm);
	
	for ($i = 0; $i < 5; $i++) 
		$myFarm->AddAnimal(AnimalType::CHICKEN);
	$myFarm->AddAnimal(AnimalType::COW);
	PrintAnimalInfo($myFarm);
	
	PrintProductInfo($myFarm);
}

main();


