<?php

final class SettingsTests extends PHPUnit\Framework\TestCase {

    public function testUseDataCacheReturnsANumber() {
        global $DB_USEDATACACHE;
        $DB_USEDATACACHE = 0;
        $result = settings::getsetting("usedatacache", "");
        $this->assertSame(0, $result);
    }

    public function testDataCachePathIsReturned() {
        global $DB_DATACACHEPATH;
        $DB_DATACACHEPATH = "testPath";
        $result = settings::getsetting("datacachepath", "");
        $this->assertSame("testPath", $result);
    }

    public function testDataIsLoadedFromDataCache() {
        global $settings, $datacache, $DB_USEDATACACHE;
        $DB_USEDATACACHE = 1;
        $settings = null;
        $datacache = [
            "game-settings" => [
                "testSetting" => "testSettingValue"
            ]
        ];

        $result = settings::getsetting("testSetting", "");
        $this->assertSame("testSettingValue", $result);
    }

    public function testDataIsLoadedFromSettingsArray() {
        global $settings;
        $settings = [
            "testSetting" => "testSettingValue"
        ];
        $result = settings::getsetting("testSetting", "");
        $this->assertSame("testSettingValue", $result);
    }

    public function testDataIsNotFoundAndDefaultValueIsReturned() {
        global $settings, $datacache, $DB_USEDATACACHE;
        $DB_USEDATACACHE = 1;
        $settings = null;
        $datacache = [
            "game-settings" => []
        ];

        $result = settings::getsetting("testSetting", "defaultValue");
        $this->assertSame("defaultValue", $result);
    }

}
