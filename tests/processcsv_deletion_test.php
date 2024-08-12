<?php
namespace enrol_oneroster\tests;
defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/config.php');


class processcsv_test extends \advanced_testcase {
    private $testDir;

    protected function setUp(): void
    {
        // Create a temporary directory for testing
        $this->testDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'testDir';

        if (!file_exists($this->testDir)) {
            mkdir($this->testDir);
        }

        // Create some test files and subdirectories
        mkdir($this->testDir . DIRECTORY_SEPARATOR . 'subdir');
        file_put_contents($this->testDir . DIRECTORY_SEPARATOR . 'file1.csv', 'Test file 1');
        file_put_contents($this->testDir . DIRECTORY_SEPARATOR . 'subdir' . DIRECTORY_SEPARATOR . 'file2.csv', 'Test file 2');
    }

    protected function tearDown(): void
    {
        // Clean up after the test, if the directory still exists
        if (file_exists($this->testDir)) {
            $this->delete_directory($this->testDir);
        }
    }
    public function testDeleteDirectory()
    {
        // Ensure the directory exists before deletion
        $this->assertTrue(file_exists($this->testDir));

        // Call the function to delete the directory
        $result = $this->delete_directory($this->testDir);

        // Assert that the directory has been deleted
        $this->assertTrue($result);
        $this->assertFalse(file_exists($this->testDir));
    }

    // The delete_directory function as described
    private function delete_directory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            if (!self::delete_directory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
        return rmdir($dir);
    }
}
