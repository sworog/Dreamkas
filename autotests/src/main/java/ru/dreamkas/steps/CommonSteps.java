package ru.dreamkas.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.hamcrest.Matchers;
import org.jbehave.core.model.ExamplesTable;
import org.junit.Assert;
import org.openqa.selenium.Alert;
import org.openqa.selenium.By;
import ru.dreamkas.collection.error.ValidationErrorsCollection;
import ru.dreamkas.common.pageObjects.CommonPageObject;
import ru.dreamkas.elements.bootstrap.SimplePreloader;
import ru.dreamkas.elements.bootstrap.WaitForModalWindowClose;
import ru.dreamkas.elements.preLoader.BodyPreLoader;
import ru.dreamkas.pages.authorization.AuthorizationPage;
import ru.dreamkas.storage.DefaultStorage;

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
        getCommonPageObject().shouldContainsText(text);
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
        String actualContent = getCommonPageObject().findVisibleElement(By.xpath("//*[contains(@class, 'modal_visible')]//*[@class='removeButton__error']")).getText();
        Assert.assertThat(actualContent, Matchers.is(expectedContent));
    }

    @Step
    public void switchToLastWindowHandle() {
        String mainWindowHandle = getCommonPageObject().getDriver().getWindowHandle();
        DefaultStorage.getCustomVariableStorage().setMainWindowHandle(mainWindowHandle);
        for (String windowHandle : getCommonPageObject().getDriver().getWindowHandles()) {
            getCommonPageObject().getDriver().switchTo().window(windowHandle);
        }
    }

    @Step
    public void beforeScenarioSwitchToMainWindowHandleIfNeeded() {
        String mainWindowHandle = DefaultStorage.getCustomVariableStorage().getMainWindowHandle();
        if (mainWindowHandle != null && containWindowHandle(mainWindowHandle)) {
            getCommonPageObject().getDriver().close();
            getCommonPageObject().getDriver().switchTo().window(mainWindowHandle);
            DefaultStorage.getCustomVariableStorage().setMainWindowHandle(null);
        }
    }

    private Boolean containWindowHandle(String windowHandle) {
        return getDriver().getWindowHandles().contains(windowHandle);
    }
}
