package project.lighthouse.autotests.pages.commercialManager.catalog;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.InputOnlyVisible;
import project.lighthouse.autotests.elements.SelectByVisibleText;

public class MarkUpTab extends CommonPageObject {

    public MarkUpTab(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        items.put("retailMarkupMin", new InputOnlyVisible(this, "retailMarkupMin"));
        items.put("retailMarkupMax", new InputOnlyVisible(this, "retailMarkupMax"));
        items.put("priceRoundings", new SelectByVisibleText(this, "priceRoundings"));
    }

    public WebElement saveMarkUpButton() {
        return findVisibleElement(
                By.xpath("//*[@class='button button_color_blue']")
        );
    }

    public WebElement getSuccessMessage() {
        return findVisibleElement(
                By.xpath("//*[@class='form__successMessage']")
        );
    }

    public void checkSuccessMessage(String expectedMessage) {
        String actualSuccessMessage = getSuccessMessage().getText();
        if (!actualSuccessMessage.contains(expectedMessage)) {
            String errorMessage = String.format("Success message is not expected. Actual: '%s', Expected: '%s'", actualSuccessMessage, expectedMessage);
            throw new AssertionError(errorMessage);
        }
    }

    public void checkDropDownDefaultValue(String expectedValue) {
        WebElement element = items.get("priceRoundings").getVisibleWebElement();
        commonPage.checkDropDownDefaultValue(element, expectedValue);
    }
}
