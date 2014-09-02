package project.lighthouse.autotests.common;

import org.jbehave.core.model.ExamplesTable;

public interface GeneralPageObject {

    public void input(ExamplesTable examplesTable);

    public void input(String elementName, String value);

    public void checkValue(String elementName, String value);

    public void checkValues(ExamplesTable examplesTable);

    public void checkItemErrorMessage(String elementName, String errorMessage);

    public String getTitle();
}
