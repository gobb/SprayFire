<?php

/**
 * A test case
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Test\Cases\Controller\FireController;

class BaseTest extends \PHPUnit_Framework_TestCase {

    public function testGivingAndGettingDirtyData() {
        $name = 'SprayFire';
        $title = 'MRC Framework';
        $description = 'A unit-tested PHP 5.3+ framework';
        $data = \compact('name', 'title', 'description');

        $Controller = $this->getController();
        $Controller->giveDirtyData($data);
        $actual = $Controller->getDirtyData();
        $this->assertSame($data, $actual);
    }

    public function testGivingAndGettingCleanData() {
        $name = 'Charles';
        $title = 'Benevolent Dictator for Life';
        $description = 'A nerd playing video games or coding.';
        $data = \compact('name', 'title', 'description');

        $Controller = $this->getController();
        $Controller->giveCleanData($data);
        $actual = $Controller->getCleanData();
        $this->assertSame($data, $actual);
    }

    public function testGivingMultipleDataSets() {
        $nameOne = 'SprayFire';
        $titleOne = 'MRC Framework';
        $descriptionOne = 'A unit-tested PHP 5.3+ framework';
        $dataOne = \compact('nameOne', 'titleOne', 'descriptionOne');
        $nameTwo = 'Charles';
        $titleTwo = 'Benevolent Dictator for Life';
        $descriptionTwo = 'A nerd playing video games or coding.';
        $dataTwo = \compact('nameTwo', 'titleTwo', 'descriptionTwo');
        $data = \compact('nameOne', 'titleOne', 'descriptionOne', 'nameTwo', 'titleTwo', 'descriptionTwo');

        $Controller = $this->getController();
        $Controller->giveDirtyData($dataOne);
        $Controller->giveDirtyData($dataTwo);
        $actual = $Controller->getDirtyData();
        $this->assertSame($data, $actual);
    }

    protected function getController() {
        return new \SprayFire\Test\Cases\Controller\FireController\Base();
    }

}

class Base extends \SprayFire\Controller\FireController\Base {

    public function getResponderName() {

    }

}