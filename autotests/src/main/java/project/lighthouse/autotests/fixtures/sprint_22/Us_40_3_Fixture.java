package project.lighthouse.autotests.fixtures.sprint_22;

import project.lighthouse.autotests.fixtures.OldFixture;

import java.io.File;

public class Us_40_3_Fixture extends OldFixture {

    public File getImportReturnDataFixture() {
        return getFileFixture("purchases-return-data.xml");
    }
}
