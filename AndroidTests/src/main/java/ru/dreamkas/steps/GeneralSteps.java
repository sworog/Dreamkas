package ru.dreamkas.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;

import java.util.HashMap;
import java.util.List;
import java.util.Map;

import ru.dreamkas.pageObjects.CommonPageObject;
import ru.dreamkas.pageObjects.LoginPage;
import ru.dreamkas.pageObjects.PosPage;
import ru.dreamkas.pageObjects.dialogs.EditReceiptItemPage;
import ru.dreamkas.pageObjects.elements.Collection;
import ru.dreamkas.pageObjects.elements.interfaces.Clickable;
import ru.dreamkas.pageObjects.elements.interfaces.Elementable;
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
        put("касса", PosPage.class);
        put("экран логина", LoginPage.class);
        put("редактирование товарной позиции в чеке продажи", EditReceiptItemPage.class);
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
        ((Clickable)getElement(elementName)).click();
    }

    @Step
    public Boolean getButtonIsEnabled(String elementName) {
        return ((Clickable)getElement(elementName)).isEnabled();
    }


    @Step
    public void assertText(String elementName, String value) {
        assertThat(
                ((Gettable)getElement(elementName)).getText()
                , is(value));
    }

    @Step
    public void setValue(String elementName, String value) {
        ((Settable)getElement(elementName)).set(value);
    }

    private Elementable getElement(String elementName) {
        try{
            return getCurrentPageObject().getElements().get(elementName);
        }catch (NullPointerException ex){
            throw new NullPointerException(String.format("On page '%s' not found element: '%s'", getCurrentPageObject().getClass().toString(), elementName));
        }
    }

    @Step
    public <T> List<T> getItems(String elementName) {
        return ((Collection)getElement(elementName)).getItems();
    }

    @Step
    public void clickOnListItem(String elementName, String item){
        ((Collection)getElement(elementName)).click(item);

    }

}
