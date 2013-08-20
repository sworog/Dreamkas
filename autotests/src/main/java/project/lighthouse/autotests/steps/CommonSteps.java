package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.common.CommonPage;

public class CommonSteps extends ScenarioSteps {

    CommonPage commonPage;

    public CommonSteps(Pages pages) {
        super(pages);
    }

    @Step
    public void checkTheRequiredPageIsOpen(String pageObjectName) {
        commonPage.isRequiredPageOpen(pageObjectName);
    }

    @Step
    public void checkErrorMessages(ExamplesTable errorMessageTable) {
        commonPage.checkErrorMessages(errorMessageTable);
    }

    @Step
    public void checkErrorMessage(String message) {
        commonPage.checkErrorMessage(message);
    }

    @Step
    public void checkNoErrorMessages() {
        commonPage.checkNoErrorMessages();
    }

    @Step
    public void checkNoErrorMessages(ExamplesTable errorMessageTable) {
        commonPage.checkNoErrorMessages(errorMessageTable);
    }

    @Step
    public void checkAutoCompleteNoResults() {
        commonPage.checkAutoCompleteNoResults();
    }

    @Step
    public void checkAutoCompleteResults(ExamplesTable checkValuesTable) {
        commonPage.checkAutoCompleteResults(checkValuesTable);
    }

    @Step
    public void checkAutoCompleteResult(String autoCompleteValue) {
        commonPage.checkAutoCompleteResult(autoCompleteValue);
    }

    @Step
    public void checkAlertText(String expectedText) {
        commonPage.checkAlertText(expectedText);
    }

    @Step
    public void NoAlertIsPresent() {
        commonPage.NoAlertIsPresent();
    }

    @Step
    public void pageRefresh() {
        getDriver().navigate().refresh();
    }

    @Step
    public void pageContainsText(String text) {
        commonPage.pageContainsText(text);
    }
}
