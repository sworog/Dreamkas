package project.lighthouse.autotests.steps;

import junit.framework.Assert;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.common.CommonPage;
import project.lighthouse.autotests.objects.web.error.ValidationErrorsCollection;

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
        new ValidationErrorsCollection(getDriver()).matchesWithExampleTable(errorMessageTable);
    }

    @Step
    public void checkErrorMessage(String message) {
        new ValidationErrorsCollection(getDriver()).matchesWithMessage(message);
    }

    @Step
    public void checkNoErrorMessages() {
        try {
            String errorMessage = String.format("Present messages: '%s'", new ValidationErrorsCollection(getDriver()).getActualMessages());
            Assert.fail(errorMessage);
        } catch (Exception ignored) {
        }
    }

    @Step
    public void checkNoErrorMessages(ExamplesTable errorMessageTable) {
        try {
            new ValidationErrorsCollection(getDriver()).notMatchesWithExampleTable(errorMessageTable);
        } catch (Exception ignored) {
        }
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
