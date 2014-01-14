package project.lighthouse.autotests.fixtures.sprint_20;

import project.lighthouse.autotests.fixtures.OldFixture;

import java.io.File;

public class Us_40_Fixture extends OldFixture {

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
}
