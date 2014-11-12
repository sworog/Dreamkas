package ru.dreamkas.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import ru.dreamkas.elements.interfaces.Clickable;
import ru.dreamkas.elements.interfaces.Gettable;
import ru.dreamkas.elements.interfaces.Inputtable;
import ru.dreamkas.pages.CommonPageObject;
import ru.dreamkas.pages.dialogs.LoginDialogPage;
import ru.dreamkas.pages.StartPage;
import ru.dreamkas.pages.dialogs.SelectStoreDialog;

import java.util.HashMap;
import java.util.Map;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class GeneralSteps extends ScenarioSteps {

    private CommonPageObject pageObject;

    private static Map<String, Class> pageObjects = new HashMap<String, Class>() {{
        put("стартовом экране", StartPage.class);
        put("диалоговом окне авторизации", LoginDialogPage.class);
        put("диалоговом окне выбора магазина", SelectStoreDialog.class);
    }};

    private CommonPageObject getPageObject(String pageObjectName) {
        return (CommonPageObject)getPages().get(pageObjects.get(pageObjectName));
    }

    @Step
    public void setPageObject(String pageObjectName) {
        pageObject = getPageObject(pageObjectName);
    }

    @Step
    public CommonPageObject getCurrentPageObject() {
        return pageObject;
    }

    @Step
    public void setValue(String elementName, String value) {
        ((Inputtable)pageObject.getElementableByName(elementName)).set(value);
    }

    @Step
    public void click(String elementName) {
        ((Clickable)pageObject.getElementableByName(elementName)).click();
    }

    @Step
    public void assertText(String elementName, String value) {
        assertThat(
                ((Gettable)pageObject.getElementableByName(elementName)).getText(),
                is(value)
        );
    }
}
