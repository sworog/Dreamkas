package project.lighthouse.autotests.fixtures.sprint_23;

import project.lighthouse.autotests.fixtures.OldFixture;

import java.io.File;

public class Us_52_Fixture extends OldFixture {

    public File getImportSaleDataFixture() {
        return getFileFixture("purchases-data-us52.xml");
    }
}
