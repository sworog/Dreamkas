package project.lighthouse.autotests.common;

import org.jbehave.core.model.ExamplesTable;

public interface GeneralPageObject {

    public void checkValue(String elementName, String value);
    public void fieldInput(ExamplesTable fieldInputTable);
}
