package project.lighthouse.autotests.steps.general;

import net.thucydides.core.annotations.Step;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.common.GeneralPageObject;
import project.lighthouse.autotests.pages.pos.PosLaunchPage;
import project.lighthouse.autotests.pages.pos.PosPage;
import project.lighthouse.autotests.pages.stockMovement.StockMovementPage;

import java.util.HashMap;
import java.util.Map;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class GeneralSteps<T extends GeneralPageObject> extends AbstractGeneralSteps<T> {

    @Override
    Map<String, Class> getPageObjectClasses() {
        return new HashMap<String, Class>() {{
            put("товародвижение", StockMovementPage.class);
            put("выбора кассы", PosLaunchPage.class);
            put("выбранной кассы", PosPage.class);
        }};
    }

    @Step
    public void input(String elementName, String value) {
        getCurrentPageObject().input(elementName, value);
    }

    @Step
    public void input(ExamplesTable fieldInputTable) {
        getCurrentPageObject().input(fieldInputTable);
    }

    @Step
    public void checkValue(String element, String value) {
        getCurrentPageObject().checkValue(element, value);
    }

    @Step
    public void checkValues(ExamplesTable examplesTable) {
        getCurrentPageObject().checkValues(examplesTable);
    }

    @Step
    public void checkItemErrorMessage(String elementName, String errorMessage) {
        getCurrentPageObject().checkItemErrorMessage(elementName, errorMessage);
    }

    @Step
    public void assertTitle(String title) {
        assertThat(getCurrentPageObject().getTitle(), is(title));
    }
}
