package project.lighthouse.autotests.fixtures.sprint_20;

import java.io.File;

public class Us_40_Fixture {

    public File getFixtureFile() {
        return getFileFixture("purchases-data.xml");
    }

    public File getFixtureFileWithNoSuchProduct() {
        return getFileFixture("purchases-data-no-product.xml");
    }

    public File getFixtureFileWithNoExistStore() {
        return getFileFixture("purchases-data-no-store.xml");
    }

    public File getFixtureFileWithCorruptedData() {
        return getFileFixture("purchases-data-corrupted.xml");
    }

    public File getFileFixture(String fileName) {
        return new File(
                String.format("%s/xml/purchases/%s", System.getProperty("user.dir").replace("\\", "/"), fileName));
    }
}
