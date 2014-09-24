package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.hamcrest.Matchers;
import org.jbehave.core.model.ExamplesTable;
import org.junit.Assert;
import org.openqa.selenium.Alert;
import org.openqa.selenium.By;
import project.lighthouse.autotests.collection.error.ValidationErrorsCollection;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.bootstrap.SimplePreloader;
import project.lighthouse.autotests.elements.bootstrap.WaitForModalWindowClose;
import project.lighthouse.autotests.elements.preLoader.BodyPreLoader;
import project.lighthouse.autotests.pages.authorization.AuthorizationPage;
import project.lighthouse.autotests.storage.Storage;

import static junit.framework.Assert.fail;

public class CommonSteps extends ScenarioSteps {

    public CommonPageObject getCommonPageObject() {
        return getPages().get(AuthorizationPage.class);
    }

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
    public void checkAlertText(String expectedText) {
        getCommonPageObject().getCommonActions().checkAlertText(expectedText);
    }

    @Step
    public void NoAlertIsPresent() {
        try {
            Alert alert = getCommonPageObject().getWaiter().getAlert();
            fail(
                    String.format("Alert is present! Alert text: '%s'", alert.getText())
            );
        } catch (Exception ignored) {
        }
    }

    @Step
    public void pageRefresh() {
        getDriver().navigate().refresh();
    }

    @Step
    public void pageContainsText(String text) {
        getCommonPageObject().getWaiter().getVisibleWebElement(
                By.xpath(
                        String.format("//*[contains(normalize-space(text()), '%s')]", text)
                )
        );
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
        String actualContent = getCommonPageObject().findVisibleElement(By.className("popover-content")).getText();
        Assert.assertThat(actualContent, Matchers.is(expectedContent));
    }

    @Step
    public void switchToLastWindowHandle() {
        String mainWindowHandle = getCommonPageObject().getDriver().getWindowHandle();
        Storage.getCustomVariableStorage().setMainWindowHandle(mainWindowHandle);
        for (String windowHandle : getCommonPageObject().getDriver().getWindowHandles()) {
            getCommonPageObject().getDriver().switchTo().window(windowHandle);
        }
    }

    @Step
    public void beforeScenarioSwitchToMainWindowHandleIfNeeded() {
        String mainWindowHandle = Storage.getCustomVariableStorage().getMainWindowHandle();
        if (mainWindowHandle != null && containWindowHandle(mainWindowHandle)) {
            getCommonPageObject().getDriver().close();
            getCommonPageObject().getDriver().switchTo().window(mainWindowHandle);
            Storage.getCustomVariableStorage().setMainWindowHandle(null);
        }
    }

    private Boolean containWindowHandle(String windowHandle) {
        return getDriver().getWindowHandles().contains(windowHandle);
    }
}
