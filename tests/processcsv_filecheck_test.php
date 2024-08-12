<?php
namespace enrol_oneroster\tests;
defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/config.php');


class processcsv_test extends \advanced_testcase {
    private $testDir;
    private $manifestPath;

    protected function setUp(): void
    {
        // Create a temporary directory for testing
        $this->testDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'testDir';
        if (!file_exists($this->testDir)) {
            mkdir($this->testDir);
        }

        // Create a manifest.csv file
        $this->manifestPath = $this->testDir . DIRECTORY_SEPARATOR . 'manifest.csv';
        $manifest_content = [
            ['propertyName', 'value'],
            ['manifest.version', 1],
            ['oneroster.version', 1.1],
            ['file.academicSessions', 'bulk'],
            ['file.categories', 'absent'],
            ['file.classes', 'bulk'],
            ['file.classResources', 'absent'],
            ['file.courses', 'bulk'],
            ['file.courseResources', 'absent'],
            ['file.demographics', 'absent'],
            ['file.enrollments', 'bulk'],
            ['file.lineItems', 'absent'],
            ['file.orgs', 'bulk'],
            ['file.resources', 'absent'],
            ['file.results', 'absent'],
            ['file.users', 'bulk'],
        ];

        $handle = fopen($this->manifestPath, 'w');
        foreach ($manifest_content as $line) {
            fputcsv($handle, $line);
        }
        fclose($handle);

        // Create required files listed in the manifest
        file_put_contents($this->testDir . DIRECTORY_SEPARATOR . 'users.csv', 'User data');
        file_put_contents($this->testDir . DIRECTORY_SEPARATOR . 'enrollments.csv', 'Enrollment data');
        file_put_contents($this->testDir . DIRECTORY_SEPARATOR . 'courses.csv', 'Course data');
        file_put_contents($this->testDir . DIRECTORY_SEPARATOR . 'classes.csv', 'Class data');
        file_put_contents($this->testDir . DIRECTORY_SEPARATOR . 'academicSessions.csv', 'Academic session data');
        file_put_contents($this->testDir . DIRECTORY_SEPARATOR . 'orgs.csv', 'Org data');
    }

    // Clean up after the test, if the directory still exists
    protected function tearDown(): void
    {
        if (file_exists($this->testDir)) {
            $this->delete_directory($this->testDir);
        }
    }

    
    // Function to check the manifest.csv file and validate required files
    private function check_manifest_and_files($manifest_path, $tempdir) {
    $required_files = [];
    if (($handle = fopen($manifest_path, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if (in_array($data[1], ['bulk', 'delta'])) {
                $required_files[] = str_replace('file.', '', $data[0]) . '.csv';
            }
        }
        fclose($handle);
    }

    // Check if all required files are present
    $extracted_files = array_diff(scandir($tempdir), array('.', '..', 'uploadedzip.zip'));
    $missing_files = array_diff($required_files, $extracted_files);

    return $missing_files;
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

    // Assert that there are no missing files
    public function testCheckManifestAndFiles_allFilesPresent()
    {
        $missing_files = self::check_manifest_and_files($this->manifestPath, $this->testDir);
        $this->assertEmpty($missing_files, 'No files should be missing');
    }

    // Assert that the users.csv file is reported as missing
    public function testCheckManifestAndFiles_missingFile()
    {
        unlink($this->testDir . DIRECTORY_SEPARATOR . 'users.csv');
        $missing_files = self::check_manifest_and_files($this->manifestPath, $this->testDir);
        $this->assertEqualsCanonicalizing(['users.csv'], $missing_files, 'users.csv should be reported as missing');
    }
}
