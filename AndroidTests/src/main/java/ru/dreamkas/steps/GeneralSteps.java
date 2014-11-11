package ru.dreamkas.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;

import java.util.HashMap;
import java.util.Map;

import ru.dreamkas.pageObjects.CommonPageObject;
import ru.dreamkas.pageObjects.LoginPage;
import ru.dreamkas.pageObjects.PosPage;
import ru.dreamkas.pageObjects.elements.interfaces.Clickable;
import ru.dreamkas.pageObjects.elements.interfaces.Gettable;
import ru.dreamkas.pageObjects.elements.interfaces.Settable;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class GeneralSteps extends ScenarioSteps {

    public GeneralSteps(Pages pages) {
        super(pages);
    }

    private CommonPageObject currentPageObject;

    private Map<String, Class> pageObjectMap = new HashMap<String, Class>() {{
        put("экран кассы", PosPage.class);
        put("экран логина", LoginPage.class);
    }};

    protected CommonPageObject getCurrentPageObject() {
        return currentPageObject;
    }

    @Step
    public void setCurrentPageObject(String pageObjectName) {
        currentPageObject = (CommonPageObject)getPages().get(pageObjectMap.get(pageObjectName));
    }

    @Step
    public void clickOnElement(String elementName) {
        ((Clickable)getCurrentPageObject().getElements().get(elementName)).click();
    }

    @Step
    public void assertText(String elementName, String value) {
        assertThat(
                ((Gettable)getCurrentPageObject().getElements().get(elementName)).getText()
                , is(value));
    }

    @Step
    public void setValue(String elementName, String value) {
        ((Settable)getCurrentPageObject().getElements().get(elementName)).set(value);
    }
}
