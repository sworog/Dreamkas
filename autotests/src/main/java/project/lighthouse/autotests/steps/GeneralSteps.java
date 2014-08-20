package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.common.GeneralPageObject;
import project.lighthouse.autotests.pages.stockMovement.StockMovementPage;

import java.util.HashMap;
import java.util.Map;

public class GeneralSteps extends ScenarioSteps {

    private GeneralPageObject currentPageObject;

    private Map<String,Class> pageObjectClasses = new HashMap<String,Class>(){{
        put("stockMovementPage", StockMovementPage.class);
    }};

    public void setCurrentPageObject(String pageObjectName) {
        Class pageObjectClass = pageObjectClasses.get(pageObjectName);
        currentPageObject = (GeneralPageObject) getPages().get(pageObjectClass);
    }

    @Step
    public void input(String elementName, String value) {
        currentPageObject.input(elementName, value);
    }

    @Step
    public void input(ExamplesTable fieldInputTable) {
        currentPageObject.input(fieldInputTable);
    }

    @Step
    public void checkValue(String element, String value) {
        currentPageObject.checkValue(element, value);
        ;
    }

    @Step
    public void checkValues(ExamplesTable examplesTable) {
        currentPageObject.checkValues(examplesTable);
    }

    @Step
    public void checkItemErrorMessage(String elementName, String errorMessage) {
        currentPageObject.checkItemErrorMessage(elementName, errorMessage);
    }
}
