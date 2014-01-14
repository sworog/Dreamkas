package project.lighthouse.autotests.fixtures.sprint_21;

import project.lighthouse.autotests.fixtures.OldFixture;

import java.io.File;

public class Us_40_1_Fixture extends OldFixture {

    public File getSaleImportCloneDataFixture() {
        return getFileFixture("purchases-clone-data.xml");
    }
}
