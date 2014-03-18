package project.lighthouse.autotests.pages.commercialManager.catalog;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.InputOnlyVisible;
import project.lighthouse.autotests.elements.SelectByVisibleText;
import project.lighthouse.autotests.elements.preLoader.PreLoader;

import static junit.framework.Assert.assertEquals;

public class MarkUpTab extends CommonPageObject {

    public MarkUpTab(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("retailMarkupMin", new InputOnlyVisible(this, "retailMarkupMin"));
        put("retailMarkupMax", new InputOnlyVisible(this, "retailMarkupMax"));
        put("rounding", new SelectByVisibleText(this, "rounding.name"));
    }

    public void saveMarkUpButtonClick() {
        new ButtonFacade(this, "Сохранить").catalogClick();
        new PreLoader(getDriver()).await();
    }

    public WebElement getSuccessMessage() {
        return findVisibleElement(
                By.xpath("//*[@class='form__successMessage']")
        );
    }

    public void checkSuccessMessage(String expectedMessage) {
        assertEquals(
                String.format("Success message is not expected. Actual: '%s', Expected: '%s'", getSuccessMessage().getText(), expectedMessage),
                getSuccessMessage().getText(), expectedMessage
        );
    }

    public void checkDropDownDefaultValue(String expectedValue) {
        WebElement element = getItems().get("rounding").getVisibleWebElement();
        getCommonActions().checkDropDownDefaultValue(element, expectedValue);
    }
}
