package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.hamcrest.Matchers;
import org.jbehave.core.model.ExamplesTable;
import org.junit.Assert;
import org.openqa.selenium.By;
import project.lighthouse.autotests.common.CommonPage;
import project.lighthouse.autotests.elements.bootstrap.SimplePreloader;
import project.lighthouse.autotests.elements.bootstrap.WaitForModalWindowClose;
import project.lighthouse.autotests.elements.preLoader.BodyPreLoader;
import project.lighthouse.autotests.objects.web.error.ValidationErrorsCollection;

public class CommonSteps extends ScenarioSteps {

    CommonPage commonPage;

    @Step
    public void checkErrorMessages(ExamplesTable errorMessageTable) {
        new ValidationErrorsCollection(getDriver()).matchesWithExampleTable(errorMessageTable);
    }

    @Step
    public void exactCheckErrorMessages(ExamplesTable examplesTable) {
        new ValidationErrorsCollection(getDriver()).exactCompareExampleTable(examplesTable);
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
        commonPage.getCommonActions().checkAlertText(expectedText);
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

    @Step
    public void waitForModalPageClose() {
        new WaitForModalWindowClose(getDriver()).await();
    }

    @Step
    public void waitForSimplePreloaderLoading() {
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void waitForPageFinishingLoading() {
        new BodyPreLoader(getDriver()).await();
    }

    @Step
    public void assertPopOverContent(String expectedContent) {
        String actualContent = commonPage.findVisibleElement(By.className("popover-content")).getText();
        Assert.assertThat(actualContent, Matchers.is(expectedContent));
    }

    @Step
    public void openUrl(String url) {
        commonPage.getDriver().navigate().to(url);
    }
}
