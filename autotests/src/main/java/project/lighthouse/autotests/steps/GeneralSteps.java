package project.lighthouse.autotests.steps;

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

    private GeneralPageObject getCurrentPageObject() {
        return currentPageObject;
    }

    public void checkValue(String element, String value) {
        getCurrentPageObject().checkValue(element, value);;
    }

    public void fieldInput(ExamplesTable fieldInputTable) {
        getCurrentPageObject().fieldInput(fieldInputTable);
    }
}
