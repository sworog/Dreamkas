package project.lighthouse.autotests.fixtures;

import java.io.File;

public class OldFixture {

    public File getFileFixture(String fileName) {
        return new File(
                String.format("%s/xml/purchases/%s", System.getProperty("user.dir").replace("\\", "/"), fileName));
    }
}
