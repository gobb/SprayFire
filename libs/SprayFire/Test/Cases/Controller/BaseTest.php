<?php

/**
 * A test case
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Test\Cases\Controller;

class BaseTest extends \PHPUnit_Framework_TestCase {

    public function testGivingAndGettingDirtyData() {
        $name = 'SprayFire';
        $title = 'MRC Framework';
        $description = 'A unit-tested PHP 5.3+ framework';
        $data = \compact('name', 'title', 'description');

        $Controller = new \SprayFire\Controller\Base();
        $Controller->giveDirtyData($data);
        $actual = $Controller->getDirtyData();
        $this->assertSame($data, $actual);
    }

    public function testGivingAndGettingCleanData() {
        $name = 'Charles';
        $title = 'Benevolent Dictator for Life';
        $description = 'A nerd playing video games or coding.';
        $data = \compact('name', 'title', 'description');

        $Controller = new \SprayFire\Controller\Base();
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

        $Controller = new \SprayFire\Controller\Base();
        $Controller->giveDirtyData($dataOne);
        $Controller->giveDirtyData($dataTwo);
        $actual = $Controller->getDirtyData();
        $this->assertSame($data, $actual);
    }

}
